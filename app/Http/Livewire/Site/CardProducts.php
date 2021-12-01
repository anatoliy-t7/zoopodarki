<?php

namespace App\Http\Livewire\Site;

use Livewire\Component;

class CardProducts extends Component
{
    public $product;
    public $catalog;
    public $category;

    public function render()
    {
        return view('livewire.site.card-products');
    }
}
