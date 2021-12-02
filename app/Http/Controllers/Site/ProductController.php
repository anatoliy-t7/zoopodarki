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
    public $productslug;

    public function __construct(Request $request)
    {
        $this->catalog = Catalog::where('slug', $request->catalogslug)->first();
        $this->category = Category::where('slug', $request->categoryslug)->first();

        $this->productslug = $request->productslug;


        // dd($this->catalog);
    }

    public function show()
    {
        $category = $this->category;
        $catalog = $this->catalog;
        $productslug = $this->productslug;
        $tab = 1;

        return view('site.product', compact('category', 'tab', 'catalog', 'productslug'));
    }

    public function showСonsist()
    {
        $category = $this->category;
        $catalog = $this->catalog;
        $productslug = $this->productslug;
        $tab = 2;

        return view('site.product', compact('category', 'tab', 'catalog', 'productslug'));
    }

    public function showApplying()
    {
        $category = $this->category;
        $catalog = $this->catalog;
        $productslug = $this->productslug;
        $tab = 3;

        return view('site.product', compact('category', 'tab', 'catalog', 'productslug'));
    }
}
