<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Http\Controllers\Controller;

class PosController extends Controller
{
    public function index(){

        $products = Product::all();
        return response()->json([
            'data' => $products,
        ], 200);
    }
}
