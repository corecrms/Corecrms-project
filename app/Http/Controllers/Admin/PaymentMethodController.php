<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = PaymentMethod::all();
        // $payments = collect();

        return view('back.payment-methods.index',compact('payments'));
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
        	'name' => 'required',
        	'short_code' => 'required',
        	'status' => 'required',
        ]);
        $data = $request->all();
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        PaymentMethod::create($data);
        return redirect()->route('payment-methods.index')->with('success','Payment Method created successfully');
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
        	'short_code' => 'required',
        	'status' => 'required',
        ]);
        $data = $request->all();
        $data['updated_by'] = auth()->user()->id;
        PaymentMethod::find($id)->update($data);
        return redirect()->route('payment-methods.index')->with('success','Payment Method updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PaymentMethod::find($id)->delete();
        return redirect()->route('payment-methods.index')->with('success','Payment Method deleted successfully');
    }

    public function multipleDelete(){
        $ids = request('ids');
        PaymentMethod::whereIn('id',$ids)->delete();
        return response()->json(['status' => 200,'message' => 'Payment Methods deleted successfully.']);
    }


}
