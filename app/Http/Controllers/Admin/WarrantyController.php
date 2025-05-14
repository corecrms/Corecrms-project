<?php

namespace App\Http\Controllers\Admin;

use App\Models\Warranty;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\WarrantyResource;
// use App\Http\Requests\BrandStoreRequest;

class WarrantyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            $warranties = WarrantyResource::collection(Warranty::all());
            return view('back.warranties.index', compact('warranties'));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.warranties.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    $data = $request->validate([
            'warranty_name' => 'required',
            'warranty_type' => 'required',
            // 'warranty_period' => 'required',
            'warranty_description' => 'required',
        ]);

        $warrantyPeriodQuantity = $request->input('warranty_period_quantity');
        $warrantyPeriodUnit = $request->input('warranty_period_unit');

        if ($warrantyPeriodQuantity && $warrantyPeriodUnit) {
            $warrantyPeriod = $warrantyPeriodQuantity . ' ' . $warrantyPeriodUnit;
        } else {
            $warrantyPeriod = null;
        }
        $data['warranty_period'] = $warrantyPeriod;
        // dd($data);
        Warranty::create($data);

        return redirect()->route('warranties.index')
            ->with('success', 'Warranty created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function show(Warranty $warranty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function edit(Warranty $warranty)
    {
        return $this->handleException(function () use ($warranty) {
            $warranty = new WarrantyResource($warranty);
            return view('back.warranties.edit', compact('warranty'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Warranty $warranty)
    {
        $data = $request->validate([
            'warranty_name' => 'required',
            'warranty_type' => 'required',
            // 'warranty_period' => 'required',
            'warranty_description' => 'required',
        ]);
        
        $warrantyPeriodQuantity = $request->input('warranty_period_quantity');
        $warrantyPeriodUnit = $request->input('warranty_period_unit');

        if ($warrantyPeriodQuantity && $warrantyPeriodUnit) {
            $warrantyPeriod = $warrantyPeriodQuantity . ' ' . $warrantyPeriodUnit;
        } else {
            $warrantyPeriod = null;
        }
        $data['warranty_period'] = $warrantyPeriod;

        $warranty->update($data);

        return redirect()->route('warranties.index')
            ->with('success', 'Warranty updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warranty  $warranty
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warranty $warranty)
    {
        $warranty->delete();
        return redirect()->route('warranties.index')
            ->with('success', 'Warranty deleted successfully.');
    }
}
