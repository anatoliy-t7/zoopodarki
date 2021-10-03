<?php

namespace App\Exports;

use App\Models\Catalog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CatalogsExport implements FromCollection, WithMapping, WithHeadings
{

    protected $catalogId;

    public function __construct($catalogId)
    {
        $this->catalogId = $catalogId;
    }

    public function collection()
    {
        return Catalog::where('id', $this->catalogId)
            ->has('categories.products')
            ->get()
            ->pluck('products')
            ->flatten()
            ->unique('id');
    }

    public function headings(): array
    {
        return [
            'id',
            'product',
            'category_id',
        ];
    }

    public function map($product): array
    {

        $categories = $product->categories()->pluck('product_category.category_id')->toArray();

        return [
            $product->id,
            $product->name,
            $categories,
        ];
    }

}
