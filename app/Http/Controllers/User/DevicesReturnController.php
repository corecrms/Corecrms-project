<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DevicesReturnService;
use Illuminate\Http\Request;

class DevicesReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(protected DevicesReturnService $devicesReturnService)
    {

    }

    public function index()
    {
        return view('user.devices.index', [
            'orders' => $this->devicesReturnService->getDevicesReturnByUserId(auth()->id())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.devices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'imei_number' => 'required',
            'shipping_method' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);
        $data = $request->all();
        $data['customer_id'] = auth()->id();
        $this->devicesReturnService->createDevicesReturn($data);
        return redirect()->route('devices.index')->with('success', 'Device return request has been submitted successfully');
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
        $request->validate([
            'imei_number' => 'required',
            'shipping_method' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);
        $data = $request->all();
        $data['customer_id'] = auth()->id();
        $this->devicesReturnService->updateDevicesReturn($id, $data);
        return redirect()->route('devices.index')->with('success', 'Device return request has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->devicesReturnService->deleteDevicesReturn($id);
        return redirect()->route('devices.index')->with('success', 'Device return request has been deleted successfully');
    }
}
