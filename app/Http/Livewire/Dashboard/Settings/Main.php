<?php

namespace App\Http\Livewire\Dashboard\Settings;

use App\Jobs\UpdateProduct;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Main extends Component
{
    use WireToast;

    public function removeDbCache()
    {
        try {
            Cache::flush();

            return toast()
                ->success('Кеш DB отчистен')
                ->push();
        } catch (\Throwable $th) {
            Log::error($th);

            return toast()
                ->warning('Кеш DB не отчистен')
                ->push();
        }
    }

    public function removeAllCache()
    {
        try {
            \Artisan::call('optimize:clear');
            \Artisan::call('optimize');
            toast()
                ->success('Кеш отчистен')
                ->push();
        } catch (\Throwable$th) {
            toast()
                ->warning('Кеш не отчистен')
                ->push();

            \Log::error($th);
        }
    }

    public function generateSitemap()
    {
        try {
            \Artisan::call('optimize:clear');
            \Artisan::call('optimize');
            \Artisan::call('sitemap:generate');
            toast()
                ->success('Карта сайта создана')
                ->push();
        } catch (\Throwable$th) {
            toast()
                ->warning('Карта сайта не создается')
                ->push();

            \Log::error($th);
        }
    }

    public function updateProduct()
    {
        UpdateProduct::dispatch();

        toast()
            ->info('Job UpdateProduct added')
            ->push();
    }

    public function render()
    {
        return view('livewire.dashboard.settings.main')
            ->extends('dashboard.app')
            ->section('content');
    }
}
