<?php

namespace App\Http\Livewire\Dashboard\Pages;

use App\Models\Brand;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class Home extends Component
{
    use WireToast;

    public $homePageBrands = [];
    public $brands;
    public $homePageBlockOneTitle;
    public $homePageBlockOneProducts;
    public $homePageBlockTwoTitle;
    public $homePageBlockTwoProducts;

    protected $listeners = ['save'];

    public function mount()
    {
        $this->homePageBrands = Setting::where('name', 'homePageBrands')->get('options')->pluck('options')->flatten(1)->toArray();

        $this->brands = Brand::orderBy('name', 'ASC')->get(['id', 'name']);
    }

    public function sendDataToFrontend()
    {
        if ($this->homePageBrands) {
            $this->dispatchBrowserEvent('set-home-page-brand', $this->homePageBrands);
        }

        $this->dispatchBrowserEvent('set-brands', $this->brands);
    }

    public function save($homePageBrands)
    {
        $this->homePageBrands = $homePageBrands;

        Setting::updateOrCreate(
            ['name' => 'homePageBrands'],
            ['options' => $this->homePageBrands]
        );

        // Clean the cache after saving
        Cache::forget('brandsOffer-homepage');

        toast()
            ->success('Сохраннено')
            ->push();
    }

    public function render()
    {
        return view('livewire.dashboard.pages.home')
            ->extends('dashboard.app')
            ->section('content');
    }
}
