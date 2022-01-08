<?php

namespace App\Http\Livewire\Site;

use App\Models\Favorite;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class AddToFavorite extends Component
{
    use WireToast;

    public $model = [];
    public $mode = true;

    protected $listeners = ['checkIfItFavorite'];

    public function mount($model)
    {
        $this->model = $model;
        $this->checkIfItFavorite();
    }

    public function setFavorite()
    {
        $favorite = new Favorite([
            'user_id' => auth()->id(),
        ]);

        $this->model->favorites()->save($favorite);

        toast()
            ->success('Товар добавлен в избранное')
            ->push();

        $this->emitSelf('checkIfItFavorite');
    }

    public function removeFavorite($item_id)
    {
        $this->model->favorites()->delete($item_id);

        toast()
            ->warning('Товар убран из избранных')
            ->push();

        $this->emitSelf('checkIfItFavorite');
    }

    public function checkIfItFavorite()
    {
        if ($this->model->favorites->firstWhere('product_id', $this->model->id)) {
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
