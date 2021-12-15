<?php

namespace App\Traits;

use App\Models\Attribute;
use App\Models\AttributeItem;
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
            $this->setData2($collection);

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
                ->with('attributes', 'attributes.attribute', 'categories', 'brand')
                ->first();

                $categories = explode(',', $row['categories']);

                if (count($categories) !== 0) {
                    $product->categories()->detach();
                    $product->categories()->attach($categories);
                }

                $this->setAttributes($product, $row);
                $this->setSpecialAttrs($product, $row);
                $this->setBrand($product, $row['brand']);

                if ($product->id === 17231) {
                    logger('Done');
                }

                unset($product, $row, $categories);
            }
        }
    }

    public function setAttributes(Product $product, $row)
    {
        $attrsId = [2, 3, 6, 7, 8, 10, 11, 13, 14, 15, 16, 17, 18, 19, 20, 22, 23, 24, 26, 28, 30, 31, 32, 33, 34, 35, 36, 38, 39, 40, 41, 42, 43, 44, 45];

        $product->attributes()->detach();

        foreach ($attrsId as $itemId) {
            if (data_get($row, $itemId) !== '') {
                $attr = Attribute::where('id', $itemId)
                ->with('items')
                ->first();

                $attributeItems = explode(',', $row[$itemId]);

                foreach ($attributeItems as $value) {
                    $value = trim($value);

                    if ($attr->items()->where('name', $value)->first()) {
                        $attribute_item = $attr->items()->where('name', $value)->first();

                        if (!$product->attributes()
                        ->where('attribute_item.attribute_id', $attr->id)
                        ->where('attribute_item.id', $attribute_item->id)
                        ->first()) {
                            $product->attributes()->attach($attribute_item->id);
                        }
                    } else {
                        $attribute_item = AttributeItem::create([
                            'name' => $value,
                            'attribute_id' => $attr->id,
                        ]);

                        $product->attributes()->attach($attribute_item->id);

                        unset($attribute_item);
                    }
                }
                unset($attr);
            }
        }
        unset($attrsId);
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

        $products = Product::whereHas('categories', function ($query) {
            $query->where('product_category.category_id', 44);
        })->get();

        foreach ($products as $key => $product) {
            // $arrayCategories = [];
            // foreach ($product->product->categories as $key => $cat) {
            //     array_push($arrayCategories, $cat->name);
            // }

            // $arrayAttributes = [];
            // foreach ($product->unit_value as $key => $cat) {
            //     array_push($arrayAttributes, $cat->name . PHP_EOL);
            // }
            // $unitName = '';
            // if ($product->product->unit !== null) {
            //      $unitName = $product->product->unit->name;
            // }

            $collection->push([
                'id' => $product->id,
                'name' => $product->name,
                // 'categories' => implode(", ", $arrayCategories),
                // $unitName => $product->unit_value,
            ]);

            // $arrayAttributes = [];
            // $arrayCategories = [];
        }

        $path = storage_path('app/excel');
        $filePath = (new FastExcel($collection))->export($path . '/products.xlsx');

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
        $unit = ProductUnit::find(1);

        foreach ($collection->toArray() as $key => $row) {
            if (Product1C::where('id', $row['id'])->first()) {
                $product1c = Product1C::where('id', $row['id'])
                    ->with('product', 'product.unit')
                    ->first();

                $unitValue = Str::replace(',', '.', $row['unit_value']);
                $product1c->unit_value = trim($unitValue);

                $product1c->save();

                if ($product1c->product !== null) {
                    $product1c->product->unit_id = 1;
                    $product1c->push();
                }

                unset($unitValue, $product1c);
            }
            unset($row);
        }
    }
}
