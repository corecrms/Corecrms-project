<?php

namespace App\Http\Controllers\Admin;

use App\Models\Sale;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Store;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $shipments = Shipment::all();
        // $shipments->load('sales');
        // // dd($shipment);
        // return view('back.shipment.index',compact('shipments'));

        // if(auth()->user()->hasRole(['Cashier', 'Manager'])){
        //     $shipments = Sale::with('customer','warehouse')->where('tracking_number','!=',null)->where('warehouse_id',auth()->user()->warehouse_id)->latest()->get();
        // }
        // else
        // {
        //     if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
        //         $warehouseId = session()->get('selected_warehouse_id');
        //         $shipments = Sale::with('customer','warehouse')->where('tracking_number','!=',null)->where('warehouse_id',$warehouseId)->latest()->get();
        //     }
        //     else {
        //         $shipments = Sale::with('customer','warehouse')->where('tracking_number','!=',null)->latest()->get();
        //     }
        // }

        if (auth()->user()->hasRole('Cashier') || auth()->user()->hasRole('Manager')) {
            $warehouseId = auth()->user()->warehouse_id;
            $sales = Sale::where('warehouse_id', $warehouseId)->latest()->get();
            $sales->load('customer');
            $sales->load('warehouse');

            return view('back.shipment.fedex.index', compact('sales'));
        }
        else
        {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $sales = Sale::where('warehouse_id', $warehouseId)->latest()->get();
            } else {
                $sales = Sale::where('shipping_method', '=' , 'Store Pickup')->latest()->get();
            }

            // Eager load related customer and warehouse data
            $sales->load(['customer', 'warehouse','productItems.product']);

            // Return the view with the compacted data
            return view('back.shipment.fedex.index', compact('sales'));
        }


    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'status' => 'required'
        ]);

        $shipment = Shipment::where('sale_id',$req->sale_id)->first();

        $data = $req->all();
        $data['date'] = date('Y-m-d');
        if($shipment){
            $shipment->update($data);
        }
        else
        {
            // Generate a unique reference for the sale
            $reference = substr(uniqid(), 0, 6);
            // append 'SAL-' to the reference
            $reference = 'SM-' . $reference;
            $data['reference'] = $reference;
            Shipment::create($data);
        }

        return redirect()->back()->with('success','Shipping status has been updated successfully!');



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
    public function update(Request $req, $id)
    {
        $req->validate([
            'status' => 'required'
        ]);

        $shipment = Shipment::find($id);

        if($shipment){
            $data = $req->all();
            $data['date'] = date('Y-m-d');
            $shipment->update($data);
        }
        return redirect()->back()->with('success','Shipping status has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipment = Shipment::find($id);
        if($shipment){
            $shipment->delete();
        }
        return redirect()->back()->with('success','Shipping status has been deleted successfully!');
    }

    public function deleteShipments(Request $req)
    {
        if(!empty($req->ids) && is_array($req->ids)){
            // dd($req->all());
            foreach ($req->ids as $id) {
                $shipment = Shipment::find($id);
                if($shipment){
                    $shipment->delete();
                }
            }
            return response()->json(['status' => 200,'message' => 'Shipping status deleted successfully!']);

        }

    }
}
