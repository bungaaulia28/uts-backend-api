<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Product::with(['category'])->get();

        $response = [
            'status' => 'success',
            'massage' => 'List all products',
            'data' => $produk,
        ];

        return response()->json($response, 200);


        // Mengembalikan tampilan dengan data posts
        return view('product.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'category_id' => 'required',
            'product' => 'required|min:2|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }


        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        //jika validasi sukses masukan data produk ke database
        $produk = Product::create([
            'category_id' => $request->category_id,
            'product' => $request->product,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $image->hashName(),
        ]);


        //response
        $response = [
            'status' => 'success',
            'message'   => 'Add product success',
            'data'      => $produk,
        ];


        return response()->json($response, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);

        // Memeriksa apakah post ditemukan
        if ($product) {
            // Jika post ditemukan, kembalikan tampilan dengan data post
            return response()->json([
                'status' => 'success',
                'message' => 'Detail product found',
                'data' => $product
            ],200);
        } else {
            // Jika post tidak ditemukan, kembalikan respons 404
            return response()->json([
                'status' => 'failed',
                'message' => 'Detail product not found'
            ],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'product' => 'required|min:2|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

        ]);


        //check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }


        //find level by ID
        $produk = Product::find($id);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $produk->update([
            'category_id' => $request->category_id,
            'product' => $request->product,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image' => $image->hashName(),

        ]);


        //response
        $response = [
            'status' => 'success',
            'massage'   => 'Update product success',
            'data'      => $produk,
        ];


        return response()->json($response, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);


        if ($product) {

            $product->delete();
            // Jika post ditemukan, kembalikan tampilan dengan data post
            return response()->json([
                'status' => 'success',
                'message' => 'Delete product Success',
            ],200);
        } else {
            // Jika post tidak ditemukan, kembalikan respons 404
            return response()->json([
                'status' => 'failed',
                'message' => 'Data Product Not Found'
            ],404);
        }

    }
}
