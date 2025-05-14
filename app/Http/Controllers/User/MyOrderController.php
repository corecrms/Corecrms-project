<?php

namespace App\Http\Controllers\User;

use App\Models\Sale;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Imports\OrdersImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;

class MyOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sales = Sale::where('customer_id', auth()->user()->customer->id)->orderBy('created_at', 'desc')->get();
        $sales->load('customer');
        $sales->load('warehouse');
        // dd($sales);
        return view('user.order.my-orders', compact('sales' ));
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
        $sale = Sale::find($id);
        $sale->load('productItems', 'customer', 'warehouse', 'invoice');

        return view('user.order.view-order', compact('sale'));
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


    public function quickOrder(){
        return view('user.order.quick-order');
    }

    public function import(Request $request)
    {
        // dd($request->all());

        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ]);

            $import = new OrdersImport;

            Excel::import($import, $request->file('file'));

            if ($import->newOrdersCount() == 0) {
                return redirect()->back()->with('error', 'No Order imported please check your imported file');
            }
            

            return redirect()->route('user.orders.index')->with('success', 'Orders imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $error = [];

            foreach ($failures as $failure) {
                // dd($failure);
                $error[] = $failure->row() . ' - ' . $failure->errors()[0];
            }

            return redirect()->route('user.quick-order')->with('error', $error);
        } catch (QueryException $e) {
            dd($e);
            // Check if it's a duplicate entry error
            if ($e->errorInfo[2] === 1062) {
                return redirect()->route('user.quick-order')
                    ->with('error', 'Duplicate entry. This product already exists.');
            } else {
                // Handle other database errors as needed
                return redirect()->route('user.quick-order')
                    ->with('error', 'An error occurred during import.');
            }
        }
    }
}

