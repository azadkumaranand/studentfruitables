<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $product = Product::with('images')->get();
        // return $product;
        return view('index', ['products'=>$product]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.add_product');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return response()->json(['message'=>'hello world']);
        $request->validate([
            'product_name'=>'required|string|min:3',
            'mrp'=>'required|numeric',
        ]);

        $imgurl = [];
        
        foreach ($request->file('product_images') as $image) {
            $imgurl[] = $image->store('product_image', 'public');
        }
        
        $produt = Product::create([
            'product_name'=>$request->product_name,
            'mrp_price'=>$request->mrp,
            'selling_price'=>$request->selling_price,
            'short_desc'=>$request->short_description,
            'long_desc'=>$request->long_description,
            'vendor_id'=>Auth::user()->id,
            'tags'=>$request->tags,
            'category'=>$request->category
        ]);
        // return $produt;
       for($i=0;$i<count($imgurl); $i++){
            Image::create([
                'image_path'=>$imgurl[$i],
                'product_id'=>$produt->id
            ]);
        }

        return redirect()->back()->with('success', "Product has been created!");
    }

    public function shopdetails(Request $request){
        // return Product::find($request->query('product_id'));
        $product = Product::with('images')->where('id', '=', $request->query('product_id'))->first();
        // return $product;
        // return $product->images[0]['image_path'];
        return view('shop-detail', ['product'=>$product]);
    }

    // public function shopdetails($product_id){
    //     return $product_id;
    // }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
