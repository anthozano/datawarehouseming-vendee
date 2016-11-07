<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CSVImportController extends Controller
{
    public function dashBoard() {
        return view('dashboard/dashboard');
    }

    public function import() {
        return view('dashboard/import');
    }
}
