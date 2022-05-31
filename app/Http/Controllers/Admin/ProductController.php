<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploading;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    use MediaUploading;

    public function index()
    {
        $productsPage = Product::with('category')->paginate(5);
        $product = Product::all();
        return response()->json([
            'data' => $productsPage,
            'product' => $product
        ]);

    }

    public function create()
    {

        $categories = Category::all()->pluck('name','id');

        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated() + ['code' =>  rand(1,1000)]);

        if($request->input('image', false)){
            $product->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
        }


        // return redirect()->route('admin.products.index')->with([
        //     'message' => 'successfully created !',
        //     'alert-type' => 'success'
        // ]);

        return response()->json([
            'status' => 200,
            'data' => $product,
            'message' => 'success',
        ]);
    }

    public function show(Product $product, $id)
    {
        $product = Product::find($id);
        return response()->json([
            'status' => 200,
            'data' => $product,
            'message' => 'success',
        ]);
        // return view('admin.products.show', compact('product'));


    }

    public function edit(Product $product)
    {
        $categories = Category::all()->pluck('name','id');

        return response()->json([
            'status' => 200,
            'data' => $product,
            'message' => 'success',
        ]);
        // return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product, $id)
    {
        $product->where('id', $id)->update($request->validated());

        if($request->input('image', false)){
            if(!$product->image || $request->input('image') !== $product->image->file_name){
                $product->image->delete();
                $product->addMedia(storage_path('tmp/uploads/' . $request->input('image')))->toMediaCollection('image');
            }
        }else if($product->image){
            $product->image->delete();
        }

        return response()->json([
            'status' => 200,
            'data' => $product,
            'message' => 'success',
        ]);
        // return redirect()->route('admin.products.index')->with([
        //     'message' => 'successfully updated !',
        //     'alert-type' => 'info'
        // ]);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'status' => 200,
            'data' => $product,
            'message' => 'success',
        ]);
        // return redirect()->route('admin.products.index')->with([
        //     'message' => 'successfully deleted !',
        //     'alert-type' => 'warning'
        // ]);
    }

    public function massDestroy()
    {
        Product::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

    public function search(Request $request){
        $products = Product::where('name', 'like', '%' . $request->search . '%')->get();

        return json_encode($products);
    }
}
