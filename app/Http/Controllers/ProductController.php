<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class ProductController extends Controller
{
    public function index()
    {

        return view('layouts/backend/products/index');
    }
    public function getDataProduct()
    {
        $products = Product::query();


        return DataTables::of($products)
            ->addColumn('action', function ($row) {
                return '<button type="button"  class="btn  btn-info" data-id="' . $row->id . '" data-name="' . htmlspecialchars($row->name) . '" data-price="' . $row->price . '" data-quantity="' . $row->quantity . '" data-description="' . htmlspecialchars($row->description) . '" data-action="detail" onclick="f_action(this)" title="Detail"><i class="fas fa-eye"></i></button> ' .
                    '<button type="button"  class="btn  btn-warning" data-id="' . $row->id . '" data-name="' . htmlspecialchars($row->name) . '" data-price="' . $row->price . '" data-quantity="' . $row->quantity . '" data-description="' . htmlspecialchars($row->description) . '" data-action="edit" onclick="f_action(this)" title="Edit"><i class="fas fa-edit"></i></button> ' .
                    '<button type="button" class="btn  btn-danger" data-id="' . $row->id . '" data-action="delete" onclick="f_action(this)" title="Delete"><i class="fas fa-trash-alt"></i></button>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Disimpan'
        ], 201);
    }


    public function edit_save(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $product = Product::find($id);


        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',

            ], 404);
        }


        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data Berhasil Diupdate'
        ], 200);
    }


    public function destroy($id)
    {

        $product = Product::find($id);


        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }


        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }
}
