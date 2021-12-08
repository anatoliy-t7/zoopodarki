<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Setting;

class HomeController extends Controller
{
    public function page()
    {
        $discountsCats = Product::withWhereHas('categories', function ($query) {
            $query->where('catalog_id', 1)->with('catalog');
        })
            ->withWhereHas('variations', function ($query) {
                $query->where('promotion_type', '!=', 0);
            })
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('attributes')
            ->with('variations')
            ->take(5)
            ->get();

        $discountsDogs = Product::withWhereHas('categories', function ($query) {
            $query->where('catalog_id', 2);
        })
            ->withWhereHas('variations', function ($query) {
                $query->where('promotion_type', '!=', 0);
            })
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('attributes')
            ->with('variations')
            ->take(5)
            ->get();

        $discountsBirds = Product::withWhereHas('categories', function ($query) {
            $query->where('catalog_id', 4);
        })
            ->withWhereHas('variations', function ($query) {
                $query->where('promotion_type', '!=', 0);
            })
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('attributes')
            ->with('variations')
            ->take(5)
            ->get();

        $discountsRodents = Product::withWhereHas('categories', function ($query) {
            $query->where('catalog_id', 3);
        })
            ->withWhereHas('variations', function ($query) {
                $query->where('promotion_type', '!=', 0);
            })
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('attributes')
            ->with('variations')
            ->with('categories.catalog')
            ->take(5)
            ->get();

        if ($brands = Setting::where('name', 'homePageBrands')->get('options')->pluck('options')->flatten(1)) {
            $brandsOffer = Brand::whereIn('id', $brands->pluck('id'))->get(['id', 'name', 'logo', 'slug']);
        }

        $popular1 = Product::withWhereHas('categories', function ($query) {
            $query->where('category_id', 20); // Наполнитель для кошачего туалета
        })
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('attributes')
            ->with('variations')
            ->with('categories.catalog')
            ->orderBy('popularity', 'desc')
            ->take(5)
            ->get();

        $popular2 = Product::withWhereHas('categories', function ($query) {
            $query->where('category_id', 44); // Одежда для собак
        })
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('attributes')
            ->with('variations')
            ->with('categories.catalog')
            ->orderBy('popularity', 'desc')
            ->take(5)
            ->get();

        $popular3 = Product::withWhereHas('categories', function ($query) {
            $query->where('category_id', 61); // Аквариумы для рыбок
        })
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('attributes')
            ->with('variations')
            ->with('categories.catalog')
            ->orderBy('popularity', 'desc')
            ->take(5)
            ->get();

        $popular4 = Product::withWhereHas('categories', function ($query) {
            $query->where('category_id', 58); // Кормушки для птиц
        })
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('attributes')
            ->with('variations')
            ->with('categories.catalog')
            ->orderBy('popularity', 'desc')
            ->take(5)
            ->get();

        return view('site.home', compact('brandsOffer', 'discountsCats', 'discountsDogs', 'discountsBirds', 'discountsRodents', 'popular1', 'popular2', 'popular3', 'popular4'));
    }
}
