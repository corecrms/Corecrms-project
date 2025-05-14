<?php
namespace App\Repositories;

use App\Models\SupportTicket;

class SupportRepository implements SupportInterfaceRepository {
    public function all(){
        return SupportTicket::all();
    }
    public function ticketByUserId($id){
        return SupportTicket::where('customer_id', $id)->get();
    }

    public function create($data){
        return SupportTicket::create($data);
    }

    public function update($id){
        return SupportTicket::find($id);
    }

    public function delete($id){
        return SupportTicket::destroy($id);
    }
}

?>
