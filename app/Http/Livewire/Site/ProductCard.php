<?php
namespace App\Http\Livewire\Site;

use App\Mail\OrderOneClick;
use App\Models\Category;
use App\Models\Product1C;
use App\Models\Product;
use App\Models\Waitlist;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class ProductCard extends Component
{
    use WireToast;

    public $product;
    public $productAttributes;
    public $slug;
    public $category;
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
        $this->getProduct();
        $this->getRelatedProducts();
        $this->seo();
    }

    public function seo()
    {
        SEOMeta::setTitle($this->product->meta_title);
        SEOMeta::setDescription($this->product->meta_description);
        OpenGraph::setTitle($this->product->meta_title);
        OpenGraph::setDescription($this->product->meta_description);
        OpenGraph::addProperty('type', 'product');

        if ($this->product->media->count() > 0) {
            OpenGraph::addImage(config('app.url') . $this->product->getMedia('product-images')[0]->getUrl('medium'));
        }
    }

    public function preOrder(int $itemId)
    {

        if (!auth()->user()) {
            $this->dispatchBrowserEvent('auth');

            return toast()
                ->success('Вам необходимо авторизоваться что бы заказать товар')
                ->push();
        }

        Waitlist::create([
            'phone' => auth()->user()->phone,
            'email' => auth()->user()->email,
            'status' => 'pending',
            'user_id' => auth()->user()->id,
            'product1c_id' => $itemId,
        ]);

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
        $this->product = Product::where('slug', $this->slug)
            ->isStatusActive()
            ->whereHas('variations', function ($query) {
                $query
                    ->where('price', '>', 0)
                    ->orderBy('unit_value');
            })
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'published');
            },
            ])
            ->with('brand')
            ->with('attributes')
            ->with('attributes.attribute')
            ->with('serie')
            ->with('unit')
            ->with('variations')
            ->with('media')
            ->first();

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
