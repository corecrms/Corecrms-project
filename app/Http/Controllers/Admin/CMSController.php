<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\BannerSection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CMSController extends Controller
{
    public function landingPage()
    {
        $sections = BannerSection::all();
        return view('back.cms.landing-page', compact('sections'));
    }

    // public function landingPageUpdate(Request $request){
    //     $request->validate([
    //         'title' => 'required',
    //         'description' => 'required',
    //         // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    //     ]);

    //     // if($request->hasFile('image')){
    //     //     $imageName = time().'.'.$request->image->extension();
    //     //     $request->image->move(public_path('images'), $imageName);
    //     //     $data['image'] = $imageName;
    //     // }
    //     if ($request->hasFile('image')) {
    //         $image = $request->file('image');
    //         $filename = $image->getClientOriginalName() . '-' . time() . '.' . $image->getClientOriginalExtension();
    //         $data['image'] = 'images/' . $filename;

    //         Storage::disk('public')->put($data['image'], file_get_contents($image));
    //     }

    //     // $data['image'] = 'storage/' . $data['image'];

    //     // dd($data['image']);
    //     $data = $request->all();
    //     $data['created_by'] = auth()->id();
    //     $data['updated_by'] = auth()->id();
    //     BannerSection::updateOrCreate(['id' => 1], $data); // If id 1 exists, update it. If not, create it.

    //     return redirect()->back()->with('success', 'Banner Section Updated Successfully');

    // }

    public function landingPageStore(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link' => 'required|url'
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $data['image'] = $image->storeAs('images', $filename, 'public');
        }

        BannerSection::create($data);
        return redirect()->back()->with('success', 'Banner Section Created Successfully');

    }


    public function landingPageUpdate(Request $request,$id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            // 'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $data['image'] = $image->storeAs('images', $filename, 'public');
        }
        $section = BannerSection::find($id);

        $section->update($data);

        return redirect()->back()->with('success', 'Banner Section Updated Successfully');
    }

    public function landingPageDelete($id){
        $section = BannerSection::find($id);
        if(!$section){
            return redirect()->back()->with('danger', 'Banner Not Found');
        }
        $section->image ? unlink(public_path('storage/'.$section->image)) : '';
        $section->delete();
        return redirect()->back()->with('success', 'Banner section deleted successfully');

    }

}
