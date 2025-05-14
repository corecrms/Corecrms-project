<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\LandingPageHeading;
use App\Http\Controllers\Controller;
use Doctrine\Inflector\LanguageInflectorFactory;

class LandingPageHeadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $headings = LandingPageHeading::find($id);
        return view('back.cms.landing-page-heading', compact('headings'));
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

        LandingPageHeading::UpdateOrCreate(['id' => $id], [
            'top_selling_product' => $request->top_selling_product,
            'our_recomandation' => $request->our_recomandation,
            'free_shipping_heading' => $request->free_shipping_heading,
            'free_shipping_desc' => $request->free_shipping_desc,
            'money_returns_heading' => $request->money_returns_heading,
            'money_returns_desc' => $request->money_returns_desc,
            'secure_payment_heading' => $request->secure_payment_heading,
            'secure_payment_desc' => $request->secure_payment_desc,
            'support_heading' => $request->support_heading,
            'support_desc' => $request->support_desc,
            'feature_category' => $request->feature_category,
            'shop_by_brands' => $request->shop_by_brands,
        ]);

        return redirect()->back()->with('message', 'Landing Page Heading Updated Successfully');
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
