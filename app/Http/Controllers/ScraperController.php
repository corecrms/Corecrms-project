<?php

namespace App\Http\Controllers;

use App\Jobs\StartScrapingJob;
use App\Models\User;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductWarehouse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Cache\RateLimiting\Unlimited;
use App\Models\Warehouse;
class ScraperController extends Controller
{


    // public function startScraping(Request $request)
    // {
    //     $url = $request->url;
    //     $html = file_get_contents($url);
    //     $dom = new \DOMDocument();
    //     @$dom->loadHTML($html);
    //     $xpath = new \DOMXPath($dom);
    //     $hrefs = $xpath->evaluate("/html/body//a");
    //     $data = [];
    //     for ($i = 0; $i < $hrefs->length; $i++) {
    //         $href = $hrefs->item($i);
    //         $url = $href->getAttribute('href');
    //         $text = $href->nodeValue;
    //         $data[] = [
    //             'url' => $url,
    //             'text' => $text
    //         ];
    //     }
    //     return response()->json($data);
    // }

    // public function startScraping(Request $request)
    // {
    //     ini_set('max_execution_time', 3600); // 1 hour

    //     $baseUrl = 'https://www.atlantawholecell.com/';
    //     $categoryUrl = 'https://www.atlantawholecell.com/api/menu?businessTypeId=1';
    //     $client = new \GuzzleHttp\Client();
    //     $request = $client->request('GET', $categoryUrl);
    //     $response = $request->getBody();
    //     $result = json_decode($response, true);
    //     // return $result['result'];
    //     $data = [];
    //     $subCategories = [];
    //     foreach ($result['result'] as $category) {
    //         // return $category;
    //         // dd($category);
    //         $categoryName = $category['name'];
    //         foreach($category['subCategories'] as $subCategory)
    //         {
    //             $subCategoryName = $subCategory['name'];
    //             foreach($subCategory['subCategories'] as $subSubCategory)
    //             {
    //                 $subSubCategoryName = $subSubCategory['name'];
    //                 $subSubCategoryId = $subSubCategory['id'];
    //                 $subSubCategoryUrl = 'https://www.atlantawholecell.com/api/ecommerce/product/category?categoryIdList='.$subSubCategoryId.'&page=0&size=20&sort=price&sortDirection=DESC&storeIds=2';
    //                 $request = $client->request('GET', $subSubCategoryUrl);
    //                 $response = $request->getBody();
    //                 $responseDecode = json_decode($response, true);
    //                 if($responseDecode['status'] == 200  && $responseDecode['result']['content'] != null)
    //                 {
    //                     foreach ($responseDecode['result']['content'] as $product) {
    //                         // if iteration reach 5 then break
    //                         $data[] = [
    //                             'name' => $product['productName'] ?? 'No Name',
    //                             'description' => $product['productName'] ?? 'No Description',
    //                             'sku' => $product['sku'] ?? rand(10000, 99999),
    //                             'purchase_price' => $product['standardPrice'],
    //                             'sale_price' => $product['standardPrice'],
    //                             'imageUrl' => $product['imageUrl'] ?? 'https://via.placeholder.com/150',
    //                             'quantity' => $product['availableQuantity'] ?? 0,
    //                         ];
    //                         if(count($data) == 5)
    //                         {
    //                             break;
    //                         }
    //                     }
    //                 }

    //             }
    //         }
    //     }
    //     return response()->json($data);
    // }


    // public function startScraping(Request $request)
    // {
    //     ini_set('max_execution_time', 3600); // 1 hour

    //     $baseUrl = 'https://www.atlantawholecell.com/';
    //     $categoryUrl = 'https://www.atlantawholecell.com/api/menu?businessTypeId=1';

    //     $client = new \GuzzleHttp\Client();

    //     try {
    //         // Fetch categories
    //         $response = $client->get($categoryUrl);
    //         $result = json_decode($response->getBody(), true);

    //         $data = [];
    //         foreach ($result['result'] as $category) {
    //             $categoryName = $category['name'];

    //             foreach ($category['subCategories'] as $subCategory) {
    //                 $subCategoryName = $subCategory['name'];

    //                 foreach ($subCategory['subCategories'] as $subSubCategory) {
    //                     $subSubCategoryName = $subSubCategory['name'];
    //                     $subSubCategoryId = $subSubCategory['id'];

    //                     $subSubCategoryUrl = $baseUrl . "api/ecommerce/product/category?categoryIdList=$subSubCategoryId&page=0&size=20&sort=price&sortDirection=DESC&storeIds=2";

    //                     // Fetch products for the sub-sub-category
    //                     $productResponse = $client->get($subSubCategoryUrl);
    //                     $productData = json_decode($productResponse->getBody(), true);

    //                     if ($productData['status'] === 200 && !empty($productData['result']['content'])) {
    //                         foreach ($productData['result']['content'] as $product) {
    //                             $data[] = [
    //                                 'name' => $product['productName'] ?? 'No Name',
    //                                 'description' => $product['productName'] ?? 'No Description',
    //                                 'sku' => $product['sku'] ?? rand(10000, 99999),
    //                                 'purchase_price' => $product['standardPrice'] ?? 0,
    //                                 'sale_price' => $product['standardPrice'] ?? 0,
    //                                 'imageUrl' => $product['imageUrl'] ?? 'https://via.placeholder.com/150',
    //                                 'quantity' => $product['availableQuantity'] ?? 0,
    //                             ];

    //                             // Break if data reaches 5 items
    //                             if (count($data) >= 2) {
    //                                 break 3; // Exit all loops
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }

    //         return response()->json($data);
    //     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //         return response()->json([
    //             'error' => 'Failed to fetch data.',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'An unexpected error occurred.',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }





    // public function startScraping(Request $request)
    // {
    //     ini_set('max_execution_time', 36000); // 10 hours

    //     $baseUrl = 'https://www.atlantawholecell.com/';
    //     $categoryUrl = 'https://www.atlantawholecell.com/api/menu?businessTypeId=1';

    //     $client = new \GuzzleHttp\Client();

    //     $creator = User::first();
    //     $warehouseId = Warehouse::first()->id;

    //     try {
    //         // Fetch categories
    //         $response = $client->get($categoryUrl);
    //         $result = json_decode($response->getBody(), true);

    //         $data = [];
    //         $requestCount = 0;

    //         foreach ($result['result'] as $category) {

    //             $brandName = $category['name'];

    //             //  $dbBrandName = Brand::where('name',$brandName)->get();
    //             $dbBrand = Brand::firstOrCreate(
    //                 ['name' => $brandName]
    //             );


    //             // Check if subCategories exist for this category
    //             if (!isset($category['subCategories']) || empty($category['subCategories'])) {
    //                 continue; // Skip to the next category if no subCategories exist
    //             }

    //             foreach ($category['subCategories'] as $subCategory) {

    //                 $categoryName = $subCategory['name'];

    //                 $dbCategory = Category::firstOrCreate(
    //                     ['name' => $categoryName],
    //                     ['brand_id' => $dbBrand->id, 'code' => $categoryName],
    //                 );

    //                 // Check if subCategories exist for this subCategory
    //                 if (!isset($subCategory['subCategories']) || empty($subCategory['subCategories'])) {
    //                     continue; // Skip to the next subCategory if no subSubCategories exist
    //                 }

    //                 foreach ($subCategory['subCategories'] as $subSubCategory) {
    //                     $subSubCategoryId = $subSubCategory['id'];
    //                     $subCategoryName = $subSubCategory['name'];

    //                     $dbSubCategory = SubCategory::firstOrCreate(
    //                         ['name' => $subCategoryName],
    //                         ['category_id' => $dbCategory->id, 'code' => $subCategoryName]
    //                     );

    //                     $subSubCategoryUrl = $baseUrl . "api/ecommerce/product/category?categoryIdList=$subSubCategoryId&page=0&size=20&sort=price&sortDirection=DESC&storeIds=2";

    //                     // Make the API request
    //                     $productResponse = $client->get($subSubCategoryUrl);
    //                     $productData = json_decode($productResponse->getBody(), true);

    //                     // Handle delay after every second request
    //                     // $requestCount++;
    //                     // if ($requestCount % 2 == 0) {
    //                     //     sleep(2); // Wait for 2 seconds
    //                     // }

    //                     if ($productData['status'] === 200 && !empty($productData['result']['content'])) {
    //                         foreach ($productData['result']['content'] as $product) {
    //                             $productName = $product['productName'] ?? 'No Name';
    //                             $imageUrl = $product['imageUrl'] ?? 'https://via.placeholder.com/150';

    //                             $dbProduct =   $this->createProduct($product,$dbBrand->id,$dbCategory->id,$dbSubCategory->id,$creator->id,$warehouseId);

    //                             ProductWarehouse::create([
    //                                 'product_id' => $dbProduct->id,
    //                                 'warehouse_id' => $warehouseId,
    //                             ]);

    //                             // Download and save the image
    //                             if ($imageUrl) {
    //                                 $this->downloadImage($imageUrl,$dbProduct->id ,$productName);
    //                             }

    //                             // Store product details
    //                             $data[] = [
    //                                 'name' => $productName,
    //                                 'description' => $product['productName'] ?? 'No Description',
    //                                 'sku' => $product['sku'] ?? rand(10000, 99999),
    //                                 'price' => $product['standardPrice'] ?? 0,
    //                                 'imageUrl' => $imageUrl,
    //                                 'quantity' => $product['availableQuantity'] ?? 0,
    //                                 'category' => $subSubCategory['name'],
    //                             ];
    //                         }
    //                     }

    //                     // if (count($data) >= 2) {
    //                     //     break 3; // Exit all loops
    //                     // }
    //                 }
    //             }
    //         }

    //         // return response()->json($subCategories);
    //         // Save JSON data to a file
    //         $jsonFileName = 'products_' . now()->format('Ymd_His') . '.json';
    //         Storage::disk('local')->put("scraped_data/$jsonFileName", json_encode($data, JSON_PRETTY_PRINT));

    //         return response()->json(['message' => 'Data scraped successfully', 'json_file' => $jsonFileName]);
    //     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //         return response()->json([
    //             'error' => 'Failed to fetch data.',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'error' => 'An unexpected error occurred.',
    //             'message' => $e->getMessage()
    //         ], 500);
    //     }
    // }



    public function startScraping(Request $request){
        StartScrapingJob::dispatch();
        return response()->json(['message' => 'Scraping started successfully!']);
    }

    /**
     * Download an image from a URL and save it with the product name.
     */
    // private function downloadImage($imageUrl, $productName)
    // {
    //     try {
    //         $client = new \GuzzleHttp\Client();
    //         $fileName = Str::slug($productName) . '.jpg';
    //         $path = "product_images/$fileName";

    //         // Check if the file already exists
    //         if (!Storage::disk('local')->exists($path)) {
    //             $response = $client->get($imageUrl);
    //             Storage::disk('local')->put($path, $response->getBody());
    //         } else {
    //             Log::info("File already exists: $path");
    //         }
    //     } catch (\Exception $e) {
    //         // Log error if the image download fails
    //         Log::error('Failed to download image: ' . $e->getMessage());
    //     }
    // }



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
        $productData['sku'] = $product['sku'] ?? rand(10000, 99999);
        $productData['barcode'] = "Code 128";
        $productData['purchase_price'] = $product['standardPrice'] ?? 0;
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
