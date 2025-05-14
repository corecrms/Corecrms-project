<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Warehouse;

class StartScrapingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', 36000);

        $baseUrl = 'https://www.atlantawholecell.com/';
        $categoryUrl = 'https://www.atlantawholecell.com/api/menu?businessTypeId=1';

        $client = new \GuzzleHttp\Client();

        $creator = User::first();
        $warehouseId = Warehouse::first()->id;

        try {
            // Fetch categories
            $response = $client->get($categoryUrl);
            $result = json_decode($response->getBody(), true);

            $data = [];
            $requestCount = 0;

            foreach ($result['result'] as $category) {

                $brandName = $category['name'];

                $dbBrand = Brand::firstOrCreate(
                    ['name' => $brandName]
                );


                // Check if subCategories exist for this category
                if (!isset($category['subCategories']) || empty($category['subCategories'])) {
                    continue; // Skip to the next category if no subCategories exist
                }

                foreach ($category['subCategories'] as $subCategory) {

                    $categoryName = $subCategory['name'];

                    $dbCategory = Category::firstOrCreate(
                        ['name' => $categoryName],
                        ['brand_id' => $dbBrand->id, 'code' => $categoryName],
                    );

                    // Check if subCategories exist for this subCategory
                    if (!isset($subCategory['subCategories']) || empty($subCategory['subCategories'])) {
                        continue; // Skip to the next subCategory if no subSubCategories exist
                    }

                    foreach ($subCategory['subCategories'] as $subSubCategory) {
                        $subSubCategoryId = $subSubCategory['id'];
                        $subCategoryName = $subSubCategory['name'];

                        $dbSubCategory = SubCategory::firstOrCreate(
                            ['name' => $subCategoryName],
                            ['category_id' => $dbCategory->id, 'code' => $subCategoryName]
                        );

                        $subSubCategoryUrl = $baseUrl . "api/ecommerce/product/category?categoryIdList=$subSubCategoryId&page=0&size=20&sort=price&sortDirection=DESC&storeIds=2";

                        // Make the API request
                        $productResponse = $client->get($subSubCategoryUrl);
                        $productData = json_decode($productResponse->getBody(), true);

                        // Handle delay after every second request
                        // $requestCount++;
                        // if ($requestCount % 2 == 0) {
                        //     sleep(2); // Wait for 2 seconds
                        // }

                        if ($productData['status'] === 200 && !empty($productData['result']['content'])) {
                            foreach ($productData['result']['content'] as $product) {
                                $productName = $product['productName'] ?? 'No Name';
                                $imageUrl = $product['imageUrl'] ?? 'https://via.placeholder.com/150';

                                $dbProduct =   $this->createProduct($product,$dbBrand->id,$dbCategory->id,$dbSubCategory->id,$creator->id,$warehouseId);

                                ProductWarehouse::create([
                                    'product_id' => $dbProduct->id,
                                    'warehouse_id' => $warehouseId,
                                    'quantity' => $product['availableQuantity'] ?? 0,
                                ]);

                                // Download and save the image
                                if ($imageUrl) {
                                    $this->downloadImage($imageUrl,$dbProduct->id ,$productName);
                                }

                                // Store product details
                                $data[] = [
                                    'name' => $productName,
                                    'description' => $product['productName'] ?? 'No Description',
                                    'sku' => $product['sku'] ?? rand(10000, 99999),
                                    'price' => $product['standardPrice'] ?? 0,
                                    'imageUrl' => $imageUrl,
                                    'quantity' => $product['availableQuantity'] ?? 0,
                                    'category' => $subSubCategory['name'],
                                ];
                            }
                        }

                        // if (count($data) >= 1) {
                        //     break 3; // Exit all loops
                        // }
                    }
                }
            }

            // return response()->json($subCategories);
            // Save JSON data to a file
            $jsonFileName = 'products_' . now()->format('Ymd_His') . '.json';
            Storage::disk('local')->put("scraped_data/$jsonFileName", json_encode($data, JSON_PRETTY_PRINT));

            return response()->json(['message' => 'Data scraped successfully', 'json_file' => $jsonFileName]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return response()->json([
                'error' => 'Failed to fetch data.',
                'message' => $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An unexpected error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function createProduct($product,$brandId,$categoryId,$subCategoryId,$createdBy,$warehouseId)
    {

        $productData = [];
        $productData['warehouse_id'] = $warehouseId;
        $productData['created_by'] = $data['updated_by'] = $createdBy;
        $productData['brand_id'] = $brandId;
        $productData['category_id'] = $categoryId;
        $productData['sub_category_id'] = $subCategoryId;
        $productData['name'] = $product['productName'] ?? 'No Name';
        $productData['description'] = $product['productName'] ?? 'No Description';
        $productData['sku'] = $product['sku'] ?? rand(10000000, 99999999);
        $productData['barcode'] = "Code 128";
        $productData['sell_price'] = $product['standardPrice'] ?? 0;
        $productData['quantity'] = $product['availableQuantity'] ?? 0;
        $productData['product_type'] = 'service';
        $productData['tax_type'] = 2;
        $productData['order_tax'] = 0;
        $productData['imei_no'] = 0;
        $productData['status'] = 1;

        return Product::create($productData);
    }


    private function downloadImage($imageUrl, $productId, $productName)
    {
        try {
            $client = new \GuzzleHttp\Client();

            // Generate a unique filename
            $filename = Str::slug($productName) . '.jpg';

            // Define the path
            $path = "public/images/products/$filename";

            // Check if the file already exists
            if (!Storage::exists($path)) {
                // Download the image
                $response = $client->get($imageUrl);

                // Save the image
                Storage::put($path, $response->getBody());

                // Save the image path in the database
                ProductImage::create([
                    'img_path' => "/images/products/$filename",
                    'product_id' => $productId,
                ]);
            } else {
                Log::info("File already exists: $path");
            }

            // Return the image URL or path
            return Storage::url($path);
        } catch (\Exception $e) {
            // Log and handle any errors
            Log::error('Failed to download and save image: ' . $e->getMessage());
            return null; // Return null in case of failure
        }
    }
}
