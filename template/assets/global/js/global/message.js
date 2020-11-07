!function (window, document, $) {
    "use strict";
    var selector = '.message-data';
    jQuery(selector).on('click', function () {
        jQuery(selector).removeClass('active');
        jQuery(this).addClass('active');
        jQuery('.chat-call').removeClass('calling');
    });
    jQuery('.message-data').on('click',function () {
        var img = jQuery(this).find("a .message-image img"),
            userTitle = jQuery(this).find(".sender-name").html(),
            newSrc = img.attr("src");
        jQuery('.active-user').attr('src', newSrc);
        jQuery('.active-user-title').html(userTitle);

    });
    jQuery('.chat-call').on('click',function(){
        var $this = $(this);
        $this.toggleClass('calling');
    });
}(window, document, jQuery);