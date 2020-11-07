!function (document, window, $) {
    "use strict";

    var splineData2 = [{
        data: [
            ['JAN', 25],
            ['FEB', 18],
            ['MAR', 35],
            ['APR', 20],
            ['MAY', 28],
            ['JUN', 65],
            ['JUL', 30],
            ['AUG', 20],
            ['SEP', 30],
            ['OCT', 45],
            ['NOV', 20],
            ['DEC', 10]
        ],
        splines: {
            show: true,
            tension: 0.45,
            lineWidth: 4,
            fill: 0.1
        }
    }, {
        data: [
            ['JAN', 25],
            ['FEB', 18],
            ['MAR', 35],
            ['APR', 20],
            ['MAY', 28],
            ['JUN', 65],
            ['JUL', 30],
            ['AUG', 20],
            ['SEP', 30],
            ['OCT', 45],
            ['NOV', 20],
            ['DEC', 10]
        ],
        bars: {
            show: true,
            barWidth: 0.05,
            align: 'center',
            lineWidth: 0,
            fillColor: {
                colors: [{
                    opacity: 0.2
                }, {
                    opacity: 0.2
                }]
            }
        }
    }];

    var splineOptions2 = {
        series: {
            points: {
                show: false
            }
        },
        colors: ['#087380', '#087380'],
        grid: {
            borderColor: '#eee',
            borderWidth: 0,
            hoverable: true,
            clickable: true,
            backgroundColor: 'transparent'
        },
        tooltip: true,
        tooltipOpts: {
            content: function(label, x, y) {
                return 'Income : ' + '$' + y;
            }
        },
        xaxis: {
            mode: 'categories',
            show: true
        },
        yaxis: {
            max: 80,
            show: true
        },
        legend: {
            backgroundColor: 'transparent',
            show: true
        },
        shadowSize: 0
    };

    var plot = $('#dashboard_v4_revenue_chart').each(function(){
        runFlot(this, splineData2, splineOptions2);
    });
    // Common function to run charts
    // by reading custom height from data attribute
    function runFlot(el, data, opts) {
        var $el = $(el),
            height = $el.data('height');
        if (height) $el.height(height);
        $el.plot(data, opts);
    }

    //project table chart
    $(".stackline-bar").sparkline("html", {
        type: "bar",
        height: "40px",
        barWidth: 5,
        barSpacing: 5,
        barColor: ['#087380'],
        negBarColor: ['#c9302c'],
        stackedBarColor: ['#087380', "#c9302c"]
    });
    $(".sparkline-line").sparkline("html", {
        height: "40px",
        width: "75px",
        lineColor: ['#087380'],
        fillColor: ['#087380']
    });
    $(".sparkline-inlinerange").sparkline("html", {
        fillColor: !1,
        height: "40px",
        width: "75px",
        lineColor: ['#087380'],
        spotColor: ['#087380'],
        minSpotColor: ['#087380'],
        maxSpotColor: ['#087380'],
        normalRangeColor: ['transparent'],
        normalRangeMin: -1,
        normalRangeMax: 8
    });
    $(".sparkline-linecustom").sparkline("html", {
        height: "40px",
        width: "75px",
        lineColor: ['#087380'],
        fillColor: ['#087380'],
        minSpotColor: !1,
        maxSpotColor: !1,
        spotColor: ['#087380'],
        spotRadius: 2
    });

    //weather icon animation
    var icons = new Skycons(
        {"color": "#fff"},
        {"resizeClear": true}
        ),
        list  = [
            "partly-cloudy-day",
            "partly-cloudy-night",
            "sleet",
            "wind"
        ],
        i;

    for(i = list.length; i--; )
        icons.set(list[i], list[i]);
    icons.play();

    //daily visiter
    var d1 = [
        [0, 6],
        [1, 15],
        [2, 23],
        [3, 17],
        [4, 30],
        [5, 50],
        [6, 34],
        [7, 20],
        [8, 13],
        [9, 16],
        [10, 25]

    ];
    var data = ([{
        label: "Too",
        data: d1,
        lines: {
            show: true,
            fill: true,
            lineWidth: 2,
            fillColor: {
                colors: ["rgba(240, 173, 78, .1)", "rgba(240, 173, 78, .6)"]
            }
        }
    }]);
    var options = {
        grid: {
            backgroundColor: {
                colors: ["#fff", "#fff"]
            },
            borderWidth: 0,
            borderColor: "#f0f0f0",
            margin: 0,
            minBorderMargin: 0,
            labelMargin: 20,
            hoverable: true,
            clickable: true
        },
        // Tooltip
        tooltip: true,
        tooltipOpts: {
            content: "%s X: %x Y: %y",
            shifts: {
                x: -60,
                y: 25
            },
            defaultTheme: false
        },
        legend: {
            labelBoxBorderColor: "#ccc",
            show: false,
            noColumns: 0
        },
        series: {
            stack: true,
            shadowSize: 0,
            highlightColor: 'rgba(240, 173, 78, 0.6)'

        },
        xaxis: {
            tickLength: 0,
            tickDecimals: 0,
            show: true,
            min: 2,
            font: {
                style: "normal",
                color: "#666666"
            }
        },
        yaxis: {
            ticks: 3,
            tickDecimals: 0,
            show: true,
            tickColor: "#f0f0f0",
            font: {

                style: "normal",


                color: "#666666"
            }
        },
        points: {
            show: true,
            radius: 2,
            symbol: "circle"
        },
        colors: ["#f0ad4e", "#f0ad4e"]
    };
    var plot2 = $.plot($("#dashboard_v4_daily_visit"), data, options);

    //top advertise
    var dataPie = [{
        label: "Samsung",
        data: 35
    }, {
        label: "Sony",
        data: 40
    }, {
        label: "Lenovo",
        data: 55
    }];

    $.plot($(".dashboard_v4_advertise"), dataPie, {
        series: {
            pie: {
                innerRadius: 0.7,
                show: true,
                stroke: {
                    width: 0.1,
                    color: '#ffffff'
                }
            }

        },

        legend: {
            show: true
        },
        grid: {
            hoverable: true,
            clickable: true
        },

        colors: ["#fae3c4", "#f0b9b8", "#b5dfb5"]
    });

    //daily sale
    jQuery('.vertical-bottom .progress-fill span').each(function(){
        var percent = jQuery(this).html();
        var pTop = 100 - ( percent.slice(0, percent.length - 1) ) + "%";
        jQuery(this).parent().css({
            'height' : percent,
            'top' : pTop

        });
    });

    //monthly
    var target = document.getElementById('dashboard_v4_monthly_expense');
    setInterval(function () {
        var random = Math.round(1e3 * Math.random()),
            options = {
                lines: 12,
                angle: 0,
                lineWidth: 0.48,
                pointer: {
                    length: 0.6,
                    strokeWidth: 0.03,
                    color: '#464646'
                },
                colorStart: "#36A9E1",
                colorStop: "#36A9E1",
                strokeColor: '#E0E0E0'
            };
        var gauge = new Gauge(target).setOptions(options);
        gauge.maxValue = 1000;
        random > 700 ? (options.colorStart = "#e91e63",
            options.colorStop = "#e91e63") : 300 > random && (options.colorStart = "#4caf50",
            options.colorStop = "#4caf50");
        gauge.setOptions(options).set(random);
        gauge.setTextField(document.getElementById("dashboard_v4_expense_text"));
    }, 2000);


    /*---- vector map ----*/
    $(function () {
        $(".mapcontainer").mapael({
            map: {
                name: "world_countries",
                defaultArea: {
                    attrs: {
                        fill: "#f4f4e8"
                        , stroke: "#ced8d0"
                    }
                }
                // Default attributes can be set for all links
                , defaultLink: {
                    factor: 0.4
                    , attrsHover: {
                        stroke: "#a4e100"
                    }
                }
                , defaultPlot: {
                    text: {
                        attrs: {
                            fill: "#000"
                        },
                        attrsHover: {
                            fill: "#000"
                        }
                    }
                }
            },
            plots: {
                'paris': {
                    latitude: 48.86,
                    longitude: 2.3444,
                    tooltip: {content: "Paris"}
                },
                'newyork': {
                    latitude: 40.667,
                    longitude: -73.833,
                    tooltip: {content: "New york"}
                },
                'sanfrancisco': {
                    latitude: 37.792032,
                    longitude: -122.394613,
                    tooltip: {content: "San Francisco"}
                },
                'brasilia': {
                    latitude: -15.781682,
                    longitude: -47.924195,
                    tooltip: {content: "Brasilia"}
                },
                'india': {
                    latitude: 20.5937,
                    longitude: 78.9629,
                    tooltip: {content: "India"}
                },
                'roma': {
                    latitude: 41.827637,
                    longitude: 12.462732,
                    tooltip: {content: "Roma"}
                },
                'miami': {
                    latitude: 25.789125,
                    longitude: -80.205674,
                    tooltip: {content: "Miami"}
                },
                'tokyo': {
                    latitude: 35.687418,
                    longitude: 139.692306,
                    tooltip: {content: "tokyo"}
                },
                'Sydney': {
                    latitude: -33.917,
                    longitude: 151.167,
                    tooltip: {content: "Sydney"}
                },
                'Russian': {
                    latitude: 61.5240,
                    longitude: 105.3188,
                    tooltip: {content: "Russian"}
                }
            }
        });
    });
    /*---- Vector map ----*/

    //slider
    if ($('.tile-active').size() > 0) {
        var tileMoveDuration = 1500;
        var tileDefaultStop = 5000;

        var tileGoUp = function(el, stop1, stop2, height) {
            $(el).children('.tile').animate({top: '-='+ height +'px'}, tileMoveDuration);
            setTimeout(function(){ tileGoDown(el, stop1, stop2, height); }, stop2 + tileMoveDuration);
        };

        var tileGoDown = function(el, stop1, stop2, height) {
            $(el).children('.tile').animate({top: '+='+ height +'px'}, tileMoveDuration);
            setTimeout(function(){ tileGoUp(el, stop1, stop2, height); }, stop1 + tileMoveDuration);
        };

        $('.tile-active').each(function(index, el){
            var tile1, tile2, stop1, stop2, height;

            tile1 = $(this).children('.tile').first();
            tile2 = $(this).children('.tile').last();
            stop1 = $(tile1).data('stop');
            stop2 = $(tile2).data('stop');
            height = $(tile1).outerHeight();

            if (stop1 == undefined) {
                stop1 = tileDefaultStop;
            }
            if (stop2 == undefined) {
                stop2 = tileDefaultStop;
            }

            setTimeout(function(){ tileGoUp(el, stop1, stop2, height); }, stop1);
        });
    }
    var app_demo_dashboard = {
        rickshaw: function() {
            if ($("#dashboard-chart-line").length > 0) {
                {
                    var a = [{
                            x: 1,
                            y: 14
                        }, {
                            x: 1,
                            y: 12
                        }, {
                            x: 2,
                            y: 18
                        }, {
                            x: 3,
                            y: 17
                        }, {
                            x: 4,
                            y: 15
                        }, {
                            x: 5,
                            y: 11
                        }, {
                            x: 6,
                            y: 15
                        }, {
                            x: 7,
                            y: 13
                        }, {
                            x: 8,
                            y: 16
                        }, {
                            x: 9,
                            y: 18
                        }, {
                            x: 10,
                            y: 16
                        }, {
                            x: 11,
                            y: 18
                        }, {
                            x: 12,
                            y: 16
                        }, {
                            x: 13,
                            y: 15
                        }, {
                            x: 14,
                            y: 15
                        }, {
                            x: 15,
                            y: 15
                        }, {
                            x: 16,
                            y: 17
                        }, {
                            x: 17,
                            y: 16
                        }, {
                            x: 18,
                            y: 19
                        }, {
                            x: 19,
                            y: 20
                        }, {
                            x: 20,
                            y: 22
                        }, {
                            x: 21,
                            y: 23
                        }, {
                            x: 22,
                            y: 21
                        }, {
                            x: 23,
                            y: 18
                        }, {
                            x: 24,
                            y: 19
                        }, {
                            x: 25,
                            y: 15
                        }, {
                            x: 26,
                            y: 16
                        }, {
                            x: 27,
                            y: 17
                        }, {
                            x: 28,
                            y: 19
                        }, {
                            x: 29,
                            y: 22
                        }, {
                            x: 30,
                            y: 24
                        }],
                        e = [{
                            x: 0,
                            y: 12
                        }, {
                            x: 1,
                            y: 10
                        }, {
                            x: 2,
                            y: 14
                        }, {
                            x: 3,
                            y: 15
                        }, {
                            x: 4,
                            y: 11
                        }, {
                            x: 5,
                            y: 9
                        }, {
                            x: 6,
                            y: 14
                        }, {
                            x: 7,
                            y: 12
                        }, {
                            x: 8,
                            y: 13
                        }, {
                            x: 9,
                            y: 16
                        }, {
                            x: 10,
                            y: 15
                        }, {
                            x: 11,
                            y: 16
                        }, {
                            x: 12,
                            y: 13
                        }, {
                            x: 13,
                            y: 10
                        }, {
                            x: 14,
                            y: 8
                        }, {
                            x: 15,
                            y: 11
                        }, {
                            x: 16,
                            y: 13
                        }, {
                            x: 17,
                            y: 15
                        }, {
                            x: 18,
                            y: 16
                        }, {
                            x: 19,
                            y: 19
                        }, {
                            x: 20,
                            y: 20
                        }, {
                            x: 21,
                            y: 19
                        }, {
                            x: 22,
                            y: 17
                        }, {
                            x: 23,
                            y: 14
                        }, {
                            x: 24,
                            y: 15
                        }, {
                            x: 25,
                            y: 12
                        }, {
                            x: 26,
                            y: 14
                        }, {
                            x: 27,
                            y: 13
                        }, {
                            x: 28,
                            y: 15
                        }, {
                            x: 29,
                            y: 18
                        }, {
                            x: 30,
                            y: 21
                        }],
                        t = new Rickshaw.Graph({
                            element: document.getElementById("dashboard-chart-line"),
                            renderer: "lineplot",
                            min: 5,
                            max: 25,
                            padding: {
                                top: 10
                            },
                            series: [{
                                data: a,
                                color: "#2D3349",
                                name: "Purchase click"
                            }, {
                                data: e,
                                color: "#76AB3C",
                                name: "Sales"
                            }]
                        });
                    new Rickshaw.Graph.Axis.X({
                        graph: t,
                        orientation: "bottom",
                        element: document.querySelector("#xaxis")
                    }), new Rickshaw.Graph.Axis.Y({
                        graph: t,
                        orientation: "left",
                        element: document.querySelector("#yaxis")
                    })
                }
                new Rickshaw.Graph.HoverDetail({
                    graph: t,
                    formatter: function(a, e, t) {
                        var n = '<span class="detail_swatch" style="background-color: ' + a.color + '"></span>',
                            i = n + a.name + ": " + parseInt(t) + "<br>";
                        return i
                    }
                }), t.render();
                var n = function() {
                    t.configure({
                        width: $("#dashboard-chart-line").width(),
                        height: $("#dashboard-chart-line").height()
                    }), t.render()
                };

                var random = new Rickshaw.Fixtures.RandomData(30);


                window.addEventListener("resize", n), n()
            }
            function addRandomData(chart) {
                var data = {
                    one: Math.floor(Math.random() * 40) + 120
                };
                chart.series.addData(data);
            }
        }
    };
    $(function() {
        app_demo_dashboard.rickshaw()
    });



}(document, window, jQuery);