<?php

namespace App\Http\Livewire\Site\Pages;

use App\Models\Catalog;
use App\Models\Category;
use Artesaos\SEOTools\Facades\SEOMeta;
use Livewire\Component;

class CatalogPage extends Component
{
    public $catalog;
    public $selectedCategories;

    public function mount($catalogslug)
    {
        $this->catalog = Catalog::where('slug', $catalogslug)->with('categories')->first();

        $categoriesId = $this->catalog->categories()
            ->where('show_in_catalog', true)
            ->get('id'); // Categories ID that marked with show in catalog

        $this->selectedCategories = Category::whereIn('id', $categoriesId)
            ->with(
                ['products' => function ($query) {
                    return $query->with(['media', 'variations', 'unit', 'brand'])
                        ->orderBy('products.popularity');
                }]
            )
            ->get();
        $this->setSeo();
    }

    public function setSeo()
    {
        //SEO TITLE
        // *like* название для SEO title + в спб с доставкой (цена от ) +*доллар* акции и скидки + петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

        $metaTitle = '👍 '
            . $this->catalog->meta_title
            . ' в спб с доставкой (цена от 100 ₽) акции и скидки петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

        // SEO description
        // *like* название для SEO description + в спб *самолетик* с бесплатной доставкой *галочка* фото, составы, описание, применение* + *доллар* акции и скидки *сердечко* душевное обслуживание, гарантии *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

        $metaDescription = '👍 '
            . $this->catalog->meta_description .
            ' в спб 🚚 с бесплатной доставкой❗ фото, составы, описание, применение ₽ акции и скидки 🧡 душевное обслуживание, гарантии, самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

        SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
    }

    public function render()
    {
        return view('livewire.site.pages.catalog-page')
            ->extends('layouts.app')
            ->section('content');
    }
}
