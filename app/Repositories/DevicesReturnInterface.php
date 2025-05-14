<?php
namespace App\Repositories;

interface DevicesReturnInterface
{
    public function all();
    public function getDevicesReturnByDeviceId($id);
    public function getDevicesReturnByUserId($id);
    public function createDevicesReturn($data);
    public function updateDevicesReturn($data);
    public function deleteDevicesReturn($id);
}
?>
