<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function home() {
        return view('dashboard/home');
    }

    public function stats() {
        return view('dashboard/stats');
    }

    public function import() {
        return view('dashboard/import/panel');
    }

    public function processImportMariage(Request $request) {
        dd($request);
        $request->file('csv')->store('csv');
        return view('dashboard/import/result');
    }

    public function processImportDeces(Request $request) {
        return view('dashboard/import/result');
    }
}
