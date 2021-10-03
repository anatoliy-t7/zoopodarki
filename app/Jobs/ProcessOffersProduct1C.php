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

class ProcessOffersProduct1C implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    public $timeout = 300;
    protected $file;

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
        $parser = new Parser\UniqueNode(['uniqueNode' => 'Предложение']);

        $streamer = new XmlStringStreamer($parser, $stream);

        while ($node = $streamer->getNode()) {
            $offer = json_decode(json_encode(simplexml_load_string($node)), true);
            $this->getProducts($offer);
            unset($offer);
        }

        unlink($this->file);
    }

    public function getProducts($offer)
    {
        \DB::connection()->disableQueryLog();

        if (Product1C::where('uuid', $offer['Ид'])->exists()) {
            $product = Product1C::where('uuid', $offer['Ид'])->first();

            $product->update([
                'price' => $offer['Цены']['Цена']['ЦенаЗаЕдиницу'],
                'stock' => $offer['Количество'],
            ]);

            if (Arr::exists($offer, 'Скидка') and !empty($offer['Скидка'])) {
                $product->discount = $offer['Скидка'];
                $product->save();
            }
            unset($offer, $product);

        }
    }

    public function failed(Throwable $exception)
    {
        return \Log::error($exception->getMessage());
    }
}
