!function (document, window, $) {
    "use strict";
    var $defaults = {
        time: "icon icon_clock",
        date: "icon icon_calendar",
        up: "icon arrow_carrot-up",
        down: "icon arrow_carrot-down",
        previous: 'icon arrow_carrot-left',
        next: 'icon arrow_carrot-right',
    };

    $("#owl-full").owlCarousel({
        navigation: true,
        slideSpeed: 400,
        paginationSpeed: 500,
        items: 1
    });

    $('.slider-nav').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: true,
        centerMode: true,
        focusOnSelect: true,
        slickSetOption: true,
        responsive: [
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                }
            }
        ]
    });

    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        slickSetOption: true,
        asNavFor: '.slider-nav',
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 320,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

}(document, window, jQuery);
