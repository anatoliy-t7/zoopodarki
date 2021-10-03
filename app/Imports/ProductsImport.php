<?php

namespace App\Imports;

use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Models\Product;
use App\Notifications\ImportHasFailedNotification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithChunkReading, WithHeadingRow, WithBatchInserts, WithEvents
{
    public $count = 0;

    public $detach = 0;

    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function (ImportFailed $event) {
                $this->importedBy->notify(new ImportHasFailedNotification());
            },
        ];
    }

    public function collection(Collection $rows)
    {
        $attrsId = [34, 9];

        foreach ($rows as $row) {
            if (Product::where('id', $row['id'])->first()) {
                $product = Product::where('id', $row['id'])
                    ->with('attributes')
                    ->first();

                foreach ($attrsId as $itemId) {
                    logger('itemId: '.$itemId);
                    if ($row[$itemId] !== null) {
                        $attr = Attribute::where('id', $itemId)->with('items')->first();

                        $attributeItems = explode(';', $row[$itemId]);

                        foreach ($attributeItems as $value) {
                            $value = trim($value);

                            logger($value);

                            if ($attr->items()->where('name', $value)->first()) {
                                $attribute_item = $attr->items()->where('name', $value)->first();

                                if (! $product->attributes()->where('attribute_item.attribute_id', $attr->id)->first()) {
                                    $product->attributes()->attach($attribute_item->id);
                                } else {
                                    $product->attributes()->detach($attr->items()->pluck('id'));
                                    $this->detach = $this->detach + 1;
                                    $product->attributes()->attach($attribute_item->id);
                                }
                            } else {
                                $attribute_item = AttributeItem::create([
                                    'name'         => $value,
                                    'attribute_id' => $attr->id,
                                ]);

                                $product->attributes()->attach($attribute_item->id);
                            }
                        }
                    }
                }
                $this->count = $this->count + 1;
            }
        }

        \Log::debug('Got: '.$this->count);
        \Log::debug('detach: '.$this->detach);
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
