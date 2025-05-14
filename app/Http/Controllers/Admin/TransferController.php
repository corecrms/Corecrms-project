<?php

namespace App\Http\Controllers\Admin;

use App\Models\Transfer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\TransferProductItem;
use App\Models\ProductWarehouse;

class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:transfer-list|transfer-create|transfer-edit|transfer-delete|transfer-show
          ', ['only' => ['index', 'show']]);
         $this->middleware('permission:transfer-list', ['only' => ['index']]);
         $this->middleware('permission:transfer-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:transfer-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:transfer-delete', ['only' => ['destroy']]);
         $this->middleware('permission:transfer-show', ['only' => ['show']]);
     }

    public function index()
    {
        if(auth()->user()->hasRole('Manager')){
            $transfers = Transfer::where('from_warehouse_id', auth()->user()->warehouse_id)->orWhere('to_warehouse_id', auth()->user()->warehouse_id)->get();
        }
        else
        {
            if (session()->has('selected_warehouse_id') && auth()->user()->hasRole('Admin')) {
                $warehouseId = session()->get('selected_warehouse_id');
                $transfers = Transfer::where('from_warehouse_id', $warehouseId)->orWhere('to_warehouse_id', $warehouseId)->get();
            } else {
                $transfers = Transfer::all();
            }
        }

        $transfers->load('from_warehouse', 'to_warehouse', 'transfer_products');
        // $warehouse = Warehouse::with('users')->get();
        // dd($transfers);
        $warehouses = Warehouse::all();
        return view('back.transfer.index', compact('transfers', 'warehouses'));
    }

    public function filterTransfers(Request $req)
    {
        $query = Transfer::with('from_warehouse', 'to_warehouse', 'transfer_products');

        $filters = $req->all();


        if (isset($filters['reference'])) {
            $query->where('reference', $filters['reference']);
        }

        if (isset($filters['from_warehouse_id'])  && $filters['from_warehouse_id'] > 0) {
            $query->where('from_warehouse_id', $filters['from_warehouse_id']);
        }
        if (isset($filters['to_warehouse_id'])  && $filters['to_warehouse_id'] > 0) {
            $query->where('to_warehouse_id', $filters['to_warehouse_id']);
        }
        if (isset($filters['status'])  && $filters['status'] > 0) {
            $query->where('status', $filters['status']);
        }

        $transfers = $query->get();
        $warehouses = Warehouse::all();
        return view('back.transfer.index', compact('transfers', 'warehouses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $warehouses = Warehouse::with('users')->get();
        // dd($warehouse);
        return view('back.transfer.create', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $req)
    {
        // return $req->all();

        $req->validate([
            'date' => 'required',
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required',
            'transfer_items' => 'required',
        ], [
            'from_warehouse_id.required' => 'Please select from warehouase',
            'from_warehouse_id.required' => 'Please select from warehouase',
            'transfer_items.required' => 'Please select product or increment quantity',
        ]);

        if ($req->from_warehouse_id != $req->to_warehouse_id) {

            $transfer = new Transfer();
            // // Generate a unique reference for the inventory
            $reference = substr(uniqid(), 0, 5);
            // // append 'SAL-' to the reference
            $reference = 'TR-' . $reference;
            $transfer->date = $req->date;
            $transfer->reference = $reference;
            $transfer->from_warehouse_id = $req->from_warehouse_id;
            $transfer->to_warehouse_id = $req->to_warehouse_id;
            $transfer->grand_total = $req->grand_total;
            $transfer->discount = $req->discount;
            $transfer->order_tax = $req->order_tax;
            $transfer->shipping = $req->shipping;
            $transfer->status = $req->status;
            $transfer->items = count($req->transfer_items);
            $transfer->save();

            foreach ($req->transfer_items as $itemData) {
                $productItem =  new TransferProductItem();
                $productItem->transfer_id = $transfer->id;
                $productItem->product_id = $itemData['product_id'];
                $productItem->quantity = $itemData['quantity'];
                $productItem->subtotal = $itemData['subtotal'];
                $productItem->save();

                $from_warehouse = ProductWarehouse::where('product_id', $itemData['product_id'])->where('warehouse_id', $req->from_warehouse_id)->first();
                $to_warehouse = ProductWarehouse::where('product_id', $itemData['product_id'])->where('warehouse_id', $req->to_warehouse_id)->first();
                if ($from_warehouse) {
                    if ($to_warehouse) {
                        $from_warehouse->quantity -= $itemData['quantity'];
                        $from_warehouse->save();
                        $to_warehouse->quantity += $itemData['quantity'];
                        $to_warehouse->save();
                    } else {
                        // return "enter";
                        $from_warehouse->quantity -= $itemData['quantity'];
                        $from_warehouse->save();
                        $product_warehouse = new ProductWarehouse();
                        $product_warehouse->product_id = $itemData['product_id'];
                        $product_warehouse->warehouse_id = $req->to_warehouse_id;
                        $product_warehouse->quantity = $itemData['quantity'];
                        $product_warehouse->save();
                    }
                }
            }

            return response()->json(['success' => 'Transfer created successfully!']);
        } else {
            return response()->json(['error' => 'Both warehouse can not be identical!']);
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
        $transfer = Transfer::find($id);
        $transfer->load('from_warehouse', 'to_warehouse', 'transfer_products');
        $warehouses = Warehouse::with('users')->get();
        // dd($transfer);
        return view('back.transfer.edit', compact('warehouses', 'transfer'));
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
        // return $request->all();
        $req->validate([
            'date' => 'required',
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required',
            'transfer_items' => 'required',
        ], [
            'from_warehouse_id.required' => 'Please select from warehouase',
            'from_warehouse_id.required' => 'Please select from warehouase',
            'transfer_items.required' => 'Please select product or increment quantity',
        ]);

        if ($req->from_warehouse_id != $req->to_warehouse_id) {
            // remove the transfers
            $transfer = Transfer::find($id);
            $productItem =  TransferProductItem::where('transfer_id', $id)->get();

            foreach ($productItem as $product) {
                // update warehouse qty
                $from_warehouse = ProductWarehouse::where('product_id', $product->product_id)->where('warehouse_id', $transfer->from_warehouse_id)->first();
                $to_warehouse = ProductWarehouse::where('product_id', $product->product_id)->where('warehouse_id', $transfer->to_warehouse_id)->first();

                if ($from_warehouse && $to_warehouse) {
                    $to_warehouse->quantity -= $product->quantity;
                    $to_warehouse->save();
                    $from_warehouse->quantity += $product->quantity;
                    $from_warehouse->save();
                }
                $product->delete();
            }

            // insert updated transfers product
            $transfer->date = $req->date;
            $transfer->from_warehouse_id = $req->from_warehouse_id;
            $transfer->to_warehouse_id = $req->to_warehouse_id;
            $transfer->grand_total = $req->grand_total;
            $transfer->discount = $req->discount;
            $transfer->order_tax = $req->order_tax;
            $transfer->shipping = $req->shipping;
            $transfer->status = $req->status;
            $transfer->items = count($req->transfer_items);
            $transfer->save();



            foreach ($req->transfer_items as $itemData) {
                $productItem =  new TransferProductItem();
                $productItem->transfer_id = $id;
                $productItem->product_id = $itemData['product_id'];
                $productItem->quantity = $itemData['quantity'];
                $productItem->subtotal = $itemData['subtotal'];
                $productItem->save();

                $from_warehouse = ProductWarehouse::where('product_id', $itemData['product_id'])->where('warehouse_id', $req->from_warehouse_id)->first();
                $to_warehouse = ProductWarehouse::where('product_id', $itemData['product_id'])->where('warehouse_id', $req->to_warehouse_id)->first();
                if ($from_warehouse) {
                    if ($to_warehouse) {
                        $from_warehouse->quantity -= $itemData['quantity'];
                        $from_warehouse->save();
                        $to_warehouse->quantity += $itemData['quantity'];
                        $to_warehouse->save();
                    } else {
                        $from_warehouse->quantity -= $itemData['quantity'];
                        $from_warehouse->save();
                        $product_warehouse = new ProductWarehouse();
                        $product_warehouse->product_id = $itemData['product_id'];
                        $product_warehouse->warehouse_id = $req->to_warehouse_id;
                        $product_warehouse->quantity = $itemData['quantity'];
                        $product_warehouse->save();
                    }
                }
            }

            return response()->json(['success' => 'Transfer updated successfully!']);
        } else {
            return response()->json(['error' => 'Both warehouse can not be identical!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transfer = Transfer::find($id);

        if ($transfer) {
            $productItem =  TransferProductItem::where('transfer_id', $id)->get();

            foreach ($productItem as $product) {
                // update warehouse qty
                $from_warehouse = ProductWarehouse::where('product_id', $product->product_id)->where('warehouse_id', $transfer->from_warehouse_id)->first();
                $to_warehouse = ProductWarehouse::where('product_id', $product->product_id)->where('warehouse_id', $transfer->to_warehouse_id)->first();

                if ($from_warehouse && $to_warehouse) {
                    $to_warehouse->quantity -= $product->quantity;
                    $to_warehouse->save();
                    $from_warehouse->quantity += $product->quantity;
                    $from_warehouse->save();
                }
                $product->delete();
            }

            $transfer->delete();

            return redirect()->route('transfers.index')->with('success', 'Transfer has been deleted successfully!');
        }
    }
    public function deleteTransfer(Request $req)
    {
        if (!empty($req->ids) && is_array($req->ids)) {
            // dd($req->all());
            foreach ($req->ids as $id) {
                $transfer = Transfer::find($id);

                if ($transfer) {
                    $productItem =  TransferProductItem::where('transfer_id', $id)->get();

                    foreach ($productItem as $product) {
                        // update warehouse qty
                        $from_warehouse = ProductWarehouse::where('product_id', $product->product_id)->where('warehouse_id', $transfer->from_warehouse_id)->first();
                        $to_warehouse = ProductWarehouse::where('product_id', $product->product_id)->where('warehouse_id', $transfer->to_warehouse_id)->first();

                        if ($from_warehouse && $to_warehouse) {
                            $to_warehouse->quantity -= $product->quantity;
                            $to_warehouse->save();
                            $from_warehouse->quantity += $product->quantity;
                            $from_warehouse->save();
                        }
                        $product->delete();
                    }

                    $transfer->delete();
                }
            }
            return response()->json(['status' => 200, 'message' => 'Transfer record deleted Successfully!']);
        }
    }
}
