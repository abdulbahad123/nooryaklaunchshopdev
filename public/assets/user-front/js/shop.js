!(function ($) {
    'use strict';
    /*============================================
        Sidebar Toggle
    ============================================*/
    $(".category-toggle").on("click", function (t) {
        var i = $(this).closest("li"),
            o = i.find("ul").eq(0);
        if (i.hasClass("open")) {
            o.slideUp(300, function () {
                i.removeClass("open")
            })
        } else {
            o.slideDown(300, function () {
                i.addClass("open")
            })
        }
        t.stopPropagation(), t.preventDefault()
    })


    /*============================================
        Slick Slider
    ============================================*/

    // Product Single Slider
    var proSingleSlider = $(".product-single-slider");
    var proSingleNav = $(".slider-thumbnails")

    proSingleSlider.slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        cssEase: 'linear',
        swipe: false,
        draggable: false,
        touchMove: false,
        infinite: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    swipe: true,
                    draggable: true,
                    touchMove: true
                }
            }
        ]
    });
    // Product SIngle SLider Nav
    proSingleNav.slick({
        vertical: true,
        verticalSwiping: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        dots: false,
        focusOnSelect: false,
        prevArrow: '<button type="button" class="btn-icon slider-btn slider-prev"><i class="fal fa-angle-left"></i></button>',
        nextArrow: '<button type="button" class="btn-icon slider-btn slider-next"><i class="fal fa-angle-left"></i></span>',
        infinite: false,
        responsive: [
            {
                breakpoint: 576,
                settings: {
                    vertical: false,
                    verticalSwiping: false,
                    slidesToShow: 4,
                    arrows: false
                }
            }
        ]
    });

    $(document).on('click', '.slider-thumbnails .slick-slide', function () {
        var index = $(this).data('slick-index');
        if (typeof index !== 'undefined') {
            var slickObj = proSingleSlider.slick('getSlick');
            var slideCount = slickObj.slideCount;
            var realIndex = (index % slideCount + slideCount) % slideCount;
            proSingleSlider.slick('slickGoTo', realIndex);

            $('.slider-thumbnails .slick-slide').removeClass('slick-current slick-active');
            $(this).addClass('slick-current slick-active');
        }
    });

    proSingleSlider.on('afterChange', function (event, slick, currentSlide) {
        $('.slider-thumbnails .slick-slide').removeClass('slick-current slick-active');
        $('.slider-thumbnails .slick-slide[data-slick-index="' + currentSlide + '"]').addClass('slick-current slick-active');
    });

    proSingleSlider.on("beforeChange", function (event, slick, currentSlide, nextSlide) {
        var img = $(slick.$slides[nextSlide]).find("img");
        $(".zoomWindowContainer,.zoomContainer").remove();

        if ($(window).width() >= 992) {
            $(img).elevateZoom({
                zoomWindowFadeIn: 500,
                zoomWindowFadeOut: 750,
                zoomType: "inner",
                cursor: "crosshair"
            });
        }
    });

    ////Elevate Zoom
    if (proSingleSlider.length && $(window).width() >= 992) {
        $(".product-single-slider .slick-active img").elevateZoom({
            zoomWindowFadeIn: 500,
            zoomWindowFadeOut: 750,
            zoomType: "inner",
            cursor: "crosshair",
        });
    }




    //====== product details page ===
    // Product Single Slider
    var proSingleSlider2 = $(".product-single-slider2");
    var proSingleNav2 = $(".slider-thumbnails2")

    proSingleSlider2.slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        fade: true,
        cssEase: 'linear',
        rtl: $('html').attr('dir') === 'rtl',
        swipe: false,
        draggable: false,
        touchMove: false,
        infinite: false,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    swipe: true,
                    draggable: true,
                    touchMove: true
                }
            }
        ]
    });
    // Product SIngle SLider Nav
    proSingleNav2.slick({
        vertical: true,
        verticalSwiping: true,
        slidesToShow: 5,
        slidesToScroll: 1,
        dots: false,
        focusOnSelect: false,
        prevArrow: '<button type="button" class="btn-icon slider-btn slider-prev"><i class="fal fa-angle-left"></i></button>',
        nextArrow: '<button type="button" class="btn-icon slider-btn slider-next"><i class="fal fa-angle-left"></i></span>',
        infinite: false,
        responsive: [
            {
                breakpoint: 576,
                settings: {
                    vertical: false,
                    verticalSwiping: false,
                    slidesToShow: 4,
                    arrows: false
                }
            }
        ]
    });

    $(document).on('click', '.slider-thumbnails2 .slick-slide', function () {
        var index = $(this).data('slick-index');
        if (typeof index !== 'undefined') {
            var slickObj = proSingleSlider2.slick('getSlick');
            var slideCount = slickObj.slideCount;
            var realIndex = (index % slideCount + slideCount) % slideCount;
            proSingleSlider2.slick('slickGoTo', realIndex);

            $('.slider-thumbnails2 .slick-slide').removeClass('slick-current slick-active');
            $(this).addClass('slick-current slick-active');
        }
    });

    proSingleSlider2.on('afterChange', function (event, slick, currentSlide) {
        $('.slider-thumbnails2 .slick-slide').removeClass('slick-current slick-active');
        $('.slider-thumbnails2 .slick-slide[data-slick-index="' + currentSlide + '"]').addClass('slick-current slick-active');
    });

    $(".product-single-slider2").on('setPosition afterChange', function (event, slick, currentSlide) {
        $(".zoomContainer").remove();
        if ($(window).width() >= 992) {
            $(".product-single-slider2 .slick-active img").elevateZoom({
                zoomWindowFadeIn: 500,
                zoomWindowFadeOut: 750,
                zoomType: "inner",
                cursor: "crosshair",
                scrollZoom: false,
            });
        }
    });

    (function ($) {
        "use strict";

        $(document).on('click', '.review-value li a', function () {
            $('.review-value li a i').removeClass('review-color');
            let reviewValue = $(this).attr('data-href');
            let parentClass = `review-${reviewValue}`;
            $('.' + parentClass + ' li a i').addClass('review-color');
            $('#reviewValue').val(reviewValue);
        });


        $(document).on('change', '.image', function () {
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.showimage').attr('src', e.target.result)
            };

            reader.readAsDataURL(file);
        });
    })(jQuery);

    /*============================================
        Horizontal Quantity
    ============================================*/
    $(".horizontal-quantity").TouchSpin({
        verticalupclass: "fal fa-minus",
        verticaldownclass: "fal fa-plus",
        buttondown_class: "btn btn-minus",
        buttonup_class: "btn btn-plus",
        initval: 0,
        min: 1,
        max: 9999
    })
    $(".vertical-quantity").TouchSpin({
        verticalbuttons: true,
        initval: 0,
        min: 1,
        max: 9999
    })

})(jQuery);
