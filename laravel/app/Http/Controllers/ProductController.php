<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $product = Product::orderBy('name', 'asc')->get();
        return view('product.index', compact('product'));
    }

    public function show($id)
    {
        $product = Product::find($id);
        return view('product.show', compact('product'));
    }
}
