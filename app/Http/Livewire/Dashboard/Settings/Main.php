<?php
namespace App\Http\Livewire\Dashboard\Settings;

use Livewire\Component;

class Main extends Component
{
    public function render()
    {
        return view('livewire.dashboard.settings.main')
            ->extends('dashboard.app')
            ->section('content');
    }
}
