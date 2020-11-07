!function (document, window, $) {
    "use strict";

    var randomScalingFactorPie = function() {
        return Math.round(Math.random() * 100);
    };

    var configpie = {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    randomScalingFactorPie(),
                    randomScalingFactorPie(),
                    randomScalingFactorPie(),
                ],
                backgroundColor: [
                    window.chartColors.red,
                    window.chartColors.yellow,
                    window.chartColors.green,
                ],
                label: 'Dataset 1'
            }],
            labels: [
                "Total Sales",
                "New Customer",
                "Customers"
            ]
        },
        options: {
            responsive: true
        }
    };
    var ctxpie = document.getElementById("chart-area").getContext("2d"),
        myPie = new Chart(ctxpie, configpie);




    var randomScalingFactorDoughnut = function() {
        return Math.round(Math.random() * 100);
    };
    var configdoughnut = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    randomScalingFactorDoughnut(),
                    randomScalingFactorDoughnut(),
                    randomScalingFactorDoughnut(),
                ],
                backgroundColor: [
                    window.chartColors.yellow,
                    window.chartColors.red,
                    window.chartColors.green,
                ],
                label: 'Dataset 1'
            }],
            labels: [
                "Yellow",
                "Red",
                "Green"
            ]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Site all trafic legend'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };

    var ctxdoughnut = document.getElementById("chart-douhnut").getContext("2d"),
        myDoughnut = new Chart(ctxdoughnut, configdoughnut);




    var randomScalingFactorradar = function() {
        return Math.round(Math.random() * 100);
    };

    var color = Chart.helpers.color;
    var config = {
        type: 'radar',
        data: {
            labels: [ "Hotel", "House", "Office", "Farm", "Rent"],
            datasets: [{
                label: "Total Conver.",
                backgroundColor: color(window.chartColors.green).alpha(0.2).rgbString(),
                borderColor: window.chartColors.green,
                pointBackgroundColor: window.chartColors.green,
                data: [
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar()
                ]
            }, {
                label: "Rate Conver.",
                backgroundColor: color(window.chartColors.yellow).alpha(0.2).rgbString(),
                borderColor: window.chartColors.yellow,
                pointBackgroundColor: window.chartColors.yellow,
                data: [
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar()
                ]
            }, {
                label: "Advertisment",
                backgroundColor: color(window.chartColors.red).alpha(0.2).rgbString(),
                borderColor: window.chartColors.red,
                pointBackgroundColor: window.chartColors.red,
                data: [
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar(),
                    randomScalingFactorradar()
                ]
            },]
        },
        options: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Chart.js Radar Chart'
            },
            scale: {
                ticks: {
                    beginAtZero: true
                }
            }
        }
    };

    window.onload = function() {
        window.myRadar = new Chart(document.getElementById("canvas-polar"), config);
    };



    // var randomScalingFactorProperties = function() {
    //     return Math.round(Math.random() * 100);
    // };
    //
    // var chartData = {
    //     labels: ["January", "February", "March", "April", "May", "June", "July"],
    //     datasets: [{
    //         type: 'line',
    //         label: 'Dataset 1',
    //         borderColor: window.chartColors.blue,
    //         borderWidth: 2,
    //         fill: false,
    //         data: [
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties()
    //         ]
    //     }, {
    //         type: 'bar',
    //         label: 'Dataset 2',
    //         backgroundColor: window.chartColors.red,
    //         data: [
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties()
    //         ],
    //         borderColor: 'white',
    //         borderWidth: 2
    //     }, {
    //         type: 'bar',
    //         label: 'Dataset 3',
    //         backgroundColor: window.chartColors.green,
    //         data: [
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties(),
    //             randomScalingFactorProperties()
    //         ]
    //     }]
    //
    // };
    // var ctxproperties = document.getElementById("canvas-stats").getContext("2d"),
    //     myMixedChart = new Chart(ctxproperties, {
    //         type: 'bar',
    //         data: chartData,
    //         options: {
    //             responsive: true,
    //             title: {
    //                 display: true,
    //                 text: 'Properties stats legend'
    //             },
    //             tooltips: {
    //                 mode: 'index',
    //                 intersect: true
    //             }
    //         }
    //     });
    //
    // function e(a, b, c) {
    //     a.push(Math.floor(Math.random() * (c - b + 1)) + b), a.shift(), myMixedChart.update()
    // }
    //
    // setInterval(function() {
    //     e(myMixedChart.data.datasets[0].data, 25, 50)
    //     e(myMixedChart.data.datasets[1].data, 35, 60)
    //     e(myMixedChart.data.datasets[2].data, 45, 75)
    // }, 3500);




    new Chartist.Line('.ct-chart', {
        labels: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        series: [
            [5, -4, 3, 7, -10, 4, -10]
        ]
    }, {
        showArea: true,
        axisY: {
            onlyInteger: true
        },
        plugins: [
            Chartist.plugins.ctThreshold({
                threshold: 4
            })
        ]
    });



    $(function() {


        var data = [],
            totalPoints = 300;

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


        var updateInterval = 30;
        $("#updateInterval").val(updateInterval).change(function () {
            var v = $(this).val();
            if (v && !isNaN(+v)) {
                updateInterval = +v;
                if (updateInterval < 1) {
                    updateInterval = 1;
                } else if (updateInterval > 2000) {
                    updateInterval = 2000;
                }
                $(this).val("" + updateInterval);
            }
        });

        var plot = $.plot("#placeholder", [ getRandomData() ], {
            markings: [
                {xaxis: { from: 0, to: 6 },color: "#C11B17"},
                {xaxis: { from: 6, to: 100},color: "#FDD017"}
            ],
            grid: {borderColor: "#e5e5e5"},
            series: {
                shadowSize: 0,
                lines: {
                    fill: true,
                    fillColor: "rgba(8, 115, 128, 0.1)",
                }
            },
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


            plot.draw();
            setTimeout(update, updateInterval);
        }

        update();


    });


    new Chartist.Bar('.ct-chart-stats', {
        labels: ['JA', 'FA', 'MA', 'AP', 'MA', 'JU', 'JU', 'AU'],
        series: [
            [800000, 1200000, 1400000, 1300000, 1300000,800000, 1200000, 1400000],
            [200000, 400000, 500000, 300000, 300000,800000, 1200000, 1400000],
            [100000, 200000, 400000, 600000, 600000,800000, 1200000, 1400000]
        ]
    }, {
        stackBars: true,
        axisY: {
            labelInterpolationFnc: function(value) {
                return (value / 1000) + 'k';
            }
        }
    }).on('draw', function(data) {
        if(data.type === 'bar') {
            var viewportWidth = $(window).width();
            if (viewportWidth < 767) {
                data.element.attr({
                    style: 'stroke-width: 10px'
                });
            }else{
                data.element.attr({
                    style: 'stroke-width: 40px'
                });
            }
        }
    });


}(document, window, jQuery);









