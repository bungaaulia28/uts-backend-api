<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::latest()->paginate(5);

        $response = [
            'status' => 'success',
            'massage' => 'List all Categories',
            'data' => $category,
        ];

        return response()->json($response, 200);
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
            'category' => 'required|unique:categories|min:2',

        ]);


        //jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'status' => 'faild',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }


        //jika validasi sukses masukan data level ke database
        $category = Category::create([
            'category' => $request->category,

        ]);


        //response
        $response = [
            'status' => 'success',
            'success'   => 'Add category success',
            'data'      => $category,
        ];


        return response()->json($response, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $category = Category::find($id);

        // Memeriksa apakah post ditemukan
        if ($category) {
            // Jika post ditemukan, kembalikan tampilan dengan data post
            return response()->json([
                'status' => 'success',
                'message' => 'Detail category found',
                'data' => $category
            ],200);
        } else {
            // Jika post tidak ditemukan, kembalikan respons 404
            return response()->json([
                'status' => 'failed',
                'message' => 'Detail category not found'
            ]);
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
            'category' => 'required|unique:categories|min:2',

        ]);


        //check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }


        //find categoty by ID
        $category = Category::find($id);


        $category->update([
            'category' => $request->category,

        ]);


        //response
        $response = [
            'status' => 'success',
            'massage'   => 'Update category success',
            'data'      => $category,
        ];


        return response()->json($response, 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);


        if ($category) {

            $category->delete();
            // Jika post ditemukan, kembalikan tampilan dengan data post
            return response()->json([
                'status' => 'success',
                'message' => 'Delete Category Success',
            ],200);
        } else {
            // Jika post tidak ditemukan, kembalikan respons 404
            return response()->json([
                'status' => 'failed',
                'message' => 'Data Category Not Found'
            ],404);
        }


    }
}
