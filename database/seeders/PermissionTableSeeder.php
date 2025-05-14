<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $permissions = [
        //     'role-list',
        //     'role-create',
        //     'role-edit',
        //     'role-delete',
        //     'product-list',
        //     'product-create',
        //     'product-edit',
        //     'product-delete',
        //     'category-list',
        //     'category-create',
        //     'category-edit',
        //     'category-delete',
        //     'app-settings',
        //  ];
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',

            'category-list',
            'category-create',
            'category-edit',
            'category-delete',
            'app-settings',

            'products-list',
            'products-create',
            'products-edit',
            'products-delete',
            'products-show',
            'sale-list',
            'sale-create',
            'sale-edit',
            'sale-delete',
            'sale-show',
            'sale-return-list',
            'sale-return-create',
            'sale-return-delet',
            'sale-return-show',
            'sale-show',
            'purchase-list',
            'purchase-create',
            'purchase-edit',
            'purchase-delete',
            'purchase-show',

            'purchase-return-list',
            'purchase-return-create',
            'purchase-return-edit',
            'purchase-return-delete',
            'purchase-return-show',

            'invoice-payment-list',
            'invoice-payment-create',
            'invoice-payment-edit',
            'invoice-payment-delete',
            'invoice-payment-show',
            'non-invoice-payment-list',
            'non-invoice-payment-create',
            'non-invoice-payment-edit',
            'non-invoice-payment-delete',
            'non-invoice-payment-show',
            'purchase-payment-list',
            'purchase-payment-create',
            'purchase-payment-edit',
            'purchase-payment-delete',
            'purchase-payment-show',
            'inventory-list',
            'inventory-create',
            'inventory-edit',
            'inventory-delete',
            'inventory-show',
            'customer-list',
            'customer-create',
            'customer-edit',
            'customer-delete',
            'customer-show',
            'supplier-list',
            'supplier-create',
            'supplier-edit',
            'supplier-delete',
            'supplier-show',
            'warehouse-list',
            'warehouse-create',
            'warehouse-edit',
            'warehouse-delete',
            'warehouse-show',
            'transfer-list',
            'transfer-create',
            'transfer-edit',
            'transfer-delete',
            'transfer-show',
            'account-list',
            'account-create',
            'account-edit',
            'account-delete',
            'account-show',
            'transfer-money-list',
            'transfer-money-create',
            'transfer-money-edit',
            'transfer-money-delete',
            'transfer-money-show',
            'expense-list',
            'expense-create',
            'expense-edit',
            'expense-delete',
            'expense-show',
            'deposit-list',
            'deposit-create',
            'deposit-edit',
            'deposit-delete',
            'deposit-show',
            'today-report',
            'inventory-valuation-report',
            'sale-payment-report',
            'purchase-payment-report',
            'purchase-payment-report',
            'warehouse-report',
            'supplier-report',
            'expense-report',
            'deposit-report',
            'stock-report',
            'product-report',
            'product-sale-report',
            'purchase-report',
            'product-purchase-report',
            'user-report',
            'customer-report',
            'top-selling-product-report',
            'best-customer-report',
            'tier-list',
            'tier-create',
            'tier-edit',
            'tier-delete',
            'tier-show',
         ];



        foreach ($permissions as $permission) {
            // Check if the permission already exists
            if (!Permission::where('name', $permission)->exists()) {
                // If it doesn't exist, create it
                Permission::create(['name' => $permission]);
            }
        }
    }
}
