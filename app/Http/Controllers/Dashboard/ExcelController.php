<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\Products1CExport;
use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{

    public function exportProducts()
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }

    public function exportProducts1C()
    {
        return Excel::download(new Products1CExport, 'products1c.xlsx');
    }

}
