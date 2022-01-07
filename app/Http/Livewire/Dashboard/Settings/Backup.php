<?php

namespace App\Http\Livewire\Dashboard\Settings;

use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Backup extends Component
{
    use WireToast;

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



    public function render()
    {
        return view('livewire.dashboard.settings.backup')
            ->extends('dashboard.app')
            ->section('content');
    }
}
