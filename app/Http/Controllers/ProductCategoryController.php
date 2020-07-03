<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = ProductCategory::simplePaginate(10);

        // return response
        $response = [
            'success' => true,
            'message' => 'Categories retrieved successfully.',
        ];

        // We don't want to have nested data array to make API consumption simple
        $response = array_merge($response, $result->toArray());

        return response()->json($response, 200);
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
            'name' => 'required',
            'description' => 'string'
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

        $category = ProductCategory::create($input);

        // return response
        $response = [
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $category
        ];
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = ProductCategory::find($id);

        if (is_null($category)) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Category not found.',
            ];
            return response()->json($response, 404);
        }

        // return response
        $response = [
            'success' => true,
            'message' => 'Product retrieved successfully.',
            'data' => $category
        ];
        return response()->json($response, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $productCategory = ProductCategory::find($id);

        if (!$productCategory) {
            // return response
            $response = [
                'success' => false,
                'message' => 'Category not found',
            ];
            return response()->json($response, 404);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'string',
            'description' => 'string',
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

        $productCategory->name = $input['name'] ?? $productCategory->name;
        $productCategory->description = $input['description'] ?? $productCategory->description;

        $productCategory->save();

        // return response
        $response = [
            'success' => true,
            'message' => 'Category updated successfully.',
            'data' => $productCategory
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ProductCategory $productCategory
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();

        // return response
        $response = [
            'success' => true,
            'message' => 'Category deleted successfully.',
        ];
        return response()->json($response, 200);
    }
}
