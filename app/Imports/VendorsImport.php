<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class VendorsImport implements ToModel, WithStartRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    private $newVendorsCount = 0;

    public function model(array $row)
    {

        $user = User::where('email', $row[2])->first();
        if ($user || !filter_var($row[2], FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $indexes = [2];
        foreach ($indexes as $index) {
            if (!isset($row[$index]) || empty($row[$index])) {
                return null;
            }
        }

        $user = new User([
            // 'id' => $row[0],
            'name' => $row[1],
            'email' =>  $row[2],
            'password' => Hash::make(123456),
            'contact_no' => $row[3],
            'address' => $row[4],
        ]);

        $user->save();
        $user->assignRole('Vendor');

        $this->newVendorsCount++;

        $vendor = new Vendor([
            // 'id' => $row[3],
            'user_id' => $user->id,
            'country' => $row[5],
            'city' => $row[6],
            'tax_number' => $row[7],
        ]);

        $user->vendor()->save($vendor);
        return $vendor;
    }

    public function newVendorsCount()
    {
        return $this->newVendorsCount;
    }

    public function startRow(): int
    {
        return 2;
    }
}
