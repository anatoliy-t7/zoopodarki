<?php

namespace App\Http\Livewire\Dashboard\Pages;

use App\Models\Page;
use Livewire\Component;
use Livewire\WithPagination;
use Usernotnull\Toast\Concerns\WireToast;

class Pages extends Component
{
    use WireToast;
    use WithPagination;

    public function remove($pageId)
    {
        Page::find($pageId)->delete();

        toast()
            ->success('Страница удалена')
            ->push();
    }

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
