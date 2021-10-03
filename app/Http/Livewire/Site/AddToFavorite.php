<?php

namespace App\Http\Livewire\Site;

use App\Models\Favorite;
use Livewire\Component;

class AddToFavorite extends Component
{

    public $model;
    public $mode = true;

    protected $listeners = ['check'];

    public function mount($model)
    {
        $this->model = $model;
        $this->check();
    }

    public function setFavorite()
    {
        $favorite = new Favorite([
            'user_id' => auth()->id(),
        ]);

        $this->model->favorites()->save($favorite);

        $this->dispatchBrowserEvent('toaster', ['message' => 'Товар добавлен в избранное']);

        $this->emitSelf('check');
    }

    public function removeFavorite($item_id)
    {
        $this->model->favorites()->delete($item_id);

        $this->dispatchBrowserEvent('toaster', ['message' => 'Товар убран из избранных']);

        $this->emitSelf('check');
    }

    public function check()
    {
        if ($this->model->favorites->firstWhere('favoritable_id', $this->model->id)) {
            $this->mode = true;
        } else {
            $this->mode = false;
        }
    }

    public function render()
    {
        return view('livewire.site.add-to-favorite');
    }
}
