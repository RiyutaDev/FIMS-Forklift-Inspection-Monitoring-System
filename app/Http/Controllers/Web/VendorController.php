<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    public function index()
    {
        return view('vendors.index');
    }
}
