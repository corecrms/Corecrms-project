<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class ProductsExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Product::all();
        $products = Product::with('category','brand')->get();

        $exportData = [];

        foreach ($products as $product) {
            $exportData[] = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'description' => $product->description,
                'barcode' => $product->barcode,
                'product_type' => $product->product_type,
                'category_id' => $product->category->id,
                'brand_id' => $product->brand->id,
                'stock_alert' => $product->stock_alert,
                'purchase_price' => $product->purchase_price,
                'sale_price' => $product->sell_price,
                'status' => $product->status,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        }

        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'sku',
            'description',
            'barcode',
            'product_type',
            'category_id',
            'brand_id',
            'stock_alert',
            'purchase_price',
            'sale_price',
            'status',
            'created_at',
            'updated_at',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => '@',
            'B' => '@',
            'C' => '@',
            'D' => '@',
            'E' => '@',
            'F' => '@',
            'G' => '@',
            'H' => '@',
            'I' => '@',
            'J' => '@',
            'K' => '@',
            'L' => '@',
            'M' => '@',
            'N' => '@',
        ];
    }


}
