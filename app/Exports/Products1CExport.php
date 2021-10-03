<?php

namespace App\Exports;

use App\Models\Brand;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Products1CExport implements FromCollection, WithMapping, WithHeadings
{

    public function collection()
    {
        return Brand::all();
    }

    public function headings(): array
    {
        return [
            'id',
            'Название',
            // 'Вариации',
            // 'Каталоги',
            // 'Категории',
        ];
    }

    public function map($product): array
    {

        // $variations = null;

        // foreach ($product->variations as $key => $variation) {
        //     $variations = $variations . '' . $variation->name . '\n';
        // }
        // bin2hex($variations);
        // $variations = str_replace('\n', "\n", $variations);

        return [
            $product->id,
            $product->name,
            // $variations,
        ];
    }

    // public function collection()
    // {
    //     return Product::has('categories')
    //         ->with('variations')
    //         ->get();
    // }

    // public function headings(): array
    // {
    //     return [
    //         'id',
    //         'Название',
    //         'Вариации',
    //         // 'Каталоги',
    //         // 'Категории',
    //     ];
    // }

    // public function map($product): array
    // {

    //     $variations = null;

    //     foreach ($product->variations as $key => $variation) {
    //         $variations = $variations . '' . $variation->name . '\n';
    //     }
    //     bin2hex($variations);
    //     $variations = str_replace('\n', "\n", $variations);

    //     return [
    //         $product->id,
    //         $product->name,
    //         $variations,
    //     ];
    // }

}
