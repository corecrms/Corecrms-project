<section class="mt-5 bg-light py-5">

    <div class="container-xxl container-fluid">
        <div>
            <div class="text-center">
                <p class="badge light-warningbg">WE LOVE THEM</p>
            </div>
            <h2 class="hr-text text-center mt-2 heading fs-2">
                <span class="bg-light px-2">
                    {{ $heading->shop_by_brands ?? 'Shop by Brands' }}
                </span>
            </h2>
        </div>
        <div class="framebox mt-5">
            <div class="owl-carousel px-2">
                @php
                    $brands = App\Models\Brand::all();
                @endphp
                @foreach ($brands as $brand)
                <a href="#" class="item rounded-3">
                    <img src="{{ asset('storage/brand/' . $brand->brand_img) }}" alt="" class="img-fluid"
                    style="`width: 200px;height: 125px;object-fit: contain; /* Ensures the image covers the entire area */"/>
                </a>
                @endforeach

            </div>
        </div>
    </div>
</section>
