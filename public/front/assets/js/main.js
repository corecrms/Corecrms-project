(function ($) {
	"use strict"

	// Quantity Button
	// $(document).ready(function () {
	// 	$(document).on("click", ".plusBtn", function () {
	// 		var $quantityInput = $(this).siblings(".quantityInput");
	// 		$quantityInput.val(parseInt($quantityInput.val()) + 1);
	// 	});

	// 	$(document).on("click", ".minusBtn", function () {
	// 		var $quantityInput = $(this).siblings(".quantityInput");
	// 		var currentValue = parseInt($quantityInput.val());
	// 		if (currentValue > 1) {
	// 			$quantityInput.val(currentValue - 1);
	// 		}
	// 	});
	// });
	// Quantity Button End

	// Owl carousel
	jQuery(document).ready(function ($) {
		var $owl = $(".owl-carousel");
		$owl.children().each(function (index) {
			jQuery(this).attr("data-position", index);
		});

		$owl.owlCarousel({
			nav: true,
			dots: false,
			loop: true,
			items: 6,
			autoplay: true,
			autoplayTimeout: 2000,
			margin: 20,
			navText: [
				"<i class='fa-solid fa-chevron-left'></i>",
				"<i class='fa-solid fa-chevron-right'></i>",
			],
			responsive: {
				0: {
					items: 2,
				},
				500: {
					items: 3,
				},
				700: {
					items: 4,
				},
				1000: {
					items: 6,
				},
			},
		});
		$(document).on("click", ".item", function () {
			$owl.trigger("to.owl.carousel", $(this).data("position"));
		});
	});
	// End Owl Carousel

	// Latest News Carousel
	jQuery('#carousel').owlCarousel({
		autoplay: true,
		// rewind: false /* use rewind if you don't want loop */,
		// margin: 10,
		loop: true,
		dots: false,
		navText: [
			"<i class='fa-solid fa-chevron-left prev-btn'></i>",
			"<i class='fa-solid fa-chevron-right next-btn'></i>",
		],
		responsiveClass: true,
		autoHeight: true,
		autoplayTimeout: 7000,
		smartSpeed: 800,
		nav: true,
		responsive: {
			0: {
				items: 1,
			},

			// 600: {
			// 	items: 2,
			// },
            500: {
                items: 3,
            },
            700: {
                items: 4,
            },


			1024: {
				items: 3,
			},

			1366: {
				items: 3,
			},
		},
	});
	//   End Latest New Carousel

	// Mobile Nav toggle
	$('.menu-toggle > a').on('click', function (e) {
		e.preventDefault();
		$('#responsive-nav').toggleClass('active');
	})

	// Fix cart dropdown from closing
	$('.cart-dropdown').on('click', function (e) {
		e.stopPropagation();
	});

	/////////////////////////////////////////

	// Products Slick
	$('.products-slick').each(function () {
		var $this = $(this),
			$nav = $this.attr('data-nav');

		$this.slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplay: true,
			infinite: true,
			speed: 300,
			dots: false,
			arrows: true,
			appendArrows: $nav ? $nav : false,
			responsive: [{
				breakpoint: 991,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1,
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1,
				}
			},
			]
		});
	});

	// Products Widget Slick
	$('.products-widget-slick').each(function () {
		var $this = $(this),
			$nav = $this.attr('data-nav');

		$this.slick({
			infinite: true,
			autoplay: true,
			speed: 300,
			dots: false,
			arrows: true,
			appendArrows: $nav ? $nav : false,
		});
	});

	/////////////////////////////////////////

	// Product Main img Slick
	$('#product-main-img').slick({
		infinite: true,
		speed: 300,
		dots: false,
		arrows: true,
		fade: true,
		asNavFor: '#product-imgs',
	});

	// Product imgs Slick
	$('#product-imgs').slick({
		slidesToShow: 3,
		slidesToScroll: 1,
		arrows: true,
		centerMode: true,
		focusOnSelect: true,
		centerPadding: 0,
		vertical: true,
		asNavFor: '#product-main-img',
		responsive: [{
			breakpoint: 991,
			settings: {
				vertical: false,
				arrows: false,
				dots: true,
			}
		},
		]
	});

	// // Product img zoom
	// var zoomMainProduct = document.getElementById('product-main-img');
	// if (zoomMainProduct) {
	// 	$('#product-main-img .product-preview').zoom();
	// }

	/////////////////////////////////////////

	// Input number
	$('.input-number').each(function () {
		var $this = $(this),
			$input = $this.find('input[type="number"]'),
			up = $this.find('.qty-up'),
			down = $this.find('.qty-down');

		down.on('click', function () {
			var value = parseInt($input.val()) - 1;
			value = value < 1 ? 1 : value;
			$input.val(value);
			$input.change();
			updatePriceSlider($this, value)
		})

		up.on('click', function () {
			var value = parseInt($input.val()) + 1;
			$input.val(value);
			$input.change();
			updatePriceSlider($this, value)
		})
	});

	var priceInputMax = document.getElementById('price-max'),
		priceInputMin = document.getElementById('price-min');

	priceInputMax.addEventListener('change', function () {
		updatePriceSlider($(this).parent(), this.value)
	});

	priceInputMin.addEventListener('change', function () {
		updatePriceSlider($(this).parent(), this.value)
	});

	function updatePriceSlider(elem, value) {
		if (elem.hasClass('price-min')) {
			console.log('min')
			priceSlider.noUiSlider.set([value, null]);
		} else if (elem.hasClass('price-max')) {
			console.log('max')
			priceSlider.noUiSlider.set([null, value]);
		}
	}

	// Price Slider
	var priceSlider = document.getElementById('price-slider');
	if (priceSlider) {
		noUiSlider.create(priceSlider, {
			start: [1, 999],
			connect: true,
			step: 1,
			range: {
				'min': 1,
				'max': 999
			}
		});

		priceSlider.noUiSlider.on('update', function (values, handle) {
			var value = values[handle];
			handle ? priceInputMax.value = value : priceInputMin.value = value
		});
	}


})(jQuery);
