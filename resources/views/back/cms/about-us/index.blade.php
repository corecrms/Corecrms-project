@extends('back.layout.app')
@section('content')
    <!--start page wrapper -->
@section('title', 'Add Customer')
<div class="content">
    <div class="container-fluid px-4 mt-3">
        <div class="border-bottom">
            <h3 class="all-adjustment text-center pb-2 mb-0">About Us</h3>
        </div>

        <div class="shadow p-4 mt-3" style="margin-bottom: 190px">
            <div class="container-fluid create-product-form rounded">
                @include('back.layout.errors')

                <!--end breadcrumb-->
                <form action="{{ route('about-us.store') }}" method="POST" enctype="multipart/form-data">
                    <span class="text-secondary fw-bold">Section 1</span>
                    @csrf
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Section Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    placeholder="Section Heading" required name="section_1_title" value="{{$section->section_1_title ?? ''}}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Section Description <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    placeholder="Section Description" required name="section_1_desc" value="{{$section->section_1_desc ?? ''}}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Section Image <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control subheading" id="exampleFormControlInput1"
                                    name="section_1_image" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            @if ($section->section_1_image ?? false)
                                <img src="{{ asset('storage/' . $section->section_1_image) }}" style="width: 100px; height: 80px;">
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <span class="text-secondary fw-bold">Section 2</span>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Section Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    placeholder="Section Heading" required name="section_2_title" value="{{$section->section_2_title ?? ''}}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Section Description <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control subheading" id="exampleFormControlInput1"
                                    placeholder="Section Description" required name="section_2_desc" value="{{$section->section_2_desc ?? ''}}"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlSelect1" class="mb-1">Section Image <span
                                        class="text-danger">*</span></label>
                                <input type="file" class="form-control subheading" id="exampleFormControlInput1"
                                    name="section_2_image" />
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            @if ($section->section_2_image ?? false)
                                <img src="{{ asset('storage/' . $section->section_2_image) }}" style="width: 100px; height: 100px;">
                            @endif
                        </div>
                    </div>
                    {{-- <div class="container mt-4">
                        <div class="row mt-3">
                            <span class="text-secondary fw-bold">Our Services
                                <button type="button" id="add-review"
                                    class="btn bg-white shadow-sm rounded px-2 py-1 border">+</button>
                            </span>
                        </div>
                        <div id="services-container">
                            <!-- Hidden template row -->
                            <div class="row mt-3 service-row d-none" id="service-row-template">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Icon</label>
                                        <input type="file" class="form-control subheading" name="image[]" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Description</label>
                                        <input type="text" class="form-control subheading"
                                            placeholder="Section Description" name="description[]" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleFormControlSelect1" class="mb-1">Link</label>
                                        <input type="text" class="form-control subheading" name="link[]" />
                                    </div>
                                </div>
                                <div class="col-md-12 text-end mt-2">
                                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="container">
                        <div class="row mt-3">
                            <span class="text-secondary fw-bold">Our Services
                                <button type="button" id="add-service"
                                    class="btn bg-white shadow-sm rounded px-2 py-1 border">+</button>
                            </span>
                        </div>
                        @php
                            $extData = !is_null($section) ? json_decode($section->our_services, true) : [];
                            $extraContent =
                                !is_null($section) && !empty($section->our_services)
                                    ? json_decode($section->our_services, true)
                                    : [];
                        @endphp
                        <div id="services-form">

                            @if (isset($extraContent) && count($extraContent) > 0)
                                @foreach ($extraContent as $index => $service)
                                    <div class="row mt-3 service-item">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="icon_{{ $index }}" class="mb-1">Icon
                                                    <img src="{{asset('storage/'.$service['icon'])}}" alt="" width="40">
                                                </label>
                                                <input type="file" class="form-control subheading"
                                                    id="icon_{{ $index }}"
                                                    name="extra_content[{{ $index }}][icon]" />
                                                <input type="hidden" name="extra_content[{{ $index }}][existing_icon]" value="{{ $service['icon'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="description_{{ $index }}"
                                                    class="mb-1">Description</label>
                                                <input type="text" class="form-control subheading"
                                                    id="description_{{ $index }}"
                                                    placeholder="Section Description" required
                                                    name="extra_content[{{ $index }}][description]"
                                                    value="{{ $service['description'] }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="link_{{ $index }}" class="mb-1">Link</label>
                                                <input type="text" class="form-control subheading"
                                                    id="link_{{ $index }}"
                                                    name="extra_content[{{ $index }}][link]"
                                                    value="{{ $service['link'] }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-end mt-2">
                                            <button type="button"
                                                class="btn btn-danger remove-service">Remove</button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row mt-3 service-item">
                                    <div class="col-md-4">
                                        <div class="form-group
                                        ">
                                            <label for="icon_0" class="mb-1">Icon</label>
                                            <input type="file" class="form-control subheading" id="icon_0"
                                                name="extra_content[0][icon]" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group
                                    ">
                                            <label for="description_0" class="mb-1">Description</label>
                                            <input type="text" class="form-control subheading" id="description_0"
                                                placeholder="Section Description" required
                                                name="extra_content[0][description]" />
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group
                                ">
                                            <label for="link_0" class="mb-1">Link</label>
                                            <input type="text" class="form-control subheading" id="link_0"
                                                name="extra_content[0][link]" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-end mt-2">
                                        <button type="button" class="btn btn-danger remove-service">Remove</button>
                                    </div>
                                </div>

                            @endif
                        </div>
                        <button class="btn save-btn text-white mt-4" type="submit">Submit</button>
                </form>

            </div>
        </div>
    </div>
</div>
<!--end page wrapper -->
@endsection

@section('scripts')
<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     document.getElementById('add-review').addEventListener('click', function() {
    //         // Clone the hidden row template
    //         let container = document.getElementById('services-container');
    //         let template = document.getElementById('service-row-template');
    //         let newRow = template.cloneNode(true);

    //         // Remove the 'd-none' class to make it visible
    //         newRow.classList.remove('d-none');
    //         newRow.id = ''; // Clear the ID attribute

    //         // Clear input values in the cloned row
    //         let inputs = newRow.querySelectorAll('input');
    //         inputs.forEach(input => {
    //             if (input.type === 'file') {
    //                 input.value = '';
    //             } else {
    //                 input.value = '';
    //             }
    //         });

    //         // Append the cloned row to the container
    //         container.appendChild(newRow);

    //         // Add event listener to the remove button in the cloned row
    //         newRow.querySelector('.remove-row').addEventListener('click', function() {
    //             newRow.remove();
    //         });
    //     });

    //     // Add event listener to the remove button in the initial row template
    //     document.querySelectorAll('.remove-row').forEach(function(button) {
    //         button.addEventListener('click', function() {
    //             button.closest('.service-row').remove();
    //         });
    //     });
    // });

    $(document).ready(function() {
        let serviceIndex = @json(count($extraContent)); // Start from the count of existing services

        $('#add-service').click(function() {
            let newService = `
            <div class="row mt-3 service-item">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="icon_${serviceIndex}" class="mb-1">Icon</label>
                        <input type="file" class="form-control subheading" id="icon_${serviceIndex}" name="extra_content[${serviceIndex}][icon]" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="description_${serviceIndex}" class="mb-1">Description</label>
                        <input type="text" class="form-control subheading" id="description_${serviceIndex}" placeholder="Section Description" required name="extra_content[${serviceIndex}][description]" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="link_${serviceIndex}" class="mb-1">Link</label>
                        <input type="text" class="form-control subheading" id="link_${serviceIndex}" name="extra_content[${serviceIndex}][link]" />
                    </div>
                </div>
                <div class="col-md-12 text-end mt-2">
                    <button type="button" class="btn btn-danger remove-service">Remove</button>
                </div>
            </div>
        `;
            $('#services-form').append(newService);
            serviceIndex++;
        });

        $(document).on('click', '.remove-service', function() {
            $(this).closest('.service-item').remove();
        });
    });
</script>
@endsection
