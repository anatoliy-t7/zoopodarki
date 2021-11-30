<?php
namespace App\Http\Livewire\Dashboard\Settings;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Main extends Component
{

    use WireToast;

    public function removeAllCache()
    {
        try {
            Cache::flush();

            return toast()
                ->success('Весь кеш отчистин')
                ->push();
        } catch (\Throwable $th) {
            Log::error($th);
            return toast()
                ->success('Кеш не отчистин')
                ->push();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.settings.main')
            ->extends('dashboard.app')
            ->section('content');
    }
}
