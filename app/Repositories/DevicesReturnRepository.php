<?php
namespace App\Repositories;

use App\Models\DeviceReturn;

class DevicesReturnRepository implements DevicesReturnInterface{
    public function all(){
        return DeviceReturn::all();
    }
    public function getDevicesReturnByDeviceId($id){
        return DeviceReturn::where('device_id', $id)->get();
    }
    public function getDevicesReturnByUserId($id){
        return DeviceReturn::where('customer_id', $id)->get();
    }
    public function createDevicesReturn($data){
        return DeviceReturn::create($data);
    }
    public function updateDevicesReturn($data){
        return DeviceReturn::find($data['id'])->update($data);
    }
    public function deleteDevicesReturn($id){
        return DeviceReturn::find($id)->delete();
    }
}
?>
