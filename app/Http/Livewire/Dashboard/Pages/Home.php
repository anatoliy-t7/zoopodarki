<?php

namespace App\Http\Livewire\Dashboard\Pages;

use App\Models\Brand;
use App\Models\Setting;
use Livewire\Component;

class Home extends Component
{

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

        if ($homePageBlockOne = Setting::where('name', 'homePageBlockOne')->get('options')->pluck('options')->toArray()) {
            $this->homePageBlockOneTitle = $homePageBlockOne[0]['title'];
            $this->homePageBlockOneProducts = $homePageBlockOne[0]['products'];
        }

        if ($homePageBlockTwo = Setting::where('name', 'homePageBlockTwo')->get('options')->pluck('options')->toArray()) {
            $this->homePageBlockTwoTitle = $homePageBlockTwo[0]['title'];
            $this->homePageBlockTwoProducts = $homePageBlockTwo[0]['products'];
        }

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

        $homePageBlockOne = array(
            "title" => $this->homePageBlockOneTitle,
            "products" => $this->homePageBlockOneProducts,
        );

        Setting::updateOrCreate(
            ['name' => 'homePageBlockOne'],
            ['options' => $homePageBlockOne]
        );

        $homePageBlockTwo = array(
            "title" => $this->homePageBlockTwoTitle,
            "products" => $this->homePageBlockTwoProducts,
        );

        Setting::updateOrCreate(
            ['name' => 'homePageBlockTwo'],
            ['options' => $homePageBlockTwo]
        );

        $this->dispatchBrowserEvent('toast', ['text' => 'Сохраннено']);

    }

    public function render()
    {

        return view('livewire.dashboard.pages.home')
            ->extends('dashboard.app')
            ->section('content');

    }
}
