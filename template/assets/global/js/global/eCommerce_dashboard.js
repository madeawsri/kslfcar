!function (document, window, $) {
    "use strict";

    $(".check_all").change(function () {
        var $this = $(this),
            $checkbox = $this.closest('.check_all').find('[type="checkbox"]');
        $checkbox.is(":checked") ? $this.closest('table').closest('.dataTables_scroll').find('.mail_message .checkbox').children('label').addClass("checked").find('input[type="checkbox"]').prop('checked', true) : $this.closest('table').closest('.dataTables_scroll').find('.mail_message .checkbox').children('label').removeClass("checked").find('input[type="checkbox"]').prop('checked', false)
    });
    $('.mail_message .checkbox').change(function () {
        var $this = $(this),
            $checkvalue = $this.find('[type="checkbox"]');
        $checkvalue.is(":checked") ? $checkvalue.parents('label').addClass("checked") : $checkvalue.parents('label').removeClass("checked");
        $this.find('[type="checkbox"]').length == $this.find('[type="checkbox"]:checked').length ? $this.closest('table').closest('.dataTables_scroll').find(".check_all").children('label').addClass('checked').find('input[type="checkbox"]').prop('checked', true) : $this.closest('table').closest('.dataTables_scroll').find(".check_all").children('label').removeClass('checked').prop('checked', false);
    });

    $(".check_all").on('change', function (e) {
        var $this = $(this);
        if ($this.is(':checked') === true) {
            $this.parents('div.dataTables_wrapper').find('[type="checkbox"]').prop('checked', true);
        }
        else {
            $this.parents('div.dataTables_wrapper').find('[type="checkbox"]').prop('checked', false);
        }
    });

    /*---- Area chart ----*/
    Morris.Area({
        element: 'area-chart',
        data: [
            {y: '2009', a: 50, b: 100, c: -50},
            {y: '2010', a: 80, b: -25, c: -10},
            {y: '2011', a: 30, b: 40, c: 100},
            {y: '2012', a: 55, b: -15, c: 10},
            {y: '2013', a: 60, b: 10, c: 20},
            {y: '2014', a: 25, b: 65, c: 50},
            {y: '2015', a: 100, b: -20, c: -40},
            {y: '2016', a: 40, b: 100, c: 40}
        ],
        xkey: 'y',
        ykeys: ['a', 'b', 'c'],
        lineWidth: '0',
        lineColors: ['#087380', '#c9302c', '#5cb85c'],
        pointSize: 0,
        hoverpointSize: 0,
        fillOpacity: 0.25,
        labels: ['A', 'B', 'C'],
        resize: true
    });

    var myChart1 = echarts.init(document.getElementById('monthly-sales-chart'));
    myChart1.setOption({
        tooltip: {
            trigger: 'axis',
            axisPointer: {
                type: 'shadow'
            }
        },
        resize: true,
        legend: {
            data: ['Mail', 'User', 'Products', 'Visit', 'Order']
        },
        toolbox: {
            show: true,
            orient: 'vertical',
            x: 'right',
            y: 'center',
            feature: {
                mark: {show: true},
                dataView: {show: false, readOnly: false},
                magicType: {show: false, type: ['line', 'bar', 'stack', 'tiled']},
                restore: {show: false},
                saveAsImage: {show: false}
            }
        },
        calculable: true,
        xAxis: [
            {
                type: 'category',
                data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']
            }
        ],
        yAxis: [
            {
                type: 'value'
            }
        ],
        series: [
            {
                name: 'Mail',
                type: 'bar',
                data: [320, 332, 301, 310, 315, 305]
            },
            {
                name: 'User',
                type: 'bar',
                stack: 'advertising',
                data: [120, 132, 101, 125, 130, 90]
            },
            {
                name: 'Products',
                type: 'bar',
                stack: 'advertising',
                data: [220, 182, 191, 180, 210, 200]
            },
            {
                name: 'Visit',
                type: 'bar',
                stack: 'advertising',
                data: [150, 232, 201, 160, 120, 210]
            },
            {
                name: 'Order',
                type: 'bar',
                data: [1018, 862 , 1323, 1560, 1679, 1520],
                markLine: {
                    itemStyle: {
                        normal: {
                            lineStyle: {
                                type: 'dashed'
                            }
                        }
                    },
                    data: [
                        [{type: 'min'}, {type: 'max'}]
                    ]
                }
            }
        ]

    });

    window.onresize = function () {
        myChart1.resize();
    }
}(document, window, jQuery);
