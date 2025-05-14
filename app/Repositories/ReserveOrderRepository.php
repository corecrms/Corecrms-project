<?php

namespace App\Repositories;

use App\Models\ReserveOrder;

class ReserveOrderRepository implements ReserveOrderRepositoryInterface
{
    public function all()
    {
        return ReserveOrder::all();
    }

    public function customerAllOrders()
    {
        return ReserveOrder::where('customer_id',auth()->id())->get();
    }


    public function find($id)
    {
        return ReserveOrder::find($id);
    }

    public function create($data)
    {
        return ReserveOrder::create($data);
    }

    public function update($data, $id)
    {
        return ReserveOrder::where('id',$id)->update($data);
    }

    public function delete($id)
    {
        return ReserveOrder::destroy($id);
    }
}

?>
