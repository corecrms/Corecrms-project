<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Sale and Purchase - Register</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <!-- Icon Font Stylesheet -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />

    <!-- Customized Bootstrap Stylesheet -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <!-- Template Stylesheet -->
    <link href="{{asset('back/assets/dasheets/css/style.css')}}" rel="stylesheet" />
  </head>

  <body>
    <section class="">
      <div class="container-fluid">
        <div class="row mt-2">
          <div class="col-md-5 mt-2">
            <div class="card rounded-3 border-0 card-shadow h-100 p-3">
              <div class="card-body">
                <div class="form-group">
                  <div class="input-group mb-3">
                    <select
                      class="form-control form-select subheading"
                      aria-label="Default select example"
                      id="exampleFormControlSelect1"
                    >
                      <option>Customer 1</option>
                      <option>Customer 2</option>
                      <option>Customer 3</option>
                    </select>
                    <span class="input-group-text" id="basic-addon2"
                      ><i class="bi bi-person-fill"></i
                    ></span>
                  </div>
                </div>
                <div class="form-group mt-2">
                  <select
                    class="form-control form-select subheading"
                    aria-label="Default select example"
                    id="exampleFormControlSelect1"
                  >
                    <option>Warehouse 1</option>
                    <option>Warehouse 2</option>
                    <option>Warehouse 3</option>
                  </select>
                </div>

                <div class="table-responsive mt-4 border-top pt-4">
                  <table class="table text-center">
                    <thead class="fw-bold">
                      <tr>
                        <th class="align-middle">Product</th>
                        <th class="align-middle">Price</th>
                        <th class="align-middle">Qty</th>
                        <th class="align-middle">Subtotal</th>
                        <th class="align-middle">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="align-middle">Banana</td>
                        <td class="align-middle">$3.00</td>
                        <td class="align-middle">
                          <div
                            class="quantity d-flex justify-content-center align-items-center"
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
                        <td class="align-middle">$0.00</td>
                        <td class="align-middle">
                          <img
                            src="dasheets/img/plus-circle.svg"
                            class="btn p-0 m-0"
                            alt=""
                          />
                        </td>
                      </tr>
                      <tr>
                        <td class="align-middle">Banana</td>
                        <td class="align-middle">$3.00</td>
                        <td class="align-middle">
                          <div
                            class="quantity d-flex justify-content-center align-items-center"
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
                        <td class="align-middle">$0.00</td>
                        <td class="align-middle">
                          <img
                            src="dasheets/img/plus-circle.svg"
                            class="btn p-0 m-0"
                            alt=""
                          />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="text-center save-btn text-white p-2 mt-5">
                  <p class="m-0">Total Payable: $00</p>
                </div>

                <div class="row mt-4">
                  <div class="col-md-4">
                    <div class="form-group fw-bold">
                      <label for="exampleFormControlSelect1">Tax</label>
                      <div class="input-group mt-2">
                        <input
                          type="text"
                          class="form-control subheading"
                          placeholder="0"
                          aria-describedby="basic-addon2"
                        />
                        <span
                          class="input-group-text subheading"
                          id="basic-addon2"
                          ><i class="bi bi-percent"></i
                        ></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group fw-bold">
                      <label for="exampleFormControlSelect1">Discount</label>
                      <div class="input-group mt-2">
                        <input
                          type="text"
                          class="form-control subheading"
                          placeholder="0"
                          aria-describedby="basic-addon2"
                        />
                        <span
                          class="input-group-text subheading"
                          id="basic-addon2"
                          ><i class="bi bi-currency-dollar"></i
                        ></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group fw-bold">
                      <label for="exampleFormControlSelect1">Shipping</label>
                      <div class="input-group mt-2">
                        <input
                          type="text"
                          class="form-control subheading"
                          placeholder="0"
                          aria-describedby="basic-addon2"
                        />
                        <span
                          class="input-group-text subheading"
                          id="basic-addon2"
                          ><i class="bi bi-currency-dollar"></i
                        ></span>
                      </div>
                    </div>
                  </div>
                </div>

                <button class="btn delete-btn text-white mt-5 me-2">
                  <i class="bi bi-power me-2"></i>Reset
                </button>
                <button class="btn print-btn text-white mt-5 px-3">
                  <i class="fa-solid fa-cart-shopping me-2"></i>Pay Now
                </button>
              </div>
            </div>
          </div>

          <div class="col-md-7 mt-2">
            <div class="card border-0 rounded-3 card-shadow h-100">
              <div class="card-body product-scroll">
                <ul
                  class="nav nav-pills mb-3 row"
                  id="pills-tab"
                  role="tablist"
                >
                  <div class="col-md-4 mt-1">
                    <div class="position-relative" id="searchContainer">
                      <div class="input-search position-relative">
                        <input
                          type="text"
                          placeholder="Search Product by code"
                          class="search-box form-control rounded-3 subheading"
                          id="searchInput"
                          onclick="toggleDropdown()"
                          oninput="handleInput()"
                        />
                        <span
                          class="fa fa-search search-icon text-secondary"
                        ></span>
                      </div>
                      <div class="search-dropdown" id="searchDropdown">
                        <div
                          class="search-item d-flex align-items-center border-bottom"
                        >
                          <img
                            src="{{asset('back/assets/dasheets/img/pepsi.svg')}}"
                            class="img-fluid me-2"
                            alt=""
                          />
                          <p class="m-0 p-0">Result 1</p>
                        </div>
                        <div
                          class="search-item d-flex align-items-center border-bottom"
                        >
                          <img
                            src="dasheets/img/pepsi.svg"
                            class="img-fluid me-2"
                            alt=""
                          />
                          <p class="m-0 p-0">Result 2</p>
                        </div>
                        <div
                          class="search-item d-flex align-items-center border-bottom"
                        >
                          <img
                            src="dasheets/img/pepsi.svg"
                            class="img-fluid me-2"
                            alt=""
                          />
                          <p class="m-0 p-0">Result 3</p>
                        </div>
                        <div
                          class="search-item d-flex align-items-center border-bottom"
                        >
                          <img
                            src="dasheets/img/pepsi.svg"
                            class="img-fluid me-2"
                            alt=""
                          />
                          <p class="m-0 p-0">Result 4</p>
                        </div>
                        <div
                          class="search-item d-flex align-items-center border-bottom"
                        >
                          <img
                            src="dasheets/img/pepsi.svg"
                            class="img-fluid me-2"
                            alt=""
                          />
                          <p class="m-0 p-0">Result 5</p>
                        </div>
                        <div
                          class="search-item d-flex align-items-center border-bottom"
                        >
                          <img
                            src="dasheets/img/pepsi.svg"
                            class="img-fluid me-2"
                            alt=""
                          />
                          <p class="m-0 p-0">Result 6</p>
                        </div>
                        <div
                          class="search-item d-flex align-items-center border-bottom"
                        >
                          <img
                            src="dasheets/img/pepsi.svg"
                            class="img-fluid me-2"
                            alt=""
                          />
                          <p class="m-0 p-0">Result 7</p>
                        </div>
                        <div
                          class="search-item d-flex align-items-center border-bottom"
                        >
                          <img
                            src="dasheets/img/pepsi.svg"
                            class="img-fluid me-2"
                            alt=""
                          />
                          <p class="m-0 p-0">Result 8</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <li class="nav-item col-md-4 mt-1" role="presentation">
                    <button
                      class="shopping-items nav-link active me-2 w-100"
                      id="pills-home-tab"
                      data-bs-toggle="pill"
                      data-bs-target="#pills-home"
                      type="button"
                      role="tab"
                      aria-controls="pills-home"
                      aria-selected="true"
                    >
                      <i class="fa-solid fa-layer-group"></i>
                      List of Category
                    </button>
                  </li>
                  <li class="nav-item col-md-4 mt-1" role="presentation">
                    <button
                      class="nav-link shopping-items w-100"
                      id="pills-profile-tab"
                      data-bs-toggle="pill"
                      data-bs-target="#pills-profile"
                      type="button"
                      role="tab"
                      aria-controls="pills-profile"
                      aria-selected="false"
                    >
                      <i class="fa-solid fa-building-columns"></i>
                      Brands
                    </button>
                  </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                  <div
                    class="tab-pane fade show active"
                    id="pills-home"
                    role="tabpanel"
                    aria-labelledby="pills-home-tab"
                    tabindex="0"
                  >
                    <div class="row" id="rowToHide1">
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button
                            class="btn text-decoration-none"
                            onclick="toggleRows('rowToHide1', 'rowToShow1')"
                          >
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button
                            class="btn text-decoration-none"
                            onclick="toggleRows('rowToHide1', 'rowToShow1')"
                          >
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button
                            class="btn text-decoration-none"
                            onclick="toggleRows('rowToHide1', 'rowToShow1')"
                          >
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button
                            class="btn text-decoration-none"
                            onclick="toggleRows('rowToHide1', 'rowToShow1')"
                          >
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="row" id="rowToShow1" style="display: none">
                      <div class="col-md-3 col-6 mt-2">
                        <!-- Your card content -->
                        <div class="card">
                          <button class="btn text-decoration-none">
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-dark">$19.99</p>
                              <a href="#" class="text-dark"
                                ><i class="fa-solid fa-cart-shopping"></i
                              ></a>
                            </div>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <!-- Your card content -->
                        <div class="card">
                          <button class="btn text-decoration-none">
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-dark">$19.99</p>
                              <a href="#" class="text-dark"
                                ><i class="fa-solid fa-cart-shopping"></i
                              ></a>
                            </div>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <!-- Your card content -->
                        <div class="card">
                          <button class="btn text-decoration-none">
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-dark">$19.99</p>
                              <a href="#" class="text-dark"
                                ><i class="fa-solid fa-cart-shopping"></i
                              ></a>
                            </div>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <!-- Your card content -->
                        <div class="card">
                          <button class="btn text-decoration-none">
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-dark">$19.99</p>
                              <a href="#" class="text-dark"
                                ><i class="fa-solid fa-cart-shopping"></i
                              ></a>
                            </div>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div
                    class="tab-pane fade"
                    id="pills-profile"
                    role="tabpanel"
                    aria-labelledby="pills-profile-tab"
                    tabindex="0"
                  >
                    <div class="row" id="rowToHide2">
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button
                            class="btn text-decoration-none"
                            onclick="toggleRows('rowToHide2', 'rowToShow2')"
                          >
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button
                            class="btn text-decoration-none"
                            onclick="toggleRows('rowToHide2', 'rowToShow2')"
                          >
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button
                            class="btn text-decoration-none"
                            onclick="toggleRows('rowToHide2', 'rowToShow2')"
                          >
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="row" id="rowToShow2" style="display: none">
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button class="btn text-decoration-none">
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-dark">$19.99</p>
                              <a href="#" class="text-dark"
                                ><i class="fa-solid fa-cart-shopping"></i
                              ></a>
                            </div>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button class="btn text-decoration-none">
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-dark">$19.99</p>
                              <a href="#" class="text-dark"
                                ><i class="fa-solid fa-cart-shopping"></i
                              ></a>
                            </div>
                          </button>
                        </div>
                      </div>
                      <div class="col-md-3 col-6 mt-2">
                        <div class="card">
                          <button class="btn text-decoration-none">
                            <img
                              src="dasheets/img/pepsi.svg"
                              alt=""
                              class="img-fluid w-100"
                            />
                            <h4 class="text-center pt-2 heading text-dark">
                              Product
                            </h4>
                            <div class="d-flex justify-content-between">
                              <p class="text-dark">$19.99</p>
                              <a href="#" class="text-dark"
                                ><i class="fa-solid fa-cart-shopping"></i
                              ></a>
                            </div>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Template Javascript -->
    <script src="{{asset('back/assets/dasheets/js/main.js')}}"></script>
    <script>
      var searchContainer = document.getElementById("searchContainer");
      var searchInput = document.getElementById("searchInput");
      var searchDropdown = document.getElementById("searchDropdown");

      // Function to toggle the dropdown visibility
      function toggleDropdown() {
        if (
          searchDropdown.style.display === "none" ||
          searchDropdown.style.display === ""
        ) {
          setSearchDropdownWidth();
          searchDropdown.style.display = "block";
        } else {
          searchDropdown.style.display = "none";
        }
      }

      // Set dropdown width equal to search box width
      function setSearchDropdownWidth() {
        searchDropdown.style.width = searchInput.offsetWidth + "px";
      }

      // Event listener to close dropdown when clicking outside the search container
      document.addEventListener("click", function (event) {
        var isClickInside = searchContainer.contains(event.target);
        if (!isClickInside) {
          searchDropdown.style.display = "none";
        }
      });

      function toggleRows(rowToHideId, rowToShowId) {
        var rowToHide = document.getElementById(rowToHideId);
        var rowToShow = document.getElementById(rowToShowId);

        if (rowToHide.style.display !== "none") {
          rowToHide.style.display = "none";
          rowToShow.style.display = "flex"; // or "block" depending on the layout you want
        } else {
          rowToHide.style.display = "flex"; // or "block" depending on the layout you want
          rowToShow.style.display = "none";
        }
      }
    </script>
    <!-- <script>
      var searchContainer = document.getElementById("searchContainer");
      var searchInput = document.getElementById("searchInput");
      var searchDropdown = document.getElementById("searchDropdown");

      // Function to toggle the dropdown visibility
      function toggleDropdown() {
        if (
          searchDropdown.style.display === "none" ||
          searchDropdown.style.display === ""
        ) {
          setSearchDropdownWidth();
          searchDropdown.style.display = "block";
        } else {
          searchDropdown.style.display = "none";
        }
      }

      // Set dropdown width equal to search box width
      function setSearchDropdownWidth() {
        searchDropdown.style.width = searchInput.offsetWidth + "px";
      }

      // Event listener to close dropdown when clicking outside the search container
      document.addEventListener("click", function (event) {
        var isClickInside = searchContainer.contains(event.target);
        if (!isClickInside) {
          searchDropdown.style.display = "none";
        }
      });
    </script> -->

    <!-- <script>
      function toggleRows(rowToHideId, rowToShowId) {
        var rowToHide = document.getElementById(rowToHideId);
        var rowToShow = document.getElementById(rowToShowId);

        if (rowToHide.style.display !== "none") {
          rowToHide.style.display = "none";
          rowToShow.style.display = "block"; // or "block" depending on the layout you want
        } else {
          rowToHide.style.display = "flex"; // or "block" depending on the layout you want
          rowToShow.style.display = "none";
        }
      }
    </script> -->
  </body>
</html>
