<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use App\Http\Requests\SubCategoryRequest;
use App\Http\Resources\SubCategoryResource;

class SubCategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:category-list|category-create|category-edit|category-delete
         ', ['only' => ['index', 'show']]);
        $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->handleException(function () {
            $subCategories = SubCategoryResource::collection(SubCategory::all());
            $categories = Category::all();
            return view('back.sub-categories.index', compact('subCategories','categories'));
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubCategoryRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = $data['updated_by'] = auth()->user()->id;

        SubCategory::create($data);

        return redirect()->route('sub-categories.index')
            ->with('success', 'Sub Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(SubCategoryRequest $request, SubCategory $sub_category)
    {
        $data = $request->validated();

        $data['updated_by'] = auth()->user()->id;

        $sub_category->update($data);

        return redirect()->route('sub-categories.index')
            ->with('success', 'Sub Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubCategory $subCategory)
    {
        return $this->handleException(function () use ($subCategory) {
            $subCategory->delete();

            return redirect()->route('sub-categories.index')
                ->with('success', 'Category deleted successfully');
        });
    }

    public function deleteCategory(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                SubCategory::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Category Deleted Successfully!']);

        }

    }
    public function change_status(SubCategory $subCategory)
    {
        // dd($subCategory->status );
        if($subCategory->status==1){
            $subCategory->status=0;
            $message= trans('Blacklist Successfully');
        }else{
            $subCategory->status=1;
            $message= trans('Active Successfully');
        }
        $subCategory->save();
        return response()->json($message);

    }
}

