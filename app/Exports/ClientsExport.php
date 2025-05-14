<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class ClientsExport implements FromCollection, WithHeadings
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $clients = Customer::with('user')->get();

        $exportData = [];

        foreach ($clients as $clients) {
            $exportData[] = [
                'name' => $clients->user->name,
                'email' => $clients->user->email,
                'contact_no' => $clients->user->contact_no,
                'address' => $clients->user->address,
                'status' => $clients->status,
                'blacklisted' => $clients->blacklist,
                'created_at' => $clients->created_at,
                'updated_at' => $clients->updated_at,
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
