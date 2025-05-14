<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Models\AddToCart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddToCartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cartItems = AddToCart::where('customer_id',auth()->id())->get();
        $products = Product::with('images')->take(6)->get();
        // dd($products);
        return view('user.addtocart.index',compact('cartItems','products'));
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

        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required',
        ]);


        $data = $request->all();
        if(!auth()->user()){
            return redirect()->route('login')->with('error','Please login to add to card');
        }

        if($request->input('warehouse_id') == null || $request->input('warehouse_id') == ''){
            return redirect()->back()->with('error','Error: Please select warehouse.');
        }

        if($request->input('quantity') > $request->input('warehouse_stock')){
            return redirect()->back()->with('error','Error: Selected warehouse have only '.$request->input('warehouse_stock').' quantity.');
        }

        $addToCart = AddToCart::where('customer_id',auth()->id())->where('product_id',$data['product_id'])->first();
        if($addToCart){
            return redirect()->back()->with('error','Product already in the cart.');
        }
        $data['customer_id'] = auth()->id();
        AddToCart::create($data);
        return redirect()->back()->with('success','Product add to cart successfully');
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
        AddToCart::find($id)->delete();
        return redirect()->back()->with('success','Product remove from cart successfully');
    }
}
