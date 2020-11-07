!function (document, window, $) {
    "use strict";

    (function () {
        for (var seriesData = [[], [], []], random = new Rickshaw.Fixtures.RandomData(150), i = 0; 150 > i; i++)
            random.addData(seriesData);
        var $element = $("#linestoggling"), graph = new Rickshaw.Graph({
                element: $element.get(0),
                width: $element.width(),
                height: 300,
                renderer: "line",
                series: [
                    {
                        color: "#449d44",
                        data: seriesData[1],
                        name: "Sales"
                    }
                    ,
                    {
                        color: "#31b0d5",
                        data: seriesData[2],
                        name: "Marketing"
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

    /*---- Line chart ----*/
    $(".sparkline-line").sparkline("html", {
        height: "20px",
        width: "65px",
        lineColor: ['#449d44'],
        fillColor: ['#82ce82']
    });

    /*---- Bar chart ----*/
    $(".stackline-bar").sparkline("html", {
        type: "bar",
        height: "100px",
        barWidth: 10,
        barSpacing: 5,
        barColor: ['#087380'],
        negBarColor: ['#c9302c'],
        stackedBarColor: ['#087380', "#c9302c"]
    });

    $(".sparkline-pie").sparkline("html", {
        type: "pie",
        height: "100px",
        sliceColors: ['#087380', '#057b8a', '#069caf']
    });

    /*---- inline range chart ----*/
    $(".sparkline-inlinerange").sparkline("html", {
        fillColor: !1,
        height: "100px",
        width: "100px",
        lineColor: ['#087380'],
        spotColor: ['#00e3ff'],
        minSpotColor: ['#00e3ff'],
        maxSpotColor: ['#00e3ff'],
        normalRangeColor: ['transparent'],
        normalRangeMin: -1,
        normalRangeMax: 8
    });


    function chart_order_survey() {

        var d1 = [],
            series = Math.floor(Math.random() * 6) + 3;

        d1[0] = {
            label: "Success",
            data: Math.floor(Math.random() * 100) + 1
        };
        d1[1] = {
            label: "Pending",
            data: Math.floor(Math.random() * 100) + 1
        };
        d1[2] = {
            label: "Not available",
            data: Math.floor(Math.random() * 100) + 1
        };
        d1[3] = {
            label: "Progress",
            data: Math.floor(Math.random() * 100) + 1
        };
        d1[4] = {
            label: "Others",
            data: Math.floor(Math.random() * 100) + 1
        };
        $.plot('#order-chart', d1, {
            series : {
                pie : {
                    innerRadius : 0.4,
                    show : true,
                    stroke : {
                        width : 0
                    },
                    label : {
                        show : true,
                        threshold : 0.05
                    }
                }
            },
            colors : ['#15b315','#febf34','#ff4a5d','#F2784B','#363b5b'],
            grid : {
                hoverable : true
            }
        });
    }

    chart_order_survey();

    var myChart2 = echarts.init(document.getElementById('sales-data-chart'));
    myChart2.setOption({
        tooltip : {
            trigger: 'axis'
        },
        resize: true,
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: false, readOnly: false},
                magicType : {show: false, type: ['line', 'bar']},
                restore : {show: false},
                saveAsImage : {show: false}
            }
        },
        legend: {
            data:['This week', 'Last week'],
            selectedMode:false
        },
        xAxis : [
            {
                type : 'category',
                data : ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday']
            }
        ],
        yAxis : [
            {
                type : 'value',
                min : 200,
                max : 450
            }
        ],
        series : [
            {
                name:'This week',
                type:'line',
                data:[400, 374, 251, 300, 420, 400, 440]
            },
            {
                name:'Last week',
                type:'line',
                symbol:'none',
                itemStyle:{
                    normal:{
                        lineStyle: {
                            width:1,
                            type:'dashed'
                        }
                    }
                },
                data:[320, 332, 301, 334, 360, 330, 350]
            },
            {
                name:'Last week 2',
                type:'bar',
                stack: '1',
                barWidth: 6,
                itemStyle:{
                    normal:{
                        color:'rgba(0,0,0,0)'
                    },
                    emphasis:{
                        color:'rgba(0,0,0,0)'
                    }
                },
                data:[320, 332, 251, 300, 360, 330, 350]
            },
            {
                name:'Variety',
                type:'bar',
                stack: '1',
                data:[
                    80, 42,
                    {value : 50, itemStyle:{ normal:{color:'red'}}},
                    {value : 34, itemStyle:{ normal:{color:'red'}}},
                    60, 70, 90
                ]
            }
        ]

    });

    var myChart3 = echarts.init(document.getElementById('left-right-chart'));
    var labelRight = {normal: {label : {position: 'right'}}};
    myChart3.setOption({
        tooltip : {
            trigger: 'axis',
            axisPointer : {
                type : 'shadow'
            }
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: false},
                dataView : {show: false, readOnly: false},
                magicType : {show: false, type: ['line', 'bar']},
                restore : {show: false},
                saveAsImage : {show: false}
            }
        },
        grid: {
            y: 80,
            y2: 30
        },
        xAxis : [
            {
                type : 'value',
                position: 'top',
                splitLine: {lineStyle:{type:'dashed'}},
            }
        ],
        yAxis : [
            {
                type : 'category',
                axisLine: {show: false},
                axisLabel: {show: false},
                axisTick: {show: false},
                splitLine: {show: false},
                data : ['ten', 'nine', 'eight', 'seven', 'six', 'five', 'four', 'three', 'two', 'one']
            }
        ],
        series : [
            {
                name:'Living expenses',
                type:'bar',
                stack: 'Amount',
                itemStyle : { normal: {
                    color: 'orange',
                    borderRadius: 5,
                    label : {
                        show: true,
                        position: 'left',
                        formatter: '{b}'
                    }
                }},
                data:[
                    {value:-0.07, itemStyle:labelRight},
                    {value:-0.09, itemStyle:labelRight},
                    0.2, 0.44,
                    {value:-0.23, itemStyle:labelRight},
                    0.08,
                    {value:-0.17, itemStyle:labelRight},
                    0.47,
                    {value:-0.36, itemStyle:labelRight},
                    0.18
                ]
            }
        ]

    });

    var myChart4 = echarts.init(document.getElementById('straight-line-chart'));
    myChart4.setOption({
        tooltip : {
            trigger: 'axis',
            axisPointer : {
                type : 'shadow'
            }
        },
        resize: true,
        legend: {
            data:['Show','Mail','Order','Video','Search']
        },
        toolbox: {
            show : true,
            orient: 'vertical',
            x: 'right',
            y: 'center',
            feature : {
                mark : {show: true},
                dataView : {show: false, readOnly: false},
                magicType : {show: false, type: ['line', 'bar', 'stack', 'tiled']},
                restore : {show: false},
                saveAsImage : {show: false}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                data : ['January','February','March']
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'Show',
                type:'bar',
                data:[320, 332, 301]
            },
            {
                name:'Mail',
                type:'bar',
                stack: 'advertising',
                data:[120, 132, 101]
            },
            {
                name:'Order',
                type:'bar',
                stack: 'advertising',
                data:[220, 182, 191]
            },
            {
                name:'Video',
                type:'bar',
                stack: 'advertising',
                data:[150, 232, 201]
            },
            {
                name:'Search',
                type:'bar',
                data:[862, 1018, 1679],
                markLine : {
                    itemStyle:{
                        normal:{
                            lineStyle:{
                                type: 'dashed'
                            }
                        }
                    },
                    data : [
                        [{type : 'min'}, {type : 'max'}]
                    ]
                }
            }
        ]

    });

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

    /*---- live chart ----*/
    var ctx1 = document.getElementById("crm-chart").getContext("2d");
    var myChart = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [{
                backgroundColor: "#efefef",
                label: "Employees",
                data: [75, 80, 76, 68, 73, 62, 80],
                borderColor: "rgb(8, 115, 128)",
                borderWidth: 3,
                strokeColor: "#FFF",
                pointColor: "#fff",
                pointBorderColor: "rgb(8, 115, 128)",
                pointBackgroundColor: "#087380",
                pointBorderWidth: 3,
                pointHoverBorderWidth: 4,
                pointRadius: 7
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
        e(myChart.data.datasets[0].data, 50, 80)
    }, 3500);

    /*---- live chart ----*/

    window.onresize = function () {
        myChart2.resize();
        myChart3.resize();
        myChart4.resize();
    }

}(document, window, jQuery);
