<?php

namespace App\Traits;

use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Models\Category;
use App\Models\Product1C;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

trait ExportImport
{
    public function importFromFile($filePath)
    {
        $collection = (new FastExcel())->import($filePath);

        try {
            $this->importData($collection);

            return true;
        } catch (\Throwable $th) {
            logger($th);

            return false;
        }

        unlink($filePath);
    }

    public function importData($collection)
    {
        foreach ($collection->toArray() as $key => $row) {
            if (Product::where('id', $row['id'])->first()) {
                $product = Product::where('id', $row['id'])
                ->with('attributes')
                ->first();

                // $categories = explode(',', $row['categories']);

                // if (count($categories) !== 0) {
                //     $product->categories()->detach();
                //     $product->categories()->attach($categories);
                // }

                $this->setAttributes($product, $row);
                // $this->setSpecialAttrs($product, $row);
                // $this->setBrand($product, $row['brand']);

                // if ($product->id === 17231) {
                //     logger('Done');
                // }

                unset($product, $row);
            }
        }
    }

    public function setAttributes(Product $product, $row)
    {
        $attributeItems = [2729, 959, 513, 2735];


        foreach ($attributeItems as $itemId) {
            $value = trim($row[$itemId]);

            if ($value === '1') {
                // dd($value);
                if (!$product->attributes()->where('attribute_item.id', $itemId)->first()) {
                    $product->attributes()->attach($itemId);
                }
            }
        }
    }

    public function setSpecialAttrs(Product $product, $row)
    {
        $attrs = ['морепродукты', 'птица', 'рыба', 'без курицы', 'без птицы', 'молочные продукты', 'крупы', 'потрошки', 'без риса'];

        $ingredients = Attribute::where('id', 26) // Ингредиенты
        ->with('items')
        ->first();

        foreach ($attrs as $key => $attr) {
            if ($attr === $row[$attr]) {
                if ($ingredients->items()->where('name', $attr)->first()) {
                    $attribute_item = $ingredients->items()->where('name', $attr)->first();

                    if (!$product->attributes()->where('attribute_item.id', $attribute_item->id)->first()) {
                        $product->attributes()->attach($attribute_item->id);
                    }
                } else {
                    $attribute_item = AttributeItem::create([
                        'name' => $attr,
                        'attribute_id' => 26,
                    ]);

                    $product->attributes()->attach($attribute_item->id);
                }
            }
        }

        unset($ingredients);
    }

    public function setBrand(Product $product, $brand)
    {
        if (!empty($brand) && Brand::where('name', $brand)->first()) {
            $brand = Brand::where('name', $brand)->first();
            $product->brand()->associate($brand->id)->save();
            unset($brand);
        }
    }

    public function exportToFile()
    {
        $collection = collect();

        $categories = Category::with('catalog', )
        ->get();

        // dd($categories);
        foreach ($categories as $key => $category) {
            $attributesId = Str::replace('.', ',', $category->attributes);
            $ids = explode(',', $attributesId);

            $attributes = Attribute::whereIn('id', $ids)
                    ->with('items')
                    ->orderBy('name', 'asc')
                    ->get();

            foreach ($attributes as $attribute) {
                foreach ($attribute->items as $item) {
                    $collection->push([
                        'catalog_name' => $category->catalog->name,
                        'category_name' => $category->name,
                        'attribute_name' => $attribute->name,
                        'item_id' => $item->id,
                        'item_name' => $item->name,
                    ]);
                }
            }
        }

        if (!file_exists(storage_path('app/excel'))) {
            mkdir(storage_path('app/excel'), 0777, true);
        }
        $path = storage_path('app/excel');
        $filePath = (new FastExcel($collection))->export($path . '/attributes.xlsx');

        return $filePath;
    }

    public function setData2($collection)
    {
        foreach ($collection->toArray() as $row) {
            if (Product::where('id', $row['id'])->first()) {
                $product = Product::where('id', $row['id'])
                // ->withWhereHas('attributes', fn ($q) => $q->where('attribute_item.attribute_id', 64))
                ->first();

                $product->name = $row['name'];
                $product->meta_title = $row['name'];
                $product->save();
                // foreach ($product->attributes as $attribute) {
                //     if ($attribute->attribute_id === 64) {
                //         $product->attributes()->detach($attribute->id);
                //     }
                // }

                // $product->attributes()->attach(2540);

                unset($product);
            }

            // if (Product1C::where('id', $row['id'])->first()) {
            //     $product1c = Product1C::where('id', $row['id'])
            //     ->with('product', 'product.unit')
            //     ->first();


            //     if ($row['гр'] !== '') {
            //         $product1c->unit_value = trim($row['гр']);
            //         $product1c->save();

            //         if ($product1c->product()->exists()) {
            //             $product1c->product->unit()->associate(1);
            //             $product1c->push();
            //         }
            //     }

            // if ($row['мл'] !== '') {
            //     $product1c->unit_value = trim($row['мл']);
            //     $product1c->save();

            //     if ($product1c->product()->exists()) {
            //         $product1c->product->unit()->associate(2);
            //         $product1c->push();
            //     }
            // }

            // if ($row['см'] !== '') {
            //     $product1c->unit_value = trim($row['см']);
            //     $product1c->save();

            //     if ($product1c->product()->exists()) {
            //         $product1c->product->unit()->associate(3);
            //         $product1c->push();
            //     }
            // }

            // if ($row['размер'] !== '') {
            //     logger($row['размер']);
            //     $product1c->unit_value = trim($row['размер']);
            //     $product1c->save();

            //     if ($product1c->product()->exists()) {
            //         $product1c->product->unit()->associate(5);
            //         $product1c->push();
            //     }
            // }

            // if ($row['шт'] !== '') {
            //     $product1c->unit_value = trim($row['шт']);
            //     $product1c->save();

            //     if ($product1c->product()->exists()) {
            //         $product1c->product->unit()->associate(6);
            //         $product1c->push();
            //     }
            // }



            //     unset($product1c);
            // }
            unset($row);
        }

        // logger($this->count);
    }

    public function setData($collection)
    {
        // $unit = ProductUnit::find(1);

        foreach ($collection->toArray() as $key => $row) {
            if (Product::where('id', $row['id'])->first()) {
                $product = Product::where('id', $row['id'])
                    ->with('attributes')
                    ->first();


                // if ($product->categories()) {
                //     # code...
                // }
                // $product->categories()->detach();

                $product->attributes()->attach(2553);


                // unset($unitValue, $product1c);
            }
            unset($row);
        }
    }

    public function hide($collection)
    {
        // $unit = ProductUnit::find(1);

        foreach ($collection->toArray() as $key => $row) {
            if (Product::where('id', $row['id'])->first()) {
                $product = Product::where('id', $row['id'])
                    ->first();

                $product->status = 'inactive';

                $product->save();


                // unset($unitValue, $product1c);
            }
            unset($row);
        }
    }
}
