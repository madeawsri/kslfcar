!function (document, window, $) {
    "use strict";

    /*---- Donut formatter ----*/
    var donutformate1 = Morris.Donut({
        element: 'donut-formatter1',
        data: [
            {value: 35, label: 'Mozilla', formatted: 'at uniform 35%'},
            {value: 25, label: 'Chrome', formatted: 'lingue. 25%'},
            {value: 20, label: 'Safari', formatted: 'simplic 20%'},
            {value: 15, label: 'Opera', formatted: 'at esser 15%'},
            {value: 5, label:  'IE', formatted: 'lingue. 5%'}
        ],
        labelColor: ['#5cb85c'],
        colors: ['#5cb85c','#50cc50', '#6ede6e', '#8def8d', '#9ffd9f'],
        resize: true,
        redraw: true,
        stack: true,
        formatter: function (x, data) {
            return data.formatted;
        }
    });
    var donutformate2 = Morris.Donut({
        element: 'donut-formatter2',
        data: [
            {value: 35, label:  'India', formatted: 'lingue. 35%'},
            {value: 12, label: 'USA', formatted: 'at uniform 12%'},
            {value: 25, label: 'Canada', formatted: 'lingue. 25%'},
            {value: 19, label: 'Mexico', formatted: 'simplic 19%'},
            {value: 10, label: 'Argentina', formatted: 'at esser 10%'}
        ],
        labelColor: ['#f0ad4e'],
        colors: ['#f0ad4e', '#fbb95b', '#fdc577', '#f9d199', '#ffdfb2'],
        resize: true,
        redraw: true,
        stack: true,
        formatter: function (x, data) {
            return data.formatted;
        }
    });
    var donutformate3 = Morris.Donut({
        element: 'donut-formatter3',
        data: [
            {value: 25, label: 'Fees', formatted: 'at lingue. 25%'},
            {value: 35, label: 'Profit', formatted: 'uniform 30%'},
            {value: 20, label: 'Tax', formatted: 'simplic 22%'},
            {value: 15, label: 'Rate', formatted: 'esser 15%'},
            {value: 5, label:  'Bounce', formatted: 'lingue. 8%'}
        ],
        labelColor: ['#d9534f'],
        colors: ['#d9534f', '#e2635f', '#e86864', '#f5736e', '#ff8884'],
        resize: true,
        redraw: true,
        stack: true,
        formatter: function (x, data) {
            return data.formatted;
        }
    });

    /*---- Months bars ----*/
    var ctx = document.getElementById("month-bars");
    var month_bars = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ["M", "T", "W", "R", "F", "S", "S"],
            datasets: [{
                label: 'apples',
                data: [30, 29, 15, 27, 48, 20, 50]
            }, {
                label: 'oranges',
                data: [25, 39, 20, 10, 35, 8, 55]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            hover: {
                mode: "label"
            },
            scales: {
                xAxes: [{
                    display: false
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        min: 0,
                        max: 85
                    }
                }]
            },
            title: {
                display: false,
                fontColor: "#FFF",
                fullWidth: true,
                fontSize: 40,
                text: "82%"
            }
        }
    });

    function n(a, b, c) {
        a.push(Math.floor(Math.random() * (c - b + 1)) + b), a.shift(), month_bars.update()
    }

    setInterval(function() {
        n(month_bars.data.datasets[0].data, 25, 70)
        n(month_bars.data.datasets[1].data, 35, 80)
    }, 3500);


    /*---- Bar chart ----*/
    $(".stackline-bar").sparkline("html", {
        type: "bar",
        height: "70px",
        width: "100%",
        barWidth: 10,
        barSpacing: 5,
        barColor: ['#0a7f8e'],
        negBarColor: ['#c9302c'],
        stackedBarColor: ['#0a7f8e', "#c9302c"]
    });
    $(".stackline-bar1").sparkline("html", {
        type: "bar",
        height: "70px",
        barWidth: 29,
        barSpacing: 5,
        barColor: ['#ea689a'],
        negBarColor: ['#c9302c'],
        stackedBarColor: ['#ea689a', "#c9302c"]
    });

    /*---- Line chart ----*/
    $(".sparkline-line").sparkline("html", {
        height: "70px",
        width: "100%",
        lineColor: ['#906355'],
        fillColor: ['#986859']
    });

    $(".sparkline-line1").sparkline("html", {
        height: "70px",
        width: "100%",
        lineColor: ['#7c67cc'],
        fillColor: ['#7661c3']
    });

    /*---- inline range chart ----*/
    $(".sparkline-inlinerange").sparkline("html", {
        fillColor: !1,
        height: "70px",
        width: "234px",
        lineColor: ['#ffb498'],
        spotColor: ['#087380'],
        minSpotColor: ['#087380'],
        maxSpotColor: ['#087380'],
        normalRangeColor: ['transparent'],
        normalRangeMin: -1,
        normalRangeMax: 8
    });
    $(".sparkline-google").sparkline("html", {
        fillColor: !1,
        height: "70px",
        width: "234px",
        lineColor: ['#087380'],
        spotColor: ['#087380'],
        minSpotColor: ['#087380'],
        maxSpotColor: ['#087380'],
        normalRangeColor: ['transparent'],
        normalRangeMin: -1,
        normalRangeMax: 8
    });

    /*---- line custom chart ----*/
    $(".sparkline-linecustom").sparkline("html", {
        height: "70px",
        width: "230px",
        lineColor: ['#f9d199'],
        fillColor: ['#ffdfb2'],
        minSpotColor: !1,
        maxSpotColor: !1,
        spotColor: ['green'],
        spotRadius: 2
    });

    //svg path animation
    var chart = new Chartist.Line('.path-animation', {
        labels: ['Jan', 'Feb', 'march', 'April', 'May'],
        series: [
            [1, 5, 2, 5, 4]
        ]
    }, {
        low: 0,
        showArea: true,
        showPoint: false,
        fullWidth: true
    });
    chart.on('draw', function (data) {
        if (data.type === 'line' || data.type === 'area') {
            data.element.animate({
                d: {
                    begin: 2000 * data.index,
                    dur: 2000,
                    from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                    to: data.path.clone().stringify(),
                    easing: Chartist.Svg.Easing.easeOutQuint
                }
            });
        }
    });

    Morris.Area({
        element: 'analytics-area-chart',
        data: [{
            period: '2010',
            iphone: 0,
            ipad: 0,
            itouch: 0
        }, {
            period: '2011',
            iphone: 110,
            ipad: 75,
            itouch: 60
        }, {
            period: '2012',
            iphone: 90,
            ipad: 110,
            itouch: 125
        }, {
            period: '2013',
            iphone: 120,
            ipad: 72,
            itouch: 67
        }, {
            period: '2014',
            iphone: 90,
            ipad: 80,
            itouch: 180
        }, {
            period: '2015',
            iphone: 85,
            ipad: 140,
            itouch: 100
        }, {
            period: '2016',
            iphone: 70,
            ipad: 70,
            itouch: 70
        }


        ],
        lineColors: ['#d9534f', '#f0ad4e', '#269ba9'],
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['Site A', 'Site B', 'Site C'],
        pointSize: 0,
        lineWidth: 0,
        resize:true,
        fillOpacity: 0.8,
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        hideHover: 'auto'

    });

    // For a pie chart
    var pichart = c3.generate({
        bindto: "#traffic_overview_chart", data: {
            // iris data from R
            columns: [
                ['data1', 30],
                ['data2', 120],
            ],
            type : 'pie',
            onclick: function (d, i) { console.log("onclick", d, i); },
            onmouseover: function (d, i) { console.log("onmouseover", d, i); },
            onmouseout: function (d, i) { console.log("onmouseout", d, i); }
        }
    });

    setTimeout(function () {
        pichart.load({
            columns: [
                ["Direct", 0.2, 0.2, 0.2, 0.2, 0.2, 0.4, 0.3, 0.2, 0.2, 0.1, 0.2, 0.2, 0.1, 0.1, 0.2, 0.4, 0.4, 0.3, 0.3, 0.3, 0.2, 0.4, 0.2, 0.5, 0.2, 0.2, 0.4, 0.2, 0.2, 0.2, 0.2, 0.4, 0.1, 0.2, 0.2, 0.2, 0.2, 0.1, 0.2, 0.2, 0.3, 0.3, 0.2, 0.6, 0.4, 0.3, 0.2, 0.2, 0.2, 0.2],
                ["Referral", 1.4, 1.5, 1.5, 1.3, 1.5, 1.3, 1.6, 1.0, 1.3, 1.4, 1.0, 1.5, 1.0, 1.4, 1.3, 1.4, 1.5, 1.0, 1.5, 1.1, 1.8, 1.3, 1.5, 1.2, 1.3, 1.4, 1.4, 1.7, 1.5, 1.0, 1.1, 1.0, 1.2, 1.6, 1.5, 1.6, 1.5, 1.3, 1.3, 1.3, 1.2, 1.4, 1.2, 1.0, 1.3, 1.2, 1.3, 1.3, 1.1, 1.3],
                ["Search", 2.5, 1.9, 2.1, 1.8, 2.2, 2.1, 1.7, 1.8, 1.8, 2.5, 2.0, 1.9, 2.1, 2.0, 2.4, 2.3, 1.8, 2.2, 2.3, 1.5, 2.3, 2.0, 2.0, 1.8, 2.1, 1.8, 1.8, 1.8, 2.1, 1.6, 1.9, 2.0, 2.2, 1.5, 1.4, 2.3, 2.4, 1.8, 1.8, 2.1, 2.4, 2.3, 1.9, 2.3, 2.5, 2.3, 1.9, 2.0, 2.3, 1.8],
            ]
        });
    }, 1500);

    setTimeout(function () {
        pichart.unload({
            ids: 'data1'
        });
        pichart.unload({
            ids: 'data2'
        });
    }, 2500);

    //pageview chart
    (function () {
        for (var seriesData = [[], [], []], random = new Rickshaw.Fixtures.RandomData(150), i = 0; 150 > i; i++)
            random.addData(seriesData);
        var $element = $("#interpolateChart"), graph = new Rickshaw.Graph({
                element: $element.get(0),
                width: $element.width(),
                height: 300,
                renderer: "area",
                stroke: !0,
                series: [{
                    color: "#ef5350",
                    data: seriesData[0],
                    name: "Mexico"
                }
                ]
            }
        );
        graph.render(),
            setInterval(function () {
                    random.removeData(seriesData), random.addData(seriesData), graph.update()
                }
                ,
                2e3);
        var legend = (new Rickshaw.Graph.HoverDetail({
                graph: graph
            }
        ));

        $(window).on("resize",
            function () {
                graph.configure({
                        width: $element.width()
                    }
                ),
                    graph.render()
            }
        )
    })();

    /*---- Vector map ----*/
    $(function () {
        $(".mapcontainer").mapael({
            map: {
                name: "usa_states",
                defaultPlot: {
                    size: 30,
                    eventHandlers: {
                        mouseover: function (e, id, mapElem, textElem, elemOptions) {
                            if (typeof elemOptions.myText != 'undefined') {
                                $('.myText span').html(elemOptions.myText).css({display: 'none'}).fadeIn('slow');
                            }
                        }
                    }
                }
            },
            plots: {
                'ny': {
                    latitude: 40.717079,
                    longitude: -74.00116,
                    tooltip: {content: "New York</br>User: 1200"}
                },
                'an': {
                    latitude: 61.2108398,
                    longitude: -149.9019557,
                    tooltip: {content: "Anchorage</br>User: 3000"}
                },
                'sf': {
                    latitude: 37.792032,
                    longitude: -122.394613,
                    tooltip: {content: "San Francisco</br>User: 2400"}
                },
                'pa': {
                    latitude: 19.493204,
                    longitude: -154.8199569,
                    tooltip: {content: "Pahoa</br>User: 5000"}
                },
                'la': {
                    latitude: 34.025052,
                    longitude: -118.192006,
                    tooltip: {content: "Los Angeles</br>User: 7546"}
                },
                'dallas': {
                    latitude: 32.784881,
                    longitude: -96.808244,
                    tooltip: {content: "Dallas</br>User: 1876"}
                },
                'miami': {
                    latitude: 25.789125,
                    longitude: -80.205674,
                    tooltip: {content: "Miami</br>User: 8652"}
                },
                'washington': {
                    latitude: 38.905761,
                    longitude: -77.020746,
                    tooltip: {content: "Washington</br>User: 6789"}
                },
                'seattle': {
                    latitude: 47.599571,
                    longitude: -122.319426,
                    tooltip: {content: "Seattle</br>User: 4321"}
                },
                'MT': {
                    latitude: 44.671504,
                    longitude: -110.957968,
                    tooltip: {content: "Washington</br>User: 1189"}
                },
                'AN': {
                    latitude: 40.667013,
                    longitude: -101.465781,
                    tooltip: {content: "Anchorage</br>User: 1189"}
                },
                'Na': {
                    latitude: 38.362031,
                    longitude: -86.875938,
                    tooltip: {content: "US</br>User: 1189"}
                }
            }
        });
    });
    /*---- Vector map ----*/

    /* live chart */
    var ctx1 = document.getElementById("myChart").getContext("2d");
    var myChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
                label: "Employees",
                data: [28, 35, 36, 48, 46, 42, 60],
                borderColor: "rgba(255,255,255,1)",
                borderWidth: 2,
                strokeColor: "#FF6C23",
                pointColor: "#fff",
                pointBorderColor: "rgba(255,255,255,1)",
                pointBackgroundColor: "#f0b9b8",
                pointBorderWidth: 2,
                pointHoverBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: !0,
            maintainAspectRatio: !1,
            datasetStrokeWidth: 3,
            animation:false,
            pointDotStrokeWidth: 4,
            tooltipFillColor: "rgba(0,0,0,0.8)",
            legend: {
                display: !1
            },
            hover: {
                mode: "label"
            },
            scales: {
                xAxes: [{
                    display: !1
                }],
                yAxes: [{
                    display: !1,
                    ticks: {
                        min: 0,
                        max: 85
                    }
                }]
            },
            title: {
                display: !1,
                fontColor: "#FFF",
                fullWidth: !1,
                fontSize: 40,
                text: "82%"
            }
        }
    });

    function e(a, b, c) {
        a.push(Math.floor(Math.random() * (c - b + 1)) + b), a.shift(), myChart.update()
    }

    setInterval(function() {
        e(myChart.data.datasets[0].data, 25, 50)
    }, 3500);

}(document, window, jQuery);