<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Product;

class CartsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $cart = Auth::user()->cart;
        return view('cart.index', compact('cart'));
    }
    public function store(Request $request)
    {
        $product = Product::find($request->get('product_id'));

        $cart = new Cart([
            'product_id' => $product->id,
            'qty' => $request->get('qty', 1),
            'price' => $product->price,
        ]);
        Auth::user()->carts()->save($cart);
        return redirect('/cart');
    }

    public function remove($id)
    {
        Auth::user()->carts()
            ->where('id', $id)->firstOrFail()->delete();
        return redirect('/cart');
    }
}
