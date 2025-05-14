<?php

namespace App\Http\Controllers\User;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerShippingAddress;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function dashboard()
    {
        $sales = Sale::where('customer_id', auth()->user()->customer->id)->latest()->take(5)->get();
        $sales->load('customer');
        $sales->load('warehouse');
        return view('user.dashboard',compact('sales'));
    }

    public function accountInfo()
    {
        return view('user.dashboard.account-info');
    }

    public function editProfile(Request $request)
    {
        $data = $request->all();
        // dd($data);
        $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ]);
        $user = User::find(auth()->id());
        $user->name = $data['first_name'] . ' ' . $data['last_name'];
        $user->email = $data['email'];
        $user->contact_no = $data['contact_no'];
        $user->username = $data['username'];
        $user->address = $data['address'];
        $user->postal_code = $data['postal_code'];
        $user->country_code = $data['country_code'];
        $user->state = $data['state'];
        $user->state_code = $data['state_code'];
        if($request->input('password')){
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        $user->customer->update([
            'city' => $data['city'],
            'country' => $data['country'],
        ]);
        return redirect()->back()->with('success','Profile updated successfully');
    }

    public function addressbook()
    {
        $addresses = CustomerShippingAddress::where('customer_id', auth()->id())->get();
        return view('user.dashboard.address-book',compact('addresses'));
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
        //
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
