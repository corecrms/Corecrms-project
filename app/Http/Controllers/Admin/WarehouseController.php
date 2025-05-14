<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BaseController;
use Spatie\Permission\Models\Role;
// use App\Models\User;

class WarehouseController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:warehouse-list|warehouse-create|warehouse-edit|warehouse-delete|warehouse-show
          ', ['only' => ['index', 'show']]);
        $this->middleware('permission:warehouse-list', ['only' => ['index']]);
        $this->middleware('permission:warehouse-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:warehouse-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:warehouse-delete', ['only' => ['destroy']]);
        $this->middleware('permission:warehouse-show', ['only' => ['show']]);
    }

    public function index()
    {
        $warehouses = Warehouse::with('users')->get();

        return view('back.warehouse.index', compact('warehouses'));
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
    public function store(Request $req)
    {
        $req->validate([
            'name' => 'required',
            'phone' => 'required',
            'country' => 'required|string',
            'city' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'zip_code' => 'required',
            'state_code' => 'required',
            'country_code' => 'required',
        ]);

        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->contact_no = $req->phone;
        $user->password = Hash::make('12345678');
        $user->type = "Warehouse"; //warehouse
        $user->country_code = $req->country_code;
        $user->state_code = $req->state_code;
        $user->address = $req->address;

        $role = 'Warehouse';
        if (!Role::where('name', $role)->exists()) {
            return redirect()->back()->with('error', 'Warehouse Role does not exist.');
        }

        if ($user->save()) {
            $user->assignRole($role);

            $warehouseAttributes = [
                'user_id' => $user->id,
                'country' => $req->country,
                'city' => $req->city,
                'zip_code' => $req->zip_code,
            ];
            $user->warehouse()->create($warehouseAttributes);

            return redirect()->route('warehouses.index')->with('success', 'Warehouse added successfully!');
        }
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
            'name' => 'required',
            'phone' => 'required',
            'country' => 'required|string',
            'city' => 'required|string',
            'zip_code' => 'required',
            'email' => 'unique:users,email,' . $request->user_id,
            'state_code' => 'required',
            'country_code' => 'required',
            'address' => 'required'
        ]);

        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return redirect()->back()->with('error', 'Warehouse not found.');
        }

        $user = User::find($warehouse->user_id);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found for the warehouse.');
        }

        $user->name = $request->name;
        $user->contact_no = $request->phone;
        $user->email = $request->email;
        $user->password = Hash::make('12345678');
        $user->country_code = $request->country_code;
        $user->state_code = $request->state_code;
        $user->address = $request->address;


        if (!$user->save()) {
            return redirect()->back()->with('error', 'Failed to update user. Please try again.');
        }

        $warehouse->country = $request->country;
        $warehouse->city = $request->city;
        $warehouse->zip_code = $request->zip_code;

        if (!$warehouse->save()) {
            return redirect()->back()->with('error', 'Failed to update warehouse. Please try again.');
        }

        return redirect()->route('warehouses.index')->with('success', 'Warehouse updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $warehouse = Warehouse::find($id);

        if ($warehouse) {

            $user = User::find($warehouse->user_id);

            if ($user) {


                // Delete associated Sales and Sale Returns

                foreach ($warehouse->sales as $sale) {
                    $sale->productItems()->delete();
                    $sale->invoice()->delete();
                    $sale->delete();
                }
                // $warehouse->sales()->delete();
                // $warehouse->sale_returns()->delete();

                // Delete associated Purchases and Purchase Returns
                $warehouse->purchases()->delete();

                // Delete associated Expenses
                $warehouse->expenses()->delete();

                // Delete associated Inventories
                // $warehouse->inventories()->delete();
                foreach ($warehouse->inventories as $inventory) {
                    $inventory->product_inventory()->delete();
                    $inventory->delete();
                }

                // Delete associated ProductWarehouse entries
                $warehouse->productWarehouses()->delete();
                // Delete associated Products
                $warehouse->products()->delete();

                $user->delete();

                $warehouse->delete();
                return redirect()->route('warehouses.index')->with('success', 'Warehouse Deleted successfully!');
            }
        } else {
            return redirect()->route('warehouses.index')->with('danger', 'Warehouse not found ');
        }
    }
    public function deleteWarehouse(Request $req)
    {
        // dd($req->all());

        if ($req->ids) {
            foreach ($req->ids as $id) {
                $warehouse = Warehouse::find($id);

                if ($warehouse) {
                    $user = User::find($warehouse->user_id);

                    if ($user) {
                        $user->delete();
                        $warehouse->delete();
                    }
                }
            }
            return response()->json(['status' => 200, 'message' => 'Warehouse deleted successfully!']);
        }
    }
}
