<?php
namespace App\Http\Livewire\Dashboard;

use App\Exports\CatalogsExport;
use App\Jobs\ProcessImportProduct1C;
use App\Jobs\ProcessOffersProduct1C;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Excel extends Component
{
    use WithFileUploads;

    public $totalSize;
    public $readBytes;
    public $count = 0;
    public $catalogs;
    public $catalogId;
    private $catalog;
    public $excel;

    public function mount()
    {
        $this->catalogs = Catalog::orderBy('sort')->get();
    }

    public function importProducts()
    {
        $this->validate([
            'excel' => 'file|mimes:xlsx'
        ]);

        $fileName = $this->excel->getClientOriginalName();

        $this->excel->storeAs('', $fileName, 'excel');

        if (Storage::disk('excel')->exists($fileName)) {
            $filePath = storage_path('app/excel') . '/' . $fileName;
            $collection = $this->importFromFile($filePath);

            //$file = $this->exportToFile($collection);

            $this->dispatchBrowserEvent('toast', [
                'text' => 'Done',
            ]);

            return response()->download($file);
        } else {
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'text' => 'No the file in the folder',
            ]);
        }
    }

    public function importFromFile($filePath)
    {
        $collection = collect();

        (new FastExcel)->import($filePath, function ($line) use ($collection) {
            return $collection->push([
                'id' => $line['id'],
            ]);
        });

        return $collection->toArray();
    }

    public function exportToFile($collection)
    {
        $products = Product::whereNotIn('id', $collection)
            ->whereHas('variations', function ($query) {
                $query->hasStock();
            })
            ->select('id', 'name')
            ->get();
        $path = storage_path('app/excel');
        $filePath = (new FastExcel($products))->export($path . '/export.xlsx');

        return $filePath;
    }

    public function exportCatalogs()
    {
        $this->catalog = $this->catalogs
            ->where('id', $this->catalogId)
            ->first();

        $export = new CatalogsExport($this->catalogId);

        return Excel::download($export, $this->catalog->name . '.xlsx');
    }

    public function importProducts1Cimport()
    {
        if (is_file(storage_path('sync') . '/import.xml')) {
            $file = storage_path('sync') . '/import.xml';

            ProcessImportProduct1C::dispatch($file);

            $this->dispatchBrowserEvent('toast', [
                'text' => 'File import.xml added to Job',
            ]);
        } else {
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'text' => 'File doesn`t exist',
            ]);
        }
    }

    public function importProducts1Coffers()
    {
        if (is_file(storage_path('sync') . '/offers.xml')) {
            $file = storage_path('sync') . '/offers.xml';

            ProcessOffersProduct1C::dispatch($file);

            $this->dispatchBrowserEvent('toast', [
                'text' => 'File offers.xml added to Job',
            ]);
        } else {
            $this->dispatchBrowserEvent('toast', [
                'type' => 'error',
                'text' => 'File doesn`t exist',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.dashboard.exchange')
            ->extends('dashboard.app')
            ->section('content');
    }
}
