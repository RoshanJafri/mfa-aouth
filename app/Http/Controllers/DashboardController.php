<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
    $devices = auth()->user()
        ->devices()
        ->latest()
        ->get();

    return view('dashboard', compact('devices'));
    }
}
