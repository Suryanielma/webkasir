<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuKasirController extends Controller
{
    public function index()
    {
        return view('buku-kasir');
    }
}
