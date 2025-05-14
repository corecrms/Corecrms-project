<?php

namespace App\Http\Controllers\Admin;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\BrandResource;
use App\Http\Requests\BrandStoreRequest;
use Illuminate\Support\Facades\Storage;


class BrandController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // function __construct()
    // {
    //     $this->middleware('permission:category-list|category-create|category-edit|category-delete
    //      ', ['only' => ['index', 'show']]);
    //     $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            $brands = BrandResource::collection(Brand::latest()->get());
            return view('back.brands.index', compact('brands'));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.brands.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BrandStoreRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = $data['updated_by'] = auth()->user()->id;

        if ($request->hasFile('brand_img')) {
            $image = $request->file('brand_img');
            $filename = $data['name'] . '-' . time() . '.' . $image->getClientOriginalExtension();
            $data['brand_img'] = $filename;

            Storage::disk('public')->put('brand/' . $filename, file_get_contents($image));
        }

        Brand::create($data);

        return redirect()->route('brands.index')
            ->with('success', 'Brand created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return $this->handleException(function () use ($brand) {
            $brand = new BrandResource($brand);

            return view('back.brands.show', compact('brand'));
        });
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        return $this->handleException(function () use ($brand) {
            $brand = new BrandResource($brand);

            return view('back.brands.edit', compact('brand'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
            // dd($request->all());
            $data = $request->validate([
                'name' => 'required|unique:brands,name,' . $brand->id,
                'brand_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'description' => 'nullable',
            ]);
            $data['updated_by'] = auth()->user()->id;
            if ($request->hasFile('brand_img')) {
                if (Storage::disk('public')->exists('brand/' . $brand->brand_img)) {
                    Storage::disk('public')->delete('brand/' . $brand->brand_img);
                }

                $image = $request->file('brand_img');
                $filename = $data['name'] . '-' . time() . '.' . $image->getClientOriginalExtension();
                $data['brand_img'] = $filename;

                Storage::disk('public')->put('brand/' . $filename, file_get_contents($image));
            } else {
                unset($data['brand_img']);
            }
            $brand->update($data);

            return redirect()->route('brands.index')
                ->with('success', 'Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {

            return $this->handleException(function () use ($brand) {
                $brand->delete();

                return redirect()->route('brands.index')
                    ->with('success', 'Brand deleted successfully');
            });
    }
    public function deleteBrand(Request $req)
    {

        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                Brand::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Brand Deleted Successfully!']);
        }
    }
}
