<?php
namespace App\Http\Livewire\Dashboard;

use App\Mail\ReviewChangedToUser;
use App\Models\Media;
use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;

    public $search;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $itemsPerPage = 30;
    public $reviewId;
    public $body;
    public $statuses;
    public $status;
    public $reviewEdit;
    public $filteredBy;
    protected $listeners = ['save'];
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField',
        'sortDirection',
        'itemsPerPage',
        'filteredBy',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->statuses = config('constants.review_status');
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'desc' ? 'asc' : 'desc';
        } else {
            $this->sortDirection = 'desc';
        }
        $this->sortField = $field;
    }

    public function openForm($reviewId)
    {
        $this->reviewEdit = Review::where('id', $reviewId)
            ->with('media')
            ->with('user')
            ->with('revieweable')
            ->with('revieweable.categories')
            ->with('revieweable.categories.catalog')
            ->first();

        $this->reviewId = $this->reviewEdit->id;
        $this->body = $this->reviewEdit->body;
        $this->status = $this->reviewEdit->status;
    }

    public function save()
    {
        $this->validate([
            'body' => 'required',
            'status' => 'required',
        ]);

        DB::transaction(function () {
            $review = Review::updateOrCreate(
                ['id' => $this->reviewId],
                [
                    'body' => trim($this->body),
                    'status' => $this->status,
                ]
            );

            $review->load('user', 'user.orders');

            if (now()->subMonth()->toDateString() === $review->user->promotion_date) {
                $review->user()->update([
                    'review_date' => null,
                    'review' => 'off',
                ]);
            }

            if ($review->status === 'published' && count($review->user->orders) !== 0 && $review->user->review_date === null) {
                $review->user()->update([
                    'review_date' => now(),
                    'review' => 'on',
                ]);
            }

            $this->dispatchBrowserEvent('toast', ['message' => 'Отзыв сохранен с статусом "' . __('constants.review_status.' . $review->status) . '"']);

            $this->closeForm();

            $this->dispatchBrowserEvent('close');
        });
    }

    public function sandEmail()
    {
        if ($this->reviewEdit->user->email) {
            $productName = $this->reviewEdit->revieweable->name;

            $productLink = env('APP_URL') . '/pet/' . $this->reviewEdit->revieweable->categories[0]->catalog->slug . '/' . $this->reviewEdit->revieweable->categories[0]->slug . '/' . $this->reviewEdit->revieweable->slug;

            Mail::to($this->reviewEdit->user->email)->send(new ReviewChangedToUser($this->reviewEdit, $productName, $productLink));

            $this->dispatchBrowserEvent('toast', ['message' => 'Пользователь оповещен.']);
        } else {
            $this->dispatchBrowserEvent('toast', ['message' => 'У пользователя нет почты']);
        }
    }

    public function deletePhoto($photoId)
    {
        Media::find($photoId)->delete();

        $this->dispatchBrowserEvent('toast', ['message' => 'Фото удалено.']);
    }

    public function remove($itemId)
    {
        Review::find($itemId)->delete();

        $this->closeForm();

        $this->dispatchBrowserEvent('close');

        $this->dispatchBrowserEvent('toast', ['message' => 'Отзыв удален.']);
    }

    public function closeForm()
    {
        $this->reset(['reviewId', 'body', 'status', 'reviewEdit']);
    }

    public function render()
    {
        $reviews = Review::when($this->filteredBy, function ($query) {
            $query->where('status', $this->filteredBy);
        })
            ->when($this->search, function ($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                    ->whereHasMorph(
                        'revieweable',
                        'App\Models\Product',
                        function (Builder $query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        }
                    )
                    ->orWhereHas('user', function (Builder $query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });

                return $query;
            })
            ->with('media')
            ->with('user')
            ->with('revieweable')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->itemsPerPage);

        return view('livewire.dashboard.reviews', [
            'reviews' => $reviews,
        ])
            ->extends('dashboard.app')
            ->section('content');
    }
}
