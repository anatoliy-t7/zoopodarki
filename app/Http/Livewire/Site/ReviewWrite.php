<?php
namespace App\Http\Livewire\Site;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class ReviewWrite extends Component
{
    use WithFileUploads;

    public $body;
    public $stars;
    public $modelId;
    public $photos = [];

    public function attributes()
    {
        return [
            'photos.*' => 'фотография',
        ];
    }

    public function updatedPhotos()
    {
        $this->validate(
            [
                'photos' => 'max:5',
                'photos.*' => 'image|mimes:jpeg,png,jpg|max:512', // 512kb Max
            ],
            [
                'photos.max' => 'Вы можете загрузить максимум :max фотографий',
            ],
        );
    }

    public function saveReview()
    {
        $this->validate([
            'body' => 'required|min:10|max:500',
            'stars' => 'required',
        ]);

        \DB::transaction(function () {
            $model = Product::where('id', $this->modelId)->with('reviews')->first();

            $review = new Review();
            $review->rating = $this->stars;
            $review->body = clean($this->body, ['AutoFormat.AutoParagraph' => false]);
            $review->status = 'pending';
            $review->user_id = Auth::id();

            $model->reviews()->save($review);

            foreach ($this->photos as $photo) {
                $path = $photo->store('photos');

                $img = \Image::make(storage_path('app/public/') . $path);

                $img->resize(800, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $img->save();

                $review->addMedia(storage_path('app/public/') . $path)->toMediaCollection('product-customers-photos');
            }

            $this->dispatchBrowserEvent('close-writer');
        });

        $this->dispatchBrowserEvent('toast', ['text' => 'Ваш отзыв отправлен на проверку.']);

        $this->body = null;
        $this->stars = null;

        $this->emitTo('reviews-com', 'render');
    }

    public function render()
    {
        return view('livewire.site.review-write');
    }
}
