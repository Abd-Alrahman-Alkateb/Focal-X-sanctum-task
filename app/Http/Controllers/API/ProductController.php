<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return product::all();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name'    => 'required|min:2|max:15',
            'price'   => 'required|numeric',
            'description'   => 'required',
        ]);
        $validation['user_id'] = Auth::id();
        $product = Product::create($validation);
        return response(['message' => 'product was created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        $product = Product::findOrFail($product_id);
        return new ProductResource(['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);
        if ($product->user_id == Auth::id()) {
            $validation = $request->validate([
                'name'    => 'required|min:2|max:15',
                'price'   => 'required|numeric',
                'description'   => 'required',
            ]);
            $product->save();
            return response(['message' => 'product was edited']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id)
    {
        $product = Product::findOrFail($product_id);
        if ($product->user_id == Auth::id()) {

                $product->delete();
                return response(['message' => 'product successfully deleted!']);
            }
    }
}
