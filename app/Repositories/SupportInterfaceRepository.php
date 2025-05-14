<?php
namespace App\Repositories;

interface SupportInterfaceRepository{
    public function all();
    public function ticketByUserId($id);
    public function create($data);
    public function update($id);
    public function delete($id);
}

?>
