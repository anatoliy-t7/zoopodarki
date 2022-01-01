<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Artesaos\SEOTools\Facades\SEOMeta;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('isActive', true)->firstOrFail();

        SEOMeta::setTitle($page->meta_title)->setDescription($page->meta_description);

        return view('site.pages.page', compact('page'));
    }
}
