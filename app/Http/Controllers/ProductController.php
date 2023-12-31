<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    // public function newProduct( Request $request) {
    //     $product = product::create($request->all());
    //     return response($product, 200);
    // }

    public function getProducts(){
        $products = product::all();
        foreach ($products as $product) {
            $product->image = asset(Storage::url($product->image));
        }
        return response()->json($products, 200);
    }

    public function newProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'price' => 'required|numeric',
            'price_sale' => 'required|numeric',
            'stock' => 'required',
            'expired' => 'required',
            'category_id' => 'required',
            'image' => 'required|image|max:2048',
            // 'precio' => 'required|numeric|regex:/^\d{1,4}(\.\d{1,2})?$/|max:9999.99',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $rutaArchivoImg = $request->file('image')->store('public/imgproductos');
        $producto = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'image' => $rutaArchivoImg,
            'stock' => $request->stock,
            'price_sale' => $request->price_sale,
            'expired' => $request->expired,
        ]);

        return response()->json(['producto' => $producto], 201);
    }


}
