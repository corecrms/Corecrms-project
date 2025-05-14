<?php

namespace App\Services;

use App\Repositories\ReserveOrderRepositoryInterface;

class ReserveOrderService
{
    protected $reserveOrderRepository;

    public function __construct(ReserveOrderRepositoryInterface $reserveOrderRepository)
    {
        $this->reserveOrderRepository = $reserveOrderRepository;
    }

    public function all()
    {
        return $this->reserveOrderRepository->all();
    }

    public function customerAllOrders()
    {
        return $this->reserveOrderRepository->customerAllOrders();
    }


    public function find($id)
    {
        return $this->reserveOrderRepository->find($id);
    }

    public function create($data)
    {
        return $this->reserveOrderRepository->create($data);
    }

    public function update($data, $id)
    {
        return $this->reserveOrderRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->reserveOrderRepository->delete($id);
    }
}

?>
