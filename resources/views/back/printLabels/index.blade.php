@extends('back.layout.app')
@section('title', 'Categories')
@section('style')
    <link href="{{ asset('back/assets/js/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
@endsection

@section('content')

    <div class="content">

        <div class="container-fluid py-5 px-4">
            <div class="border-bottom">
              <h3 class="all-adjustment text-center pb-2 mb-0">Print Label</h3>
            </div>
            <div class="row mt-5">
              <div class="col-md-8">
                <div class="card rounded-3 border-0 card-shadow">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="exampleFormControlSelect1" class="fw-bold"
                            >Warehouse <span class="text-danger">*</span></label
                          >
                          <select
                            class="form-control form-select subheading mt-1"
                            aria-label="Default select example"
                            id="exampleFormControlSelect1"
                          >
                            <option>Select Warehouse</option>
                            <option>Warehouse 1</option>
                            <option>Warehouse 2</option>
                            <option>Warehouse 3</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="exampleFormControlSelect1" class="fw-bold"
                          >Search Product</label
                        >
                        <div class="input-search position-relative mt-1">
                          <input
                            type="text"
                            placeholder="Product Code / Name"
                            class="form-control rounded-3 subheading"
                          />
                          <span
                            class="fa fa-search search-icon text-secondary"
                          ></span>
                        </div>
                      </div>
                    </div>
                    <!-- Table -->
                    <div class="table-responsive rounded-3 mt-3">
                      <table class="table text-start">
                        <thead class="fw-bold">
                          <tr>
                            <th class=" align-middle">
                              Product Name
                            </th>
                            <th class=" align-middle">
                              Product Code
                            </th>
                            <th class=" align-middle">Quantity</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="align-middle">Banana</td>
                            <td class="align-middle">7878678</td>
                            <td class="align-middle">
                              <div
                                class="quantity d-flex justify-content-start align-items-center"
                              >
                                <button class="btn qty-minus-btn" id="minusBtn">
                                  <i class="fa-solid fa-minus"></i>
                                </button>
                                <input
                                  type="number"
                                  id="quantityInput"
                                  class="border-0 qty-input"
                                  value="1"
                                />
                                <button class="btn qty-plus-btn" id="plusBtn">
                                  <i class="fa-solid fa-plus"></i>
                                </button>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <!-- Table End -->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="exampleFormControlSelect1" class="fw-bold"
                            >Paper Size</label
                          >
                          <select
                            class="form-control form-select subheading mt-1"
                            aria-label="Default select example"
                            id="exampleFormControlSelect1"
                          >
                            <option>40 Per sheet (4” X 6”)</option>
                            <option>40 Per sheet (4” X 5”)</option>
                            <option>40 Per sheet (4” X 8”)</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <button class="btn delete-btn text-white mt-3">Reset</button>
                <button class="btn print-btn text-white mt-3 px-3">Print</button>
              </div>

              <div class="col-md-4">
                <div class="card border-0 rounded-3 p-3 card-shadow">
                  <p class="m-0 fw-bold">Paper View</p>
                </div>

                <div class="card rounded-3 border-0 mt-3 card-shadow">
                  <div class="card-body p-4">
                    <div class="row">
                      <div class="col-md-3 text-center pineapple-barcode">
                          <p class="m-0 subheading text-start"> Pineapple</p>
                          <p class="m-0 subheading text-start">10$</p>
                          <i class="fa-solid fa-barcode fs-1 text-center"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 text-center pineapple-barcode">
                          <p class="m-0 subheading text-start"> Pineapple</p>
                          <p class="m-0 subheading text-start">10$</p>
                          <i class="fa-solid fa-barcode fs-1 text-center"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 text-center pineapple-barcode">
                          <p class="m-0 subheading text-start"> Pineapple</p>
                          <p class="m-0 subheading text-start">10$</p>
                          <i class="fa-solid fa-barcode fs-1 text-center"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 text-center pineapple-barcode">
                          <p class="m-0 subheading text-start"> Pineapple</p>
                          <p class="m-0 subheading text-start">10$</p>
                          <i class="fa-solid fa-barcode fs-1 text-center"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 text-center pineapple-barcode">
                          <p class="m-0 subheading text-start"> Pineapple</p>
                          <p class="m-0 subheading text-start">10$</p>
                          <i class="fa-solid fa-barcode fs-1 text-center"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-3 text-center pineapple-barcode">
                          <p class="m-0 subheading text-start"> Pineapple</p>
                          <p class="m-0 subheading text-start">10$</p>
                          <i class="fa-solid fa-barcode fs-1 text-center"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                      <div class="col-md-3 pineapple-barcode">
                          <p class="m-0 subheading"> Pineapple</p>
                          <p class="m-0 subheading">10$</p>
                          <i class="fa-solid fa-barcode fs-1"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

    </div>
@endsection


