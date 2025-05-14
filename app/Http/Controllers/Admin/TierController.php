<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tier;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;

class TierController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tiers = Tier::all();
        return view('back.customers.tiers.index',compact('tiers'));
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
        $data =  $req->validate([
            'name' => 'required|string',
            // 'tier_type' => 'required|string',
            'discount' => 'required|integer'
        ]);

        $data['created_by'] = auth()->id();
        Tier::create($data);
        return redirect()->route('tiers.index')->with('success','Tier has been added successfully!');
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
        $tier = Tier::find($id);
        if($tier){
            return view('back.customers.tiers.edit',compact('tier'));
        }
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
        $tier = Tier::find($id);
        if($tier){
            $data =  $req->validate([
                'name' => 'required|string',
                // 'tier_type' => 'required|string',
                'discount' => 'required|integer'
            ]);
            $data['updated_by'] = auth()->id();
            $tier->update($data);
            return redirect()->route('tiers.index')->with('success','Tier has been updated successfully!');
        }
    }



    public function updateAllTiers(Request $req)
    {

        $req->validate([
            'discount' => 'required',
        ]);

        foreach($req->tier_id as $key => $tierId){
            $tier = Tier::findOrFail($tierId);
            $tier->discount = $req->discount[$tierId][0];
            $tier->active =   isset($req->active[$tierId]) ? 1 : 0 ;
            $tier->save();
        }
            return redirect()->route('customers.index')->with('success','Tier updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tier = Tier::find($id);

        if($tier){
            $tier->delete();
            return redirect()->route('tiers.index')->with('success','Tier has been deleted!');
        }
        else
        {
            return redirect()->route('tiers.index')->with('success','Tier has been deleted successfully!');
        }
    }
}
