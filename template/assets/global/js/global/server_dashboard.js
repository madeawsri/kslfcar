!function (document, window, $) {
    "use strict";

    //Realtime Rickshaw Chart

    (function () {
        for (var seriesData = [[], [], []], random = new Rickshaw.Fixtures.RandomData(150), i = 0; 150 > i; i++)
            random.addData(seriesData);
        var $element = $("#linestoggling"), graph = new Rickshaw.Graph({
                element: $element.get(0),
                width: $element.width(),
                height: 300,
                renderer: "line",
                series: [{
                    color: "#c9302c",
                    data: seriesData[0],
                    name: "RAM"
                }
                    ,
                    {
                        color: "#449d44",
                        data: seriesData[1],
                        name: "CPU"
                    }
                    ,
                    {
                        color: "#31b0d5",
                        data: seriesData[2],
                        name: "SERVER"
                    }
                ]
            }
        );
        graph.render(),
            setInterval(function () {
                    random.removeData(seriesData), random.addData(seriesData), graph.update()
                }
                ,
                2e2);
        var legend = (new Rickshaw.Graph.HoverDetail({
                graph: graph
            }
            ),
                new Rickshaw.Graph.Legend({
                        graph: graph,
                        element: document.getElementById("linestogglingLegend")
                    }
                )),
            axes = (new Rickshaw.Graph.Behavior.Series.Toggle({
                    graph: graph,
                    legend: legend
                }
            ),
                new Rickshaw.Graph.Axis.Time({
                        graph: graph
                    }
                ));
        axes.render()
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

    (function () {
        for (var seriesData = [[], [], []], random = new Rickshaw.Fixtures.RandomData(150), i = 0; 500 > i; i++)
            random.addData(seriesData);
        var $element = $("#log_ajax"), graph = new Rickshaw.Graph({
                element: $element.get(0),
                width: $element.width(),
                height: 300,
                renderer: "scatterplot",
                series: [
                    {
                        color: "#FB8C00",
                        data: seriesData[0],
                        name: "Ruby",
                        opacity: 0.5
                    }, {
                        color: "#36A9E1",
                        data: seriesData[1],
                        name: "SQL",
                        opacity: 0.3
                    }, {
                        color: "#2C9079",
                        name: "Apache",
                        data: seriesData[2]
                    }
                ]
            }
        );
        graph.render(),
            setInterval(function () {
                    random.removeData(seriesData), random.addData(seriesData), graph.update()
                }
                ,
                2e2);
        var legend = (new Rickshaw.Graph.HoverDetail({
                graph: graph
            }
            ),
                new Rickshaw.Graph.Legend({
                        graph: graph,
                        element: document.getElementById("log_ajaxLegend")
                    }
                )),
            axes = (new Rickshaw.Graph.Behavior.Series.Toggle({
                    graph: graph,
                    legend: legend
                }
            ),
                new Rickshaw.Graph.Axis.Time({
                        graph: graph
                    }
                ));
        axes.render()
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

    /*---- sparkline ----*/
    $(".stackline-bar-white").sparkline("html", {
        type: "bar",
        height: "173px",
        barWidth: 10,
        barSpacing: 5,
        barColor: ['#fff'],
        negBarColor: ['#c9302c'],
        stackedBarColor: ['#fff', "#c9302c"]
    });
    $(".stackline-bar").sparkline("html", {
        type: "bar",
        height: "173px",
        barWidth: 10,
        barSpacing: 5,
        barColor: ['#d9534f'],
        negBarColor: ['#c9302c'],
        stackedBarColor: ['#d9534f', "#c9302c"]
    });


    /*---- Donut color ----*/
    var donutcolor = Morris.Donut({
        element: 'donut-color',
        data: [
            {value: 20, label: 'Memory'},
            {value: 30, label: 'Disk'},
            {value: 15, label: 'RAM'},
            {value: 35, label: 'CPU'}
        ],
        backgroundColor: '#ccc',
        labelColor: '#ccc',
        colors: ['#449d44', '#c9302c', '#31b0d5', '#ec971f'],
        resize: true,
        redraw: true,
        stack: true,
        formatter: function (x) {
            return x + "%"
        }
    });


    /*-------------------*/

    $(".sparkline-pie-color").sparkline("html", {
        type: "pie",
        height: "300px"
    });

    /*---- Echart ----*/
    var myChart1 = echarts.init(document.getElementById('task-data-chart'));

    var option = {
        tooltip: {
            formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
            show: true,
            feature: {
                mark: {show: false},
                restore: {show: false},
                saveAsImage: {show: false}
            }
        },
        series: [
            {
                name: 'Server',
                title: {
                    offsetCenter: ['0', '-30%'],
                    textStyle: {
                        fontSize : 12
                    }
                },
                type: 'gauge',
                detail: {formatter: '{value}%', textStyle: {fontSize: 18}},
                data: [{value: 68.05, name: 'Server'}]
            }
        ]
    };


    setInterval(function () {
        option.series[0].data[0].value = Math.floor(Math.random() * 30) + 50;
        /*option.series[0].data[0].value = (Math.random() * 100).toFixed(2) - 0;*/
        myChart1.setOption(option, true);
    }, 2000);

    /*---- Chartist ----*/
    function chartistChart() {
        //Horizontal bar chart
        new Chartist.Bar('.horizontal-chart', {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            series: [
                [5, 4, 3, 7, 5, 10, 3],
                [3, 2, 9, 5, 4, 6, 4]
            ]
        }, {
            seriesBarDistance: 10,
            reverseData: true,
            horizontalBars: true,
            axisY: {
                offset: 70
            }
        });

        //simple line chart
        new Chartist.Line('.line-chartist', {
            labels: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri'],
            series: [
                [12, 9, 7, 8, 5],
                [2, 1, 3.5, 7, 3],
                [1, 3, 4, 5, 6]
            ]
        }, {
            fullWidth: true,
            chartPadding: {
                right: 40
            }
        });
    }

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
                    tooltip: {content: "Paris<br />server: 1"}
                },
                'newyork': {
                    latitude: 40.667,
                    longitude: -73.833,
                    tooltip: {content: "New york<br />server: 2"}
                },
                'sanfrancisco': {
                    latitude: 37.792032,
                    longitude: -122.394613,
                    tooltip: {content: "San Francisco<br />server: 2"}
                },
                'brasilia': {
                    latitude: -15.781682,
                    longitude: -47.924195,
                    tooltip: {content: "Brasilia<br />server: 3"}
                },
                'india': {
                    latitude: 20.5937,
                    longitude: 78.9629,
                    tooltip: {content: "India<br />server: 1"}
                },
                'roma': {
                    latitude: 41.827637,
                    longitude: 12.462732,
                    tooltip: {content: "Roma<br />server: 2"}
                },
                'miami': {
                    latitude: 25.789125,
                    longitude: -80.205674,
                    tooltip: {content: "Miami<br />server: 1"}
                },


                // Size=0 in order to make plots invisible
                'tokyo': {
                    latitude: 35.687418,
                    longitude: 139.692306,
                    size: 0,
                    text: {content: 'Tokyo'}
                },
                'Main': {
                    latitude: -33.917,
                    longitude: 151.167,
                    size: 0,
                    text: {content: 'Main'}
                },
                'server1': {
                    latitude: 22.906561,
                    longitude: 86.840170,
                    size: 0,
                    text: {content: 'server1', position: 'left', margin: 5}
                },
                'server2': {
                    latitude: -0.390553,
                    longitude: 115.586762,
                    size: 0,
                    text: {content: 'server2'}
                },
                'server3': {
                    latitude: 44.065626,
                    longitude: 94.576079,
                    size: 0,
                    text: {content: 'server3'}
                }
            },
            // Links allow you to connect plots between them
            links: {
                'link1': {
                    factor: -0.3
                    // The source and the destination of the link can be set with a latitude and a longitude or a x and a y ...
                    , between: [{latitude: 24.708785, longitude: -5.402427}, {x: 560, y: 280}]
                    , attrs: {
                        "stroke-width": 2
                    }
                    , tooltip: {content: "Link"}
                }
                , 'parisnewyork': {
                    // ... Or with IDs of plotted points
                    factor: -0.3
                    , between: ['paris', 'newyork']
                    , attrs: {
                        "stroke-width": 2
                    }
                    , tooltip: {content: "Paris - New-York"}
                }
                , 'parissanfrancisco': {
                    // The curve can be inverted by setting a negative factor
                    factor: -0.5
                    , between: ['paris', 'sanfrancisco']
                    , attrs: {
                        "stroke-width": 4
                    }
                    , tooltip: {content: "Paris - San - Francisco"}
                }
                , 'parisbrasilia': {
                    factor: -0.8
                    , between: ['paris', 'brasilia']
                    , attrs: {
                        "stroke-width": 1
                    }
                    , tooltip: {content: "Paris - Brasilia"}
                }
                , 'romamiami': {
                    factor: 0.2
                    , between: ['roma', 'miami']
                    , attrs: {
                        "stroke-width": 4
                    }
                    , tooltip: {content: "Roma - Miami"}
                }
                , 'indiamiami': {
                    factor: -0.4
                    , between: ['india', 'miami']
                    , attrs: {
                        "stroke-width": 3
                    }
                    , tooltip: {content: "India - Miami"}
                }
                , 'Mainserver1': {
                    factor: -0.2
                    , between: ['Main', 'server1']
                    , attrs: {
                        stroke: "#a4e100",
                        "stroke-width": 3,
                        "stroke-linecap": "round",
                        opacity: 0.6
                    }
                    , tooltip: {content: "Main - server1"}
                }
                , 'Mainserver2': {
                    factor: -0.1
                    , between: ['Main', 'server2']
                    , attrs: {
                        stroke: "#a4e100",
                        "stroke-width": 8,
                        "stroke-linecap": "round",
                        opacity: 0.6
                    }
                    , tooltip: {content: "Main - server2"}
                }
                , 'Mainserver3': {
                    factor: 0.2
                    , between: ['Main', 'server3']
                    , attrs: {
                        stroke: "#a4e100",
                        "stroke-width": 4,
                        "stroke-linecap": "round",
                        opacity: 0.6
                    }
                    , tooltip: {content: "Main - server3"}
                }
                , 'Maintokyo': {
                    factor: 0.2
                    , between: ['Main', 'tokyo']
                    , attrs: {
                        stroke: "#a4e100",
                        "stroke-width": 6,
                        "stroke-linecap": "round",
                        opacity: 0.6
                    }
                    , tooltip: {content: "Main - server2"}
                }
            }
        });
    });
    /*---- Vector map ----*/

    $(window).on('resizeEnd', function () {
        chartistChart();
    });

    $(window).on('load', function () {
        chartistChart();
    });

    //create trigger to resizeEnd event
    $(window).resize(function () {
        if (this.resizeTO) clearTimeout(this.resizeTO);
        this.resizeTO = setTimeout(function () {
            $(this).trigger('resizeEnd');
        }, 500);
    });

    window.onresize = function () {
        myChart1.resize();
    }


}(document, window, jQuery);
