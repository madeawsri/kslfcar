(function (window, document, $) {
    'use strict';
    var tour = new Tour({
        name: 'test',
        debug: false,
        orphan: true,
        smartPlacement: true,
        animation: true,
        container: "body",
        backdrop: true,
        backdropContainer: 'body',
        backdropPadding: false,
        steps: [
            {
                element: "#search_tour",
                title: "Search",
                placement: "left",
                content: "Click this button you can search the admin template.",
                template: "<div class='tour_block popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-primary' data-role='prev'>« Prev</button><button class='btn btn-primary' data-role='end'>Close</button><button class='btn btn-primary' data-role='next'>Next »</button></div></div>",
            }, {
                element: "#mail_tour",
                title: "Message",
                placement: "left",
                content: "This is a sidebar dialog box for user massage list.",
                template: "<div class='tour_block popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-primary' data-role='prev'>« Prev</button><button class='btn btn-primary' data-role='end'>Close</button><button class='btn btn-primary' data-role='next'>Next »</button></div></div>",
            },
            {
                element: "#chat_tour",
                title: "Chatbox",
                placement: "left",
                content: "This is a sidebar dialog box for user chat list.",
                template: "<div class='tour_block popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-primary' data-role='prev'>« Prev</button><button class='btn btn-primary' data-role='end'>Close</button><button class='btn btn-primary' data-role='next'>Next »</button></div></div>",
            },
            {
                element: "#fullscreen_tour",
                title: "Full screen",
                placement: "left",
                content: "Click this button you can view the admin template in full screen.",
                template: "<div class='tour_block popover tour'><div class='arrow'></div><h3 class='popover-title'></h3><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-primary' data-role='prev'>« Prev</button><button class='btn btn-primary' data-role='end'>End tour</button></div></div>",
            }
        ]
    })

    // Start the tour
    tour.start();

    jQuery('#tour_link').on('click', function () {
        tour.restart(true);
    });

    /*var tour = new Tour({
        storage : false,
        steps: [
            {
                element: "#search_tour",
                title: "",
                placement: "left",
                content: "<p>This is the Deployment Manager Dashboard.</p><p>It shows you which versions of your software are deployed to your different environments.</p>"
            },
            {
                element: "#mail_tour",
                title: "",
                placement: "left",
                content: "<p>This is the Deployment Manager Dashboard.</p><p>It shows you which versions of your software are deployed to your different environments.</p>"
            },
            {
                element: "#chat_tour",
                title: "",
                placement: "left",
                content: "<p>This is the Deployment Manager Dashboard.</p><p>It shows you which versions of your software are deployed to your different environments.</p>"
            },
            {
                element: "#fullscreen_tour",
                title: "",
                placement: "left",
                content: "<p>This is the Deployment Manager Dashboard.</p><p>It shows you which versions of your software are deployed to your different environments.</p>"

            }
        ]});


    tour.init();

    tour.start();

    jQuery('#tour_link').on('click', function () {
        tour.restart(true);
    });*/

})(window, document, jQuery);


