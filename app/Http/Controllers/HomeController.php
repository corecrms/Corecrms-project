<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Warehouse;
use App\Models\ProductItem;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\BannerSection;
use App\Models\LandingPageHeading;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return redirect(route('dashboard'));
        $products = Product::with('images')->where('status','1')->take(8)->get();
        $new_products = Product::with('images')->where('status','1')->latest()->take(6)->get();
        $brands = Brand::all();
        $categories = Category::where('status', 1)->get();
        $top_selling_products = ProductItem::with('product.category', 'product.images')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as total_sales'))
            ->groupBy('product_id')
            ->limit(3)->get();
        $banner_sections = BannerSection::get();
        $ads = Ad::all();
        $headings = LandingPageHeading::first();
        $setting = Setting::first();
        return view('user.index', compact('new_products', 'categories', 'brands', 'top_selling_products', 'banner_sections', 'ads', 'products','headings','setting'));
    }


    public function search(Request $request)
    {
        $search = $request->input('search');
        $products = Product::where('name', 'like', '%' . $search . '%')->get();
        return view('user.search', compact('products'));
    }


    public function viewCategoryProducts($code){
        // dd($code);
        $category = SubCategory::where('code',$code)->first();
        $query = Product::with('images','product_warehouses')->where('status','1')->where('sub_category_id',$category->id);



        $request = request();
        if ($request->has('min_price') && $request->has('max_price')) {
            if(!auth()->check()){
                return redirect()->back()->with('error','Please login first');
            }
            $query->whereBetween('sell_price', [$request->min_price, $request->max_price]);
        }

        $products = $query->get();


        $min_price = Product::where('sell_price', '>', 0)->min('sell_price');
        $max_price = Product::where('sell_price', '>', 0)->max('sell_price');
        $ads = Ad::first();
        $setting = Setting::first();
        return view('user.category_products',compact('products','category','min_price','max_price','ads','setting'));
    }




    public function switchWarehouse(Request $request)
    {
        $storeId = $request->input('warehouse_id');
        session(['selected_warehouse_id' => $storeId]);
        // dd(session());

        return redirect()->back();
    }

    // public function shopPage(){
    //     $products = Product::with('images')->get();
    //     $categories = Category::where('status',1)->get();
    //     $brands = Brand::all();
    //     return view('user.shop',compact('products','categories','brands'));
    // }


    public function shopPage(Request $request)
    {

        $query = Product::with('images','category')->where('status','1');

        if ($request->has('categories')) {

            if(!auth()->check()){
                return redirect()->back()->with('error','Please login first');
            }

            $query->whereHas('category', function ($q) use ($request) {
                $q->whereIn('id', $request->categories);
            });

            // dd($request->categories);
        }


        if ($request->has('brands')) {
            if(!auth()->check()){
                return redirect()->back()->with('error','Please login first');
            }
            $query->whereHas('brand', function ($q) use ($request) {
                $q->whereIn('id', $request->brands);
            });
        }


        if ($request->has('min_price') && $request->has('max_price')) {
            if ($request->min_price !== null && $request->max_price !== null) {
                if (!auth()->check()) {
                    return redirect()->back()->with('error', 'Please login first');
                }
                $query->whereBetween('sell_price', [$request->min_price, $request->max_price]);
            }
        }


        $products = $query->paginate(12);

        if ($request->ajax()) {
            return response()->json($products);
        }

        $categories = Category::where('status', 1)->get();
        $min_price = Product::where('sell_price', '>', 0)->min('sell_price');
        $max_price = Product::where('sell_price', '>', 0)->max('sell_price');
        // $max_price = 1000.00;
        $brands = Brand::all();
        $ads = Ad::first();
        $setting = Setting::first();


        return view('user.shop', compact('products', 'categories', 'brands','min_price','max_price','ads','setting'));
    }
}
