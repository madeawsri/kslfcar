!function (document, window, $) {
    "use strict";
    /*---- Bar chart ----*/
    /*$(".stackline-bar").sparkline("html", {
     type: "bar",
     height: "50px",
     barWidth: 10,
     barSpacing: 5,
     barColor: ['#333'],
     negBarColor: ['#c9302c'],
     stackedBarColor: ['#087380', "#c9302c"]
     });*/
    $(".stackline-bar").sparkline("html", {
        height: "50px",
        width: "100px",
        lineColor: ['#333'],
        fillColor: ['transparent'],
        minSpotColor: !1,
        maxSpotColor: !1,
        spotColor: ['#333'],
        spotRadius: 2
    });

    /*---- Line chart ----*/
    $(".sparkline-line").sparkline("html", {
        height: "50px",
        width: "100px",
        lineColor: ['#333'],
        fillColor: ['transparent']
    });

    /*---- inline range chart ----*/
    $(".sparkline-inlinerange").sparkline("html", {
        fillColor: !1,
        height: "50px",
        width: "100px",
        lineColor: ['#333'],
        spotColor: ['#333'],
        minSpotColor: ['#333'],
        maxSpotColor: ['#333'],
        normalRangeColor: ['transparent'],
        normalRangeMin: -1,
        normalRangeMax: 8
    });

    /*---- line custom chart ----*/
    $(".sparkline-linecustom").sparkline("html", {
        height: "50px",
        width: "100px",
        lineColor: ['#333'],
        fillColor: ['transparent'],
        minSpotColor: !1,
        maxSpotColor: !1,
        spotColor: ['#333'],
        spotRadius: 2
    });

    /*---- Carousel ----*/
    $("#owl-full").owlCarousel({
        navigation: true,
        slideSpeed: 400,
        loop: true,
        autoplay: true,
        dots: false,
        autoplayTimeout: 3000,
        autoplayHoverPause: true,
        paginationSpeed: 500,
        items: 1,
    });

    /*---- operation ----*/
    $(function () {
        // We use an inline data source in the example, usually data would
        // be fetched from a server
        var data = [], totalPoints = 300;

        function getRandomData() {
            if (data.length > 0)
                data = data.slice(1);

            while (data.length < totalPoints) {
                var prev = data.length > 0 ? data[data.length - 1] : 50,
                    y = prev + Math.random() * 10 - 5;
                if (y < 0) {
                    y = 0;
                } else if (y > 100) {
                    y = 100;
                }
                data.push(y);
            }

            var res = [];
            for (var i = 0; i < data.length; ++i) {
                res.push([i, data[i]])
            }
            return res;
        }

        // Set up the control widget
        var updateInterval = 50;
        var plot = $.plot("#liveoperations", [getRandomData()], {
            series: {
                shadowSize: 0	// Drawing is faster without shadows
            },
            grid: {borderWidth: 0, labelMargin: 0, axisMargin: 0, minBorderMargin: 0},
            yaxis: {
                min: 0,
                max: 100
            },
            xaxis: {
                show: false
            },
            colors: ["#087380"]
        });

        function update() {
            plot.setData([getRandomData()]);
            // Since the axes don't change, we don't need to call plot.setupGrid()
            plot.draw();
            setTimeout(update, updateInterval);
        }

        update();
    });

    /*---- Todays operation ----*/
    $(function () {
        // We use an inline data source in the example, usually data would
        // be fetched from a server
        var data1 = [], totalPoints1 = 40;

        function getRandomData1() {
            if (data1.length > 0)
                data1 = data1.slice(1);

            while (data1.length < totalPoints1) {
                var prev1 = data1.length > 0 ? data1[data1.length - 1] : 50,
                    y = prev1 + Math.random() * 10 - 5;
                if (y < 0) {
                    y = 0;
                } else if (y > 100) {
                    y = 100;
                }
                data1.push(y);
            }

            var res1 = [];
            for (var i = 0; i < data1.length; ++i) {
                res1.push([i, data1[i]])
            }
            return res1;
        }

        // Set up the control widget
        var updateInterval1 = 500;
        var plot1 = $.plot("#visited_patient_chart", [getRandomData1()], {
            series: {
                shadowSize: 0	// Drawing is faster without shadows
            },
            grid: {borderWidth: 0, labelMargin: 0, axisMargin: 0, minBorderMargin: 0},
            yaxis: {
                min: 0,
                max: 100
            },
            xaxis: {
                show: false
            },
            colors: ["#d9534f"]
        });

        function update1() {
            plot1.setData([getRandomData1()]);
            // Since the axes don't change, we don't need to call plot.setupGrid()
            plot1.draw();
            setTimeout(update1, updateInterval1);
        }

        update1();
    });

    /*---- Donut-color ----*/
    var donut = Morris.Donut({
        element: 'donut-color',
        data: [
            {value: 40, label: 'Satisfied'},
            {value: 35, label: 'Neutral'},
            {value: 25, label: 'Unsatisfied'}
        ],
        backgroundColor: '#ccc',
        labelColor: '#449d44',
        colors: ['#449d44', '#c9302c', '#ec971f'],
        resize: true,
        formatter: function (x) {
            return x + "%"
        }
    });
    //patient-in chart
    var bar = Morris.Bar({
        element: 'patient_in',
        data: [
            {x: 'JAN', OPD: 30, ICU: 25},
            {x: 'FEB', OPD: 29, ICU: 39},
            {x: 'MAR', OPD: 15, ICU: 20},
            {x: 'APR', OPD: 27, ICU: 10},
            {x: 'MAY', OPD: 48, ICU: 35},
            {x: 'JUN', OPD: 20, ICU: 8},
            {x: 'JUL', OPD: 50, ICU: 52},
            {x: 'AUG', OPD: 30, ICU: 40},
            {x: 'SEP', OPD: 45, ICU: 20},
            {x: 'OCT', OPD: 15, ICU: 10},
            {x: 'NAV', OPD: 33, ICU: 35},
            {x: 'DEC', OPD: 25, ICU: 15}
        ],
        xkey: 'x',
        ykeys: ['OPD', 'ICU'],
        labels: ['OPD', 'ICU'],
        barColors: ['#2d8994', '#ffcb80'],
        resize: true,
        stack: true,
        redraw: true,
        legend: {
            display: true
        }
    });

    /*---- admitted ----*/
    var month = Morris.Bar({
        element: 'admitted_chart',
        data: [
            {days: 'MON', Patient: 4},
            {days: 'TUE', Patient: 3},
            {days: 'WED', Patient: 2},
            {days: 'THR', Patient: 1},
            {days: 'FRI', Patient: 2},
            {days: 'SAT', Patient: 3},
            {days: 'SUN', Patient: 1}
        ],
        xkey: 'days',
        ykeys: ['Patient'],
        labels: ['Patient'],
        xLabelAngle: 86,
        hideHover: "auto",
        barColors: ['#087380'],
        resize: true,
        stack: true,
        redraw: true
    });

    /*---- Discharge chart ----*/
    new Chartist.Line('.discharge_chart', {
        labels: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', 'Sun'],
        series: [
            [12, 9, 7, 8, 5, 10, 11]
        ]
    }, {
        fullWidth: true,
        chartPadding: {
            right: 40
        }
    });

    /*---- Surgury chart ----*/
    Morris.Area({
        element: 'surgeries_chart',
        resize: true,
        data: [
            {x: '2013', y: 15},
            {x: '2014', y: 20},
            {x: '2015', y: 32},
            {x: '2016', y: 22},
            {x: '2017', y: 37}
        ],
        xkey: 'x',
        ykeys: ['y', 'z'],
        labels: ['Y', 'Z'],
        lineColors: ['#36a9e1', 'z']
    });

    /*---- Medical chart ----*/
    /*var chart = new Chartist.Line('.medical_chart', {
     labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
     series: [
     [5, 10, 15, 13, 17]
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
     });*/

    new Chartist.Line('.medical_chart', {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
        series: [
            [5, 10, 15, 13, 17]
        ]
    }, {
        low: 0,
        showArea: true
    });
    /*---- earning chart ----*/
    Morris.Area({
        element: 'earning_chart',
        data: [{
            period: '2011',
            Quality: 70,
            Recommend:100

        }, {
            period: '2012',
            Quality: 120,
            Recommend:160

        }, {
            period: '2013',
            Quality: 110,
            Recommend:140

        }, {
            period: '2014',
            Quality: 180,
            Recommend:130

        }, {
            period: '2015',
            Quality: 260,
            Recommend:150
        }, {
            period: '2016',
            Quality: 230,
            Recommend:200
        },
            {
                period: '2017',
                Quality: 280,
                Recommend:210

            }],
        xkey: 'period',
        ykeys: ['Quality','Recommend'],
        labels: ['Quality','World recommend'],
        pointSize: 0,
        fillOpacity: 0.5,
        pointStrokeColors:['#b4becb'],
        behaveLikeLine: true,
        gridLineColor: '#e0e0e0',
        lineWidth: 0,
        smooth: false,
        hideHover: 'auto',
        lineColors: ['#087380','#5cb85c'],
        resize: true

    });

    /*---- growth chart ----*/
    var myChart1 = echarts.init(document.getElementById('multipal-funnels'));
    myChart1.setOption({
        color: [
            '#F2784B',
            '#087380',
            '#ec407a',
            '#eb6d6d',
            '#6d5cae'
        ],
        tooltip: {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c}%"
        },
        resize: true,
        toolbox: {
            show: true,
            feature: {
                mark: {show: false},
                dataView: {show: false, readOnly: false},
                restore: {show: false},
                saveAsImage: {show: false}
            }
        },
        legend: {
            data: ['aa', 'bb', 'cc', 'dd', 'ee'],
            show: false
        },
        hoverLink: false,
        calculable: true,
        series: [
            {
                name: 'expected',
                type: 'funnel',
                sort: 'ascending',
                x: '10%',
                width: '80%',
                itemStyle: {
                    normal: {
                        label: {
                            formatter: '{b}'
                        },
                        labelLine: {
                            show: false
                        }
                    },
                    emphasis: {
                        label: {
                            position: 'inside',
                            formatter: '{b} : {c}%',
                            show: false
                        }
                    }
                },
                data: [
                    {value: 100, name: '2012'},
                    {value: 80, name: '2013'},
                    {value: 40, name: '2015'},
                    {value: 20, name: '2016'},
                    {value: 60, name: '2014'}
                ]
            },
            {
                name: 'actual',
                type: 'funnel',
                x: '10%',
                sort: 'ascending',
                width: '80%',
                maxSize: '80%',
                itemStyle: {
                    normal: {
                        borderColor: '#fff',
                        borderWidth: 2,
                        label: {
                            position: 'inside',
                            formatter: '{c}%',
                            textStyle: {
                                color: '#fff'
                            }
                        }
                    },
                    emphasis: {
                        label: {
                            position: 'inside',
                            formatter: '{b} : {c}%',
                        }
                    }
                },
                data: [
                    {value: 80, name: '2012'},
                    {value: 50, name: '2013'},
                    {value: 5, name: '2015'},
                    {value: 10, name: '2016'},
                    {value: 30, name: '2014'}
                ]
            }
        ]

    });

    /*---- Patient's attention  ----*/
    var myChart2 = echarts.init(document.getElementById('angular-gauge'));
    myChart2.setOption({
        tooltip : {
            formatter: "{a} <br/>{b} : {c}%"
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: false},
                restore : {show: false},
                saveAsImage : {show: false}
            }
        },
        series : [
            {
                name:'Attention',
                type:'gauge',
                detail : {formatter:'{value}%'},
                data:[{value: 43}]
            }
        ]
    });

    function escapeXml(string) {
        return string.replace(/[<>]/g, function (c) {
            switch (c) {
                case '<':
                    return '\u003c';
                case '>':
                    return '\u003e';
            }
        });
    }

    var pins = {
        mo: escapeXml('<div class="map-pin red"><span>MO</span></div>'),
        fl: escapeXml('<div class="map-pin blue"><span>FL</span></div>'),
        or: escapeXml('<div class="map-pin purple"><span>OR</span></div>')
    };

    /*---- Patient OPD week ----*/
    function chartistChart() {
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


    /*---- gradiant background ----*/
    var granimInstance1 = new Granim({
        element: '#hospital_canvas1',
        name: 'hospital-gradient',
        elToSetClassOn: '.hospital_dashboard_box',
        direction: 'diagonal',
        opacity: [1, 1],
        isPausedWhenNotInView: true,
        states: {
            "default-state": {
                gradients: [
                    ['#834d9b', '#d04ed6'],
                    ['#1CD8D2', '#93EDC7']
                ],
                transitionSpeed: 12000
            }
        }
    });
    var granimInstance2 = new Granim({
        element: '#hospital_canvas2',
        name: 'hospital-gradient',
        elToSetClassOn: '.hospital_dashboard_box',
        direction: 'diagonal',
        opacity: [1, 1],
        isPausedWhenNotInView: true,
        states: {
            "default-state": {
                gradients: [
                    ['#834d9b', '#d04ed6'],
                    ['#1CD8D2', '#93EDC7']
                ],
                transitionSpeed: 12000
            }
        }
    });
    var granimInstance3 = new Granim({
        element: '#hospital_canvas3',
        name: 'hospital-gradient',
        elToSetClassOn: '.hospital_dashboard_box',
        direction: 'diagonal',
        opacity: [1, 1],
        isPausedWhenNotInView: true,
        states: {
            "default-state": {
                gradients: [
                    ['#834d9b', '#d04ed6'],
                    ['#1CD8D2', '#93EDC7']
                ],
                transitionSpeed: 12000
            }
        }
    });
    var granimInstance4 = new Granim({
        element: '#hospital_canvas4',
        name: 'hospital-gradient',
        elToSetClassOn: '.hospital_dashboard_box',
        direction: 'diagonal',
        opacity: [1, 1],
        isPausedWhenNotInView: true,
        states: {
            "default-state": {
                gradients: [
                    ['#834d9b', '#d04ed6'],
                    ['#1CD8D2', '#93EDC7']
                ],
                transitionSpeed: 12000
            }
        }
    });

    window.onresize = function () {
        myChart1.resize();
        myChart2.resize();
    }

}(document, window, jQuery);
