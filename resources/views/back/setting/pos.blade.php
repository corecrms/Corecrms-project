@extends('back.layout.app')

@section('style')
    <style>

    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid pt-4 px-4 mb-5">
            <div class="border-bottom">
                <h3 class="all-adjustment text-center pb-2 mb-0">Pos Setting</h3>
            </div>
            @include('back.layout.errors')
            <form action="javascript:void(0);" method="post" enctype="multipart/form-data">
                @csrf
                <!-- Input Fields -->
                <div class="card card-shadow rounded-3 border-0 mt-4 p-2 pt-0 px-0">
                    <div class="card-header p-2 border-0">
                        <h4 class="mb-0 mt-2 heading text-start card-title">
                            Pos Settings (Receipt)
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1" class="mb-1">Note to customer <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control subheading" id="exampleFormControlInput1" name="pos_note"
                                value="@if(isset($setting)) {{$setting->pos_note ?? ''}} @endif" />
                        </div>
                        <div class="row mt-3">

                            <div class="col-md-4 col-auto mt-2">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_phone" id="show_phone" {{isset($setting) && $setting->show_phone == 1 ? 'checked': ''}}  />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Phone</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_address" id="show_address" {{isset($setting) && $setting->show_address == 1 ? 'checked': ''}}  />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Address</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_email" id="show_email" {{isset($setting) && $setting->show_email == 1 ? 'checked': ''}}  />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Email</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2 mt-lg-4">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_customer" id="show_customer" {{isset($setting) && $setting->show_customer == 1 ? 'checked': ''}}  />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Customer</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2 mt-lg-4">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_warehouse" id="show_warehouse" {{isset($setting) && $setting->show_warehouse == 1 ? 'checked': ''}}  />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Warehouse</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2 mt-lg-4">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_tax_discount" id="show_tax_discount" {{isset($setting) && $setting->show_tax_discount == 1 ? 'checked': ''}}  />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Tax & Discount & Shipping</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2 mt-lg-4">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_barcode" id="show_barcode" {{isset($setting) && $setting->show_barcode == 1 ? 'checked': ''}}  />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Barcode</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2 mt-lg-4">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_note_to_customer" id="show_note_to_customer" {{isset($setting) && $setting->show_note_to_customer == 1 ? 'checked': ''}}  />
                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Note to customer</p>
                                </div>
                            </div>
                            <div class="col-md-4 col-auto mt-2 mt-lg-4">
                                <div class="d-flex align-items-center">
                                    <label class="switch mt-2">
                                        <input type="checkbox" name="show_invoice" id="show_invoice" {{isset($setting) && $setting->show_invoice == 1 ? 'checked': ''}}  />

                                        <span class="slider"></span>
                                    </label>
                                    <p class="m-0">Show Invoice automatically</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <button class="btn save-btn text-white mt-3">Submit</button>
                    </div>
                </div>
                <!-- Input Fields End -->

            </form>

        </div>
    </div>
@endsection


@section('scripts')

<script>
    $(document).ready(function() {
        $('form').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = $(this).serialize(); // Serialize the form data
            var show_phone = $('#show_phone').prop('checked') ? 1 : 0;
            formData += `&show_phone=${show_phone}`;

            var show_address = $('#show_address').prop('checked') ? 1 : 0;
            formData += `&show_address=${show_address}`;

            var show_email = $('#show_email').prop('checked') ? 1 : 0;
            formData += `&show_email=${show_email}`;

            var show_customer = $('#show_customer').prop('checked') ? 1 : 0;
            formData += `&show_customer=${show_customer}`;

            var show_warehouse = $('#show_warehouse').prop('checked') ? 1 : 0;
            formData += `&show_warehouse=${show_customer}`;

            var show_tax_discount = $('#show_tax_discount').prop('checked') ? 1 : 0;
            formData += `&show_tax_discount=${show_tax_discount}`;

            var show_tax_discount = $('#show_tax_discount').prop('checked') ? 1 : 0;
            formData += `&show_tax_discount=${show_tax_discount}`;

            var show_barcode = $('#show_barcode').prop('checked') ? 1 : 0;
            formData += `&show_barcode=${show_barcode}`;

            var show_note_to_customer = $('#show_note_to_customer').prop('checked') ? 1 : 0;
            formData += `&show_note_to_customer=${show_note_to_customer}`;

            var show_invoice = $('#show_invoice').prop('checked') ? 1 : 0;
            formData += `&show_invoice=${show_invoice}`;

            $.ajax({
                url: "{{ route('setting.store') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    // Handle the successful response here
                    console.log(response);
                    toastr.success("Setting updated successfully!")
                    location.reload();
                    // Optionally, you can redirect the user to another page
                    // window.location.href = "/success";
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection
