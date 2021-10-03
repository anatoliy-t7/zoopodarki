<?php

namespace App\Http\Livewire\Dashboard\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;

class Pages extends Component
{

    use WithPagination;

    public function render()
    {
        return view('livewire.dashboard.pages.pages', [
            'pages' => Page::orderBy('title', 'ASC')
                ->paginate(30),
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
