var SiteSettings = function () {
    "use strict";
    var initSiteSettings = function(){

        var $footer_fixed = $('.footer-fixed-switch'), /* variable used on Footer Fixed Settings */
            $righttoggle = $('.icon-right'),
            $site_fonts = $('.site-fonts'), /* variable used on Site Fonts Settings */
            $html = $('html'),
            $body = $('body'),
            $template_color = {};

        $righttoggle.on('click', function () {
            $body.toggleClass("site-settings");
        });

        /*----- START FOOTER FIXED SETTINGS JS -----*/

        $footer_fixed.on('click', function (e) {
            e.preventDefault();
            if ($(".footer-fixed-check").prop("checked") == true) {
                $('.footer-check-text').css("font-weight", "700").html("Sticky");
                localStorage.setItem("footer_fidex", "fixed");
                $body.addClass('site-footer-fixed');
            } else {
                $('.footer-check-text').css("font-weight", "normal").html("Default");
                localStorage.removeItem("footer_fidex");
                $body.removeClass('site-footer-fixed');
            }

        });

        if (localStorage.getItem("footer_fidex") !== null) {
            if ($(".footer-fixed-check").prop("checked") != true) {
                $('.footer-check-text').css("font-weight", "700").html("Sticky");
            }
            $footer_fixed.children('.js-switch').prop('checked', true);
            $footer_fixed.trigger('click');
        }

        /*----- END FOOTER FIXED SETTINGS JS -----*/

        /*----- START SITE FONTS SETTINGS JS -----*/

        $site_fonts.children('.font-box').find('.radio-button').on('click', function (e) {
            var $this = $(this),
                $checkbox = $this.find('[type="radio"]'),
                $this_font = $checkbox.val();
            $html.removeClass('default font2 font3').addClass($this_font);

            if ($this_font === '') {
                localStorage.removeItem('site_font');
            } else {
                localStorage.setItem("site_font", $this_font);
            }
        });

        if (localStorage.getItem("site_font") != null) {
            $site_fonts.find('input[value=' + localStorage.getItem("site_font") + ']').trigger('click');
        }

        /*----- END SITE FONTS SETTINGS JS -----*/

        /*----- Start Swithery Change Color JS -----*/

        $('[data-color]').each(function () {
            var $this = $(this),
                $color_title = $this.data('color');
            $template_color[$color_title] = $this.data('color-code');
        });

        /*----- END Swithery Change Color JS -----*/
    };

    return {

        init: function () {
            initSiteSettings();
        }
    };

}()

if (typeof TempApp === "undefined") {
    jQuery(document).ready(function() {
        SiteSettings.init();
    });
}