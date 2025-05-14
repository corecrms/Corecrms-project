<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tax;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class TaxesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxes = Tax::all();
        return view('back.taxes.index',compact('taxes'));
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
    public function store(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'information' => 'required'
        ]);

        $tax = new Tax();
        $tax->name = $req->name;
        $tax->information = $req->information;
        //created_by updatedby
        // $tax->created_by = auth()->user()->id;
        // $tax->updated_by = auth()->user()->id;
        $tax->created_by = $tax->updated_by = auth()->user()->id;

        $tax->save();
        return redirect()->route('taxes.index')->with('success', 'Tax added successfully!');
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
        $tax = Tax::find($id);
        return view('back.taxes.edit',compact('tax'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, $id)
    {
        $tax = Tax::find($id);
        if($tax){
            $req->validate([
                'name' => 'required',
                'information' => 'required'
            ]);

            $tax->name = $req->name;
            $tax->information = $req->information;
            $tax->save();
            return redirect()->route('taxes.index')->with('success', 'Tax updated successfully! ');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tax $tax)
    {
        // $tax = Tax::find($id);

        if($tax){

            $tax->delete();
            $tax->deleted_by = auth()->user()->id;
            $tax->update();

            return redirect()->route('taxes.index')->with('success', 'Tax deleted successfully!');

        }
    }
}
