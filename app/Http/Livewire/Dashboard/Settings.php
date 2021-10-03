<?php

namespace App\Http\Livewire\Dashboard;

use App\Jobs\UpdateProduct;
use Livewire\Component;

class Settings extends Component
{

    public function UpdateProduct()
    {

        UpdateProduct::dispatch();

        $this->dispatchBrowserEvent('toaster', ['message' => 'Job UpdateProduct added']);

    }

    public function backupDb()
    {

        try {
            \Artisan::call('backup:run --only-db');
            \Artisan::call('backup:clean');

            $this->dispatchBrowserEvent('toaster', ['message' => 'Backup is done']);

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Backup is not done']);

            \Log::error($th);
        }

    }

    public function queueRun()
    {

        \Artisan::call('queue:work');

        $this->dispatchBrowserEvent('toaster', ['message' => 'Queue is running']);

    }

    public function generateSitemap()
    {

        try {

            \Artisan::call('cache:clear');
            \Artisan::call('route:cache');
            \Artisan::call('view:clear');
            \Artisan::call('optimize');
            \Artisan::call('sitemap:generate');

            $this->dispatchBrowserEvent('toaster', ['message' => 'Sitemap is generated']);

        } catch (\Throwable $th) {

            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'Sitemap isn`t generated']);

            \Log::error($th);

        }

    }

    public function render()
    {
        return view('livewire.dashboard.settings')
            ->extends('dashboard.app')
            ->section('content');
    }
}
