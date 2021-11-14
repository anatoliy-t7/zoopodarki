<?php

namespace App\Jobs;

use App\Traits\ExportImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Rap2hpoutre\FastExcel\FastExcel;
use Throwable;

class ImportProductsFromExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    use ExportImport;

    protected $file;

    public $tries = 1;

    public $timeout = 500;

    public $failOnTimeout = true;

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
        $collection = (new FastExcel)->import($this->file);
        $this->importData($collection);

    }

    public function failed(Throwable $exception)
    {
        \Log::error($exception);
    }
}
