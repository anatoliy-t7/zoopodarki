<?php
namespace App\Http\Livewire\Dashboard\Settings;

use App\Jobs\UpdateProduct;
use Livewire\Component;

class Backup extends Component
{
    public function UpdateProduct()
    {
        UpdateProduct::dispatch();

        $this->dispatchBrowserEvent('toast', ['message' => 'Job UpdateProduct added']);
    }

    public function backupDb()
    {
        try {
            \Artisan::call('backup:run --only-db');
            \Artisan::call('backup:clean');

            $this->dispatchBrowserEvent('toast', ['message' => 'Backup is done']);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('toast', ['type' => 'error', 'text' => 'Backup is not done']);

            \Log::error($th);
        }
    }

    public function queueRun()
    {
        \Artisan::call('queue:work');

        $this->dispatchBrowserEvent('toast', ['message' => 'Queue is running']);
    }

    public function generateSitemap()
    {
        try {
            \Artisan::call('cache:clear');
            \Artisan::call('route:cache');
            \Artisan::call('view:clear');
            \Artisan::call('optimize');
            \Artisan::call('sitemap:generate');

            $this->dispatchBrowserEvent('toast', ['message' => 'Sitemap is generated']);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('toast', ['type' => 'error', 'text' => 'Sitemap isn`t generated']);

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
