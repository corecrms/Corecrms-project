@extends('user.dashboard-layout.app')


@section('content')
    <div class="container-xxl container-fluid pt-4 px-4 mb-5">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0 w-25">
                Wishlist Items
            </h3>
        </div>

        <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
            <div class="card-body">
                <div class="table-responsive p-2">
                    <table class="table">
                        <thead class="fw-bold">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Sku</th>
                                <th>Date</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($wishlists as $wishlist)
                                <tr>
                                    <td class="align-middle">
                                        @if (count($wishlist->product->images) > 0)
                                            <img src="{{ asset('/storage' . $wishlist->product->images[0]['img_path']) }}"
                                                alt="No" style="width: 70px">
                                            {{-- <span class="badge bg-primary rounded-pill">+{{ count($wishlist->product->images) - 1 }}</span> --}}
                                        @else
                                            <img src="{{ asset('back/assets/image/no-image.png') }}" alt=""
                                                class="" style=" width: 70px" />
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        {{ $wishlist->product->name ?? '' }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $wishlist->product->sku ?? '' }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $wishlist->created_at->format('d-m-Y') }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $wishlist->product->sell_price ?? ''}}
                                    </td>
                                    {{-- <td class="align-middle">
                                        <img src="{{asset('back/assets/dasheets/img/plus-circle.svg')}}" alt="" class="img-fluid btn p-0 eye-icon" />
                                    </td> --}}
                                    <td class="align-middle">
                                        {{-- <form class="d-inline" action="{{route('add-to-cart.store')}}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <input type="hidden" name="product_id" value="{{$wishlist->product->id}}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="price" value="{{$wishlist->product->sell_price}}">
                                            <input type="hidden" name="customer_id" value="{{auth()->id()}}">

                                            <button class="btn save-btn text-white btn-sm" type="submit">Add to Cart</button>
                                        </form> --}}
                                        <a href="{{ route('user.product.index',['code'=>$wishlist->product->category->code,'sku'=>$wishlist->product->sku]) }}" class="text-decoration-none">
                                            <img src="{{ asset('front/dasheets/img/eye.svg') }}" class="p-0 m-0" alt="" />
                                        </a>
                                        {{-- <a href="{{route('add-to-cart.store',$wishlist->product->id)}}" class="btn save-btn text-white btn-sm">Add to Cart</a> --}}
                                        <form class="d-inline" action="{{route('wishlist.destroy',$wishlist->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn text-danger btn-outline-light" type="submit">
                                                <img src="{{asset('back/assets/dasheets/img/plus-circle.svg')}}" class="p-0" alt="">
                                            </button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No Wishlist Items</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        <!-- Input Fields End -->
    </div>
@endsection
