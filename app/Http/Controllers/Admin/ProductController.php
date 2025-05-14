<?php

namespace App\Http\Controllers\Admin;

use stdClass;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\Brand;
use App\Models\Vendor;
use GuzzleHttp\Client;
use App\Models\Barcode;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Warranty;
use App\Models\Warehouse;
// use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\ShopifyStore;
use Illuminate\Http\Request;
use App\Models\VariantOption;
use App\Models\ProductItem;
use App\Models\Setting;
use App\Models\ProductWarehouse;
use App\Services\ShopifyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use App\Models\SubCategory;
use Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Imports\HeadingRowImport;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct()
    {
        $this->middleware('permission:products-list|products-create|products-edit|products-delete|products-show
         ', ['only' => ['index', 'show']]);
        $this->middleware('permission:products-list', ['only' => ['index']]);
        $this->middleware('permission:products-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:products-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:products-delete', ['only' => ['destroy']]);
        $this->middleware('permission:products-show', ['only' => ['show']]);
    }



    // public function index(Request $req)
    // {

    //     return $this->handleException(function () use ($req) {

    //         if ($req->filled('search')) {

    //             $searchTerm = $req->search;
    //             // Search products by name
    //             $productsByName = Product::search($searchTerm)->get();
    //             // Search products by category name
    //             $category = Category::search($searchTerm)->first();
    //             $productsByCategory = $category ? $category->products : collect();
    //             // Search products by brand name
    //             $brand = Brand::search($searchTerm)->first();
    //             $productsByBrand = $brand ? $brand->product : collect();

    //             $barcode = Barcode::search($searchTerm)->get();
    //             $barcode->load('product');
    //             $barcode = $barcode ? $barcode : collect();
    //             $barcodeProduct = [];
    //             foreach ($barcode as $productsByBarcodes) {
    //                 $barcodeProduct[] = $productsByBarcodes->product;
    //             }
    //             // Merge and remove deduplicate products from all searches
    //             $mergedProducts = $productsByName->merge($productsByCategory)->merge($productsByBrand)->merge($barcodeProduct)->unique('id');

    //             // Load relationships
    //             $mergedProducts->load('category', 'brand', 'unit', 'images', 'product_warehouses', 'barcodes');

    //             $products = $mergedProducts;
    //             $categories = Category::all();
    //             $brands = Brand::all();
    //             return view('back.products.index', compact('products', 'categories', 'brands'));
    //         }
    //         else if (auth()->user()->hasRole(['Cashier', 'Manager'])) {
    //             $products = Product::with('category', 'unit', 'images', 'product_warehouses')
    //                 ->whereHas('product_warehouses', function ($q) {
    //                     $q->where('warehouse_id', auth()->user()->warehouse_id);
    //                 })->get();
    //             $categories = Category::all();
    //             $brands = Brand::all();
    //             return view('back.products.index', compact('products', 'categories', 'brands'));
    //         }
    //         else {
    //             $selected_warehouse_id = session('selected_warehouse_id');
    //             if ($selected_warehouse_id) {
    //                 $products = Product::with('category', 'unit', 'images', 'product_warehouses')->whereHas('product_warehouses', function ($query) use ($selected_warehouse_id) {
    //                     $query->where('warehouse_id', $selected_warehouse_id);
    //                 })->get();
    //             } else {
    //                 $products = Product::with('category', 'unit', 'images', 'product_warehouses')->get();
    //                 // dd($products);
    //             }
    //             $categories = Category::all();
    //             $brands = Brand::all();

    //             return view('back.products.index', compact('products', 'categories', 'brands'));
    //         }
    //     });
    // }


    // public function index(Request $req)
    // {
    //     return $this->handleException(function () use ($req) {
    //         if ($req->ajax()) {
    //             $query = Product::with(['category', 'brand', 'unit', 'images', 'product_warehouses']);

    //             // Handle search
    //             if ($req->filled('search')) {
    //                 $searchTerm = $req->search;
    //                 $query->where('name', 'like', "%{$searchTerm}%")
    //                     ->orWhere('sku', 'like', "%{$searchTerm}%");
    //                 // Add other search conditions as needed
    //             }

    //             // Get paginated results
    //             $products = $query->paginate($req->length);

    //             // Format the products for DataTables
    //             $data = $products->map(function($product) {
    //                 return [
    //                     'id' => $product->id, // Include the ID if needed
    //                     'checkbox' => '<input class="checkbox__input select-checkbox deleteRow" type="checkbox" data-id="' . $product->id . '" />',
    //                     'image' => $product->images->isNotEmpty() ? '<img src="' . asset('/storage' . $product->images[0]['img_path']) . '" style="width: 70px">' : '<img src="' . asset('back/assets/image/no-image.png') . '" style="width: 70px" />',
    //                     'name' => $product->name,
    //                     'sku' => $product->sku ?? '',
    //                     'product_type' => $product->product_type ?? '....',
    //                     'category' => $product->category->name,
    //                     'brand' => $product->brand->name ?? '',
    //                     'purchase_price' => '$' . ($product->purchase_price ?? '....'),
    //                     'sell_price' => '$' . $product->sell_price,
    //                     'unit' => $product->unit->short_name ?? '....',
    //                     'quantity' => $this->getProductQuantity($product),
    //                     'actions' => $this->getActionButtons($product),
    //                 ];
    //             });

    //             return response()->json([
    //                 'data' => $data,
    //                 'recordsTotal' => $products->total(),
    //                 'recordsFiltered' => $products->total(),
    //             ]);
    //         }

    //         // Handle non-AJAX requests
    //         $categories = Category::all();
    //         $brands = Brand::all();
    //         return view('back.products.index', compact('categories', 'brands'));
    //     });
    // }


    public function index(Request $req)
    {
        return $this->handleException(function () use ($req) {
            if ($req->ajax()) {
                $query = Product::with(['category', 'brand', 'unit', 'images', 'product_warehouses']);

                // Handle search
                if ($req->filled('search')) {
                    $searchTerm = $req->search;
                    $query->where(function($q) use ($searchTerm) {
                        $q->where('name', 'like', "%{$searchTerm}%")
                          ->orWhere('sku', 'like', "%{$searchTerm}%")
                          ->orWhere('product_type', 'like', "%{$searchTerm}%")
                          ->orWhereHas('category', function($query) use ($searchTerm) {
                              $query->where('name', 'like', "%{$searchTerm}%");
                          })
                          ->orWhereHas('brand', function($query) use ($searchTerm) {
                              $query->where('name', 'like', "%{$searchTerm}%");
                          });
                    });
                }

                // Get paginated results
                $totalRecords = $query->count();
                $products = $query->skip($req->start)
                    ->take($req->length)
                    ->get();

                // Format the products for DataTables
                $data = $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'checkbox' => '<input class="checkbox__input select-checkbox deleteRow" type="checkbox" data-id="' . $product->id . '" />',
                        'image' => $product->images->isNotEmpty() ? '<img src="' . asset('/storage' . $product->images[0]['img_path']) . '" style="width: 70px">' : '<img src="' . asset('back/assets/image/no-image.png') . '" style="width: 70px" />',
                        'name' => $product->name,
                        'sku' => $product->sku ?? '',
                        'product_type' => $product->product_type ?? '....',
                        'category' => $product->category->name,
                        'brand' => $product->brand->name ?? '',
                        'purchase_price' => '$' . ($product->purchase_price ?? '....'),
                        'sell_price' => '$' . $product->sell_price,
                        'unit' => $product->unit->short_name ?? '....',
                        'quantity' => $this->getProductQuantity($product),
                        'actions' => $this->getActionButtons($product),
                    ];
                });

                return response()->json([
                    'data' => $data,
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalRecords,
                ]);
            }

            // Handle non-AJAX requests
            $categories = Category::all();
            $brands = Brand::all();
            return view('back.products.index', compact('categories', 'brands'));
        });
    }



    // Helper function to get product quantity based on user role
    private function getProductQuantity($product)
    {
        if (auth()->user()->hasRole(['Cashier', 'Manager'])) {
            return $product->product_warehouses->where('warehouse_id', auth()->user()->warehouse_id)->first()->quantity ?? '0';
        } elseif (session('selected_warehouse_id')) {
            return $product->product_warehouses->where('warehouse_id', session('selected_warehouse_id'))->first()->quantity ?? '0';
        } else {
            return $product->product_warehouses->sum('quantity') ?? '0';
        }
    }

    // Helper function to generate action buttons
    private function getActionButtons($product)
    {
        $buttons = '<div class="">
                    <a class="btn btn-secondary bg-transparent border-0 text-dark" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">';

        if (auth()->user()->can('products-show')) {
            $buttons .= '<a class="dropdown-item" href="' . route('get-one-product-details', $product->id) . '">Product Detail</a>';
        }
        if (auth()->user()->can('products-edit')) {
            $buttons .= '<a class="dropdown-item" href="' . route('products.edit', $product->id) . '">Edit Product</a>';
        }
        if (auth()->user()->can('products-delete')) {
            $buttons .= '<form action="' . route('products.destroy', $product->id) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="dropdown-item confirm-text">Delete Product</button>
                     </form>';
        }
        $buttons .= '<a class="dropdown-item" href="' . route('products.duplicate', $product->id) . '">Duplicate Product</a>';
        $buttons .= '</div></div>';

        return $buttons;
    }






    public function productSearch(Request $req, $cate_id = null)
    {
        if (!$req->query) {
            return response()->json(['success' => false]);
        }
        $searchTerm = $req->input('query');

        // Search products by name
        $productsByName = Product::search($searchTerm);

        if ($cate_id) {
            $productsByName = $productsByName->where('category_id', $cate_id);
        }

        $productsByName = $productsByName->get();

        // Search products by category name
        $category = Category::search($searchTerm)->first();
        $productsByCategory = $category ? $category->products : collect();

        // Search product by subscategory name
        $subCategory = SubCategory::search($searchTerm)->first();
        $productsBySubCategory = $subCategory ? $subCategory->products : collect();

        // Search products by brand name
        $brand = Brand::search($searchTerm)->first();
        $productsByBrand = $brand ? $brand->products : collect();

        $barcode = Barcode::search($searchTerm)->get();
        $barcode->load('product');
        $barcode = $barcode ? $barcode : collect();
        $barcodeProduct = [];
        foreach ($barcode as $productsByBarcodes) {
            $barcodeProduct[] = $productsByBarcodes->product;
        }

        // Merge and deduplicate products from all searches
        $mergedProducts = $productsByName->merge($productsByCategory)->merge($productsBySubCategory)->merge($productsByBrand)->merge($barcodeProduct)->unique('id');

        // dd($mergedProducts);
        // Load relationships
        $mergedProducts->load('category', 'brand', 'unit', 'images', 'product_warehouses', 'barcodes');
        $products = $mergedProducts;

        if ($products) {
            return response()->json(['success' => true, 'product' => $products]);
        } else {
            return response()->json(['success' => false]);
        }
    }


    public function productFilter(Request $req)
    {

        $selected_warehouse_id = session('selected_warehouse_id');

        $query = Product::with('category', 'unit', 'images', 'product_warehouses', 'barcodes');

        // Filter by warehouse if applicable
        if ($selected_warehouse_id) {
            $query->whereHas('product_warehouses', function ($query) use ($selected_warehouse_id) {
                $query->where('warehouse_id', $selected_warehouse_id);
            });
        }

        $filters = $req->all();

        if (isset($filters['code'])) {
            $query->whereHas('barcodes', function ($query) use ($filters) {
                $query->where('code', $filters['code']);
            })->orWhere('sku', $filters['code']);
        }

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['category_id']) && $filters['category_id'] > 0) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['brand_id']) && $filters['brand_id'] > 0) {
            $query->where('brand_id', $filters['brand_id']);
        }

        $products = $query->get();

        $categories = Category::all();
        $brands = Brand::all();

        return view('back.products.index', compact('products', 'categories', 'brands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $warranties = Warranty::all();
        $units = Unit::all();
        $mainUnits = Unit::where('parent_id', '0')->get();
        $taxes = Tax::all();
        $warehouses = Warehouse::with('users')->get();
        $subCategories = SubCategory::all();
        return view('back.products.create', compact('categories', 'brands', 'warranties', 'units', 'mainUnits', 'taxes', 'warehouses', 'subCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        // dd($request->all());
        $data = $request->validated();
        $warehouse = Warehouse::find($data['warehouse_id']);

        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found.'], 404);
        }

        $productData = [
            "title" => $data['name'],
            "body_html" => $data['description'],
            "vendor" => $warehouse->users->name, // Use the vendor's name
            "product_type" => $data['product_type'],
            'variants' => [],
            'options' => []
        ];

        // Add variants if they exist in the request data
        if ($request->input('variants') && is_array($request->input('variants'))) {
            // Extracting options for variants (assuming unique codes)
            foreach ($request->variants as $variant) {
                $optionName = ucfirst($variant['name']);
                $optionValues = array_column($variant['options'], 'code');
                $productData['options'][] = [
                    'name' => $optionName,
                    'values' => $optionValues,
                ];
            }

            $variantCombinations = [];
            foreach ($request->variants[0]['options'] as $firstOption) {
                $currentCombination = ['option1' => $firstOption['code']];
                if (isset($request->variants[1]['options'])) {
                    foreach ($request->variants[1]['options'] as $secondOption) {
                        $newCombination = $currentCombination;
                        $newCombination['option2'] = $secondOption['code'];
                        $variantCombinations[] = $newCombination;

                        // Add logic for third variant and beyond (modify loop structure)
                        if (isset($request->variants[2]['options'])) {
                            foreach ($request->variants[2]['options'] as $thirdOption) {
                                $furtherCombination = $newCombination;
                                $furtherCombination['option3'] = $thirdOption['code'];
                                $variantCombinations[] = $furtherCombination;

                                // Add similar loops for more variants (adjust loop structure)
                            }
                        }
                    }
                } else {
                    $variantCombinations[] = $currentCombination;
                }
            }

            // Generate variants from combinations
            foreach ($variantCombinations as $combination) {
                $variant = [];
                foreach ($combination as $optionName => $optionValue) {
                    $variant[$optionName] = $optionValue;
                }
                // $variant['price'] = $request->variants[0]['options'][0]['price'];
                $variant['price'] = $data['sell_price'];

                $productData['variants'][] = $variant;
            }
        }

        // if product does not have the variants the product price set like this
        if (!$request->input('variants')) {
            $productData["variants"] = [
                [
                    "price" => $data['sell_price'],
                ]
            ];
        }



        if ($request->hasFile('img')) {
            $images = collect($request->file('img'))->map(function ($image) {
                // Upload each image to a public accessible location and get the URL
                $path = $image->store('public/products');
                $url = Storage::url($path);
                return ['src' => 'https://sale-purchase.digitalisolutions.net/' . $url];
            })->toArray();

            $productData['images'] = $images;
        }

        $shopify_enable = Setting::first();
        if ($shopify_enable->shopify_enable == 1) {

            $shopify_store = ShopifyStore::first();
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


        $data['created_by'] = $data['updated_by'] = auth()->user()->id;
        $data['sku'] = $data['productCode'];

        $product = Product::create($data);
        if (!empty($request->variants)) {
            foreach ($request->variants as $index => $variantData) {
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'name' => $variantData['name'],
                ]);

                // Create Variant Options and map Shopify Variant IDs
                foreach ($variantData['options'] as $optionIndex => $optionData) {
                    $localOption = VariantOption::create([
                        'variant_id' => $variant->id,
                        'sub_name' => $optionData['sub_name'],
                        'code' => $optionData['code'],
                        'cost' => $optionData['cost'],
                        'price' => $optionData['price'],
                    ]);

                    // Here, you map the Shopify Variant ID
                    if (isset($shopifyProduct['product']['variants'][$index])) {
                        $localOption->shopify_variant_id = $shopifyProduct['product']['variants'][$index]['id'];
                        $localOption->save();
                    }
                }
            }
        }

        ProductWarehouse::create([
            'product_id' => $product->id,
            'warehouse_id' => $data['warehouse_id']
        ]);

        if (session()->has('selected_warehouse_id')) {
            if ($data['warehouse_id'] != session('selected_warehouse_id')) {
                ProductWarehouse::create([
                    'product_id' => $product->id,
                    'warehouse_id' => session('selected_warehouse_id')
                ]);
            }
        }

        if ($request->has('img')) {
            $this->handleImageUpload($request, $product->id);
        }


        if ($product) {
            $productId = $product->id; // or however you get your product ID

            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'barcodeSymbology') === 0 && $value) {
                    // Extract the index to match with product code
                    $index = substr($key, -1);

                    $barcode = new Barcode();
                    $barcode->product_id = $productId;
                    $barcode->symbology = $value;
                    $barcode->code = $request->input('productCode' . $index);
                    $barcode->save();
                }
            }

            return response()->json(['message' => 'Product created successfully!'], 200);
        }
        // return redirect()->route('products.index')
        //     ->with('error', 'Product creation failed.');
        return response()->json(['message' => 'Product creation failed!'], 200);
    }


    private function handleImageUpload(Request $request, $productId)
    {
        $images = $request->file('img');
        foreach ($images as $file) {
            // Validate the file
            $validatedData = $request->validate([
                'img.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Generate a unique filename
            $filename = "productImg" . '_' . time() . '_' . Str::uuid() . '.' . $file->extension();

            // Store the file and handle any potential errors
            try {
                $file->storeAs('public/images/products', $filename);
            } catch (\Exception $e) {
                // Handle the error
                return back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }

            // Insert the image path and product ID into the database and handle any potential errors
            try {
                ProductImage::create([
                    'img_path' => "/images/products/$filename",
                    'product_id' => $productId
                ]);
            } catch (\Exception $e) {
                // Handle the error
                return back()->with('error', 'Failed to save image: ' . $e->getMessage());
            }
        }
    }

    public function deleteImage(Request $request, $id)
    {
        // Find the image in the database
        $image = ProductImage::find($id);
        if (!$image) {
            return back()->with('error', 'Image not found');
        }

        // Delete the image file
        Storage::delete('public' . $image->img_path);

        // Delete the image record from the database
        $image->delete();

        return back()->with('success', 'Image deleted successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->handleException(function () use ($product) {
            $product = new ProductResource($product);

            return view('back.products.show', compact('product'));
        });
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return $this->handleException(function () use ($product) {
            $product->load('variants.options');
            $product = new ProductResource($product);
            $categories = Category::all();
            $warehouses = Warehouse::with('users')->get();
            $brands = Brand::all();
            // $warranties = Warranty::all();
            $units = Unit::all();
            $mainUnits = Unit::where('parent_id', '0')->get();
            $taxes = Tax::all();
            $subCategories = SubCategory::all();

            return view('back.products.edit', compact('product', 'categories', 'brands', 'units', 'mainUnits', 'taxes', 'warehouses', 'subCategories'));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */


    public function update(ProductUpdateRequest $request, $id)
    {

        $data = $request->validated();
        $data['status'] = $request->status ? 1 : 0;
        $data['updated_by'] = auth()->user()->id;
        // dd($data);
        $product = Product::findOrFail($id);
        $requestData = $request->all();
        $requestData['status'] = $request->status ? 1 : 0;
        $requestData['imei_no'] = $request->imei_no ? 1 : 0;


        $shopify_enable = Setting::first();

        $existing_images = $request->input('existing_images', []);
        if ($product->shopify_id) {
            if ($shopify_enable->shopify_enable == 1) {
                $shopifyProductId = (string) $product->shopify_id;
                $warehouse = Warehouse::find($data['warehouse_id']);
                if (!$warehouse) {
                    return response()->json(['error' => 'Warehouse not found.'], 404);
                }

                $productData = [
                    "title" => $data['name'],
                    "body_html" => $data['description'],
                    "vendor" => $warehouse->users->name, // Use the vendor's name
                    "product_type" => $data['product_type'],
                ];

                // Add variants if they exist in the request data
                if (isset($data['variants']) && is_array($data['variants'])) {
                    $productData["variants"] = array_map(function ($variant) {
                        return [
                            "option1" => $variant['option1'], // Adjust these keys according to your variant data structure
                            "price" => $variant['price'],
                            "sku" => $variant['sku']
                        ];
                    }, $data['variants']);
                } else {
                    $productData["variants"] =  [
                        [
                            "price" => $data['sell_price'],
                            "inventory_quantity" => '12',
                        ]
                    ];
                }

                $shopDomain = 'lara-app-1234.myshopify.com'; // Example, adjust based on your application logic

                // Retrieve the access token for the shop from the database
                $shop = ShopifyStore::where('shop_domain', $shopDomain)->first();
                // $existing_images = $request->input('existing_images', []);
                if (!$shop) {
                    return redirect()->route('products.index')->with('error', 'Shop not found.');
                }

                $accessToken = $shop->access_token;
                $client = new Client();

                // Handle new images for Shopify
                if ($request->hasFile('img')) {
                    $images = collect($request->file('img'))->map(function ($image) {
                        // Upload each image to a public accessible location and get the URL
                        $path = $image->store('public/products');
                        $url = Storage::url($path);
                        return ['src' => 'https://sale-purchase.digitalisolutions.net/' . $url];
                    })->toArray();

                    $productData['images'] = $images;
                }

                // Fetch and delete old images from Shopify
                try {
                    $shopifyImagesResponse = $client->request('GET', "https://{$shopDomain}/admin/api/2023-04/products/{$shopifyProductId}/images.json", [
                        'headers' => [
                            'X-Shopify-Access-Token' => $accessToken,
                            'Content-Type' => 'application/json',
                        ],
                    ]);
                    $shopifyImages = json_decode($shopifyImagesResponse->getBody(), true)['images'];
                    $existingImageUrls = collect($product->images)->whereIn('id', $existing_images)->pluck('img_path')->map(function ($path) {
                        return 'https://sale-purchase.digitalisolutions.net/' . $path;
                    })->all();

                    $imagesToDelete = collect($shopifyImages)->filter(function ($shopifyImage) use ($existingImageUrls) {
                        return !in_array($shopifyImage['src'], $existingImageUrls);
                    });

                    if ($imagesToDelete && $imagesToDelete->isNotEmpty()) {
                        foreach ($imagesToDelete as $imageToDelete) {
                            $client->request('DELETE', "https://{$shopDomain}/admin/api/2023-04/products/{$shopifyProductId}/images/{$imageToDelete['id']}.json", [
                                'headers' => [
                                    'X-Shopify-Access-Token' => $accessToken,
                                    'Content-Type' => 'application/json',
                                ],
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    return redirect()->route('products.index')->with('error', 'Failed to fetch current images from Shopify: ' . $e->getMessage());
                }

                // Update Shopify product including new images
                try {
                    $response = $client->request('PUT', "https://{$shopDomain}/admin/api/2023-04/products/{$shopifyProductId}.json", [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'X-Shopify-Access-Token' => $accessToken,
                        ],
                        'body' => json_encode(['product' => $productData]),
                    ]);
                } catch (\Exception $e) {
                    return redirect()->route('products.index')->with('error', 'Failed to update product in Shopify: ' . $e->getMessage());
                }
            }
        }

        // Delete all variants of the product
        if ($product->variants) {

            foreach ($product->variants as $variant) {
                $variant->options()->delete();
                $variant->delete();
            }
        }

        $product->update($requestData);

        foreach ($product->images as $image) {
            if (!in_array($image->id, $existing_images)) {
                Storage::delete('public/images/products' . $image->img_path);
                $image->delete();
            }
        }

        // Handle new images
        if ($request->has('img')) {
            foreach ($data['img'] as $file) {
                $filename = "productImg" . '_' . rand(1111, 9999) . '.' . $file->extension();
                $file->storeAs('public/images/products', $filename);
                ProductImage::create([
                    'img_path' => "/images/products/$filename",
                    'product_id' => $product->id
                ]);
            }
        }

        // Handle variants
        if ($request->variants) {
            foreach ($request->variants as $variantData) {
                if (isset($variantData['name'])) {
                    $variant = Variant::create([
                        'product_id' => $product->id,
                        'name' => $variantData['name'],
                    ]);
                }
                if (isset($variant)) {
                    foreach ($variantData['options'] as $optionData) {
                        VariantOption::create([
                            'variant_id' => $variant->id,
                            'sub_name' => $optionData['sub_name'],
                            'code' => $optionData['code'],
                            'cost' => $optionData['cost'],
                            'price' => $optionData['price'],
                        ]);
                    }
                }
            }
        }

        // Handle barcodes
        $product->barcodes()->delete();
        foreach ($request->all() as $key => $value) {
            if (strpos($key, 'barcodeSymbology') === 0 && $value) {
                $index = substr($key, -1);
                $barcode = new Barcode();
                $barcode->product_id = $product->id;
                $barcode->symbology = $value;
                $barcode->code = $request->input('productCode' . $index);
                $barcode->save();
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }



    public function getOneProductDetails(Product $product, $id)
    {
        $product = Product::with('unit', 'variants.options', 'category', 'brand', 'tax', 'images')->find($id);
        $product->load('product_warehouses.warehouse.users', 'product_warehouses.product.unit');

        return view('back.products.product_detail', compact('product'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Product $product)
    // {
    //     return $this->handleException(function () use ($product) {
    //         // $product->delete();
    //         $shopDomain = 'lara-app-1234.myshopify.com'; // Example, adjust based on your application logic

    //         // Retrieve the access token for the shop from the database
    //         $shop = ShopifyStore::where('shop_domain', $shopDomain)->first();

    //         if (!$shop) {
    //             return redirect()->route('products.index')->with('error', 'Shop not found.');
    //         }

    //         $accessToken = $shop->access_token;
    //         $shopifyProductId = $product->shopify_id;

    //         $client = new Client(); // Assuming you're using GuzzleHttp\Client;

    //         try {
    //             $response = $client->request('DELETE', "https://{$shopDomain}/admin/api/2023-04/products/{$shopifyProductId}.json", [
    //                 'headers' => [
    //                     'Content-Type' => 'application/json',
    //                     'X-Shopify-Access-Token' => $accessToken,
    //                 ]
    //             ]);

    //             foreach ($product->product_warehouses as  $warehouse_product) {
    //                 $warehouse_product->delete();
    //             }
    //             $product->delete(); // Or any other logic you need
    //         } catch (\Exception $e) {
    //             // Handle exceptions, such as logging errors or notifying administrators
    //             Log::error("Failed to delete product from Shopify: " . $e->getMessage());
    //             // Optionally, return or display an error message
    //             return redirect()->back()->with('error', 'Failed to delete the product from Shopify.');
    //         }

    //         return redirect()->route('products.index')
    //             ->with('success', 'Product deleted successfully');
    //     });
    // }

    public function destroy(Product $product)
    {
        return $this->handleException(function () use ($product) {
            $shopify_enable = Setting::first();

            if ($shopify_enable->shopify_enable == 1) {
                $shopDomain = 'lara-app-1234.myshopify.com'; // Example, adjust based on your application logic

                // Retrieve the access token for the shop from the database
                $shop = ShopifyStore::where('shop_domain', $shopDomain)->first();

                if (!$shop) {
                    return redirect()->route('products.index')->with('error', 'Shop not found.');
                }

                $accessToken = $shop->access_token;
                $shopifyProductId = $product->shopify_id;

                $client = new Client(); // Assuming you're using GuzzleHttp\Client

                try {
                    $response = $client->request('DELETE', "https://{$shopDomain}/admin/api/2023-04/products/{$shopifyProductId}.json", [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'X-Shopify-Access-Token' => $accessToken,
                        ]
                    ]);

                    foreach ($product->product_warehouses as $warehouse_product) {
                        $warehouse_product->delete();
                    }

                    $product->barcodes()->delete();

                    $product->delete(); // Or any other logic you need
                } catch (\Exception $e) {
                    // Handle exceptions, such as logging errors or notifying administrators
                    Log::error("Failed to delete product from Shopify: " . $e->getMessage());
                    // Optionally, return or display an error message
                    return redirect()->back()->with('error', 'Failed to delete the product from Shopify.');
                }

                return redirect()->route('products.index')
                    ->with('success', 'Product deleted successfully from Shopify and database');
            } else {
                // If Shopify integration is not enabled, delete the product only from the database
                foreach ($product->product_warehouses as $warehouse_product) {
                    $warehouse_product->delete();
                }
                // delete barcodes
                $product->barcodes()->delete();

                $product->delete();

                return redirect()->route('products.index')
                    ->with('success', 'Product deleted successfully from database');
            }
        });
    }




    public function deleteProducts(Request $req)
    {
        // Get the Shopify integration setting
        $shopify_enable = Setting::first();

        foreach ($req->ids as $id) {
            $product = Product::with('product_warehouses')->find($id);

            if ($shopify_enable->shopify_enable == 1) {
                $shopDomain = 'lara-app-1234.myshopify.com'; // Example, adjust based on your application logic

                // Retrieve the access token for the shop from the database
                $shop = ShopifyStore::where('shop_domain', $shopDomain)->first();

                if (!$shop) {
                    return redirect()->route('products.index')->with('error', 'Shop not found.');
                }

                $accessToken = $shop->access_token;
                $shopifyProductId = $product->shopify_id;

                $client = new Client(); // Assuming you're using GuzzleHttp\Client;

                try {
                    $response = $client->request('DELETE', "https://{$shopDomain}/admin/api/2023-04/products/{$shopifyProductId}.json", [
                        'headers' => [
                            'Content-Type' => 'application/json',
                            'X-Shopify-Access-Token' => $accessToken,
                        ]
                    ]);

                    // Optionally, process the response or perform additional logic upon successful deletion
                    foreach ($product->product_warehouses as $warehouse_product) {
                        $warehouse_product->delete();
                    }

                    $product->barcodes()->delete();

                    $product->delete(); // Or any other logic you need
                } catch (\Exception $e) {
                    // Handle exceptions, such as logging errors or notifying administrators
                    Log::error("Failed to delete product from Shopify: " . $e->getMessage());
                    // Optionally, return or display an error message
                    return redirect()->back()->with('error', 'Failed to delete the product from Shopify.');
                }
            } else {
                // If Shopify integration is not enabled, delete the product only from the database
                foreach ($product->product_warehouses as $warehouse_product) {
                    $warehouse_product->delete();
                }
                $product->barcodes()->delete();

                $product->delete();
            }
        }

        return response()->json(['status' => 200, 'message' => 'Products deleted successfully!']);
    }


    public function exportProducts(Request $request)
    {

        return Excel::download(new ProductsExport, 'products.xlsx');
    }
    public function importView(Request $request)
    {
        return view('products.import');
    }

    public function import(Request $request)
    {

        try {
            $request->validate([
                'file' => 'required|file|mimes:xlsx,xls,csv'
            ]);

            $import = new ProductsImport;

            Excel::import($import, $request->file('file'));


            if ($import->getNewProductsCount() == 0) {
                return redirect()->back()->with('error', 'No new product were added please check your columns');
            }

            return redirect()->route('products.index')
                ->with('success', 'Products imported successfully.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $error = [];

            foreach ($failures as $failure) {
                // dd($failure);
                $error[] = $failure->row() . ' - ' . $failure->errors()[0];
            }

            return redirect()->route('products.index')
                ->with('error', $error);
        } catch (QueryException $e) {
            // dd($e);
            // Check if it's a duplicate entry error
            if ($e->errorInfo[2] === 1062) {
                return redirect()->route('products.index')
                    ->with('error', 'Duplicate entry. This product already exists.');
            } else {
                // Handle other database errors as needed
                return redirect()->route('products.index')
                    ->with('error', 'An error occurred during import.');
            }
        } catch (\Exception $e) {
            // Handle any other exceptions
            return redirect()->route('products.index')
                ->with('error', 'An unexpected error occurred: ' . $e->getMessage());
        }
    }


    public function product_duplicate($id)
    {
        $product = Product::findOrFail($id);
        $newProduct = $product->replicate();
        $newProduct->name = $product->name . ' (Copy)';
        $newProduct->sku = rand(10000000, 99999999);
        $newProduct->save();

        foreach ($product->images as $image) {
            $newProduct->images()->create([
                'img_path' => $image->img_path
            ]);
        }

        $product->product_warehouses->each(function ($warehouse) use ($newProduct) {
            $newProduct->product_warehouses()->create([
                'warehouse_id' => $warehouse->warehouse_id
            ]);
        });

        return redirect()->back()->with('success', 'Product duplicate successfully!');
    }
}
