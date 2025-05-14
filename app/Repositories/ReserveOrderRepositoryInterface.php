<?php
namespace App\Repositories;

interface ReserveOrderRepositoryInterface
{
    public function all();
    public function customerAllOrders();
    public function find($id);
    public function create($data);
    public function update( $data, $id);
    public function delete($id);
}
?>
