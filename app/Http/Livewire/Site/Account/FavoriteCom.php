<?php

namespace App\Http\Livewire\Site\Account;

use App\Models\Favorite;
use Livewire\Component;
use Livewire\WithPagination;

class FavoriteCom extends Component
{
    use WithPagination;

    protected $listeners = ['refresh'];

    public function refresh()
    {
        $this->emit('check');
    }

    public function render()
    {
        return view('livewire.site.account.favorite-com', ['favorites' => Favorite::where('user_id', auth()->user()->id)
                ->with(['product' => function ($query) {
                    $query->with('variations')->with('variations.media');
                }])
                ->with('product.brand')
                ->with('product.categories')
                ->with('product.categories.catalog')
                ->latest()
                ->paginate(12)]
        );
    }
}
