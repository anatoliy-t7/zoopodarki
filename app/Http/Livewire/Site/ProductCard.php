<?php

namespace App\Http\Livewire\Site;

use App\Mail\OrderOneClick;
use App\Models\Product1C;
use App\Models\Product;
use App\Models\Waitlist;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class ProductCard extends Component
{
    use WireToast;

    public $product;
    public $productAttributes;
    public $productslug;
    public $category;
    public $email;
    public $catalog;
    public $tab = 1;
    public $related = null;
    protected $listeners = [
        'getProduct',
        'buyOneClick',
        'preOrder',
    ];

    public function mount()
    {
        if ($this->productslug === 'tag') {
            return redirect()->route('site.home');
        }
        $this->getProduct();
        $this->getRelatedProducts();

        if (auth()->user()) {
            $this->email = auth()->user()->email;
        }
        $this->setSeo();
    }

    public function setSeo()
    {
        $minPrice = \DB::table('products_1c')
            ->where('product_id', $this->product->id)
            ->where('price', '>', 0)
            ->min('price');

        $brand = '';
        if ($this->product->brand()->exists()) {
            if ($this->product->brand->name_rus) {
                $brand = $this->product->brand->name_rus;
            } else {
                $brand = $this->product->brand->name;
            }
        }



        if ($this->tab === 2) {
            //SEO TITLE
            // Cостав: + название товара + купите + в новом интернет зоомагазине спб с доставкой по городу + (цена  от ...) + *доллар* акции + *петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект + (бренд рус)

            $metaTitle = 'Cостав: ' . $this->product->meta_title
            . ' купите в новом интернет зоомагазине спб с доставкой по городу (цена от ' . $minPrice . ' ₽) акции, петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект, ' . $brand ;

            // SEO description
            // *like* Cостав: + название товара + купите в спб *самолетик* с бесплатной доставкой по городу *галочка* фото, описание, применение* + *доллар* акции и скидки *сердечко* душевное обслуживание, гарантии *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект* + (бренд на русском)

            $metaDescription = '👍 Cостав: '
            . $this->product->meta_title .
            ' (в наличии) купите в спб 🚚 с бесплатной доставкой по городу❗ фото, составы, описание, применение ₽ акции и скидки 🧡 душевное обслуживание, гарантии, самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект, ' . $brand ;
        } elseif ($this->tab === 3) {
            //SEO TITLE
            // Применение: название товара  + купите + в новом интернет зоомагазине спб с доставкой по городу + (цена  от ...) + *доллар* акции + *петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект + (бренд рус)

            $metaTitle = 'Применение: ' . $this->product->meta_title
            . ' купите в новом интернет зоомагазине спб с доставкой по городу (цена от ' . $minPrice . ' ₽) акции, петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект, ' . $brand ;

            // SEO description
            // *like* Применение: название товара + купите + в спб *самолетик* с бесплатной доставкой по городу *галочка* фото, описание, применение, дозировка* + *доллар* акции и скидки *сердечко* душевное обслуживание, гарантии *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект* + (бренд на русском)

            $metaDescription = '👍 Применение: '
            . $this->product->meta_title .
            ' (в наличии) купите в спб 🚚 с бесплатной доставкой по городу❗ фото, составы, описание, применение ₽ акции и скидки 🧡 душевное обслуживание, гарантии, самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект, ' . $brand ;
        } else {
            //SEO TITLE
            // название товара + купите + в новом интернет зоомагазине спб с доставкой по городу + (цена  от ...) + *доллар* акции + *петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект + (бренд рус)

            $metaTitle = $this->product->meta_title
            . ' купите в новом интернет зоомагазине спб с доставкой по городу (цена от ' . $minPrice . ' ₽) акции, петшопы в Невском районе, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект, ' . $brand ;

            // SEO description
            // *like* название товара + (в наличии) + купите в спб *самолетик* с бесплатной доставкой по городу *галочка* фото, составы, описание, применение * + *доллар* акции и скидки *сердечко* душевное обслуживание, гарантии *самовывоз из Невского района, метро пр. Большевиков, метро Ладожская, метро Гражданский проспект* + (бренд на русском)

            $metaDescription = '👍 '
            . $this->product->meta_title .
            ' (в наличии) купите в спб 🚚 с бесплатной доставкой по городу❗ фото, составы, описание, применение ₽ акции и скидки 🧡 душевное обслуживание, гарантии, самовывоз из Невского района, метро пр. Большевиков, метро Ладожская и метро Гражданский проспект, ' . $brand ;
        }

        SEOMeta::setTitle($metaTitle)->setDescription($metaDescription);
    }

    public function preOrder(int $itemId, $email)
    {
        $this->email = $email;

        $this->validate([
            'email' => 'required|email',
        ]);

        if (Waitlist::where('email', $this->email)
            ->where('product1c_id', $itemId)
            ->first()) {
            return toast()
                ->warning('Вы уже сделали заказ, мы сообщим вам когда товар поступит в продажу')
                ->push();
        }

        if (!auth()->user()) {
            Waitlist::create([
                'email' => $this->email,
                'status' => 'pending',
                'user_id' => null,
                'product1c_id' => $itemId,
            ]);
        } else {
            Waitlist::create([
                'email' => $this->email,
                'status' => 'pending',
                'user_id' => auth()->user()->id,
                'product1c_id' => $itemId,
            ]);
        }

        return toast()
            ->success('Ваш заказ принят, мы сообщим вам когда товар поступит в продажу')
            ->push();
    }

    public function buyOneClick($orderOneClick, $productId, $count)
    {
        $url = config('constants.website_url')
        . '/pet/' . $this->catalog->slug . '/'
        . $this->category->slug . '/'
        . $this->product->slug;

        $product1c = Product1C::where('id', $productId)->firstOrFail();

        Mail::to(config('constants.manager_mail'))->send(new OrderOneClick($product1c, $count, $orderOneClick, $url));

        $this->dispatchBrowserEvent('close-modal');

        toast()
            ->success('Наш оператор перезвонит вам в ближайшее время')
            ->push();
    }

    public function getProduct()
    {
        $this->product = Product::where('slug', $this->productslug)
            ->isStatusActive()
          //  ->has('media')
            ->whereHas('variations', function ($query) {
                $query
                    ->where('price', '>', 0)
                    ->orderBy('unit_value');
            })
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'published');
            },
            ])
            ->with('attributes.attribute')
            ->withWhereHas('attributes', function ($query) {
                $query->where('show', true);
            })
            ->with('brand')
            ->with('serie')
            ->with('unit')
            ->with('variations')
            ->with('media')
            ->firstOrFail();

        $attributes = collect($this->product->attributes);

        $this->productAttributes = $attributes->unique('name')->sortBy('name')->groupBy('attribute_id');

        $this->productAttributes = $this->productAttributes->values()->toArray();
    }

    public function getRelatedProducts()
    {
        $this->related = Product::isStatusActive()
            ->select(['id', 'name', 'slug'])
            ->whereHas('categories', function ($query) {
                $query->where('category_id', $this->category->id);
            })
            ->hasStock()
            ->whereNotIn('id', [$this->product->id])
            ->has('media')
            ->with('brand')
            ->with('unit')
            ->with('variations')
            ->with('media')
            ->orderBy('price_avg', 'asc')
            ->inRandomOrder()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.site.product-card');
    }
}
