<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->paginate(32);
        return view('site.brands.index', compact('brands'));
    }

    public function show($slug)
    {
        $brand    = Brand::where('slug', $slug)->firstOrFail();

        return view('site.brands.brand', compact('brand'));
    }
}
