<?php
namespace App\Jobs;

use App\Jobs\GetUserDiscountFrom1C;
use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Parser;
use Prewk\XmlStringStreamer\Stream;
use Throwable;

class ImportOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $delete = false;
    protected $file;

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
        $directory = storage_path('sync');
        $functionFile = $directory . '/' . $this->file;

        $stream = new Stream\File($functionFile, 1024);
        $parser = new Parser\UniqueNode(['uniqueNode' => 'Документ']);

        $streamer = new XmlStringStreamer($parser, $stream);

        while ($node = $streamer->getNode()) {
            $order1c = json_decode(json_encode(simplexml_load_string($node)), true);

            $this->getOrders($order1c);

            unset($order1c); // удаление переменной
        }

        unlink($functionFile); // удаление файла
    }

    public function getOrders($order1c)
    {
        \DB::connection()->disableQueryLog();

        if (Order::where('order_number', $order1c['Номер'])->exists()) {
            $order = Order::where('order_number', $order1c['Номер'])->first();

            if ($order1c['Сумма'] !== $order->amount) {
                $order->amount = $order1c['Сумма'];

                $order->save();
            }

            foreach ($order1c['ЗначенияРеквизитов']['ЗначениеРеквизита'] as $item) {
                if ($item['Наименование'] === 'Проведен' and $item['Значение'] === 'true') {
                    $order->status = 'ready';

                    $order->save();

                    $user = User::where('id', $order->user_id)->first();

                    // Проверка на дисконтную карту
                    if ($user->discount === 0) {
                        GetUserDiscountFrom1C::dispatch($user);
                    }
                }

                if ($order->payment_status === 'pending' && $item['Наименование'] === 'Номер оплаты по 1С') {
                    $order->payment_status = 'succeeded';
                    $order->save();
                }

                if ($item['Наименование'] === 'ПометкаУдаления' && $item['Значение'] === 'true') {
                    $order->delete();
                }
            }

            unset($order);
        }
    }

    public function failed(Throwable $exception)
    {
        return \Log::error($exception->getMessage());
    }
}
