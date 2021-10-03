<?php

namespace App\Http\Livewire\Dashboard;

use App\Exports\CatalogsExport;
use App\Jobs\ImportProductsFromExcel;
use App\Jobs\ProcessImportProduct1C;
use App\Jobs\ProcessOffersProduct1C;
use App\Models\Catalog;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class Exchange extends Component
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
        $this->catalogs = Catalog::orderBy('sort')
            ->get();

    }

    public function importProducts()
    {

        $fileName = $this->excel->getClientOriginalName();

        $this->excel->storeAs('', $fileName, 'excel');

        if (Storage::disk('excel')->exists($fileName)) {

            $filePath = storage_path('excel') . '/' . $fileName;

            ImportProductsFromExcel::dispatch($filePath);

            $this->dispatchBrowserEvent('toaster', ['message' => 'File success added to job']);

        } else {

            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'No the file in the folder']);

        }

    }

    public function exportCatalogs()
    {

        $this->catalog = $this->catalogs->where('id', $this->catalogId)->first();

        $export = new CatalogsExport($this->catalogId);

        return Excel::download($export, $this->catalog->name . '.xlsx');

    }

    public function importProducts1Cimport()
    {
        if (is_file(storage_path('sync') . '/import.xml')) {

            $file = storage_path('sync') . '/import.xml';

            ProcessImportProduct1C::dispatch($file);

            $this->dispatchBrowserEvent('toaster', ['message' => 'File import.xml added to Job']);

        } else {

            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'File doesn`t exist']);

        }

    }

    public function importProducts1Coffers()
    {
        if (is_file(storage_path('sync') . '/offers.xml')) {
            $file = storage_path('sync') . '/offers.xml';

            ProcessOffersProduct1C::dispatch($file);

            $this->dispatchBrowserEvent('toaster', ['message' => 'File offers.xml added to Job']);

        } else {

            $this->dispatchBrowserEvent('toaster', ['class' => 'bg-red-500', 'message' => 'File doesn`t exist']);

        }

    }

    public function render()
    {
        return view('livewire.dashboard.exchange')
            ->extends('dashboard.app')
            ->section('content');
    }
}
