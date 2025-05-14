<?php

use App\Http\Controllers\AddShippingAddressController;
use App\Models\Bill;
use App\Models\Sale;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\Account;
use App\Models\Deposit;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Purchase;
use App\Models\Shipment;
use App\Models\Transfer;
use App\Models\Inventory;
use App\Models\Warehouse;
use App\Models\Designation;
use App\Models\PaymentMethod;
use App\Models\ProductReview;
use App\Models\TransferMoney;
use App\Models\PurchaseReturn;
use App\Models\DepositCategory;
use App\Models\ExpenseCategory;
use App\Models\NonSalesPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\StripeController;
use Illuminate\Notifications\Notification;
use App\Http\Controllers\Admin\AdsController;
use App\Http\Controllers\Admin\CMSController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\Admin\BillController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TierController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\TaxesController;
use App\Http\Controllers\ShopifyAuthController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\User\MyOrderController;
use App\Http\Controllers\User\ReserveController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AddCardForAdminController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\HolidayController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\ShipmentController;
use App\Http\Controllers\Admin\TransferController;
use App\Http\Controllers\Admin\WarrantyController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\User\AddToCartController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PrintLabelController;
use App\Http\Controllers\Admin\SaleReturnController;
use App\Http\Controllers\TermAndConditionController;
use App\Http\Controllers\UseCreditBalanceController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EasyshipApiController;
use App\Http\Controllers\Admin\OfficeShiftController;
use App\Http\Controllers\Admin\OrderReservController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\DeviceReturnController;
use App\Http\Controllers\Admin\ManualReturnController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\SalesDepositController;
use App\Http\Controllers\Admin\SalesInvoiceController;
use App\Http\Controllers\Admin\SaleTemplateController;
use App\Http\Controllers\User\DevicesReturnController;
use App\Http\Controllers\User\SupportTicketController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\TransferMoneyController;
use App\Http\Controllers\Admin\ProfileSettingController;
use App\Http\Controllers\Admin\PurchaseReturnController;
use App\Http\Controllers\Admin\ApprovalRequestController;
use App\Http\Controllers\Admin\DepositCategoryController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\LandingPageHeadingController;
use App\Http\Controllers\Admin\PurchaseInvoiceController;
use App\Http\Controllers\Admin\PurchasePaymentController;
use App\Http\Controllers\Admin\UserSubscriptionController;
use App\Http\Controllers\Admin\SalesInvoicePaymentController;
use App\Http\Controllers\Admin\SubscriptionPackageController;
use App\Http\Controllers\Admin\SubscriptionServiceController;
use App\Models\Admin\AboutController as AdminAboutController;
use App\Http\Controllers\Admin\ManualPurchaseReturnController;
use App\Http\Controllers\Admin\ManualShippingController;
use App\Http\Controllers\Admin\NonSalesInvoicePaymentController;
use App\Http\Controllers\Admin\NonPurchaseInvoicePaymentController;
use App\Http\Controllers\FedExController;
use App\Http\Controllers\ScraperController;
use App\Http\Controllers\User\ProductController as UserProductController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('forgot-password', function () {
    return view('back.auth.passwords.email');
});


Route::get('optimize', function () {
    Artisan::call('optimize:clear');
    $output = Artisan::output();
    return '<pre>' . $output . '</pre>';
});
Route::get('/queue-work', function () {
    Artisan::call('queue:work');
    $output = Artisan::output();
    return '<pre>' . $output . '</pre>';
});

Route::get('queue-listen', function () {
    Artisan::call('queue:listen');
    $output = Artisan::output();
    return '<pre>' . $output . '</pre>';
});

Route::get('fresh-start', function () {
    Artisan::call('migrate:fresh --seed');
    return 'fresh start is done';
});


Route::get('migrate', function () {
    Artisan::call('migrate');
    $output = Artisan::output();
    return '<pre>' . $output . '</pre>';
});

Route::get('run-seed/{class}', function ($class) {
    Artisan::call('db:seed --class=' . $class);
    $output = Artisan::output();
    return '<pre>' . $output . '</pre>';
});

Route::get('storage-link', function () {
    Artisan::call('storage:link');
    return 'storage link updated';
});


Route::get('/artisan/migrate-status', function () {
    Artisan::call('migrate:status');
    $output = Artisan::output();
    return '<pre>' . $output . '</pre>';
});

Auth::routes();

Route::get('/shopify/connect', [ShopifyAuthController::class, 'redirectToShopify'])->name('shopify.connect');
Route::get('/shopify/callback', [ShopifyAuthController::class, 'handleCallback'])->name('shopify.callback');

Route::middleware('guest')->group(function () {
    Route::get('auth/{provider}/redirect', [SocialiteController::class, 'loginSocial'])
        ->name('socialite.auth');

    Route::get('auth/{provider}/callback', [SocialiteController::class, 'callbackSocial'])
        ->name('socialite.callback');
});





Route::get('/', [HomeController::class, 'index'])->name('/');

Route::prefix('category/{code}')->group(function () {
    Route::get('/product/{sku}', [UserProductController::class, 'index'])->name('user.product.index');
});

Route::resource('add-to-cart', AddToCartController::class);
Route::group(['middleware' => ['auth']], function () {







    /*
		App Settings Routes Start
	*/
    Route::get('app/settings', [AppSettingController::class, 'index'])->name('app.settings.index');
    Route::put('app/settings', [AppSettingController::class, 'update'])->name('app.setting.update');


    Route::get('/sms/setting', [SettingController::class, 'smsSetting'])->name('sms.setting');
    Route::get('email/setting', [SettingController::class, 'emailSetting'])->name('email.setting');
    Route::get('/pos/setting', [SettingController::class, 'posSetting'])->name('pos.setting');
    Route::get('/payment/setting', [SettingController::class, 'paymentSetting'])->name('payment.setting');


    /*
		App Settings Routes End
	*/

    /*
		Profile Settings Routes Start
	*/
    // Route::get('profile', [ProfileSettingController::class, 'index'])->name('profile.index');
    // Route::put('profile', [ProfileSettingController::class, 'update'])->name('profile.update');
    Route::resource('profile', ProfileController::class);
    Route::get('profileImageDelete/{id}', [ProfileController::class, 'deleteImage'])->name('profileImage-delete');
    Route::put('password/update', [ProfileSettingController::class, 'updatePassword'])->name('profile.password.update');


    /*
		Profile Settings Routes End
	*/

    /*
		User Management Routes Start
	*/


    Route::middleware(['isAdminOrManager'])->group(function () {

        /*
		    User Management Routes End
	    */
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        Route::resource('permissions', PermissionController::class);
        Route::resource('vendors', VendorController::class);
        Route::put('vendor/status/{vendor}', [VendorController::class, 'change_status']);
        Route::put('vendor/blacklistStatus/{vendor}', [VendorController::class, 'change_blacklistStatus']);
        Route::get('vendor/blacklist', [VendorController::class, 'blacklist'])->name('vendors.blacklist');
        Route::post('vendors/delete-all', [VendorController::class, 'deleteVendors'])->name('vendors.delete');

        Route::put('category/status/{category}', [CategoryController::class, 'change_status']);
        Route::put('sub-category/status/{sub_category}', [SubCategoryController::class, 'change_status']);
        Route::put('sale_template/status/{sale_template}', [SaleTemplateController::class, 'change_status']);

        // Categories
        Route::resource('categories', CategoryController::class);
        Route::resource('sub-categories', SubCategoryController::class);
        Route::resource('brands', BrandController::class);
        // products routes
        Route::resource('products', ProductController::class);
        Route::get('product/{id}/duplicate', [ProductController::class, 'product_duplicate'])->name('products.duplicate');

        Route::resource('warranties', WarrantyController::class);
        Route::resource('units', UnitController::class);
        Route::post('units-update/{id}', [UnitController::class, 'update']);


        Route::resource('sale_templates', SaleTemplateController::class);
        Route::resource('purchase-invoices', PurchaseInvoiceController::class);
        Route::resource('inventories', InventoryController::class);
        Route::get('inventory-stock-count-of-all-products', [InventoryController::class, 'stockCountOfAllProductsIndex'])->name('inventory-stock-count-of-all-products');
        Route::get('inventory-stock-count', [InventoryController::class, 'stockCountIndex'])->name('inventory-stock-count');
        Route::get('/filter-inventory-stock-count/{date}', [InventoryController::class, 'filterStockCount'])->name('filter-stock-count');
        Route::get('/inventory-stock-count/{warehouse_id}/download-pdf', [InventoryController::class, 'downloadStockPdf'])->name('download-stock-pdf');
        Route::resource('bills', BillController::class);
        Route::post('/add-payment', [BillController::class, 'addPayment'])->name('add-payment.store');
        Route::resource('transfers', TransferController::class);
        Route::resource('tiers', TierController::class);
        Route::post('tiers-update', [TierController::class, 'updateAllTiers'])->name('updateAllTiers');
        Route::resource('taxes', TaxesController::class);
        Route::resource('warehouses', WarehouseController::class);


        Route::resource('purchases', PurchaseController::class);
        Route::resource('purchase_return', PurchaseReturnController::class);
        Route::get('purchase_return/{id}/detail', [PurchaseReturnController::class, 'detail'])->name('purchase-return.detail');

        Route::resource('print-lables', PrintLabelController::class);

        // Route::get('shipment', function(){

        // })->name('shipment');

        // Route::resource('shipment', ShipmentController::class);
        // Route::resource('shipment', ShipmentController::class);
        // Route::resource('shipment', EasyshipApiController::class);
        Route::resource('shipment', ManualShippingController::class);

        Route::get('create/shipment/{id}', [ManualShippingController::class, 'createShipment'])->name('create.shipment');
        Route::post('store/shipment', [ManualShippingController::class, 'storeShipment'])->name('store.shipment');

        Route::resource('accounts', AccountController::class);
        Route::resource('credit-cards', AddCardForAdminController::class);
        Route::resource('expenses', ExpenseController::class);
        Route::resource('deposits', DepositController::class);
        Route::resource('expense-categories', ExpenseCategoryController::class);
        Route::resource('deposit-categories', DepositCategoryController::class);
        Route::resource('transfer-money', TransferMoneyController::class);

        // Subscription Routes Start
        // Route::resource('subscription-services', SubscriptionServiceController::class);
        // Route::resource('subscription-packages', SubscriptionPackageController::class);
        // Route::post('accounts/transfer', [AccountController::class, 'account_tranfer'])->name('accounts.transfer');

        // User side subscription package routes
        Route::get('subscription/packages', [UserSubscriptionController::class, 'index'])->name('subscription.packages.index');
        // show
        Route::get('subscription/packages/{subscriptionPackage}', [UserSubscriptionController::class, 'show'])->name('subscription.packages.show');
        // subscribe
        Route::post('subscription/packages/subscribe', [UserSubscriptionController::class, 'subscribe'])->name('subscription.packages.subscribe');
        /*
		    Subscription Routes End
	    */

        /*
		    customer Routes Start
	    */
        Route::post('changeStatus', [CustomerController::class, 'changeStatus'])->name('changeStatus');
        Route::post('changeBlacklistStatus', [CustomerController::class, 'changeBlacklistStatus'])->name('changeBlacklistStatus');
        Route::resource('customers', CustomerController::class);
        Route::get('customer/blacklist', [CustomerController::class, 'blacklist'])->name('customers.blacklist');
        Route::post('customers/delete-all', [CustomerController::class, 'deleteCustomers'])->name('customers.delete');
        Route::post('customer/{id}/add-balance', [CustomerController::class, 'addBalance'])->name('customers.addbalance');
        Route::post('customer/{id}/add-card', [CustomerController::class, 'addCard'])->name('customers.addCard');
        /*
		    customer Routes End
	    */


        // HRM Module
        Route::prefix('hrm')->group(function () {
            Route::resource('companies', CompanyController::class);
            Route::resource('departments', DepartmentController::class);
            Route::resource('designations', DesignationController::class);
            Route::resource('office-shift', OfficeShiftController::class);
            Route::resource('employees', EmployeeController::class);
            Route::resource('attendance', AttendanceController::class);
            Route::resource('leave-type', LeaveTypeController::class);
            Route::resource('leave-request', LeaveRequestController::class);
            Route::resource('holidays', HolidayController::class);
            Route::resource('payrolls', PayrollController::class);
        });



        // sales-deposits (credit notes)
        Route::resource('sales-deposits', SalesDepositController::class);


        Route::delete('/delete-product-inventory/{inventory_id}/{product_id}', [InventoryController::class, 'deleteProductInventory'])->name('delete.product.inventory');

        //REPORT MANAGEMENT
        Route::get('/product/report', [ReportController::class, 'productReport'])->name('product.report');
        Route::get('/product/filter', [ReportController::class, 'productFilter'])->name('product.filter');
        // Inventory Valuation
        Route::get('/report/inventory_valuation_summary', [ReportController::class, 'inventoryValuation'])->name('inventory.valuation.report');

        Route::get('/sale/report', [ReportController::class, 'saleReport'])->name('sale.report');
        Route::get('/sale/filter', [ReportController::class, 'saleFilter'])->name('sale.filter');

        Route::get('/purchase/report', [ReportController::class, 'purchaseReport'])->name('purchase.report');
        Route::get('/purchase/filter', [ReportController::class, 'purchaseFilter'])->name('purchase.filter');

        Route::get('/sale/product/report', [ReportController::class, 'productSaleReport'])->name('sale.product.report');
        Route::get('/sale/product/filter', [ReportController::class, 'saleProductFilter'])->name('sale.product.filter');

        Route::get('/purchase/product/report', [ReportController::class, 'ProductPurchaseReport'])->name('purchase.product.report');
        Route::get('/purchase/product/filter', [ReportController::class, 'purchaseProductFilter'])->name('purchase.product.filter');

        Route::get('/stock/report', [ReportController::class, 'stockReport'])->name('stock.report');
        Route::get('/stock/filter', [ReportController::class, 'stockFilter'])->name('stock.filter');

        Route::get('/expense/report', [ReportController::class, 'expenseReport'])->name('expense.report');
        Route::get('/expense/filter', [ReportController::class, 'expenseFilter'])->name('expense.filter');

        Route::get('/deposit/report', [ReportController::class, 'depositReport'])->name('deposit.report');
        Route::get('/deposit/filter', [ReportController::class, 'depositFilter'])->name('deposit.filter');
        // customer report
        Route::get('/customer/report', [ReportController::class, 'customerReport'])->name('customer.report');
        Route::get('/customer/report/detail_customer/{id}', [ReportController::class, 'customerDetail'])->name('customer.detail');
        Route::get('/customer/filter', [ReportController::class, 'customerFilter'])->name('customer.filter');
        Route::get('/customer/{id}/download-pdf', [ReportController::class, 'customerdownloadPdf'])->name('customer.download-pdf');

        // vendor report
        Route::get('/vendor/report', [ReportController::class, 'vendorReport'])->name('vendor.report');
        Route::get('/vendor/filter', [ReportController::class, 'vendorFilter'])->name('vendor.filter');
        // user report
        Route::get('/user/report', [ReportController::class, 'userReport'])->name('user.report');
        Route::get('/user/filter', [ReportController::class, 'userFilter'])->name('user.filter');
        Route::post('/user/filter2', [ReportController::class, 'userFilter2'])->name('user.filter2');
        Route::get('/user/show/{id}', [ReportController::class, 'userShow'])->name('user.show');

        // warehouse report
        Route::get('/warehouse/report', [ReportController::class, 'warehouseReport'])->name('warehouse.report');
        Route::get('/warehouse/filter', [ReportController::class, 'warehouseFilter'])->name('warehouse.filter');

        //other report
        Route::get('/best/customer/report', [ReportController::class, 'bestCustomer'])->name('best.customer.report');
        Route::get('/best/customer/filter', [ReportController::class, 'bestCustomerFilter'])->name('best.customer.filter');
        Route::get('/top/product/report', [ReportController::class, 'topProductReport'])->name('top.product.report');
        Route::get('/top/product/filter', [ReportController::class, 'topProductFilter'])->name('top.product.filter');
        //sale purchase payment reports
        Route::get('/sale/payment/report', [ReportController::class, 'salePaymentReport'])->name('sale.payment.report');
        Route::get('/sale/payment/filter', [ReportController::class, 'salePaymentFilter'])->name('sale.payment.filter');
        Route::post('/sale/payment/filter/customer/', [ReportController::class, 'salePaymentFilter2'])->name('sale.payment.filter.customer');
        Route::get('/purchase/payment/report', [ReportController::class, 'purchasePaymentReport'])->name('purchase.payment.report');
        Route::get('/purchase/payment/filter', [ReportController::class, 'purchasePaymentFilter'])->name('purchase.payment.filter');
        Route::post('/purchase/payment/filter/vendor/', [ReportController::class, 'purchasePaymentFilterByVendor'])->name('purchase.payment.filter.vendor');

        //stock.show route
        Route::get('/stock/show/{id}', [ReportController::class, 'stockShow'])->name('stock.show');
        Route::get('/product_item/show/{id}', [ReportController::class, 'productShow'])->name('product_item.show');
        Route::get('/product/sale/filter', [ReportController::class, 'productSaleFilter'])->name('productSale.filter');

        Route::get('/export-products', [ProductController::class, 'exportProducts'])->name('export-products');
        Route::get('/file-import-products', [ProductController::class, 'importView'])->name('import-view');
        Route::post('/import-products', [ProductController::class, 'import'])->name('import-products');


        // import vendors
        Route::get('/file-import-vendors', [VendorController::class, 'importView'])->name('import-vendors-view');
        Route::post('/import-vendors', [VendorController::class, 'import'])->name('import-vendors');

        Route::get('/file-import-customers', [CustomerController::class, 'importView'])->name('import-customers-view');
        Route::post('/import-customers', [CustomerController::class, 'import'])->name('import-customers');


        Route::get('/export-vendors', [VendorController::class, 'exportVendors'])->name('export-vendors');
        Route::get('/export-customers', [CustomerController::class, 'exportCustomers'])->name('export-customers');




        // delete multiple record routes
        Route::post('products/delete-multiple-product', [ProductController::class, 'deleteProducts'])->name('products.delete');
        Route::post('categories/delete-multiple-category', [CategoryController::class, 'deleteCategory'])->name('category.delete');
        Route::post('brands/delete-multiple-brand', [BrandController::class, 'deleteBrand'])->name('brand.delete');
        Route::post('units/delete-multiple-brand', [UnitController::class, 'deleteUnit'])->name('unit.delete');
        Route::post('purchase/delete-multiple-purchases', [PurchaseController::class, 'deletePurchases'])->name('purchase.delete');
        Route::post('purchase_return/delete-multiple-purchase_returns', [PurchaseReturnController::class, 'deletePurchaseReturns'])->name('purchase_return.delete');
        Route::post('inventories/delete-multiple-inventories', [InventoryController::class, 'deleteInventories'])->name('inventories.delete');
        Route::post('transfers/delete-multiple-transfers', [TransferController::class, 'deleteTransfer'])->name('transfers.delete');
        Route::post('shipments/delete-multiple-shipments', [ShipmentController::class, 'deleteShipments'])->name('shipments.delete');
        Route::post('accounts/delete-multiple-accounts', [AccountController::class, 'deleteAccount'])->name('accounts.delete');
        Route::post('transfer-money/delete-multiple-transfer-money', [TransferMoneyController::class, 'deleteTransferMoney'])->name('transfer-money.delete');
        Route::post('expenses/delete-multiple-expenses', [ExpenseController::class, 'deleteExpenses'])->name('expenses.delete');
        Route::post('deposits/delete-multiple-deposits', [DepositController::class, 'deleteDeposits'])->name('deposits.delete');
        Route::post('expense-category/delete-multiple-expense-category', [ExpenseCategoryController::class, 'deleteExpenseCategory'])->name('expense.category.delete');
        Route::post('deposit-category/delete-multiple-deposit-category', [DepositCategoryController::class, 'deleteDepositCategory'])->name('deposit.category.delete');
        Route::post('warehouse/delete-multiple-warehouse', [WarehouseController::class, 'deleteWarehouse'])->name('warehouses.delete');
        Route::post('users/delete-multiple-users', [UserController::class, 'deleteUsers'])->name('users.delete');
        Route::post('bills/delete-multiple-bills', [BillController::class, 'deleteBills'])->name('bill.delete');
        Route::post('manual-returns/delete-multiple-manual-returns', [ManualReturnController::class, 'deleteReturns'])->name('manual_return.delete');
        Route::post('manual-returns/delete-multiple-manual-purchase-returns', [ManualPurchaseReturnController::class, 'deleteReturns'])->name('manual_purchase_return.delete');
        Route::post('delete-multiple-payment-method', [PaymentMethodController::class, 'multipleDelete'])->name('payment_method.delete');
        Route::post('delete-multiple-sales-payment', [SalesInvoicePaymentController::class, 'multipleDelete'])->name('sales.multiplae.delete');
        Route::post('delete-multiple-non-sales-payment', [NonSalesInvoicePaymentController::class, 'multipleDelete'])->name('non-sales.multiple.delete');
        Route::post('delete-multiple-purchase-payment', [PurchasePaymentController::class, 'multipleDelete'])->name('purchase-payment.multiplae.delete');
        Route::post('delete-multiple-non-purchase-payment', [NonPurchaseInvoicePaymentController::class, 'multipleDelete'])->name('non-purchase-payment.multiplae.delete');
        Route::post('delete-multiple-companies', [CompanyController::class, 'multipleDelete'])->name('companies.multiple.delete');
        Route::post('delete-multiple-department', [DepartmentController::class, 'multipleDelete'])->name('departments.multiple.delete');
        Route::post('delete-multiple-designations', [DesignationController::class, 'multipleDelete'])->name('designations.multiple.delete');
        Route::post('delete-multiple-employees', [EmployeeController::class, 'multipleDelete'])->name('employees.multiple.delete');
        Route::post('delete-multiple-attendance', [AttendanceController::class, 'multipleDelete'])->name('attendance.multiple.delete');
        Route::post('delete-multiple-office-shift', [OfficeShiftController::class, 'multipleDelete'])->name('office-shift.multiple.delete');



        // filter products
        Route::post('products/filter', [ProductController::class, 'productFilter'])->name('products.filter');

        Route::post('purchases/filter', [PurchaseController::class, 'filterPurchase'])->name('purchases.filter');
        Route::post('purchase_returns/filter', [PurchaseReturnController::class, 'filterPurchaseReturn'])->name('purchase_returns.filter');
        Route::post('inventories/filter', [InventoryController::class, 'filterInventory'])->name('inventories.filter');
        Route::post('customers/filter', [CustomerController::class, 'filterCustomers'])->name('customers.filter');
        Route::post('vendors/filter', [VendorController::class, 'filterVendors'])->name('vendors.filter');
        Route::post('transfers/filter', [TransferController::class, 'filterTransfers'])->name('transfers.filter');
        Route::post('users/filter', [UserController::class, 'filterUsers'])->name('users.filter');



        // cms module routes

        Route::get('cms/landing-page', [CMSController::class, 'landingPage'])->name('cms.landing-page.index');
        Route::post('cms/landing-page', [CMSController::class, 'landingPageStore'])->name('cms.landing-page.store');
        Route::put('cms/landing-page/{id}', [CMSController::class, 'landingPageUpdate'])->name('cms.landing-page.update');
        Route::delete('cms/landing-page/{id}', [CMSController::class, 'landingPageDelete'])->name('cms.landing-page.destroy');
        Route::resource('cms/ads', AdsController::class);
        Route::resource('cms/about-us', AboutController::class);
        Route::get('cms/contact-us', [ContactUsController::class, 'showOnBackend'])->name('admin.contact-us');
        Route::get('cms/support-ticket', [SupportController::class, 'index'])->name('admin.support-ticket');
        Route::delete('cms/support-ticket/{id}', [SupportController::class, 'destroy'])->name('admin.support-ticket.destroy');
        Route::resource('cms/notification', NotificationController::class);
        Route::get('cms/product-feedbacks', [ProductReviewController::class, 'adminPageIndex'])->name('admin.cms.product-review');


        Route::get('cms/terms-and-condition', [TermAndConditionController::class, 'index'])->name('admin.term-and-condition.index');
        Route::get('cms/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('admin.privacy-policy.index');

        Route::post('cms/terms-and-condition', [TermAndConditionController::class, 'store'])->name('admin.term-and-condition.store');
        Route::post('cms/privacy-policy', [PrivacyPolicyController::class, 'store'])->name('admin.privacy-policy.store');

        Route::get('cms/exchange-policy', [PrivacyPolicyController::class, 'indexExchangePolicy'])->name('admin.exchange-policy.index');
        Route::get('cms/return-policy', [PrivacyPolicyController::class, 'indexReturnPolicy'])->name('admin.return-policy.index');

        Route::post('cms/exchange-policy', [PrivacyPolicyController::class, 'storeExchangePolicy'])->name('admin.exchange-policy.store');
        Route::post('cms/return-policy', [PrivacyPolicyController::class, 'storeReturnPolicy'])->name('admin.return-policy.store');
        Route::resource('cms/landing-page-heading', LandingPageHeadingController::class);


        Route::resource('cms/approval-requests', ApprovalRequestController::class);
        Route::resource('device-return', DeviceReturnController::class);
        Route::resource('reserved-order', OrderReservController::class);
        Route::resource('/setting', SettingController::class);
    });

    Route::middleware(['isNotUser'])->group(function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('sales', SalesController::class);

        Route::get('sales/{email}/{id}', [SalesController::class, 'sendInvoiceToCustomer'])->name('sales.sendInvoiceEmail');

        // Route::put('sales/{id}', [SalesController::class, 'update'])->name('sales.update');

        Route::post('sales/filter', [SalesController::class, 'filterSales'])->name('sales.filter');
        Route::post('sale_returns/filter', [SaleReturnController::class, 'filterSalereturns'])->name('sale_returns.filter');
        Route::post('sales/delete-multiples-sales', [SalesController::class, 'salesDelete'])->name('sales.delete');

        Route::resource('sale_return', SaleReturnController::class);
        Route::post('sale_return/delete-multiples-returns', [SaleReturnController::class, 'deleteSaleReturn'])->name('sale_return.delete');
        Route::resource('manual-sale-return', ManualReturnController::class);
        Route::post('manual_sale_return/filter', [ManualReturnController::class, 'filterReturns'])->name('manual_sale_return.filter');
        Route::resource('manual-purchase-return', ManualPurchaseReturnController::class);
        Route::post('manual_purchase_return/filter', [ManualPurchaseReturnController::class, 'filterReturns'])->name('manual_purchase_return.filter');

        // Route::get('create-sale-return',[ManualReturnController::class,'create_sale_return'])->name('create_sale_return');
        // Route::post('create-sale-return',[ManualReturnController::class,'store'])->name('create_sale_return.store');

        Route::get('sale_return/{id}/detail', [SaleReturnController::class, 'detail'])->name('sale-return.detail');
        Route::get('/fetch-sales-purchases', [DashboardController::class, 'fetchSalesPurchases']);
        Route::get('/sales-pos', [SalesController::class, 'pos'])->name('sales.pos');
        Route::post('/sales-pos/create', [SalesController::class, 'saleCreate'])->name('sales.pos.create');
        Route::get('/product/detail/{id}', [ProductController::class, 'getOneProductDetails'])->name('get-one-product-details');


        // sales-invoices
        Route::resource('sales-invoices', SalesInvoiceController::class);
        Route::post('sales-invoices/storePayment/{salesInvoice}', [SalesInvoiceController::class, 'storePayment'])->name('sales-invoices.payment.store');
        Route::delete('sales-invoices/payment/{salesInvoice}/{salesInvoicePayment}', [SalesInvoiceController::class, 'destroyPayment'])->name('sales-invoices.payment.destroy');

        // sales-payments
        Route::resource('sales-payments', SalesInvoicePaymentController::class);
        Route::resource('non-sales-payments', NonSalesInvoicePaymentController::class);
        Route::resource('purchase-payments', PurchasePaymentController::class);
        Route::resource('non-purchase-payments', NonPurchaseInvoicePaymentController::class);


        // get product details routes
        Route::post('/switch-warehouse', [HomeController::class, 'switchWarehouse'])->name('switch-warehouse');
        Route::get('/get-product-details', [SalesController::class, 'getProductDetails'])->name('get-product-details');
        Route::get('/get-product-detail-by-warehouse', [SalesController::class, 'getProductDetailsByWarehouse'])->name('get-product-details-by-warehouse');
        Route::post('/get-all-product-by-warehouse', [SalesController::class, 'getAllProductDetailsByWarehouse'])->name('get-all-product-details-by-warehouse');

        // check inventory
        Route::post('/check-product-inventory', [InventoryController::class, 'checkingInventory'])->name('checking-inventory');

        // today report
        Route::get('/today-reports', [ReportController::class, 'todayReport'])->name('today.report');


        //setting.shopify.enable
        Route::post('setting/shopify-enable', [SettingController::class, 'shopifyEnable'])->name('setting.shopify.enable');
        Route::post('setting/pricing-enable', [SettingController::class, 'pricingEnable'])->name('setting.pricing.enable');
        // ElasticSearch ajax route
        Route::get('search-sales', [SalesController::class, 'saleSearch'])->name('sale.search');
        Route::get('search-purchase', [PurchaseController::class, 'purchaseSearch'])->name('purchase.search');

        Route::resource('payment-methods', PaymentMethodController::class);
    });




    Route::middleware(['auth'])->group(function () {

        // User Dashboard Routes
        Route::get('user/dashboard', [UserDashboardController::class, 'dashboard'])->name('user.dashboard');
        Route::get('user/account-information', [UserDashboardController::class, 'accountInfo'])->name('user.account.info');
        Route::put('user/account-information', [UserDashboardController::class, 'editProfile'])->name('user.account.info.update');
        Route::get('user/addressbook', [UserDashboardController::class, 'addressbook'])->name('user.addressbook.index');
        Route::get('user/wishlist', [WishlistController::class, 'index'])->name('user.wishlist.index');
        Route::resource('wishlist', WishlistController::class);

        Route::resource('user/orders', MyOrderController::class)->names([
            'index' => 'user.orders.index',
            'show' => 'user.orders.show',
        ]);
        // invoice download
        Route::get('sales/download/invoice/{id}', [SalesController::class, 'downloadInvoice'])->name('sales.downloadInvoice');

        Route::get('user/checkout', [CheckoutController::class, 'index'])->name('user.checkout');
        Route::post('user/checkout', [CheckoutController::class, 'store'])->name('user.checkout.store');
        Route::resource('user/reserve-order', ReserveController::class);



        // Import orders
        Route::get('user/quick-order', [MyOrderController::class, 'quickOrder'])->name('user.quick-order');
        Route::post('/import-orders', [MyOrderController::class, 'import'])->name('import-orders');

        // devices routes
        Route::resource('user/devices', DevicesReturnController::class);
        Route::resource('user/support-ticket', SupportTicketController::class);

        // Checkout Payment Routes
        // strip routes
        Route::post('stripe', [StripeController::class, 'stripe'])->name('stripe');
        Route::get('success', [StripeController::class, 'success'])->name('success');
        Route::get('cancel', [StripeController::class, 'cancel'])->name('cancel');


        Route::post('paypal', [PaypalController::class, 'paypal'])->name('paypal');
        Route::get('paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
        Route::get('paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');

        Route::post('use-credit-balance', [UseCreditBalanceController::class, 'userCredit'])->name('usecreditbalance');
        Route::post('use-credit-balance/stripe', [UseCreditBalanceController::class, 'stripe'])->name('usebalance.stripe');
        Route::get('use-credit-balance/stripe/success', [UseCreditBalanceController::class, 'success'])->name('usebalance.stripe.success');
        Route::get('use-credit-balance/stripe/cancel', [UseCreditBalanceController::class, 'cancel'])->name('usebalance.stripe.cancel');

        // wallet module routes
        Route::get('user/saved-payment-information', [WalletController::class, 'savedPaymentInfo'])->name('saved-card-info');
        Route::post('user/add/credit-card', [WalletController::class, 'addCreditCard'])->name('user.addCreditCard');
        Route::put('user/remove-card/{id}', [WalletController::class, 'removeCard'])->name('remove-card');
        Route::get('user/balance-sheet', [WalletController::class, 'balanceSheet'])->name('user.balance-sheet');
        Route::get('user/credit-activity', [WalletController::class, 'creditActivity'])->name('user.credit-activity');

        Route::get('user/notifications', [NotificationController::class, 'showOnFrontPage'])->name('user.notification');
        Route::resource('user/shipping_address', AddShippingAddressController::class);


    });
    // Route::post('contact-us', [AboutController::class,'contactUs'])->name('contact-us');



    Route::resource('product-reviews', ProductReviewController::class);
});


Route::get('/category/{code}/products', [HomeController::class, 'viewCategoryProducts'])->name('category.products');
Route::get('about-us', [AboutController::class, 'showOnFrontend'])->name('about-us');
Route::resource('contact-us', ContactUsController::class);
Route::get('/shop', [HomeController::class, 'shopPage'])->name('shop');
Route::get('/terms-and-condition', [TermAndConditionController::class, 'showInFrontEnd'])->name('user.term-and-condition');
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'showInFrontEnd'])->name('user.privacy-policy');


Route::get('/exchange-policy', function () {
    $setting = Setting::first();
    return view('user.exchange-policy', compact('setting'));
})->name('user.exchange-policy');

Route::get('/return-policy', function () {
    $setting = Setting::first();
    return view('user.return-policy', compact('setting'));
})->name('user.return-policy');


// Route::get('fedex-authorization',[FedExController::class,'authorizeFedEx']);

Route::get('create-shippment',[FedExController::class,'createShipment']);

Route::post('calculate-rate',[FedExController::class,'calculateRates'])->name('calculate-rates');

Route::post('calculate-rates-client-side',[FedExController::class,'calculateRatesOnClientSide'])->name('calculate-rates-client-side');

Route::post('address-validation',[FedExController::class,'addressValidation'])->name('address-validation');

Route::post('postal-code-validation',[FedExController::class,'postalCodeValidation'])->name('postal-code-validation');


// ElasticSearch route
Route::get('search-products/{cate_id?}', [ProductController::class, 'productSearch'])->name('products.search');


// 404 error page route

Route::get('/404', function () {
    abort(404);
})->name('errors.404');


Route::get('start-scraping', [ScraperController::class, 'startScraping'])->name('start-scraping');


Route::get('/sales-data', [DashboardController::class, 'getSalesData2Chart']);
Route::get('/weekly-sales', [DashboardController::class, 'getWeeklySalesData']);
Route::get('/weekly-sales-heatmap', [DashboardController::class, 'getWeeklySalesHeatmapData']);

Route::get('/top-categories', [DashboardController::class, 'getTopCategories']);
Route::get('/customer-sales-returns', [DashboardController::class, 'getCustomerSalesVsReturns']);

Route::get('/top-selling-products/filter', [DashboardController::class, 'filterTopSellingProducts'])->name('topSellingProducts.filter');
Route::get('/recent-sales/filter', [DashboardController::class, 'filterRecentSales'])->name('recentSales.filter');
Route::get('/recent-transactions/filter', [DashboardController::class, 'filterRecentTransactions'])->name('recentTransactions.filter');
