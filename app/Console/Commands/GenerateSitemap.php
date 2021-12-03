<?php

namespace App\Console\Commands;

use App\Models\Catalog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $sitemapIndex = SitemapIndex::create();

        $productChunks = Product::select(['id', 'slug', 'updated_at'])
            ->isStatusActive()
            ->whereHas('variations', function ($query) {
                $query->where('price', '>', 0);
            })
            ->with('categories')
            ->with('categories.catalog')
            ->chunk(5000, function ($products, $chunk) use ($sitemapIndex) {
                $sitemapName = 'products_sitemap_' . $chunk . '.xml';
                $sitemap = Sitemap::create();

                foreach ($products as $product) {
                    $sitemap->add(Url::create('/pet' . '/' . $product->categories[0]->catalog->slug . '/' . $product->categories[0]->slug . '/' . $product->slug)
                            ->setLastModificationDate($product->updated_at));
                }

                $sitemap->writeToFile(public_path($sitemapName));
                $sitemapIndex->add($sitemapName);
            });

        $catalogs = $this->catalogs();
        $sitemapIndex->add($catalogs);

        $categories = $this->categories();
        $sitemapIndex->add($categories);

        $sitemapIndex->writeToFile(public_path('sitemap.xml'));
    }

    public function catalogs()
    {
        $catalogs = Catalog::all();
        $sitemapName = 'catalogs.xml';
        $sitemap = Sitemap::create();
        foreach ($catalogs as $key => $catalog) {
            $sitemap->add(Url::create('/pet' . '/' . $catalog->slug)
                    ->setLastModificationDate(now()));
        }
        $sitemap->writeToFile(public_path($sitemapName));

        return $sitemapName;
    }

    public function categories()
    {
        $categories = Category::with('catalog')->get();
        $sitemapName = 'categories.xml';
        $sitemap = Sitemap::create();
        foreach ($categories as $key => $category) {
            $url = '/pet' . '/' . $category->catalog->slug . '/' . $category->slug;
            $sitemap->add(Url::create($url)
                    ->setLastModificationDate(now()));
        }
        $sitemap->writeToFile(public_path($sitemapName));

        return $sitemapName;
    }
}
