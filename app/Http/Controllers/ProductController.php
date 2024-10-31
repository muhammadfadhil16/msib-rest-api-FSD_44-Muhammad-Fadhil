<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mengambil filter kategori dan nama produk dari request
        $category = $request->input('category');
        $productName = $request->input('product_name');

        $products = Product::query();

        // Menambahkan kondisi berdasarkan kategori jika ada
        if ($category) {
            $products->where('category', $category);
        }

        // Menambahkan kondisi berdasarkan nama produk jika ada
        if (!empty($productName)) {
            $products->where('product_name', 'LIKE', "%{$productName}%");
        }

        // Menjalankan query dan mengambil hasilnya
        $products = $products->get();

        if ($products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No products found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Products retrieved successfully',
            'data' => $products
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->json()->all();
        $validator = Validator::make($data, [
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $product = Product::create([
            'product_name' => $data['product_name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'category' => $data['category'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => [
                'id' => $product->id, // Menambahkan ID
                'product_name' => $product->product_name,
                'category' => $product->category, // Menambahkan kategori
                'description' => $product->description,
                'price' => (string)$product->price, // Mengubah harga menjadi string
                'created_at' => $product->created_at->toISOString(), // Format tanggal ke ISO 8601
                'updated_at' => $product->updated_at->toISOString() // Format tanggal ke ISO 8601
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'product_name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'category' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 400);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'description' => $product->description,
                'price' => $product->price,
                'category' => $product->category // Menambahkan kategori
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }



}
