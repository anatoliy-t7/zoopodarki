<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOMeta;

class HomeController extends Controller
{
    public function page()
    {
        $this->setSeo();

        $discounts = cache()->remember('discounts-homepage', 60 * 60 * 24, function () {
            return Product::isStatusActive()
            ->has('media')
            ->has('categories')
            ->whereHas('attributes', function ($query) {
                $query->whereIn('product_attribute.attribute_id', [2761, 2505]);  // + подарок и большие мешки
            })
            ->orWhereHas('variations', function ($query) {
                $query->where('promotion_type', '!=', 0)->hasStock();
            })
            ->orWhere('discount_weight', 1)
            ->with('variations')
            ->with('attributes')
            ->with('media')
            ->with('brand')
            ->with('unit')
            ->with('categories')
            ->with('categories.catalog')
            ->take(5)
            ->get();
        });

        if ($brands = Setting::where('name', 'homePageBrands')->get('options')->pluck('options')->flatten(1)) {
            $brandsOffer = cache()->remember('brandsOffer-homepage', 60 * 60 * 24, function () use ($brands) {
                return Brand::whereIn('id', $brands->pluck('id'))->get(['id', 'name', 'logo', 'slug']);
            });
        }

        $popular1 = cache()->remember('popular1-homepage', 60 * 30 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
                $query->where('category_id', 20); // Наполнитель для кошачего туалета
            })
            ->hasStock()
            ->has('media')
            ->has('variations')
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

        $popular2 = cache()->remember('popular2-homepage', 60 * 30 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
                $query->where('category_id', 45); // Амуниция для собак
            })
            ->hasStock()
            ->has('media')
            ->has('variations')
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

        $popular3 = cache()->remember('popular3-homepage', 60 * 30 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
                $query->where('category_id', 61); // Аквариумы для рыбок
            })
            ->hasStock()
            ->has('media')
            ->has('variations')
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

        $popular4 = cache()->remember('popular4-homepage', 60 * 30 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
                $query->where('category_id', 58); // Кормушки для птиц
            })
            ->hasStock()
            ->has('media')
            ->has('variations')
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

        $popular5 = cache()->remember('popular5-homepage', 60 * 30 * 24, function () {
            return Product::withWhereHas('categories', function ($query) {
                $query->where('category_id', 39); // Игрушки для собак
            })
            ->hasStock()
            ->has('media')
            ->has('variations')
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

        return view('site.home', compact('brandsOffer', 'discounts', 'popular1', 'popular2', 'popular3', 'popular4', 'popular5'));
    }

    public function setSeo()
    {
        //SEO TITLE
        // Корма, игрушки, домики, шампуни, ошейники, переноски для собак, кошек и других животных, бесплатная доставка по городу *доллар* постоянные акции и скидки в новом интернет зоомагазине кормов и товаров для животных в спб *сердечко* душевное обслуживание, гарантии *галочка* большой выбор, фото, составы *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

        $metaTitle = 'Корма, игрушки, домики, шампуни, ошейники, переноски для собак, кошек и других животных, бесплатная доставка по городу ₽ остоянные акции и скидки в новом интернет зоомагазине кормов и товаров для животных в спб 🧡 душевное обслуживание, гарантии ❗ большой выбор, фото, составы. Cамовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект';

        // SEO description
        // *like* Лучший выбор кормов и лакомств по вкусам, одежды по породам, размерам и сантиметрам, витамины, кошачьи туалеты, наполнители, когтеточки *самолетик* с бесплатной доставкой по городу в новом интернет зоомагазине кормов и товаров для животных *доллар* постоянные акции и скидки *сердечко* душевное обслуживание, гарантии *галочка* большой выбор, фото, составы *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект

        $metaDescription = '👍 Лучший выбор кормов и лакомств по вкусам, одежды по породам, размерам и сантиметрам, витамины, кошачьи туалеты, наполнители, когтеточки 🚚 с бесплатной доставкой по городу в новом интернет зоомагазине кормов и товаров для животных ₽ постоянные акции и скидки *сердечко* душевное обслуживание, гарантии *галочка* большой выбор, фото, составы. Cамовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект ';

        SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
    }
}
