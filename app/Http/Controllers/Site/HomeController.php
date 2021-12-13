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
        $discountsCats = cache()->remember('discountsCats-homepage', 60 * 60 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
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
        });


        $discountsDogs = cache()->remember('discountsDogs-homepage', 60 * 60 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
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
        });

        $discountsBirds = cache()->remember('discountsBirds-homepage', 60 * 60 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
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
        });

        $discountsRodents = cache()->remember('discountsRodents-homepage', 60 * 60 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
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
        });

        if ($brands = Setting::where('name', 'homePageBrands')->get('options')->pluck('options')->flatten(1)) {
            $brandsOffer = cache()->remember('brandsOffer-homepage', 60 * 60 * 24, function () use ($brands) {
                return Brand::whereIn('id', $brands->pluck('id'))->get(['id', 'name', 'logo', 'slug']);
            });
        }

        $popular1 = cache()->remember('popular1-homepage', 60 * 60 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
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
        });

        $popular2 = cache()->remember('popular2-homepage', 60 * 60 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
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
        });

        $popular3 = cache()->remember('popular3-homepage', 60 * 60 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
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
        });

        $popular4 = cache()->remember('popular4-homepage', 60 * 60 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
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
        });

        return view('site.home', compact('brandsOffer', 'discountsCats', 'discountsDogs', 'discountsBirds', 'discountsRodents', 'popular1', 'popular2', 'popular3', 'popular4'));
    }
}
