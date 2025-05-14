<?php
namespace App\Services;

use App\Repositories\DevicesReturnInterface;
use App\Repositories\DevicesReturnRepository;

class DevicesReturnService implements DevicesReturnInterface
{
    protected $devicesReturnRepository;

    public function __construct(DevicesReturnRepository $devicesReturnRepository)
    {
        $this->devicesReturnRepository = $devicesReturnRepository;
    }

    public function all()
    {
        return $this->devicesReturnRepository->all();
    }

    public function getDevicesReturnByDeviceId($id)
    {
        return $this->devicesReturnRepository->getDevicesReturnByDeviceId($id);
    }

    public function getDevicesReturnByUserId($id)
    {
        return $this->devicesReturnRepository->getDevicesReturnByUserId($id);
    }

    public function createDevicesReturn($data)
    {
        return $this->devicesReturnRepository->createDevicesReturn($data);
    }

    public function updateDevicesReturn($data)
    {
        return $this->devicesReturnRepository->updateDevicesReturn($data);
    }

    public function deleteDevicesReturn($id)
    {
        return $this->devicesReturnRepository->deleteDevicesReturn($id);
    }
}
?>
