<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $user_id = Auth::id();
        $product = Product::find($request->product_id);

        // Check if item already exist in cart

        $cartItems = Cart::where('product_id', $request->product_id)
            ->where('user_id', $user_id);

        if ($cartItems->count()) {
            $response = [
                'success' => false,
                'message' => "$product->name has already been added to cart"
            ];
            return response()->json($response, 400);
        }

        // Make sure we currently have enough quantity for this product
        if ($request->quantity > $product->quantity) {
            // return response
            $response = [
                'success' => false,
                'message' => "Your quantity exceeds current stock. We currently have $product->quantity $product->name in stock",
            ];
            return response()->json($response, 400);
        }

        $input['user_id'] = $user_id;

        $cart = Cart::create($input);

        // return response
        $response = [
            'success' => true,
            'message' => 'Cart created successfully.',
            'data' => $cart
        ];
        return response()->json($response, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show(Cart $cart)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function edit(Cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cart $cart)
    {
        // Increase ir decrease cart quantity based on request
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
