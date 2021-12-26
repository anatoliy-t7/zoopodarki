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
            if (Product1C::where('id', $row['id'])->first()) {
                $product1c = Product1C::where('id', $row['id'])
                ->first();


                $this->setAttributes($product1c, $row);


                unset($product1c, $row);
            }
        }
    }

    public function setAttributes(Product1C $product1c, $row)
    {
        $product1c->unit_value = 'на развес';
        $product1c->save();

        // foreach ($attrsId as $itemId) {
        //     if (data_get($row, $itemId) !== '') {
        //         $attr = Attribute::where('id', $itemId)
        //         ->with('items')
        //         ->first();


        //         $product->unit()->associate(5);


        //         $value = trim($row[$itemId]);



        //         if (!empty($value)) {
        //             if ($attr->items()->exists() && $attr->items()->where('name', $value)->first()) {
        //                 $attribute_item = $attr->items()->where('name', $value)->first();
        //                 // dd($attribute_item);
        //                 if (!$product->attributes()
        //                             ->where('attribute_item.attribute_id', $attr->id)
        //                             ->where('attribute_item.id', $attribute_item->id)
        //                             ->first()) {
        //                     $product->attributes()->attach($attribute_item->id);
        //                 }
        //             } else {
        //                 $attribute_item = AttributeItem::create([
        //                     'name' => $value,
        //                     'attribute_id' => $attr->id,
        //                 ]);

        //                 $product->attributes()->attach($attribute_item->id);
        //             }
        //             unset($attribute_item);
        //         }

        //         unset($attr);
        //     }
        // }
        unset($attrsId);
    }

    public function exportToFile()
    {
        //$collection = $this->exportOfAttributesFromCategories();
        $collection = $this->exportOfAttributes();

        if (!file_exists(storage_path('app/excel'))) {
            mkdir(storage_path('app/excel'), 0777, true);
        }
        $path = storage_path('app/excel');
        $filePath = (new FastExcel($collection))->export($path . '/attributes.xlsx');

        return $filePath;
    }

    public function exportOfAttributesFromCategories()
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
                        'hide' => $item->show,
                    ]);
                }
            }
        }

        return $collection;
    }

    public function exportOfAttributes()
    {
        $collection = collect();

        $attributes = Attribute::all();

        foreach ($attributes as $attribute) {
            $collection->push([
                'id' => $attribute->id,
                'name' => $attribute->name,
            ]);
        }

        return $collection;
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

    public function createTagsFromCollection($collection)
    {
        foreach ($collection->toArray() as $key => $row) {
            $attributesId = explode(';', $row['filter']);

            $tagAttributesId = [];

            foreach ($attributesId as $attrId) {
                $attrId = trim($attrId);

                if (!empty($attrId) && AttributeItem::where('id', $attrId)->first()) {
                    $item = AttributeItem::where('id', $attrId)->with('attribute')->first()->toArray();

                    $filter = [
                        'id' => $item['id'],
                        'name' => $item['name'],
                        'attribute_id' => $item['attribute_id'],
                        'attribute_name' => $item['attribute']['name'],
                    ];

                    array_push($tagAttributesId, $filter);
                }
            }

            Tag::create([
                'name' => $row['name'],
                'title' => $row['title'],
                'meta_title' => $row['meta_title'],
                'category_id' => $row['category'],
                'filter' => $tagAttributesId,
            ]);

            unset($tagAttributesId, $row);
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
