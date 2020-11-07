!function (document, window, $) {
    "use strict";

    $(function() {
        $('#choose-background').colorpicker({
            color: '#ffaa00',
            container: true,
            inline: true,
            sliders: {
                saturation: {
                    maxLeft: 125,
                    maxTop: 125
                },
                hue: {
                    maxTop: 125
                },
                alpha: {
                    maxTop: 125
                }
            }
        }).on('changeColor', function(e) {
            /*$('.full-bg')[0].style.backgroundColor = e.color.toString( 'rgba');*/

            var background_color = e.color.toString( 'rgba');

            /*console.log(background_color);*/

            $(".full-bg").css("background-color", background_color);
            $(".full-text").css("color", background_color);
            $(".full-border").css("border-color", background_color);
        });
    });

    $(function() {
        $('#choose-text').colorpicker({
            color: '#ffaa00',
            container: true,
            inline: true,
            sliders: {
                saturation: {
                    maxLeft: 125,
                    maxTop: 125
                },
                hue: {
                    maxTop: 125
                },
                alpha: {
                    maxTop: 125
                }
            }
        }).on('changeColor', function(e) {

            var text_color = e.color.toString( 'rgba');

            $(".choose-text-color").css("color", text_color);

        });
    });

}(document, window, jQuery);
