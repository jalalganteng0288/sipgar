<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    public function index()
    {
        // Nanti diarahkan ke view admin.home
        return view('admin.home');
    }
}
