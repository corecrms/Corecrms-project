<?php

namespace App\Imports;

use Log;
use GuzzleHttp\Client;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ShopifyStore;
use Illuminate\Validation\Rule;
use App\Models\ProductWarehouse;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, WithStartRow, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    private $newProductsCount = 0;

    public function model(array $row)
    {
        // dd('product',$row['name']);
        // dd($row);
        // Log the row data
        // Log::info('Processing row: ', $row);


        if (empty($row['name'])) {
            return null;
        }

        $existingProduct = Product::where([
            ['sku', '=', $row['sku']]
        ])->first();

        if ($existingProduct) {
            // increment the product warehouse quantity

            // get warehouse id
            $warehouse_user_id = \App\Models\User::where('name', $row['warehouse'])->first()->id;
            if ($warehouse_user_id) {
                $wareId = \App\Models\Warehouse::where('user_id', $warehouse_user_id)->first()->id;
            }

            $product_warehouse = ProductWarehouse::where('product_id', $existingProduct->id)
                ->where('warehouse_id', $wareId)
                ->first();

            if ($product_warehouse) {
                $product_warehouse->quantity += $row['quantity'] ?? 0;
                $product_warehouse->save();
                $this->newProductsCount++;
            }
            else
            {
                $product_warehouse = ProductWarehouse::create([
                    'product_id' => $existingProduct->id,
                    'warehouse_id' => $wareId,
                    'quantity' => $row['quantity'] ?? 0,
                ]);
                $product_warehouse->save();
                $this->newProductsCount++;
            }
            return $existingProduct;
        }

        $validator = Validator::make($row, [
            'category' => 'required|exists:categories,name',
            'sub_category' => 'required|exists:sub_categories,name',
            'brand' => 'required|exists:brands,name',
            'warehouse' => 'required|exists:users,name',
            'product_type' => 'required',
            'sale_price' => 'required',
            // Add other required fields here
        ]);

        if ($validator->fails()) {
            // Handle validation errors, e.g., log, throw exception, or return null
            Log::error('Product import validation failed: ' . $validator->errors()->toJson());
            return null;
        }

        // find ids
        $row['category'] = \App\Models\Category::where('name', $row['category'])->first()?->id;
        $row['sub_category'] = \App\Models\SubCategory::where('name', $row['sub_category'])->first()?->id;
        $row['brand'] = \App\Models\Brand::where('name', $row['brand'])->first()?->id;
        $warehouse_user_id = \App\Models\User::where('name', $row['warehouse'])->first()?->id;
        if ($warehouse_user_id) {
            $row['warehouse'] = \App\Models\Warehouse::where('user_id', $warehouse_user_id)->first()?->id;
        }
        $row['product_unit'] = \App\Models\Unit::where('short_name', $row['product_unit'])->first()?->id;
        $row['sale_unit'] = \App\Models\Unit::where('short_name', $row['sale_unit'])->first()?->id;
        $row['purchase_unit'] = \App\Models\Unit::where('short_name', $row['purchase_unit'])->first()?->id;


        $product = new Product([
            'name' => $row['name'],
            'sku' => $row['sku'],
            'description' => $row['description'],
            'category_id' => $row['category'],
            'sub_category_id' => $row['sub_category'],
            'brand_id' => $row['brand'],
            'barcode' => $row['barcode'],
            // 'quantity' => $row['quantity'],
            'purchase_price' => $row['purchase_price'] ?? null,
            'sell_price' => $row['sale_price'],
            'stock_alert' => $row['stock_alert'] ?? null,
            'sale_unit' => $row['sale_unit'] ?? null,
            'product_unit' => $row['product_unit'] ?? null,
            'purchase_unit' => $row['purchase_unit'] ?? null,
            'tax_type' => $row['tax_type'] ?? null,
            'product_type' => $row['product_type'],
            'order_tax' => $row['order_tax'] ?? 0,
            'status' => 1,
            'warehouse_id' => $row['warehouse'] ?? null,
            'product_live' => $row['product_live'] ?? 0,
            'new_product' => $row['new_product'] ?? 0,
            'featured_product' => $row['featured_product'] ?? 0,
            'best_seller' => $row['best_seller'] ?? 0,
            'recommended' => $row['recommended'] ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // dd($product);

        $product->save();
        $this->newProductsCount++;

        if ($product) {

            $product_warehouse = ProductWarehouse::create([
                'product_id' => $product?->id,
                'warehouse_id' => $row['warehouse'] ?? null,
                'quantity' => $row['quantity'] ?? 0,
            ]);
            $product_warehouse->save();

            $shopify_enable = Setting::first();
            if ($product && $product_warehouse && $shopify_enable->shopify_enable == 1 && $product->shopify_id == null) {

                $productData = [
                    "title" => $row['name'],
                    "body_html" => $row['description'],
                    "vendor" => $product_warehouse->warehouse->users->name ?? '', // Use the vendor's name
                    "product_type" => $row['product_type'],
                    'variants' => [],
                    'options' => []
                ];
                // dd($productData,auth()->user()->id);

                $shopify_store = ShopifyStore::where('user_id', auth()->user()->id)->first();
                $shopify_all = ShopifyStore::all();

                if ($shopify_store) {
                    $shopDomain = $shopify_store->shop_domain;
                }

                if (!$shopify_store) {
                    return redirect()->route('products.index')->with('error', 'Shop not found.');
                }

                $accessToken = $shopify_store->access_token;
                $client = new Client();

                try {
                    $response = $client->request('POST', "https://{$shopDomain}/admin/api/2023-04/products.json", [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'X-Shopify-Access-Token' => $accessToken,
                        ],
                        'body' => json_encode(['product' => $productData]),
                    ]);


                    $shopifyProduct = json_decode($response->getBody()->getContents(), true);
                    if (isset($shopifyProduct['product']['id'])) {
                        $data['shopify_id'] = $shopifyProduct['product']['id'];
                    }
                } catch (\Exception $e) {
                    return redirect()->route('products.index')->with('error', 'Failed to create product in Shopify: ' . $e->getMessage());
                }
            }
            return $product;
        }

        return null;
    }
    public function headingRow(): int
    {
        return 1;
    }

    // public function rules(): array
    // {
    //     return [
    //         // sku is unique and required
    //         // 'sku'=> 'required'
    //     ];
    // }

    public function rules(): array
    {
        return [
            // 'category_id' => ['required',Rule::exists('categories', 'id')],
            // 'sub_category_id' => [Rule::exists('sub_categories', 'id')],
            // 'brand_id' => [Rule::exists('brands', 'id')],
            // 'warehouse_id' => [Rule::exists('warehouses', 'id')],
        ];
    }

    public function getNewProductsCount()
    {
        return $this->newProductsCount;
    }
    public function startRow(): int
    {
        return 2;
    }
}
