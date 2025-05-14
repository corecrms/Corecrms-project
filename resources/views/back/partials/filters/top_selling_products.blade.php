@forelse ($topSellingProducts as $product)
<tr>
    <td>
        <div class="d-flex gap-2 align-items-center py-2">
            <div>
                <img src="{{ isset($product->product->images[0]) ? asset('/storage' . $product->product->images[0]->img_path) : 'https://placehold.co/600x400' }}"
                    width="50" alt="" class="img-fluid rounded-3" />
            </div>
            <div>
                <h6 class="heading fw-bold" style="font-size: 14px">
                    {{ strlen($product?->product?->name) > 20 ? substr($product?->product?->name, 0, 20) . '...' : $product?->product?->name }}
                </h6>
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <p class="m-0 text-muted">
                            {{ optional($product->product->product_warehouses[0] ?? null)?->warehouse?->users?->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty

<tr>
    <td class="text-center">
        No Data Found
    </td>
</tr>

@endforelse
