<?php

namespace App\Imports;

use App\Models\AttributeItem;
use App\Models\Tag;
use App\Notifications\ImportHasFailedNotification;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DataImport implements ToCollection, WithChunkReading, WithHeadingRow, WithBatchInserts, WithEvents
{

    public $count   = [];
    public $filters = [];

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

        foreach ($rows as $row) {

            $attributesIds = explode(',', $row['attribute']);

            foreach ($attributesIds as $key => $attrId) {

                if (AttributeItem::where('id', intval($attrId))->with('attribute')->first()) {

                    $item = AttributeItem::where('id', $attrId)->with('attribute')->first()->toArray();

                    $filter = [
                        'id'             => $item['id'],
                        'name'           => $item['name'],
                        'attribute_id'   => $item['attribute_id'],
                        'attribute_name' => $item['attribute']['name'],
                    ];

                    array_push($this->filters, $filter);

                } else {
                    $value = [
                        'id'   => $attrId,
                        'name' => $row['name'],
                    ];
                    array_push($this->count, $value);
                }

            }

            Tag::create([
                "name"         => $row['name'],
                "title"        => $row['title'],
                "meta_title"   => $row['meta_title'],
                "filter"       => $this->filters,
                "category_id"  => $row['category'],
                "show_on_page" => 0,
            ]);

            $this->filters = [];

        }

        \Log::debug($this->count);
    }

    public function chunkSize(): int
    {
        return 2000;
    }

    public function batchSize(): int
    {
        return 2000;
    }
}
