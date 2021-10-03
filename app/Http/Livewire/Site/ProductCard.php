<?php
namespace App\Http\Livewire\Site;

use App\Mail\OrderOneClick;
use App\Models\Category;
use App\Models\Product;
use App\Models\Product1C;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ProductCard extends Component
{
    public $product;
    public $productAttributes;
    public $slug;
    public $category;
    public $catalog;
    public $tab = 1;
    public $related = null;
    protected $listeners = ['getProduct', 'buyOneClick'];

    public function mount()
    {
        $this->getProduct();
        $this->getRelatedProducts();
    }

    public function buyOneClick($orderOneClick, $productId, $count)
    {
        $url = env('APP_URL') . '/pet/' . $this->catalog->slug . '/' . $this->category->slug . '/' . $this->product->slug;

        $product1c = Product1C::where('id', $productId)->firstOrFail();

        Mail::to(env('MAIL_TO_MANAGER'))->send(new OrderOneClick($product1c, $count, $orderOneClick, $url));

        $this->dispatchBrowserEvent('close-modal');

        $this->dispatchBrowserEvent('toaster', ['message' => 'Наш оператор перезвонит вам в ближайшее время!']);
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
            ->whereNotIn('name', [$this->product->name])
            ->has('media')
            ->with('brand')
            ->with('unit')
            ->with('variations')
            ->with('media')
            ->inRandomOrder()
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.site.product-card');
    }
}
