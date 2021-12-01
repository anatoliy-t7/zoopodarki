<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RunMeilisearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run meilisearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $process = new Process(['meilisearch', '--db-path', base_path('data.ms')]);
        // $process->disableOutput();

        if ($process->run()) {
            return true;
        }

        return false;
        // shell_exec('meilisearch --db-path' . base_path('data.ms'));
    }
}
