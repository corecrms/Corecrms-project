<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Spatie\Permission\Models\Permission;
class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'linkedin' => '',
            'fb' => '',
            'twitch' => '',
            'twitter' => '',
            'currency' => '',
            'email' => '',
            'logo' => '',
            'company_name' => '',
            'company_phone' => '',
            'developed_by' => '',
            'footer' => '',
            'default_lang' => '',
            'default_customer' => '',
            'default_warehouse' => '',
            'sms_gateway' => '',
            'time_zone' => '',
            'address' => '',
            'smtp_host' => '',
            'smtp_port' => '',
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_encryption' => '',
            'smtp_address' => '',
            'smtp_from_name' => '',
            'pos_note' => '',
            'show_phone' => '',
            'show_address' => '',
            'show_email' => '',
            'show_customer' => '',
            'show_warehouse' => '',
            'show_tax_discount' => '',
            'show_barcode' => '',
            'show_note_to_customer' => '',
            'show_invoice' => '',
            'stripe_key' => '',
            'stripe_secret' => '',
            'delete_stripe_key' => '',
            'shopify_enable' => '0',
            // 'created_by' => auth()->user()->id(),
            // 'updated_by' => auth()->user()->id(),
            'deleted_by' => '0',
        ];

        \App\Models\Setting::create($settings);

    }
}
