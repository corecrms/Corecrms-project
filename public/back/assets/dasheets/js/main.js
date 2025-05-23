(function ($) {
    "use strict";


    // $(document).ready(function () {
    //     // Event delegation for plus button
    //     $(document).on("click", ".qty-plus-btn", function () {
    //         var input = $(this).siblings(".qty-input");
    //         input.val(parseInt(input.val()) + 1);
    //     });

    //     // Event delegation for minus button
    //     $(document).on("click", ".qty-minus-btn", function () {
    //         var input = $(this).siblings(".qty-input");
    //         var currentValue = parseInt(input.val());
    //         if (currentValue > 1) {
    //             input.val(currentValue - 1);
    //         }
    //     });
    // });

    // Quantity button
    // $(document).ready(function () {
    //     $("#plusBtn").click(function () {
    //         $("#quantityInput").val(parseInt($("#quantityInput").val()) + 1);
    //     });

    //     $("#minusBtn").click(function () {
    //         var currentValue = parseInt($("#quantityInput").val());
    //         if (currentValue > 1) {
    //             $("#quantityInput").val(currentValue - 1);
    //         }
    //     });
    // });
    // Quantity Button end

    // sidebar scrol to active sidebar menu
    document.addEventListener("DOMContentLoaded", function () {
        const activeNavItem = document.querySelector(".nav-link.active");
        if (activeNavItem) {
            activeNavItem.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    });
    // End sidebar scrol to active sidebar menu


    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();


    // Sidebar Toggler
    $('.sidebar-toggler').click(function () {
        $('.sidebar, .contents').toggleClass("open");
        return false;
    });
    // document.addEventListener("DOMContentLoaded", function () {
    //     var sidebar = document.querySelector(".sidebar");
    //     var sidebarElements = [
    //         document.getElementById("product"),
    //         document.getElementById("sale"),
    //         document.getElementById("purchase"),
    //         document.getElementById("payment"),
    //         document.getElementById("inventory"),
    //         document.getElementById("customer"),
    //         document.getElementById("transfer"),
    //         document.getElementById("vendor"),
    //         document.getElementById("accounting"),
    //         document.getElementById("setting"),
    //         document.getElementById("report"),
    //         document.getElementById("cms"),
    //     ];

    //     var navbarTogglers = [
    //         document.getElementById("navbar-toggler1"),
    //         document.getElementById("navbar-toggler2"),
    //         document.getElementById("navbar-toggler3"),
    //         document.getElementById("navbar-toggler4"),
    //         document.getElementById("navbar-toggler5"),
    //         document.getElementById("navbar-toggler6"),
    //         document.getElementById("navbar-toggler7"),
    //         document.getElementById("navbar-toggler8"),
    //         document.getElementById("navbar-toggler9"),
    //         document.getElementById("navbar-toggler10"),
    //         document.getElementById("navbar-toggler11"),
    //         document.getElementById("navbar-toggler12"),
    //     ];
    //     var productDropdown = document.getElementById("product"); // Dropdown element
    //     var saleDropdown = document.getElementById("sale"); // Dropdown element
    //     var purchaseDropdown = document.getElementById("purchase"); // Dropdown element
    //     var paymentDropdown = document.getElementById("payment"); // Dropdown element
    //     var inventoryDropdown = document.getElementById("inventory"); // Dropdown element
    //     var customerDropdown = document.getElementById("customer"); // Dropdown element
    //     var transferDropdown = document.getElementById("transfer"); // Dropdown element
    //     var vendorDropdown = document.getElementById("vendor"); // Dropdown element
    //     // var accountDropdown = document.getElementById("account"); // Dropdown element
    //     var accountingDropdown = document.getElementById("accounting"); // Dropdown element
    //     var settingDropdown = document.getElementById("setting"); // Dropdown element
    //     var reportDropdown = document.getElementById("report"); // Dropdown element
    //     var cmsDropdown = document.getElementById("cms"); // Dropdown element
    //     var isDropdownOpen = false; // Flag to track dropdown state

    //     function hideSidebarElements() {
    //         sidebarElements.forEach(function (element) {
    //             element.style.display = "none";
    //         });
    //         isDropdownOpen = false; // Update flag when dropdown is closed
    //     }

    //     navbarTogglers.forEach(function (toggler, index) {
    //         toggler.addEventListener("click", function (e) {
    //             e.preventDefault();
    //             if (isDropdownOpen && sidebarElements[index].style.display === "block") {
    //                 hideSidebarElements(); // Close dropdown if already open
    //             } else {
    //                 hideSidebarElements();
    //                 sidebarElements[index].style.display = "block";
    //                 isDropdownOpen = true; // Update flag when dropdown is opened
    //             }
    //         });
    //     });

    //     document.addEventListener("click", function (e) {
    //         // Check if dropdown is open and if the click target is not inside the dropdown
    //         if (isDropdownOpen && !sidebar.contains(e.target)) {
    //             hideSidebarElements(); // Close the dropdown
    //         }
    //     });
    //     // console.log(navbarTogglers);
    //     sidebar.addEventListener("scroll", function () {
    //         // Check if the dropdown is open and update its position accordingly

    //         var productLinkRect = navbarTogglers[0].getBoundingClientRect();
    //         var saleLinkRect = navbarTogglers[1].getBoundingClientRect();
    //         var purchaseLinkRect = navbarTogglers[2].getBoundingClientRect();
    //         var paymentLinkRect = navbarTogglers[3].getBoundingClientRect();
    //         var inventoryLinkRect = navbarTogglers[4].getBoundingClientRect();
    //         var customerLinkRect = navbarTogglers[5].getBoundingClientRect();
    //         var transferLinkRect = navbarTogglers[6].getBoundingClientRect();
    //         var vendorLinkRect = navbarTogglers[7].getBoundingClientRect();
    //         var accountingLinkRect = navbarTogglers[8].getBoundingClientRect();
    //         var settingLinkRect = navbarTogglers[9].getBoundingClientRect();
    //         var reportLinkRect = navbarTogglers[10].getBoundingClientRect();
    //         productDropdown.style.top = productLinkRect.top + "px";
    //         productDropdown.style.left = productLinkRect.right + "px";
    //         saleDropdown.style.top = saleLinkRect.top + "px";
    //         saleDropdown.style.left = saleLinkRect.right + "px";
    //         purchaseDropdown.style.top = purchaseLinkRect.top + "px";
    //         purchaseDropdown.style.left = purchaseLinkRect.right + "px";
    //         inventoryDropdown.style.top = inventoryLinkRect.top + "px";
    //         inventoryDropdown.style.left = inventoryLinkRect.right + "px";
    //         transferDropdown.style.top = transferLinkRect.top + "px";
    //         transferDropdown.style.left = transferLinkRect.right + "px";
    //         accountingDropdown.style.top = accountingLinkRect.top + "px";
    //         accountingDropdown.style.left = accountingLinkRect.right + "px";
    //         customerDropdown.style.top = customerLinkRect.top + "px";
    //         customerDropdown.style.left = customerLinkRect.right + "px";
    //         vendorDropdown.style.top = vendorLinkRect.top + "px";
    //         vendorDropdown.style.left = vendorLinkRect.right + "px";
    //         paymentDropdown.style.top = paymentLinkRect.top + "px";
    //         paymentDropdown.style.left = paymentLinkRect.right + "px";
    //         // settingDropdown.style.top = settingLinkRect.top + "px";
    //         // settingDropdown.style.left = settingLinkRect.right + "px";
    //         // reportDropdown.style.top = reportLinkRect.top + "px";
    //         // reportDropdown.style.left = reportLinkRect.right + "px";
    //         if (isDropdownOpen) {
    //             hideSidebarElements();

    //         }

    //     });


    //     window.addEventListener("scroll", function () {
    //         // Hide the dropdown when the window is scrolled
    //         hideSidebarElements();
    //     });
    // });




    document.addEventListener("DOMContentLoaded", function () {
        var sidebar = document.querySelector(".sidebar");
        var sidebarElements = document.querySelectorAll(".sidebar-ul > div[id]");
        var navbarTogglers = document.querySelectorAll(".navbar-nav > div[id]");
        var isDropdownOpen = false; // Flag to track dropdown state

        function hideSidebarElements() {
            sidebarElements.forEach(function (element) {
                element.style.display = "none";
            });
            isDropdownOpen = false; // Update flag when dropdown is closed
        }

        function positionDropdowns() {
            navbarTogglers.forEach(function (toggler, index) {
                var togglerRect = toggler.getBoundingClientRect();
                sidebarElements[index].style.top = togglerRect.top + "px";
                sidebarElements[index].style.left = togglerRect.right + "px";
            });
        }

        function handleDropdownClick(e, index) {
            e.preventDefault();
            if (isDropdownOpen && sidebarElements[index].style.display === "block") {
                hideSidebarElements(); // Close dropdown if already open
            } else {
                hideSidebarElements();
                sidebarElements[index].style.display = "block";
                isDropdownOpen = true; // Update flag when dropdown is opened
            }
        }

        navbarTogglers.forEach(function (toggler, index) {
            toggler.addEventListener("click", function (e) {
                handleDropdownClick(e, index);
            });
        });

        document.addEventListener("click", function (e) {
            // Check if dropdown is open and if the click target is not inside the dropdown
            if (isDropdownOpen && !sidebar.contains(e.target)) {
                hideSidebarElements(); // Close the dropdown
            }
        });

        sidebar.addEventListener("scroll", function () {
            // Check if the dropdown is open and update its position accordingly
            positionDropdowns();
            if (isDropdownOpen) {
                hideSidebarElements();
            }
        });

        window.addEventListener("scroll", function () {
            // Hide the dropdown when the window is scrolled
            hideSidebarElements();
        });

        positionDropdowns(); // Initial positioning of dropdowns
    });






    // document.addEventListener("DOMContentLoaded", function () {
    //     var sidebar = document.querySelector(".sidebar");
    //     var sidebarElements = document.querySelectorAll(".sidebar-ul > div[id]");
    //     var navbarTogglers = document.querySelectorAll(".navbar-nav > div[id]");
    //     var isDropdownOpen = false; // Flag to track dropdown state

    //     function hideSidebarElements() {
    //         sidebarElements.forEach(function (element) {
    //             element.style.display = "none";
    //         });
    //         isDropdownOpen = false; // Update flag when dropdown is closed
    //     }

    //     function positionDropdowns() {
    //         navbarTogglers.forEach(function (toggler, index) {
    //             var togglerRect = toggler.getBoundingClientRect();
    //             sidebarElements[index].style.top = togglerRect.top + "px";
    //             sidebarElements[index].style.left = togglerRect.right + "px";
    //         });
    //     }

    //     function handleDropdownClick(e, index) {
    //         e.preventDefault();
    //         if (isDropdownOpen && sidebarElements[index].style.display === "block") {
    //             hideSidebarElements(); // Close dropdown if already open
    //         } else {
    //             hideSidebarElements();
    //             sidebarElements[index].style.display = "block";
    //             isDropdownOpen = true; // Update flag when dropdown is opened
    //         }
    //     }

    //     navbarTogglers.forEach(function (toggler, index) {
    //         toggler.addEventListener("click", function (e) {
    //             handleDropdownClick(e, index);
    //         });
    //     });

    //     document.addEventListener("click", function (e) {
    //         // Check if dropdown is open and if the click target is not inside the dropdown
    //         if (isDropdownOpen && !sidebar.contains(e.target)) {
    //             hideSidebarElements(); // Close the dropdown
    //         }
    //     });

    //     sidebar.addEventListener("scroll", function () {
    //         // Check if the dropdown is open and update its position accordingly
    //         positionDropdowns();
    //         if (isDropdownOpen) {
    //             hideSidebarElements();
    //         }
    //     });

    //     window.addEventListener("scroll", function () {
    //         // Hide the dropdown when the window is scrolled
    //         hideSidebarElements();
    //     });

    //     positionDropdowns(); // Initial positioning of dropdowns
    // });





    // Sidebar Elements show end

    // Modal Calculator
    // $(function () {
    //     $("#datepicker").datepicker({
    //         firstDay: 1,
    //     });
    // });
    // Modal Calculator End


    // Function to remove barcode row
    // function removeBarcodeRow(rowId) {
    //     var row = document.getElementById(rowId);
    //     if (row) {
    //         row.remove();
    //     }
    // }

    function removeBarcodeFun() {
        const removeButtons = document.querySelectorAll(".removeBarcodeBtn");

        removeButtons.forEach(button => {
            button.addEventListener("click", function () {
                const row = button.closest('.barcodeRow');
                if (row) {
                    row.remove();
                }

            });
        });
    }



    // Create Product on Click Create Barcode section
    var barcodeCount = 1; // Initialize barcode count

    // Function to create the barcode section
    function createBarcodeSection() {
        barcodeCount++;
        var barcodeContainer = document.createElement('div');
        barcodeContainer.classList.add('row', 'border-bottom', 'mt-2', 'barcodeRow');
        barcodeContainer.id = 'barcode' + barcodeCount;

        var col1 = document.createElement('div');
        col1.classList.add('col-md-5');

        var formGroup = document.createElement('div');
        formGroup.classList.add('form-group');

        var label1 = document.createElement('label');
        label1.textContent = 'Barcode Symbology *';
        label1.htmlFor = 'barcodeSymbology' + barcodeCount;

        var select = document.createElement('select');
        select.classList.add('form-control', 'form-select', 'subheading', 'mt-1');
        select.setAttribute('aria-label', 'Default select example');
        select.id = 'barcodeSymbology' + barcodeCount;
        select.name = 'barcodeSymbology' + barcodeCount;

        var option1 = document.createElement('option');
        option1.value = 'Code 128';
        option1.textContent = 'Code 128';
        var option2 = document.createElement('option');
        option2.value = 'Code 39';
        option2.textContent = 'Code 39';
        var option3 = document.createElement('option');
        option3.value = 'EAN8';
        option3.textContent = 'EAN8';
        var option4 = document.createElement('option');
        option4.value = 'EAN13';
        option4.textContent = 'EAN13';
        var option5 = document.createElement('option');
        option5.value = 'UPC';
        option5.textContent = 'UPC';

        select.appendChild(option1);
        select.appendChild(option2);
        select.appendChild(option3);
        select.appendChild(option4);
        select.appendChild(option5);

        formGroup.appendChild(label1);
        formGroup.appendChild(select);

        col1.appendChild(formGroup);

        var col2 = document.createElement('div');
        col2.classList.add('col-md-5');

        var label2 = document.createElement('label');
        label2.textContent = 'Product Code';
        label2.htmlFor = 'productCode' + barcodeCount;

        var inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group', 'mt-1', 'subheading');

        var input = document.createElement('input');
        input.type = 'text';
        input.classList.add('form-control', 'subheading');
        input.placeholder = 'Barcode';
        input.setAttribute('aria-label', "Recipient's username");
        input.setAttribute('aria-describedby', 'basic-addon2');
        input.id = 'productCode' + barcodeCount;
        input.name = 'productCode' + barcodeCount;

        var span = document.createElement('span');
        span.classList.add('input-group-text', 'subheading');
        span.id = 'basic-addon2' + barcodeCount;

        var icon = document.createElement('i');
        icon.classList.add('bi', 'bi-upc-scan');

        span.appendChild(icon);
        inputGroup.appendChild(input);
        inputGroup.appendChild(span);

        var paragraph = document.createElement('p');
        paragraph.textContent = 'Scan the barcode or symbology';

        col2.appendChild(label2);
        col2.appendChild(inputGroup);
        col2.appendChild(paragraph);

        var col3 = document.createElement('div');
        col3.classList.add('col-md-2', 'pt-1');

        var div = document.createElement('div');
        var button = document.createElement('button');
        button.classList.add('btn', 'text-danger', 'border-danger', 'w-100', 'subheading', 'mt-4', 'delete-barcode');
        button.setAttribute('type', 'button');
        var icon = document.createElement('i');
        icon.classList.add('bi', 'bi-trash3');

        button.appendChild(icon);
        div.appendChild(button);
        col3.appendChild(div);

        barcodeContainer.appendChild(col1);
        barcodeContainer.appendChild(col2);
        barcodeContainer.appendChild(col3);

        // Insert new barcode fields before the existing section with "Add another barcode" paragraph
        document.getElementById('barcodeSection').insertBefore(barcodeContainer, document.getElementById('barcodeButtonSection'));
    }

    // Add event listener to the "Add another barcode" paragraph
    document.getElementById('addBarcode').addEventListener('click', function () {
        createBarcodeSection();
        document.getElementById('barcodeCount').innerHTML = barcodeCount;
        removeBarcodeFun();
    });
    // delete-barcode
    // Add event listener to the "Delete Barcode" button
    $(document).on("click", ".delete-barcode", function () {
        document.getElementById('barcodeCount').innerHTML = (barcodeCount - 1);
        barcodeCount = (barcodeCount - 1);
        var barcode = $(this).closest('.row');
        barcode.remove();
    });
    removeBarcodeFun()

})(jQuery);
