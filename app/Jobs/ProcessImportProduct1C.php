<?php

namespace App\Jobs;

use App\Models\Product1C;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Parser;
use Prewk\XmlStringStreamer\Stream;
use Throwable;

class ProcessImportProduct1C implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 600;
    public $count = 0;
    public $forDelete = 0;
    protected $file;
    protected $consist = null;
    protected $barcode = null;
    protected $vendorcode = null;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stream = new Stream\File($this->file, 1024);
        $parser = new Parser\UniqueNode(['uniqueNode' => 'Товар']);

        $streamer = new XmlStringStreamer($parser, $stream);

        while ($node = $streamer->getNode()) {
            $product1c = json_decode(json_encode(simplexml_load_string($node)), true);

            $this->getProducts($product1c);

            unset($product1c);
        }

        if ($this->count > 0) {
            Log::info('Product deleted: ' . $this->count);
        }

        if ($this->forDelete > 0) {
            Log::info('Product1C deleted: ' . $this->forDelete);
        }

        Log::info('import.xml processed successed');

        unlink($this->file);
    }

    public function getProducts($product1c)
    {
        \DB::connection()->disableQueryLog();

        \DB::transaction(function () use ($product1c) {
            if (Product1C::where('uuid', $product1c['Ид'])->first() && Arr::has($product1c, '@attributes')) {
                if ($product1c['@attributes']['Статус'] === 'Удален') {
                    $oldProduct = Product1C::where('uuid', $product1c['Ид'])
                        ->with('product')
                        ->with('product.variations')
                        ->with('product.categories')
                        ->with('product.attributes')
                        ->with('product.unit')
                        ->first();

                    if ($oldProduct->product()->exists()) {
                        if ($oldProduct->product->categories()->exists()) {
                            $oldProduct->product->categories()->detach();
                        }

                        if ($oldProduct->product->attributes()->exists()) {
                            $oldProduct->product->attributes()->detach();
                        }

                        $oldProduct->product->variations()->update(['product_id' => null]);

                        if ($oldProduct->product->variations()->count() === 1) {
                            $oldProduct->product->forceDelete();
                        }

                        $this->count++;
                    }

                    $oldProduct->delete();
                    $this->forDelete++;
                }
            } elseif (Product1C::where('uuid', $product1c['Ид'])->first()) {
                $this->updateProduct($product1c);
            } else {
                $this->createProduct($product1c);
            }
        });
    }

    public function updateProduct($product1c)
    {
        $oldProduct = Product1C::where('uuid', $product1c['Ид'])->with('product')->first();

        if (Arr::exists($product1c, 'Описание')
            && !empty($product1c['Описание'])
            && $oldProduct->product()->exists()
            && $oldProduct->product->consist === null) {
            $oldProduct->product->consist = $product1c['Описание'];
            $oldProduct->push();
        }

        if (Arr::exists($product1c, 'Артикул')
            && !empty($product1c['Артикул'])
            && $product1c['Артикул'] !== $oldProduct->vendorcode) {
            $oldProduct->vendorcode = $product1c['Артикул'];
            $oldProduct->save();
        }

        if (Arr::exists($product1c, 'Штрихкод')
            && !empty($product1c['Штрихкод'])
            && $product1c['Штрихкод'] !== $oldProduct->barcode) {
            $oldProduct->barcode = $product1c['Штрихкод'];
            $oldProduct->save();
        }

        if (Arr::exists($product1c, 'ЗначенияРеквизитов')) {
            foreach ($product1c['ЗначенияРеквизитов']['ЗначениеРеквизита'] as  $requisite) {
                if ($requisite['Наименование'] == 'ВесНоменклатуры') {
                    $weight = $requisite['Значение'] * 1000;

                    $oldProduct->weight = $weight;
                    if ($oldProduct->product()->exists() && $oldProduct->product->unit()->exists() && $oldProduct->product->unit->id === 1) {
                        $oldProduct->unit_value = $weight;
                    }
                    $oldProduct->save();
                }
            }
        }

        //Update description
        if (Arr::exists($product1c, 'ЗначенияСвойств')) {
            if (Arr::has($product1c['ЗначенияСвойств']['ЗначенияСвойства'], 'Ид')) {
                if ($product1c['ЗначенияСвойств']['ЗначенияСвойства']['Ид'] === 'f5c10840-6500-11ea-bd2a-bc5ff404141d'
                    && $oldProduct->product()->exists()
                    && $oldProduct->product->description === null) {
                    $oldProduct->product->description = $product1c['ЗначенияСвойств']['ЗначенияСвойства']['Значение'];
                    $oldProduct->push();
                }
            } else {
                foreach ($product1c['ЗначенияСвойств']['ЗначенияСвойства'] as $item) {
                    if ($item['Ид'] === 'f5c10840-6500-11ea-bd2a-bc5ff404141d'
                        && $oldProduct->product()->exists()
                        && $oldProduct->product->description === null) {
                        $oldProduct->product->description = $item['Значение'];
                        $oldProduct->push();
                    }
                }
            }
        }

        unset($oldProduct);
    }

    public function createProduct($product1c)
    {
        if (Arr::exists($product1c, 'Штрихкод') && !empty($product1c['Штрихкод'])) {
            $this->barcode = $product1c['Штрихкод'];
        }

        if (Arr::exists($product1c, 'Артикул') && !empty($product1c['Артикул'])) {
            $this->vendorcode = $product1c['Артикул'];
        }

        $newProduct = Product1C::create([
            'uuid' => $product1c['Ид'],
            'name' => $product1c['Наименование'],
            'barcode' => $this->barcode,
            'vendorcode' => $this->vendorcode,
        ]);

        $this->barcode = null;
        $this->vendorcode = null;

        if (Arr::exists($product1c, 'ЗначенияРеквизитов')) {
            foreach ($product1c['ЗначенияРеквизитов']['ЗначениеРеквизита'] as  $requisite) {
                if ($requisite['Наименование'] == 'ВесНоменклатуры') {
                    $weight = $requisite['Значение'] * 1000;

                    $newProduct->weight = $weight;
                    if ($newProduct->product()->exists() && $newProduct->product->unit()->exists() && $newProduct->product->unit->id === 1) {
                        $newProduct->unit_value = $weight;
                    }
                    $newProduct->save();
                }
            }
        }

        if (Arr::exists($product1c, 'ЗначенияСвойств')) {
            //get commission
            if (Arr::has($product1c['ЗначенияСвойств']['ЗначенияСвойства'], 'Ид')) {
                if ($product1c['ЗначенияСвойств']['ЗначенияСвойства']['Ид'] === 'd65bfebe-e413-11e9-978e-bc5ff404141d') {
                    $newProduct->commission = str_replace(',', '.', $product1c['ЗначенияСвойств']['ЗначенияСвойства']['Значение']);
                    $newProduct->save();
                }
            } else {
                foreach ($product1c['ЗначенияСвойств']['ЗначенияСвойства'] as $item) {
                    if ($item['Ид'] === 'd65bfebe-e413-11e9-978e-bc5ff404141d') {
                        $newProduct->commission = str_replace(',', '.', $item['Значение']);
                        $newProduct->save();
                    }
                }
            }
        }

        unset($newProduct, $product1c);
    }

    public function failed(Throwable $exception)
    {
        return Log::error($exception->getMessage());
    }
}
