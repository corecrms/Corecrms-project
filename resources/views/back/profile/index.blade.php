@extends('back.layout.app')

@section('style')
    <style>
        /* Style the tab */
        /* Style the buttons inside the tab */
        .tab button {
            /* display: block; */
            background-color: inherit;
            padding: 16px;
            width: 100%;
            border: none;
            /* outline: none; */
            text-align: left;
            cursor: pointer;
            /* transition: 0.3s; */
        }

        .tab button:hover {
            background: rgba(76, 73, 227, 0.1);
            border-left: 4px solid rgba(76, 73, 227, 1);
        }

        .tab button.active {
            background-color: rgba(76, 73, 227, 0.1);
            border-left: 4px solid rgba(76, 73, 227, 1);
        }

        .custom-file-upload {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Hide the actual file input */
        .hidden-input {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div class="content">
        <div class="container-fluid px-4">
            <div class="row mb-5">
                <div class="border-bottom my-4">
                    <h3 class="all-adjustment text-center pb-2 mb-0">Profile</h3>
                </div>

                @include('back.layout.errors')

                <div class="col-md-3 mb-2">
                    <div class="card border-0 rounded-3 card-shadow h-100">
                        <div class="card-body h-100">
                            <div class="tab">
                                <button class="tablinks d-flex justify-content-between" id="defaultOpen"
                                    onclick="openCity(event, 'Personal-Info')">
                                    Profile Info
                                    <img src="{{ asset('back/assets/dasheets/img/profile-info.svg') }}" alt="" />
                                </button>
                                <button class="tablinks d-flex justify-content-between mt-2"
                                    onclick="openCity(event, 'Change-password')">
                                    Change Password
                                    <img src="{{ asset('back/assets/dasheets/img/change-password.svg') }}" alt="" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div id="Personal-Info" class="tabcontent">
                        <form action="{{ route('profile.update', auth()->user()->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card rounded-3 border-0 card-shadow h-100">
                                <div class="card-body">
                                    <div class="row personal-info-row">
                                        <div class="col-md-3 col-xxl-2 d-flex align-items-center">
                                            <img src="{{ isset(auth()->user()->image) ? asset('/storage' . auth()->user()->image) : asset('back/assets/dasheets/img/profile-img.png') }}"
                                                class="img-fluid rounded-circle w-100 change-picture-btn profile-img"
                                                style="max-height: 180px; cursor: pointer" alt="" />
                                        </div>
                                        <div class="col-md-5 py-4">
                                            <h4 class="mb-3 all-adjustment w-100  border-0">
                                                {{ auth()->user()->name ?? 'Mona Lissa' }}</h4>
                                            <p class="mb-1">{{ auth()->user()->username ?? '@monaLissa45' }}</p>
                                            <p class="mb-1">{{ auth()->user()->contact_no ?? '+1 234 345 3456' }}</p>
                                            <p class="mb-0">{{ auth()->user()->email ?? 'monalissa45@gmail.com' }}</p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <!-- <button class="btn create-btn">
                                                        Change Profile Picture
                                                      </button>
                                                      <p class="text-danger mt-2" style="cursor: pointer">
                                                        Remove Profile Picture
                                                      </p> -->
                                            {{-- <input type="file" class="fileInput" style="display: none" /> --}}
                                            {{-- <button class="change-picture-btn btn create-btn" type="button">
                                                Change Profile Picture
                                                <input type="file" class="hidden-input" id="fileUpload" name="img">
                                            </button> --}}
                                            <label class="change-picture-btn btn create-btn">
                                                Change Profile Picture
                                                <input type="file" class="hidden-input" id="fileUpload" name="img"
                                                    accept="image/*">
                                            </label>
                                            <p class="text-primary fileText" style="margin-right: 25px"></p>
                                            <p class="remove-picture text-danger mt-1" style="cursor: pointer">
                                                <a href="{{ route('profileImage-delete', auth()->user()->id) }}"
                                                    class="text-decoration-none text-danger" type="submit">
                                                    Remove Profile Picture
                                                </a>
                                            </p>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card rounded-3 border-0 card-shadow h-100 p-3 mt-4">
                                <div class="card-body h-100">
                                    <h4 class="all-adjustment border-0 m-0">Personal Info</h4>
                                    <p>Provide your personal info</p>
                                    <div class="row">
                                        @php
                                            $fullName = auth()->user()->name;

                                            // Split the full name into an array of first and last names
                                            $nameArray = explode(' ', $fullName);

                                            // Get the first name
                                            $firstName = $nameArray[0];

                                            // Get the last name
                                            $lastName = isset($nameArray[1]) ? $nameArray[1] : '';
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">First Name
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    placeholder="First Name" id="exampleFormControlInput1" name="first_name"
                                                    value="{{ $firstName ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Last Name
                                                    <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    placeholder="Last Name" id="exampleFormControlInput1" name="last_name"
                                                    value="{{ $lastName ?? '' }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Username <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    placeholder="Username" id="exampleFormControlInput1" name="username"
                                                    value="{{ auth()->user()->username ?? '' }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card rounded-3 border-0 card-shadow h-100 p-3 mt-4">
                                <div class="card-body h-100">
                                    <h4 class="all-adjustment border-0 m-0">Contact Info</h4>
                                    <p>Provide your Contact info</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Email <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    placeholder="MonaLissa@mail.com" id="exampleFormControlInput1"
                                                    name="email" value="{{ auth()->user()->email ?? '' }}" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Phone No <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control subheading mt-2"
                                                    placeholder="+1 234 345 3456" id="exampleFormControlInput1"
                                                    name="contact_no" value="{{ auth()->user()->contact_no ?? '' }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn save-btn text-white mt-3">Save</button>
                        </form>
                    </div>

                    <div id="Change-password" class="tabcontent">
                        <form action="{{ route('profile.password.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card rounded-3 border-0 card-shadow h-100">
                                <div class="card-body">
                                    <div class="row personal-info-row">
                                        <div class="col-md-3 col-xxl-2 d-flex align-items-center">
                                            <img src="{{ isset(auth()->user()->image) ? asset('/storage' . auth()->user()->image) : asset('back/assets/dasheets/img/profile-img.png') }}"
                                                class="img-fluid rounded-circle w-100 change-picture-btn profile-img"
                                                style="max-height: 180px; cursor: pointer" alt="" />
                                        </div>
                                        <div class="col-md-5 py-4">
                                            <h4 class="mb-3 all-adjustment w-100  border-0">
                                                {{ auth()->user()->name ?? 'Mona Lissa' }}</h4>
                                            <p class="mb-1">{{ auth()->user()->username ?? '@monaLissa45' }}</p>
                                            <p class="mb-1">{{ auth()->user()->contact_no ?? '+1 234 345 3456' }}</p>
                                            <p class="mb-0">{{ auth()->user()->email ?? 'monalissa45@gmail.com' }}</p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <!-- <button class="btn create-btn">
                                                        Change Profile Picture
                                                    </button>
                                                    <p class="text-danger mt-2" style="cursor: pointer">
                                                        Remove Profile Picture
                                                    </p> -->
                                            <label class="change-picture-btn btn create-btn">
                                                Change Profile Picture
                                                <input type="file" class="hidden-input" id="fileUpload"
                                                    name="img" accept="image/*">
                                            </label>
                                            <p class="text-primary fileText" style="margin-right: 25px"></p>
                                            <p class="remove-picture text-danger mt-1" style="cursor: pointer">
                                                <a href="{{ route('profileImage-delete', auth()->user()->id) }}"
                                                    class="text-decoration-none text-danger" type="submit">
                                                    Remove Profile Picture
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card rounded-3 border-0 card-shadow h-100 p-3 mt-4">
                                <div class="card-body h-100">
                                    <h4 class="all-adjustment border-0 m-0 w-100">
                                        Change Password
                                    </h4>
                                    <p>Update your password for enhanced security</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Current Password</label>
                                                <div class="password-container">
                                                    <input type="password" class="form-control subheading"
                                                        placeholder="********" name="current_password" />
                                                    <img src="{{ asset('back/assets/dasheets/img/profile-changed-password.svg') }}"
                                                        class="password-toggle pe-2"
                                                        onclick="togglePasswordVisibility(this)" alt="" />
                                                </div>
                                                @error('current_password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">New Password</label>
                                                <div class="password-container">
                                                    <input type="password" class="form-control subheading"
                                                        placeholder="********" name="password" />
                                                    <img src="{{ asset('back/assets/dasheets/img/profile-changed-password.svg') }}"
                                                        class="password-toggle pe-2"
                                                        onclick="togglePasswordVisibility(this)" alt="" />
                                                </div>
                                                @error('password')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group fw-bold">
                                                <label for="exampleFormControlSelect1">Retype New Password</label>
                                                <div class="password-container">
                                                    <input type="password" class="form-control subheading"
                                                        placeholder="********" name="password_confirmation" />
                                                    <img src="{{ asset('back/assets/dasheets/img/profile-changed-password.svg') }}"
                                                        class="password-toggle pe-2"
                                                        onclick="togglePasswordVisibility(this)" alt="" />
                                                </div>
                                                @error('password_confirmation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn save-btn text-white mt-3" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById("fileUpload").addEventListener("change", function() {
            // Get the file name
            var fileName = this.value.split("\\").pop();

            // Display the selected file name (optional)
            if (fileName) {
                // Example: display file name in console
                console.log("Selected file: " + fileName);
            } else {
                // Example: display a message if no file is selected
                console.log("No file selected");
            }
        });
    </script>
    <script>
        function openCity(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>

    <script>
        function togglePasswordVisibility(toggleBtn) {
            var passwordInput = toggleBtn.previousElementSibling;
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>
    <script>
        // JavaScript to handle image upload and remove picture for multiple profiles
        var changePictureButtons = document.querySelectorAll(
            ".change-picture-btn"
        );
        var removePictureButtons = document.querySelectorAll(".remove-picture");
        var fileInputs = document.querySelectorAll(".fileInput");
        var profileImages = document.querySelectorAll(".profile-img");

        // Event listener for change picture buttons
        // changePictureButtons.forEach(function(button, index) {
        //     button.addEventListener("click", function() {
        //         fileInputs[index].click();
        //     });
        // });

        // Event listener for file inputs
        // fileInputs.forEach(function(fileInput, index) {
        //     fileInput.addEventListener("change", function() {
        //         var file = this.files[0];
        //         if (file) {
        //             var reader = new FileReader();
        //             reader.onload = function(e) {
        //                 profileImages[index].src = e.target.result;
        //             };
        //             reader.readAsDataURL(file);
        //         }
        //     });
        // });

        // Event listener for remove picture buttons
        // removePictureButtons.forEach(function(removeButton, index) {
        //     removeButton.addEventListener("click", function() {
        //         profileImages[index].src =
        //         "{{ asset('back/assets/dasheets/img/profile-img.png') }}"; // Replace with default image source
        //     });
        // });
    </script>
    <script>
        document.getElementById("fileUpload").addEventListener("change", function() {
            // Get the file name
            var fileName = this.value.split("\\").pop();

            // Display the selected file name (optional)
            if (fileName) {


                const fileInput = event.target;
                const file = fileInput.files[0];

                if (file) {
                    const fileType = file.type;
                    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

                    if (!validImageTypes.includes(fileType)) {
                        alert('Please select a valid image file (JPEG, PNG, GIF).');
                        fileInput.value = ''; // Clear the input
                    }
                    else{
                        // Example: display file name in console
                        console.log("Selected file: " + fileName);
                        // document.getElementsByClassName('fileText').innerHTML = fileName;

                        // Select all elements with the class 'fileText'
                        var fileTextElements = document.querySelectorAll('.fileText');

                        // Iterate over each element and change its innerHTML
                        fileTextElements.forEach(function(element) {
                            element.innerHTML = fileName; // Replace fileName with the desired value
                        });
                    }
                }







            } else {
                // Example: display a message if no file is selected
                console.log("No file selected");
            }
        });
    </script>
    <!-- <script>
        // JavaScript to handle image upload
        document
            .getElementById("change-picture-btn")
            .addEventListener("click", function() {
                document.getElementById("fileInput").click();
            });

        document
            .getElementById("fileInput")
            .addEventListener("change", function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById("profile-img").src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

        // JavaScript to handle remove picture
        document
            .getElementById("remove-picture")
            .addEventListener("click", function() {
                document.getElementById("profile-img").src =
                    "dasheets/img/profile-img.png"; // Replace with default image source
            });
    </script> -->
@endsection
