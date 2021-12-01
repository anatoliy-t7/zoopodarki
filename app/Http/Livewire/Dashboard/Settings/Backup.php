<?php

namespace App\Http\Livewire\Dashboard\Settings;

use App\Jobs\UpdateProduct;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Backup extends Component
{
    use WireToast;

    public function updateProduct()
    {
        UpdateProduct::dispatch();

        toast()
            ->info('Job UpdateProduct added')
            ->push();
    }

    public function backupDb()
    {
        try {
            \Artisan::call('backup:run --only-db');
            \Artisan::call('backup:clean');

            toast()
                ->success('Backup is done')
                ->push();
        } catch (\Throwable$th) {
            toast()
                ->warning('Backup is not done')
                ->push();

            \Log::error($th);
        }
    }

    public function queueRun()
    {
        \Artisan::call('queue:work');

        toast()
            ->success('Queue is running')
            ->push();
    }

    public function generateSitemap()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('route:cache');
            \Artisan::call('view:clear');
            \Artisan::call('optimize');
            \Artisan::call('sitemap:generate');

            toast()
                ->info('Sitemap is generated')
                ->push();
        } catch (\Throwable$th) {
            toast()
                ->warning('Sitemap isn`t generated')
                ->push();

            \Log::error($th);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.settings.backup')
            ->extends('dashboard.app')
            ->section('content');
    }
}
