{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Download Inventory-Stock-Count-PDF</title>
</head>

<body>
    <h3>Warehouse Name: {{$warehouse->users->name}}</h3>
    <h3>Total Products: {{count($warehouse->products)}}</h3>
    <table class="table" border="2">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product Name</th>
                <th scope="col">Product Price</th>
                <th scope="col">Product Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($warehouse->products as $product)
                <tr>
                    <th scope="row">{{$loop->iteration}}</th>
                    <th scope="row">{{$product->name}}</th>
                    <td>{{$product->sell_price}}</td>
                    <td>{{$product->quantity}}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
</body>

</html> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Download Inventory-Stock-Count-PDF</title>

    <style>
        html,
        body {
            margin: 10px;
            padding: 10px;
            font-family: sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        span,
        label {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px !important;
        }

        table thead th {
            height: 28px;
            text-align: left;
            font-size: 16px;
            font-family: sans-serif;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            font-size: 14px;
        }

        .heading {
            font-size: 24px;
            margin-top: 12px;
            margin-bottom: 12px;
            font-family: sans-serif;
        }

        .small-heading {
            font-size: 18px;
            font-family: sans-serif;
        }

        .total-heading {
            font-size: 18px;
            font-weight: 700;
            font-family: sans-serif;
        }

        .order-details tbody tr td:nth-child(1) {
            width: 20%;
        }

        .order-details tbody tr td:nth-child(3) {
            width: 20%;
        }

        .text-start {
            text-align: left;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .company-data span {
            margin-bottom: 4px;
            display: inline-block;
            font-family: sans-serif;
            font-size: 14px;
            font-weight: 400;
        }

        .no-border {
            border: 1px solid #fff !important;
        }

        .bg-blue {
            background-color: #414ab1;
            color: #fff;
        }
    </style>
</head>

<body>

    <h3>Warehouse Name: {{ $warehouse->users->name ?? ''}}</h3>
    {{-- <h3>Total Products: {{ count($warehouse->products) }}</h3> --}}
    <h3>Total Products: {{ count($warehouse->productWarehouses) }}</h3>

    <br><br>
    <table>
        <thead>
            <tr>
                <th class="no-border text-start heading" colspan="5">
                    Products
                </th>
            </tr>
            <tr class="bg-blue">
                <th>#</th>
                <th>Product</th>
                <th>Stock</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>

            @forelse($warehouse->productWarehouses as $product)
                <tr>
                    <th scope="row" class="text-start">{{ $loop->iteration }}</th>
                    <th class="text-start" scope="row">{{ $product->product->name }}</th>
                    <td class="text-start">{{ $product->quantity }}{{ $product->product->unit?->short_name ? $product->product->unit->short_name : '' }}</td>
                    <td class="text-start">{{ $product->product->sell_price ?? '0.00' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</body>

</html>
