<?php

use App\Models\Sale;
use App\Jobs\SendInvoiceJob;
use App\Jobs\SendInvoiceSmsJob;
use Illuminate\Support\Facades\Log;

function getInvoiceNo($code = '#INV')
{
    if ($code == '#INV') {
        $lastInvoice = \App\Models\SalesInvoice::orderBy('id', 'desc')->first();
    } else {
        $lastInvoice = \App\Models\PurchaseInvoice::orderBy('id', 'desc')->first();
    }

    if ($lastInvoice) {

        $invoiceNo = $lastInvoice->invoice_number;
        $invoiceNo = explode('-', $invoiceNo);
        $invoiceNo = $invoiceNo[1];
        $invoiceNo = intval($invoiceNo);
        $invoiceNo++;
        $invoiceNo = str_pad($invoiceNo, 4, '0', STR_PAD_LEFT);
        $invoiceNo = $code . '-' . $invoiceNo;
    } else {
        $invoiceNo = $code . '-0001';
    }

    return $invoiceNo;
}

function getDateFormat($date)
{
    return date('M d, Y', strtotime($date));
}

function update_env($data = [])
{
    $path = base_path('.env');

    if (file_exists($path)) {

        $env_update = file_get_contents(base_path('.env'));

        foreach ($data as $key => $value) {
            // // check if key is in lowercase then convert it to uppercase
            // if (preg_match("/[a-z]/", $key)) {
            //     $key = strtoupper($key);
            // }

            $value = '"' . $value . '"';

            // check if key is already exists then update it
            if (preg_match("/^" . $key . "=(.*)$/m", $env_update)) {
                $env_update = preg_replace("/^" . $key . "=(.*)$/m", $key . "=" . $value, $env_update);
            } else {
                // if key is not exists then add it
                $env_update .= "\n" . $key . "=" . $value;
            }
        }

        file_put_contents(base_path('.env'), $env_update);
        return true;
    }
    return false;
}

function getStatusClass($status)
{
    switch ($status) {
        case "Active":
            $status_class = "badges bg-lightgreen";
            break;
        case "Inactive":
            $status_class = "badges bg-lightred";
            break;
        default:
            $status_class = "badges bg-darked";
    }
    return $status_class;
}

function getFirstAndLastName($fullName)
{
    // $name = explode(' ', trim($fullName));
    // $firstName = $name[0];
    // $lastName = $name[1];
    // return $firstName.' '.$lastName;
    // return $name;
    $nameParts = explode(' ', trim($fullName));
    $firstName = array_shift($nameParts); // Extract the first name
    $lastName = implode(' ', $nameParts); // Join the remaining parts as the last name

    return [
        'first_name' => $firstName,
        'last_name' => $lastName,
    ];
}

function getLogo()
{
    $setting = \App\Models\Setting::first();
    if ($setting && $setting->logo) {
        return $setting->logo;
    } else {
        return null;
    }
}

function getSetting()
{
    $setting = \App\Models\Setting::first();
    if ($setting) {
        return $setting;
    } else {
        return null;
    }
}


function sendInvoiceToCustomerViaEmailAndSms($email,$id){
    try{
        $sale = Sale::find($id);
        $sale->load('productItems', 'customer', 'warehouse', 'invoice');
        $job = new SendInvoiceJob($sale, $email);
        dispatch($job);
        Log::info('Email sent to: ' . $email);

        // Send SMS
        // $phone = $sale->customer->user->contact_no ?? +923164734175;
        // $job = new SendInvoiceSmsJob($phone, $sale);
        // dispatch($job);
        // Log::info('SMS sent to: ' . $phone);
        // return true;
    }
    catch(\Exception $e){
        Log::error('Error sending email: ' . $e->getMessage());
        return false;
    }


}

if (!function_exists('adminSidebarRoutes')) {
    function adminSidebarRoutes()
    {
        return $modules = [
            'product_management' => [
                'roles' => ['Admin', 'Manager'],
                'pages' => [
                    ['route' => 'products.index', 'label' => 'All Products'],
                    ['route' => 'products.create', 'label' => 'Create Product'],
                    ['route' => 'categories.index', 'label' => 'Category'],
                    ['route' => 'sub-categories.index', 'label' => 'Sub Category'],
                    ['route' => 'brands.index', 'label' => 'Brand'],
                    ['route' => 'units.index', 'label' => 'Unit'],
                ],
            ],

        ];

    }
}