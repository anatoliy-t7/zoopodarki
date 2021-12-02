<?php

namespace App\Http\Livewire\Site;

use App\Mail\OrderOneClick;
use App\Models\Product1C;
use App\Models\Product;
use App\Models\Waitlist;
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
