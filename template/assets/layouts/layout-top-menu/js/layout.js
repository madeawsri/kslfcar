(function ($) {
    "use strict";

    /*---- Start Top Menu ----*/

    var CUSTOM_SETTINGS = window.CUSTOM_SETTINGS || {};

    CUSTOM_SETTINGS.navClick = function () {
        $(function () {
            var button = $('#navtoggle');
            var navarea = $(".menucontainer");
            button.mouseup(function () {
                navarea.toggleClass("wsoffcanvasopener");
                return false;
            });
            $(".topmenu").mouseup(function () {
                return false;
            });
            $(this).mouseup(function (e) {
                navarea.removeClass("wsoffcanvasopener");
            });
        });
    };

    CUSTOM_SETTINGS.navClick();

    var responsiveWidth = 991;
    var widthResize;

    $(window).on('load', function () {
        if(widthResize) {
            $('.menu-list li').has('ul').prepend('');
        }
    });

    $(window).on('resize', function () {
        widthResize = $(this).width() > responsiveWidth;
        if (widthResize) {
            $('.menu-list li ul').removeAttr("style");
        }
    }).resize();

    $(document).delegate(".topmenu .menu-list li > a, .menu-list li > span", "click", function (e) {
        if(!widthResize) {
            $('.menu-list').children('li > span').has('.ws-activearrow').removeClass("ws-activearrow");
            $(this).parent().toggleClass("active");
            $(this).parent().children('span').toggleClass('ws-activearrow');
            $(this).parent().children('ul').slideToggle(300);
        }
    });

    var $body = $("body");
    $.extend(CORE_TEMP.function, {
        SiteColor: function () {
            var $site_color = $('.site-color'), /* variable used on Site Color Settings */
                $dark_color_switch = $('.dark-color-switch'), /* variable used on Site Color Settings */
                $cssRoot = '../../../assets/layouts/layout-top-menu/css/color/', /* variable used on Site CSS path */
                $site_dark_color = localStorage.getItem("site_dark_color");


            /*----- START SITE COLOR SETTINGS JS -----*/

            $site_color.children('div').on('click', function (e) {
                e.preventDefault();
                var $this = $(this), /* global variable */
                    $this_color = $this.attr('data-color');
                /* Global Color Value */

                // Remove and ADD color active class
                $site_color.children('div').removeClass('color-active'),
                    $this.addClass('color-active');

                // Change CSS href
                $('#site-color').attr("href", $cssRoot + "light/color-" + $this_color + ".min.css");

                if ($site_dark_color !== null && $site_dark_color !== "") {
                    if ($("#site-dark-color").length == 0) {
                        $('#site-color').after('<link id="site-dark-color" rel="stylesheet" href="' + $cssRoot + 'dark/color-' + $this_color + '-' + $site_dark_color + '.min.css">');
                    } else {
                        $('#site-dark-color').attr('href', $cssRoot + 'dark/color-' + $this_color + '-' + $site_dark_color + '.min.css');
                    }
                }

                // Check Color value is null or not
                if ($this_color == null || $this_color == "") {
                    localStorage.setItem("site_color", $this_color);
                } else {
                    localStorage.setItem("site_color", $this_color);
                }

            });

            // Get Template Color from local storage
            if (localStorage.getItem("site_color") != null) {
                $site_color.children('div[data-color=' + localStorage.getItem("site_color") + ']').trigger('click');
            }

            /*----- END SITE COLOR SETTINGS JS -----*/

            /*----- START SITE DARK COLOR SETTINGS JS -----*/

            $dark_color_switch.on('click', function (e) {
                e.preventDefault();

                // Check value true is true
                if ($(".site-dark-check").prop("checked") == true) {
                    $('.dark-color').css("font-weight", "700").html("Dark");
                    localStorage.setItem("site_dark_color", "gray");

                    // Get Template Color and dark color from local storage
                    var site_color = localStorage.getItem("site_color");
                    var site_dark_color = localStorage.getItem("site_dark_color");

                    if (site_dark_color !== null && site_dark_color !== "") {
                        if (site_color == null) {
                            $('#site-color').after('<link id="site-dark-color" rel="stylesheet" href="' + $cssRoot + 'dark/color-default-' + site_dark_color + '.min.css">');
                        } else if ($("#site-dark-color").length == 0) {
                            $('#site-color').after('<link id="site-dark-color" rel="stylesheet" href="' + $cssRoot + 'dark/color-' + site_color + '-' + site_dark_color + '.min.css">');
                        } else {
                            $('#site-dark-color').attr('href', $cssRoot + 'dark/color-' + site_color + '-' + site_dark_color + '.min.css');
                        }
                    } else {
                        $('#site-dark-color').remove();
                    }

                } else {
                    $('.dark-color').css("font-weight", "normal").html("Light");
                    localStorage.removeItem("site_dark_color");
                    $('#site-dark-color').remove();
                }
            });


            // Det Template Dark Color from local storage
            if (localStorage.getItem("site_dark_color") !== null) {
                $('.dark-color').css("font-weight", "700").html("Dark");
                $('.dark-color-switch').children('.js-switch').prop('checked', true);
                $dark_color_switch.trigger('click');
            }

            /*----- END SITE DARK COLOR SETTINGS JS -----*/
        },
        HeaderFixed: function () {
            var $headerFixedSwitch = $('.header-fixed-switch');
            $headerFixedSwitch.on('click', function (e) {
                e.preventDefault();
                if ($(".nav-fixed-check").prop("checked") == true) {
                    $('.nav-fixed-text').css("font-weight", "700").html("overlay");
                    localStorage.setItem("header_type", "overlay");
                    $body.addClass('header-overlay');
                } else {
                    $('.nav-fixed-text').css("font-weight", "normal").html("Default");
                    localStorage.removeItem("header_type");
                    $body.removeClass('header-overlay');
                }
            });

            if (localStorage.getItem("header_type") !== null) {
                if ($(".nav-fixed-check").prop("checked") != true) {
                    $('.nav-fixed-text').css("font-weight", "normal").html("fixed");
                }
                $headerFixedSwitch.children('.js-switch').prop('checked', true);
                $headerFixedSwitch.trigger('click');
            }

        },
        NavigationColor: function () {
            var $navColorSwitch = $('.nav-color-switch');
            $navColorSwitch.on('click', function (e) {
                e.preventDefault();
                if ($(".nav-color-check").prop("checked") == true) {
                    $('.nav-color-text').css("font-weight", "700").html("Dark");
                    localStorage.setItem("navigation_color", "dark");
                    $body.addClass('navigation-dark');
                } else {
                    $('.nav-color-text').css("font-weight", "normal").html("Light");
                    localStorage.removeItem("navigation_color");
                    $body.removeClass('navigation-dark');
                }
            });

            if (localStorage.getItem("navigation_color") !== null) {
                if ($(".nav-color-check").prop("checked") != true) {
                    $('.nav-color-text').css("font-weight", "normal").html("Light");
                }
                $navColorSwitch.children('.js-switch').prop('checked', true);
                $navColorSwitch.trigger('click');
            }

        },
        HeaderFixedScroll: function () {

            function scrollsitesettings(){
                var scroll = $(window).scrollTop(),
                    headertag = $('#header').outerHeight();
                if(!$body.hasClass('header-overlay')){
                    if (scroll >= headertag) {
                        $body.addClass("right-sidebar-fixed");
                    } else {
                        $body.removeClass("right-sidebar-fixed");
                    }
                }else{
                    $body.removeClass("right-sidebar-fixed");
                }
            }

            $(window).on('scroll',function() {
                scrollsitesettings();
            });

            scrollsitesettings();

            $('.header-fixed-switch').on('click',function() {
                scrollsitesettings();
            });

        },
        NavigetionMenuActive: function () {
            var $pathArray = window.location.pathname.split("/").pop(),
                $currentpageurl = $("a[href='" + $pathArray + "']");

            $currentpageurl.parent('li:not(.dropdown-mega-item)').addClass('active');
            if ($currentpageurl.parent('.submenu-sub') && $currentpageurl.parent('.submenu-sub')) {
                $currentpageurl.parents('li:not(.dropdown-mega-item)').addClass('active');
            }
        },
        MegamenuAllshow: function () {
            $('.show_all_menu').on('click',function(e){
                console.log('click');
                e.preventDefault();
                var $this = $(this);
                $this.parent('li').toggleClass('active-mega');
                $this.parent('li').children('ul').slideToggle(300);
                if($this.parent('li').hasClass('active-mega')){
                    $this.children('span + span').html('Less');
                }else{
                    $this.children('span + span').html('All');
                }
            });
        },
        Hovermenu: function () {
            if ($(window).width() < 992) {
                jQuery("#header .dropdown-mega-item").removeClass("is-hovermenu");
            } else {
                jQuery("#header .dropdown-mega-item").addClass("is-hovermenu");
                jQuery("#header .dropdown-mega-item").removeClass("open");
            }
        }
    });
    $.extend(CORE_TEMP.onReady, {
        layoutOnReady: function () {
            CORE_TEMP.function.SiteColor();
            CORE_TEMP.function.HeaderFixed();
            CORE_TEMP.function.MegamenuAllshow();
            CORE_TEMP.function.Hovermenu();
            CORE_TEMP.function.NavigationColor();
        }
    });
    $.extend(CORE_TEMP.onLoad, {
        layoutOnLoad: function () {
            CORE_TEMP.function.HeaderFixedScroll();
            CORE_TEMP.function.NavigetionMenuActive();
        }
    });
    $.extend(CORE_TEMP.onResize, {
        layoutOnResize: function () {
            CORE_TEMP.function.HeaderFixedScroll();
            CORE_TEMP.function.Hovermenu();
        }
    });
})(jQuery);