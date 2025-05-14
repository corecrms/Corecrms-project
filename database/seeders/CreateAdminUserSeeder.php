<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => bcrypt('12345678')
        ]);
        $cashier = User::create([
            'name' => 'Cashier',
            'email' => 'cashier@gmail.com',
            'password' => bcrypt('12345678')
        ]);



        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);

        // role: client
        $clientRole = Role::create(['name' => 'Client']);
        $clientPermissions = Permission::where('name', 'like', 'product%')->pluck('id','id')->all();
        $clientRole->syncPermissions($clientPermissions);

        // role: Manager
        $managerRole = Role::create(['name' => 'Manager']);
        // $managerPermissions = Permission::where('name', 'like', 'product%')->pluck('id','id')->all();
        $managerPermissions = Permission::where('name', 'like', 'product%')->orWhere('name', 'like', 'role%')->pluck('id','id')->all();
        $managerRole->syncPermissions($managerPermissions);
        $manager->assignRole([$managerRole->id]);


        // role: Cashier
        $cashierRole = Role::create(['name' => 'Cashier']);
        $cashierPermissions = Permission::where('name', 'like', 'product%')->orWhere('name', 'like', 'role%')->pluck('id','id')->all();
        $cashierRole->syncPermissions($cashierPermissions);
        $cashier->assignRole([$cashierRole->id]);

        // role: Vendor
        $managerRole = Role::create(['name' => 'Vendor']);
        $managerPermissions = Permission::where('name', 'like', 'product%')->orWhere('name', 'like', 'role%')->pluck('id','id')->all();
        $managerRole->syncPermissions($managerPermissions);
        // role: Warehouse
        $warehouseRole = Role::create(['name' => 'Warehouse']);
        $warehousePermissions = Permission::where('name', 'like', 'product%')->orWhere('name', 'like', 'role%')->pluck('id','id')->all();
        $warehouseRole->syncPermissions($warehousePermissions);


        // Create settings for the admin user
        $settings = [
            'linkedin' => null,
            'fb' => null,
            'twitch' => null,
            'twitter' => null,
            'currency' => null,
            'email' => null,
            'logo' => null,
            'company_name' => null,
            'company_phone' => null,
            'developed_by' => null,
            'footer' => null,
            'default_lang' => null,
            'default_customer' => null,
            'default_warehouse' => null,
            'sms_gateway' => '',
            'time_zone' => null,
            'address' => null,
            'smtp_host' => null,
            'smtp_port' => null,
            'smtp_username' => null,
            'smtp_password' => null,
            'smtp_encryption' => null,
            'smtp_address' => null,
            'smtp_from_name' => null,
            'pos_note' => null,
            'show_phone' => null,
            'show_address' => null,
            'show_email' => null,
            // 'show_customer' => null,
            'show_warehouse' => null,
            'show_tax_discount' => null,
            'show_barcode' => null,
            'show_note_to_customer' => null,
            // 'show_invoice' => null,
            'stripe_key' => null,
            'stripe_secret' => null,
            'delete_stripe_key' => null,
            'shopify_enable' => '0',
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'deleted_by' => null,
        ];

        \App\Models\Setting::create($settings);

    }
}
