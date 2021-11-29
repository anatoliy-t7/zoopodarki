<?php
namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Category;
use App\Models\Tag;

class CategoryController extends Controller
{
    public function show($catalog, $slug)
    {
        $category = Category::where('slug', $slug)
        ->with('tags', fn ($query) => $query->where('show_on_page', true))
        ->firstOrFail();

        $catalog = Catalog::where('slug', $catalog)
            ->firstOrFail();

        if ($slug !== $category->slug) {
            return redirect()->route('site.category', ['catalog' => $catalog->slug, 'slug' => $category->slug], 301);
        }

        $tag = [];

        return view('site.category', compact('category', 'catalog', 'tag'));
    }

    public function tag($catalog, $slug, $tagslug)
    {
        $category = Category::where('slug', $slug)
            ->with('tags', fn ($query) => $query->where('show_on_page', true))
            ->firstOrFail();

        $catalog = Catalog::where('slug', $catalog)
            ->firstOrFail();

        if ($slug !== $category->slug) {
            return redirect()->route('site.category', ['catalog' => $catalog->slug, 'slug' => $category->slug], 301);
        }

        $tag = Tag::where('slug', $tagslug)->firstOrFail();

        return view('site.category', compact('category', 'catalog', 'tag'));
    }
}
