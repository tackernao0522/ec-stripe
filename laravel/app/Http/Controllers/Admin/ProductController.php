<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;

class ProductController extends Controller
{
    public function orders()
    {
        $orders = Order::all();
        return view('admin/orders', compact('orders'));
    }
}
