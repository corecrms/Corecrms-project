<?php
namespace App\Services;

use App\Repositories\SupportInterfaceRepository;

class SupportService implements SupportInterfaceRepository {

    public function __construct(protected SupportInterfaceRepository $supportRepository)
    {
    }

    public function all(){
        return $this->supportRepository->all();
    }
    public function ticketByUserId($id){
        return $this->supportRepository->ticketByUserId($id);
    }

    public function create($data){
        return $this->supportRepository->create($data);
    }

    public function update($id){
        return $this->supportRepository->update($id);
    }

    public function delete($id){
        return $this->supportRepository->delete($id);
    }
}

?>
