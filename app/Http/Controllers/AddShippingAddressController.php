<?php

namespace App\Http\Controllers;

use App\Models\CustomerShippingAddress;
use App\Models\User;
use Illuminate\Http\Request;

class AddShippingAddressController extends Controller
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
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'state_code' => 'required',
            'country' => 'required',
            'country_code' => 'required',
            'postal_code' => 'required',
        ]);

        $data  = $request->all();
        $data['customer_id'] = auth()->id();

        CustomerShippingAddress::create($data);

        return redirect()->back()->with('success', 'Shipping address added successfully');
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
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone_no' => 'required',
            'address' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'state_code' => 'required',
            'country' => 'required',
            'country_code' => 'required',
            'postal_code' => 'required',
        ]);

        $data  = $request->all();
        $data['customer_id'] = auth()->id();

        CustomerShippingAddress::find($id)->update($data);

        return redirect()->back()->with('success', 'Shipping address updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CustomerShippingAddress::find($id)->delete();
        return redirect()->back()->with('success', 'Shipping address deleted successfully');
    }
}
