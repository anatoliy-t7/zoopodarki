<?php

namespace App\Http\Livewire\Dashboard;

//use App\Jobs\ImportProductsFromExcel;
use App\Jobs\ProcessImportProduct1C;
use App\Jobs\ProcessOffersProduct1C;
use App\Traits\ExportImport;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Usernotnull\Toast\Concerns\WireToast;

class Excel extends Component
{
    use WireToast;
    use WithFileUploads;
    use ExportImport;

    public $excel;

    public function getFile()
    {

        $this->validate([
            'excel' => 'file|mimes:xlsx',
        ]);

        $fileName = $this->excel->getClientOriginalName();

        $this->excel->storeAs('', $fileName, 'excel');

        if (Storage::disk('excel')->exists($fileName)) {
            $filePath = storage_path('app/excel').'/'.$fileName;

            if ($this->importFromFile($filePath)) {
                 return toast()
                ->warning('Done')
                ->push();
            }

             return toast()
                ->warning('Not done')
                ->push();

            // ini_set('max_execution_time', 500);

            // $this->importData($collection);
            //ImportProductsFromExcel::dispatch($filePath);
        } else {
            toast()
                ->warning('No the file in the folder')
                ->push();
        }
    }

    public function importProducts1Cimport()
    {
        if (is_file(storage_path('app/sync').'/import.xml')) {
            $file = storage_path('app/sync').'/import.xml';

            ProcessImportProduct1C::dispatch($file);

            toast()
                ->success('File import.xml added to Job')
                ->push();
        } else {
            toast()
                ->warning('File doesn`t exist')
                ->push();
        }
    }

    public function importProducts1Coffers()
    {
        if (is_file(storage_path('app/sync').'/offers.xml')) {
            $file = storage_path('app/sync').'/offers.xml';

            ProcessOffersProduct1C::dispatch($file);

            toast()
                ->success('File offers.xml added to Job')
                ->push();
        } else {
            toast()
                ->warning('File doesn`t exist')
                ->push();
        }
    }

    public function exportProduct1c()
    {

        $filePath = $this->exportToFile();

        toast()
            ->warning('Done')
            ->push();

        return response()->download($filePath);
    }

    public function render()
    {
        return view('livewire.dashboard.excel')
            ->extends('dashboard.app')
            ->section('content');
    }
}
