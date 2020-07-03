<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Product::simplePaginate(10);

        // return response
        $response = [
            'success' => true,
            'message' => 'Products retrieved successfully.',
        ];

        // We don't want to have nested data array to make API consumption simple
        $response = array_merge($response, $result->toArray());

        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'description' => 'string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'category_id' => 'required|exists:product_categories,id',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 400);
        }

        $input['user_id'] = Auth::id();

        $product = Product::create($input);

        // return response
        $response = [
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Product not found.',
            ];
            return response()->json($response, 404);
        }

        // return response
        $response = [
            'success' => true,
            'message' => 'Product retrieved successfully.',
            'data' => $product
        ];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        // Check that he product exist
        if (!$product) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Product not found',
            ];
            return response()->json($response, 404);
        }

        if (Auth::id() !== $product->user_id) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Unauthorised',
            ];
            return response()->json($response, 401);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'string',
            'description' => 'string',
            'price' => 'numeric',
            'category_id' => 'exists:product_categories,id',
        ]);

        if ($validator->fails()) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
            ];
            return response()->json($response, 400);
        }


        // If the value is sent as part of the request update it, else use existing value
        $product->name = $input['name'] ?? $product->name;
        $product->description = $input['description'] ?? $product->description;
        $product->price = $input['price'] ?? $product->price;
        $product->category_id = $input['category_id'] ?? $product->category_id;
        $product->save();

        // return response
        $response = [
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => $product
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // If the product doesn't belong to the user return 401
        if (Auth::id() !== $product->user_id) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Unauthorised',
            ];
            return response()->json($response, 401);
        }

        $product->delete();

        // return response
        $response = [
            'success' => true,
            'message' => 'Product deleted successfully.',
        ];
        return response()->json($response, 200);
    }
}
