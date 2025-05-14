<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $setting = Setting::first();
        return view('back.cms.privacy-policy',compact('setting'));
    }
    public function indexExchangePolicy()
    {
        $setting = Setting::first();
        return view('back.cms.exchange-policy',compact('setting'));
    }
    public function indexReturnPolicy()
    {
        $setting = Setting::first();
        return view('back.cms.return-policy',compact('setting'));
    }

    public function showInFrontEnd()
    {
        $setting = Setting::first();
        return view('user.privacy-policy',compact('setting'));
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
            'privacy_policy' => 'required',
        ]);
        
        Setting::updateOrCreate(
            ['id' => 1],
            ['privacy_policy' => $request->privacy_policy]
        );
        return redirect()->back()->with('success', 'Privacy policy updated successfully');
    }

    public function storeExchangePolicy(Request $request)
    {
        $request->validate([
            'exchange_policy' => 'required',
        ]);
        
        Setting::updateOrCreate(
            ['id' => 1],
            ['exchange_policy' => $request->exchange_policy]
        );
        return redirect()->back()->with('success', 'Privacy policy updated successfully');
    }
    public function storeReturnPolicy(Request $request)
    {
        $request->validate([
            'return_policy' => 'required',
        ]);
        
        Setting::updateOrCreate(
            ['id' => 1],
            ['return_policy' => $request->return_policy]
        );
        return redirect()->back()->with('success', 'Privacy policy updated successfully');
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
