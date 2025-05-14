<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Brand;

class CategoryController extends BaseController
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
            $categories = CategoryResource::collection(Category::all());
            $brands = Brand::all();

            return view('back.categories.index', compact('categories','brands'));
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
    public function store(CategoryStoreRequest $request)
    {
        $data = $request->validated();

        $data['created_by'] = $data['updated_by'] = auth()->user()->id;

        // if ($request->hasFile('icon')) {
        //     $image = $request->file('icon');
        //     $filename = $data['name'] . '-' . time() . '.' . $image->getClientOriginalExtension();
        //     $data['icon'] = $filename;
        //     Storage::disk('public')->put('category/' . $filename, file_get_contents($image));
        // }

        Category::create($data);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->handleException(function () use ($category) {
            $category = new CategoryResource($category);

            return view('back.categories.show', compact('category'));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return $this->handleException(function () use ($category) {
            $category = new CategoryResource($category);

            return view('back.categories.edit', compact('category'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $data = $request->validated();

        $data['updated_by'] = auth()->user()->id;

        if ($request->hasFile('icon')) {
            $image = $request->file('icon');
            $filename = $data['name'] . '-' . time() . '.' . $image->getClientOriginalExtension();
            $data['icon'] = $filename;
            Storage::disk('public')->put('category/' . $filename, file_get_contents($image));
        }



        $category->update($data);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        return $this->handleException(function () use ($category) {
            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', 'Category deleted successfully');
        });
    }

    public function deleteCategory(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                Category::find($id)->delete();
            }
            return response()->json(['status' => 200,'message' => 'Category Deleted Successfully!']);

        }

    }
    public function change_status(Category $category)
    {
        // dd($category->status );
        if($category->status==1){
            $category->status=0;
            $message= trans('Deactivated successfully.');
        }else{
            $category->status=1;
            $message= trans('Activated successfully.');
        }
        $category->save();
        return response()->json($message);

    }
}
