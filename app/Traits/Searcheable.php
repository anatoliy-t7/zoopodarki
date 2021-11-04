<?php
namespace App\Traits;

use App\Models\Product;
use MeiliSearch\Endpoints\Indexes as SearchIndexes;

trait Searcheable
{
    public function searchForPage($q)
    {
        try {
            return $result = Product::search($q)
              ->query(function ($query) {
                  $query
                      ->isStatusActive()
                      ->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])
                      ->has('categories')
                      ->with('media')
                      ->with('categories')
                      ->with('categories.catalog')
                      ->with('brand')
                      ->with('unit')
                      ->with('attributes')
                      ->with('variations')
                  ;
              })
              // TODO orderBy не работает
              ->orderBy($this->sortSelectedType, $this->sortBy)
              ->paginate(32);
        } catch (\Throwable $th) {
            \Log::error('Ошибка поиска');
            \Log::error($th);

            $this->dispatchBrowserEvent('toast', ['type' => 'error', 'text' => 'Поиск временно не работает, мы уже работаем над этим']);
        }
    }

    public function searchInstant($q)
    {
        return Product::search($q, function (SearchIndexes $meilisearch, $query, $options) {
            $options['attributesToHighlight'] = ['name'];

            return $meilisearch->search($query, $options);
        })
                ->take(10)
                ->raw();
    }

    public function searchThis($q, $instant = false)
    {
        if (!$instant) {
            $result = $this->searchForPage($q);
            // TODO if search server not runing
            if ($result->total() == 0) {
                $q = switcher_ru($q);

                $result = $this->searchForPage($q);
            }

            if ($result->total() == 0) {
                $q = switcher_en($q);
                $result = $this->searchForPage($q);
            }
        } else {
            $result = $this->searchInstant($q);

            if (empty($result['hits'])) {
                $q = switcher_ru($q);
                $result = $this->searchInstant($q);
            }

            if (empty($result['hits'])) {
                $q = switcher_en($q);
                $result = $this->searchInstant($q);
            }

            if (empty($result['hits'])) {
                $result = [];
            }
        }

        return [
            'result' => $result,
            'search' => $q
        ];
    }
}
