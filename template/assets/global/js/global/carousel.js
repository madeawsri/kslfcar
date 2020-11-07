!function (document, window, $) {
    "use strict";
    $('#owl-auto-width').owlCarousel({
        margin: 10,
        nav: true,
        loop: true,
        autoWidth: true,
        items: 4
    });
    $("#owl-autoplay").owlCarousel({
        loop: true,
        margin: 10,
        autoplay: true,
        autoplayTimeout: 1000,
        autoplayHoverPause: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            960: {
                items: 4
            },
            1200: {
                items: 4
            }
        }
    });
    $('#owl-url').owlCarousel({
        loop: false,
        dots: false,
        center: true,
        margin: 10,
        URLhashListener: true,
        autoplayHoverPause: true,
        startPosition: 'URLHash',
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            960: {
                items: 4
            },
            1200: {
                items: 4
            }
        }
    });
    $("#owl-full").owlCarousel({
        navigation: true,
        slideSpeed: 400,
        paginationSpeed: 500,
        items: 1,
    });
    $('#owl-lazy-load').owlCarousel({
        items: 4,
        lazyLoad: true,
        loop: true,
        margin: 10,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            960: {
                items: 4
            },
            1200: {
                items: 4
            }
        }
    });
    $('#owl-padding').owlCarousel({
        stagePadding: 50,
        loop: true,
        margin: 10,
        dots: false,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });
    var owl = $('#owl-mousewheel');
    owl.owlCarousel({
        loop: true,
        nav: true,
        dots: false,
        margin: 10,
        navText: [
            "<i class='arrow_carrot-left icon-white'></i>",
            "<i class='arrow_carrot-right icon-white'></i>"
        ],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            960: {
                items: 4
            },
            1200: {
                items: 5
            }
        }
    });
    owl.on('mousewheel', '.owl-stage', function (e) {
        if (e.deltaY > 0) {
            owl.trigger('next.owl');
        } else {
            owl.trigger('prev.owl');
        }
        e.preventDefault();
    });
    $('#owl-multiitem').owlCarousel({
        margin: 10,
        nav: true,
        autoplay: true,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 2
            },
            700: {
                items: 4
            },
            1000: {
                items: 3
            },
            1100: {
                items: 5
            }
        }
    });
    $('.responsive-slick').slick({
        dots: true,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    $('.single-slick').slick({
        slidesToShow: 1,
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
            }
        ]
    });

    $('.center-mode').slick({
        centerMode: true,
        centerPadding: '60px',
        slidesToShow: 3,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 480,
                settings: {
                    centerMode: true,
                    centerPadding: '40px',
                    slidesToShow: 1
                }
            }
        ]
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

    $('.fade-slide').slick({
        dots: true,
        infinite: true,
        slickSetOption: true,
        speed: 500,
        fade: true,
        cssEase: 'linear',
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

    $('.multiple-items').slick({
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 320,
                settings: {
                    slidesToShow: 2
                }
            }
        ]
    });

}(document, window, jQuery);

$(window).resize(function () {
    $('.slider-for').slick('refresh');
    $('.fade-slide').slick('refresh');
});
