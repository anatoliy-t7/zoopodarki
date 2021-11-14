<?php

namespace App\Http\Livewire\Site;

use App\Models\Favorite;
use Livewire\Component;
use Usernotnull\Toast\Concerns\WireToast;

class AddToFavorite extends Component
{
    use WireToast;

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

        toast()
            ->success('Товар добавлен в избранное')
            ->push();

        $this->emitSelf('check');
    }

    public function removeFavorite($item_id)
    {
        $this->model->favorites()->delete($item_id);

        toast()
            ->success('Товар убран из избранных')
            ->push();

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
