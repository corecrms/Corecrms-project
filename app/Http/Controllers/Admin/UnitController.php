<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\UnitResource;

class UnitController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            // $units = UnitResource::collection(Unit::all());
            $units = Unit::with('baseUnit')->orderBy('id', 'desc')->get();
            $parentUnit = Unit::where('parent_id', '0')->get();
            return view('back.units.index', compact('units','parentUnit'));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          return view('back.units.create');
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
            'short_name' => 'required',
        ]);

        if ($req->base_unit) {
            $req->validate([
                'operator' => 'required',
                'operator_value' => 'required|numeric'
            ]);

            $unit = new Unit();
            $unit->name = $req->name;
            $unit->short_name = $req->short_name;
            $unit->parent_id = $req->base_unit;
            $unit->operator = $req->operator;
            $unit->operator_value = $req->operator_value;
            $unit->save();
            return redirect()->route('units.index')->with('success', 'Unit added successfully!');
        }
        else {
            $unit = new Unit();
            $unit->name = $req->name;
            $unit->short_name = $req->short_name;
            $unit->parent_id = 0;
            $unit->save();
            return redirect()->route('units.index')->with('success', 'Unit added successfully!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = Unit::find($id);
        $parentUnit = Unit::where('parent_id', '0')->get();
        return view('back.units.edit', compact('unit', 'parentUnit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req,$id)
    {

        $unit = Unit::find($id);
        $req->validate([
            'name' => 'required',
            'short_name' => 'required',
        ]);

        if ($req->base_unit) {
            $req->validate([
                'operator' => 'required',
                'operator_value' => 'required|numeric'
            ]);

            $unit->name = $req->name;
            $unit->short_name = $req->short_name;
            $unit->parent_id = $req->base_unit;
            $unit->operator = $req->operator;
            $unit->operator_value = $req->operator_value;
            $unit->save();
            return redirect()->route('units.index')->with('success', 'Unit updated successfully!');
        }
        else {
            $unit->name = $req->name;
            $unit->short_name = $req->short_name;
            $unit->parent_id = 0;
            $unit->operator = "*";
            $unit->operator_value = "1";
            $unit->save();
            return redirect()->route('units.index')->with('success', 'Unit updated successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
            $unit->delete();

            return redirect()->route('units.index')
                ->with('success', 'Unit deleted successfully.');
    }
    public function deleteUnit(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                Unit::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Unit Deleted Successfully!']);
           
        }          

    }
}
