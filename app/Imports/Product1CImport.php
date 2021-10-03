<?php

namespace App\Imports;

use App\Exports\ArrayExport;
use App\Models\Product1C;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Product1CImport implements ToCollection, WithChunkReading, WithHeadingRow, WithBatchInserts
{
    public $count  = 0;
    public $images = 0;
    public $column = 1;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            if (Product1C::where('id', $row['id'])->exists()) {

                $product1c = Product1C::where('id', $row['id'])->with('product')->first();

                if ($row['weight'] !== null) {
                    $product1c->unit_value = $row['weight'];
                    $product1c->weight     = $row['weight'];

                    $product1c->product()->where('id', $product1c->product_id)->update(['unit_id' => 1]);

                }

                $product1c->save();

                // if ($product1c->product()->exists()) {

                //     $product = Product::find($product1c->product->id);

                //     $product->save();

                //     unset($product);

                // }

            }

            unset($product1c);

        }
        // $this->count = $this->count->toArray();
        // $this->export($this->count);

        // \Log::debug($this->count);
        // \Log::debug('added images: ' . $this->images);
    }

    public function newNameImage($image, $product_name, $count = '0')
    {
        $extension = Str::afterLast($image, '.');
        $random    = Str::random(4);

        return Str::slug($product_name, '-') . '-' . $count . '-' . $random . '.' . $extension;
    }

    public function storeImage($image, $name, $product)
    {
        if (is_file(storage_path('import/images/') . $image)) {
            // \Log::debug($image);
            $product->addMedia(storage_path('import/images/') . $image)->usingFileName($name)->toMediaCollection('product-images');

            $this->images += $this->images;
        }

        unset($name);
        unset($image);
    }

    public function UR_exists($url)
    {
        $headers = get_headers($url);
        return stripos($headers[0], "200 OK") ? true : false;
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    // public function getImage()
    // {
    //     $i = 0;
    //     foreach ($spreadsheet->getActiveSheet()->getDrawingCollection() as $drawing) {
    //         if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing) {
    //             ob_start();
    //             call_user_func(
    //                 $drawing->getRenderingFunction(),
    //                 $drawing->getImageResource()
    //             );
    //             $imageContents = ob_get_contents();
    //             ob_end_clean();
    //             switch ($drawing->getMimeType()) {
    //                 case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_PNG:
    //                     $extension = 'png';
    //                     break;
    //                 case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_GIF:
    //                     $extension = 'gif';
    //                     break;
    //                 case \PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing::MIMETYPE_JPEG:
    //                     $extension = 'jpg';
    //                     break;
    //             }
    //         } else {
    //             $zipReader = fopen($drawing->getPath(), 'r');
    //             $imageContents = '';
    //             while (!feof($zipReader)) {
    //                 $imageContents .= fread($zipReader, 1024);
    //             }
    //             fclose($zipReader);
    //             $extension = $drawing->getExtension();
    //         }

    //         dd($imageContents);

    //         $myFileName = '00_Image_' . ++$i . '.' . $extension;
    //         file_put_contents($myFileName, $imageContents);
    //     }

    // }$spreadsheet->getActiveSheet()->setBreak('D10', \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_COLUMN);

    public function export($array)
    {
        $export = new ArrayExport($array);

        return Excel::download($export, 'array.xlsx');
    }

}
