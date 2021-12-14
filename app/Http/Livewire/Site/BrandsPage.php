<?php

namespace App\Http\Livewire\Site;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;

class BrandsPage extends Component
{
    use WithPagination;

    public $searchBrand;

    public function getBrands()
    {
        return Brand::when($this->searchBrand, function ($query) {
            $query->whereLike(['name', 'name_rus'], $this->searchBrand);
        })
            ->orderBy('name')
            ->paginate(32);
    }

    public function render()
    {
        $brands = $this->getBrands();

        return view('livewire.site.brands-page', [
            'brands' => $brands,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
