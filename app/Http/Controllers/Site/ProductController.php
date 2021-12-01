<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $category;
    public $catalog;
    public $slug;

    public function __construct(Request $request)
    {
        $this->category = Category::where('slug', $request->category)->first();

        $this->slug = $request->slug;

        $this->catalog = Catalog::where('slug', $request->catalog)->first();
    }

    public function show()
    {
        $category = $this->category;
        $catalog = $this->catalog;
        $slug = $this->slug;
        $tab = 1;

        return view('site.product', compact('category', 'tab', 'catalog', 'slug'));
    }

    public function showСonsist()
    {
        $category = $this->category;
        $catalog = $this->catalog;
        $slug = $this->slug;
        $tab = 2;

        return view('site.product', compact('category', 'tab', 'catalog', 'slug'));
    }

    public function showApplying()
    {
        $category = $this->category;
        $catalog = $this->catalog;
        $slug = $this->slug;
        $tab = 3;

        return view('site.product', compact('category', 'tab', 'catalog', 'slug'));
    }
}
