@forelse ($recentSaleProducts as $product)
    <tr>
        <td>
            <div class="d-flex gap-2 align-items-center py-2 justify-content-between">
                <div class="d-flex gap-2 align-items-center">
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
                                    {{ $product?->sub_total ?? '0' }}
                                </p>
                            </div>
                            <div>
                                <p class="mb-1">
                                    <i class="bi bi-circle-fill orange-txt"
                                        style="font-size: 6px"></i>
                                </p>
                            </div>
                            <p class="m-0">{{ $product?->quantity }} Qty</p>
                        </div>
                    </div>
                </div>
                <div class="text-end">
                    <p class="m-0 text-muted">
                        {{ $product->created_at->format('d/m/y') }}</p>
                    @if ($product->sale->status == 'completed')
                        <span class="badges bg-success text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                            <span class="mb-1">
                                <i class="bi bi-circle-fill text-white" style="font-size: 6px"></i>
                            </span>
                            <span>Completed</span>
                        </span>
                    @else
                        <span class="badges bg-warning text-center px-1 d-flex gap-1 align-items-center justify-content-center">
                            <span class="mb-1">
                                <i class="bi bi-circle-fill text-white" style="font-size: 6px"></i>
                            </span>
                            <span>Pending</span>
                        </span>
                    @endif
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
