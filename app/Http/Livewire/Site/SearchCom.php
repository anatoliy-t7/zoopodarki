<?php
namespace App\Http\Livewire\Site;

use App\Models\Product;
use Livewire\Component;
use MeiliSearch\Endpoints\Indexes as SearchIndexes;

class SearchCom extends Component
{
    public $search;
    protected $listeners = ['resetSearch'];

    public function resetSearch()
    {
        $this->reset();
    }

    public function searchThis($query)
    {
        try {

            // $result = Product::search($this->search)
            //     ->query(function ($builder) {
            //         $builder
            //             ->isStatusActive()
            //             ->with('variations', function ($query) {
            //                 $query
            //                     ->where('stock', '>', 0)
            //                     ->where('price', '>', 0);
            //             })
            //             ->select(['id', 'name', 'slug'])
            //             ->has('categories')
            //             ->with('media')
            //             ->with('categories')
            //             ->with('categories.catalog')
            //         ;
            //     })

            //     ->take(15)
            //     ->raw();

            return Product::search($this->search, function (SearchIndexes $meilisearch, $query, $options) {
                $options['attributesToHighlight'] = ['name'];

                return $meilisearch->search($query, $options);
            })
                ->take(10)
                ->raw();
        } catch (\Throwable $th) {
            \Log::error('Ошибка поиска');
            \Log::error($th);

            $this->dispatchBrowserEvent('toast', ['type' => 'error', 'text' => 'Поиск временно не работает, мы уже работаем над этим']);
        }
    }

    public function render()
    {
        $result = [];

        if ($this->search) {
            $result = $this->searchThis();

            if ($result === null) {
                $result = [];
            } else {
                if (empty($result)) {
                    $query = switcher_ru($this->search);
                    $result = $this->searchThis($query);
                }

                if (empty($result)) {
                    $query = switcher_en($this->search);
                    $result = $this->searchThis($query);
                }

                if (empty($result)) {
                    $result = [];
                }
            }
        }

        return view('livewire.site.search-com', [
            'result' => $result,
        ]);
    }
}
