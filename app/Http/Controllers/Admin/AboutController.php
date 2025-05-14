<?php

namespace App\Http\Controllers\Admin;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $section = AboutUs::first();
        return view('back.cms.about-us.index', compact('section'));
    }

    public function showOnFrontend()
    {
        $section = AboutUs::first();
        return view('user.about-us.index', compact('section'));
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
            'extra_content' => 'nullable|required|array',
            'extra_content.*.description' => 'nullable|required|string',
            'extra_content.*.link' => 'nullable|string',
            'extra_content.*.icon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('section_1_image')) {
            $file = $request->file('section_1_image');
            $imagePath = $file->store('about-us', 'public');
            $data['section_1_image'] = $imagePath;
        }

        if ($request->hasFile('section_2_image')) {
            $file = $request->file('section_2_image');
            $imagePath = $file->store('about-us', 'public');
            $data['section_2_image'] = $imagePath;
        }

        $extraContent = [];

        // foreach ($request->extra_content as $index => $content) {
        //     if ($request->hasFile("extra_content.$index.icon")) {
        //         $file = $request->file("extra_content.$index.icon");
        //         $iconPath = $file->store('icons', 'public');
        //         $content['icon'] = $iconPath;
        //     }
        //     $extraContent[] = $content;
        // }


        foreach ($request->extra_content as $index => $content) {
            if ($request->hasFile("extra_content.$index.icon")) {
                $file = $request->file("extra_content.$index.icon");
                $iconPath = $file->store('icons', 'public');
                $content['icon'] = $iconPath;
            } else {
                // Use the existing icon if no new icon is uploaded
                $content['icon'] = $content['existing_icon'];
            }
            unset($content['existing_icon']); // Remove the existing_icon from the data to be stored
            $extraContent[] = $content;
        }

        $data['our_services'] = json_encode($extraContent);

        AboutUs::UpdateOrCreate(['id' => 1], $data);



        return redirect()->back()->with('success', 'Data updated successfully.');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
