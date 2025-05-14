<?php

namespace App\Exports;

use App\Models\Vendor;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\Exportable;

class VendorsExport implements FromCollection, WithHeadings
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        // get vendors and respective users
        $vendors = Vendor::with('user')->get();

        $exportData = [];

        foreach ($vendors as $vendor) {
            $exportData[] = [
                'name' => $vendor->user->name,
                'email' => $vendor->user->email,
                'contact_no' => $vendor->user->contact_no,
                'address' => $vendor->user->address,
                'status' => $vendor->status,
                'blacklisted' => $vendor->blacklist,
                'created_at' => $vendor->created_at,
                'updated_at' => $vendor->updated_at,
            ];
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'name*',
            'email*',
            'contact_no',
            'address',
            'status',
            'blacklisted',
            'created_at',
            'updated_at',
        ];
    }
}
