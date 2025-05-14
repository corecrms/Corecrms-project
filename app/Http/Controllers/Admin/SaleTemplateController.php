<?php

namespace App\Http\Controllers\Admin;

use App\Models\SaleTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\SaleTemplateResource;
use App\Models\User;


class SaleTemplateController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            $sale_templates = SaleTemplateResource::collection(SaleTemplate::all());

            return view('back.sale_templates.index', compact('sale_templates'));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            
    
            return $this->handleException(function () {
                return view('back.sale_templates.create');
            }); 
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
            'name' => 'required|string|max:255',
            'type' => 'required',
            // 'default_template' => 'required|string|max:255',
        ]);

        $data['created_by'] = $data['updated_by'] = auth()->user()->id;

        SaleTemplate::create($data);

        return redirect()->route('sale_templates.index')
            ->with('success', 'Sales Template created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SaleTemplate  $saleTemplate
     * @return \Illuminate\Http\Response
     */
    public function show(SaleTemplate $saleTemplate)
    {
        return $this->handleException(function () use ($saleTemplate) {
            $sale_template = new SaleTemplateResource($saleTemplate);

            return view('back.sale_templates.show', compact('sale_template'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SaleTemplate  $saleTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleTemplate $saleTemplate)
    {
        return $this->handleException(function () use ($saleTemplate) {
            $sale_template = new SaleTemplateResource($saleTemplate);

            return view('back.sale_templates.edit', compact('sale_template'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SaleTemplate  $saleTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleTemplate $saleTemplate)
    {
        //  dd($request->all());
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required',
            // 'default_template' => 'nullable|string|max:255',
        ]);
        $data['updated_by'] = auth()->user()->id;

        $saleTemplate->update($request->all());
        return redirect()->route('sale_templates.index')
                ->with('success', 'Sales Template updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SaleTemplate  $saleTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleTemplate $saleTemplate)
    {
        $saleTemplate->delete();
        return redirect()->route('sale_templates.index')
                ->with('success', 'Sales Template deleted successfully');
    }

    public function change_status(SaleTemplate $saleTemplate)
    {
        if ($saleTemplate->default_template == 1) {
            $saleTemplate->default_template = 0;
        } else {
            SaleTemplate::where('default_template', 1)->update(['default_template' => 0]);
            $saleTemplate->default_template = 1;
        }

        $saleTemplate->save();

        return response()->json([
            'message' => 'Active Successfully',
            'default_template' => $saleTemplate->default_template
        ]);
    }
}
