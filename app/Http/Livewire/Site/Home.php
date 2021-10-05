<?php
namespace App\Http\Livewire\Site;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Setting;
use Livewire\Component;

class Home extends Component
{
    public $brandsSlider;
    public $homePageBlockOne;
    public $homePageBlockOneTitle;
    public $homePageBlockTwo;
    public $homePageBlockTwoTitle;

    public function mount()
    {
        if ($brands = Setting::where('name', 'homePageBrands')->get('options')->pluck('options')->flatten(1)) {
            $this->brandsSlider = Brand::whereIn('id', $brands->pluck('id'))->get(['id', 'name', 'logo', 'slug']);
        }

        if ($homePageBlockOne = Setting::where('name', 'homePageBlockOne')->get('options')->pluck('options')->toArray()) {
            $this->homePageBlockOneTitle = $homePageBlockOne[0]['title'];

            $homePageBlockOneProducts = explode(',', $homePageBlockOne[0]['products']);

            $this->homePageBlockOne = Product::whereIn('id', $homePageBlockOneProducts)->with(['variations', 'brand', 'categories', 'categories.catalog'])->get(['id', 'name', 'slug']);
        }

        if ($homePageBlockTwo = Setting::where('name', 'homePageBlockTwo')->get('options')->pluck('options')->toArray()) {
            $this->homePageBlockTwoTitle = $homePageBlockTwo[0]['title'];

            $homePageBlockTwoProducts = explode(',', $homePageBlockTwo[0]['products']);

            $this->homePageBlockTwo = Product::whereIn('id', $homePageBlockTwoProducts)->with(['variations', 'brand', 'categories', 'categories.catalog'])->get(['id', 'name', 'slug']);
        }
    }

    public function render()
    {
        return view('livewire.site.home');
    }
}
