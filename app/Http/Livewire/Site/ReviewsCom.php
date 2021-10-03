<?php

namespace App\Http\Livewire\Site;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewsCom extends Component
{
    use WithPagination;

    public $model;

    public $sortSelectedName = 'Сначала новые';

    public $sortSelectedType = 'created_at';

    public $sortBy = 'desc';

    public $done = false;

    protected $listeners = ['render'];

    public $sortType = [
        0 => [
            'name' => 'Сначала новые',
            'type' => 'created_at',
            'sort' => 'desc',
        ],
        1 => [
            'name' => 'Сначала с высокой оценкой',
            'type' => 'rating',
            'sort' => 'desc',
        ],
        2 => [
            'name' => 'Сначала с низкой оценкой',
            'type' => 'rating',
            'sort' => 'asc',
        ],
    ];

    public function mount()
    {
        if ($this->userHasReview()) {
            $this->done = true;
        }
    }

    public function userHasReview()
    {
        if (! auth()->user()) {
            return false;
        }

        if (! Review::where('revieweable_id', $this->model->id)->where('user_id', auth()->user()->id)->first()
        ) {
            return false;
        }

        return true;
    }

    public function sortIt($type, $sort, $name)
    {
        $this->sortSelectedType = $type;
        $this->sortSelectedName = $name;
        $this->sortBy = $sort;
    }

    public function render()
    {
        $reviews = Review::where('revieweable_id', $this->model->id)
            ->where('status', 'published')
            ->with('user')
            ->with('media')
            ->with('revieweable')
            ->orderBy($this->sortSelectedType, $this->sortBy)
            ->simplePaginate();

        $gallery = collect();

        foreach ($reviews as $key => $item) {
            $gallery = $gallery->put($key, $item->getMedia('product-customers-photos'));
        }
        $gallery = $gallery->flatten();

        return view(
            'livewire.site.reviews-com',
            [
            'reviews' => $reviews,
            'gallery' => $gallery,
            ]
        );
    }
}
