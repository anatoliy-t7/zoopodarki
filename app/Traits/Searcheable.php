<?php

namespace App\Traits;

use App\Models\Product;
use MeiliSearch\Endpoints\Indexes as SearchIndexes;
use Usernotnull\Toast\Concerns\WireToast;

trait Searcheable
{
    use WireToast;

    public function searchForPage($q, $sortSelectedType, $sortBy)
    {
        try {
            return $result = Product::search($q)
                ->query(function ($query) {
                    $query
                        ->isStatusActive()
                        ->select(['id', 'name', 'slug', 'brand_id', 'brand_serie_id', 'unit_id'])
                        ->has('categories')
                        ->has('media')
                        ->with('media')
                        ->with('categories:id,slug,catalog_id')
                        ->with('categories.catalog:id,slug')
                        ->with('brand')
                        ->with('unit')
                        ->with('attributes')
                        ->with('variations');
                })
            // TODO orderBy не работает
                ->orderBy($sortSelectedType, $sortBy)
                ->paginate(32);
        } catch (\Throwable$th) {
            \Log::error('Ошибка поиска');
            \Log::error($th);

            toast()
                ->warning('Поиск временно не работает, мы уже работаем над этим')
                ->push();
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

    public function searchThis($q, $instant = false, $sortSelectedType = 'popularity', $sortBy = 'desc')
    {
        if (!$instant) {
            $result = $this->searchForPage($q, $sortSelectedType, $sortBy);

            if ($result->total() == 0) {
                $q = switcher_ru($q);

                $result = $this->searchForPage($q, $sortSelectedType, $sortBy);
            }

            if ($result->total() == 0) {
                $q = switcher_en($q);
                $result = $this->searchForPage($q, $sortSelectedType, $sortBy);
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
                if (empty($result['hits'])) {
                    $q = switcher_ru($q);
                }
            }

            if (empty($result['hits'])) {
                $result = [];
            }
        }

        return [
            'result' => $result,
            'search' => $q,
        ];
    }
}
