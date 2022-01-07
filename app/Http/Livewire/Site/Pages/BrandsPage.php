<?php

namespace App\Http\Livewire\Site\Pages;

use App\Models\Brand;
use Artesaos\SEOTools\Facades\SEOMeta;
use Livewire\Component;
use Livewire\WithPagination;

class BrandsPage extends Component
{
    use WithPagination;

    public $searchBrand;

    protected $queryString = [
        'searchBrand' => ['except' => ''],
    ];

    public function mount()
    {
        $this->setSeo();
    }

    public function setSeo()
    {
        //SEO TITLE
        // *like* Все производители, фирмы (бренды) кормов и товаров для животных в новом интернет зоомагазине спб с доставкой по городу+*доллар* акции и скидки + петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

        $metaTitle = '👍  Все производители, фирмы (бренды) кормов и товаров для животных в новом интернет зоомагазине спб с доставкой по городу, ₽ ,акции и скидки, петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

        // SEO description
        // *like* Все производители, фирмы (бренды) кормов и товаров для животных в спб *самолетик* с бесплатной доставкой по городу в интернет зоомагазине *галочка* фото, описание, применение* + *доллар* акции и скидки *сердечко* душевное обслуживание, гарантии *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

        $metaDescription = '👍  Все производители, фирмы (бренды) кормов и товаров для животных в спб 🚚 с бесплатной доставкой по городу в интернет зоомагазине ❗ фото, описание, применение. ₽ акции и скидки 🧡 душевное обслуживание, гарантии , самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

        SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
    }

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

        return view('livewire.site.pages.brands-page', [
            'brands' => $brands,
        ])
            ->extends('layouts.app')
            ->section('content');
    }
}
