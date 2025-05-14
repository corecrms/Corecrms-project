<?php

namespace App\Http\Controllers\User;

use App\Models\Ad;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\ProductItem;
use Illuminate\Http\Request;
use App\Models\LandingPageHeading;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($code, $sku)
    {
        $category = Category::where('code', $code)->first();
        if (!$category) {
            return abort(404);
        }

        $product = Product::with('images', 'product_warehouses')->where('sku', $sku)
            // ->whereHas('product_warehouses', function($query){
            //     $query->where('quantity','>',0);
            // })
            ->first();

        if (!$product) {
            return abort(404);
        }
        $stock = $product->product_warehouses->sum('quantity');
        $wishlist = null;
        if (auth()->check()) {
            $wishlist = auth()->user()->wishlist()->where('product_id', $product->id)->first();
        }

        $brands = Brand::all();

        // Fetch top-selling products excluding the current product
        $top_selling_products = ProductItem::with('product.category', 'product.images')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as total_sales'))
            ->whereHas('product', function ($query) use ($product) {
                $query->where('id', '!=', $product->id);
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc') // Ordering by the total quantity sold
            ->take(6)
            ->get();

        $ads = Ad::first();
        $setting = Setting::first();
        $headings = LandingPageHeading::first();

        return view('user.product-view', compact('product', 'stock', 'brands', 'wishlist', 'top_selling_products', 'ads','setting','headings'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
