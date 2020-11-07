//(async() => { })().catch((err) => { jLog(err); });

var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jLoader = '<img src="' + jServerPath + '/assets/images/ajax-loader.gif" width="8px" />';
! function(document, window, $) {
    "use strict";



    jLoadDataQ();
    jLoadDataCane();




    (async() => {

        //var dataToday = await 
        jLoadDataToday();
        //var dataDump = await 
        jLoadDataDump();

        jLoadDataQIN();
        jLoadDataQOUT();
        /** total value cane */
        $xdata = dataDump.data[0];
        let td = ['t1', 'w1', 'p1', 't2', 'w2', 'p2', 'st', 'sw'];

        $('.' + td[0] + '3').html(addCommas($xdata.AT1));
        $('.' + td[1] + '3').html(addCommas($xdata.AW1));
        $('.' + td[2] + '3').html($xdata.P1);
        $('.' + td[3] + '3').html(addCommas($xdata.AT2));
        $('.' + td[4] + '3').html(addCommas($xdata.AW2));
        $('.' + td[5] + '3').html($xdata.P2);
        $('.' + td[6] + '3').html(addCommas(parseInt($xdata.AT1) + parseInt($xdata.AT2)));
        $('.' + td[7] + '3').html(addCommas(parseInt($xdata.AW1) + parseInt($xdata.AW2)));
        //jLog(dataToday);
        /** */

    })().catch((err) => { jLog(err); });



}(document, window, jQuery);


var data_out_dump;

function jLoadDataToday() {
    return $.ajax({
        cache: false,
        type: "POST",
        dataType: "json",
        url: jPath + "&mode=data-today",
        beforeSend: function() {
            $('.loader1').html(jLoader);
        },
        success: function(data, status) {

            $xdata = data.data[0];
            if (data.data.length == 0) return;
            data_out_dump = $xdata;
            let td = ['t1', 'w1', 'p1', 't2', 'w2', 'p2', 'st', 'sw'];

            $('.' + td[0] + '1').html(addCommas($xdata.AT1));
            $('.' + td[1] + '1').html(addCommas($xdata.AW1 * 1.000));
            $('.' + td[2] + '1').html($xdata.P1 * 1.00);
            $('.' + td[3] + '1').html(addCommas($xdata.AT2));
            $('.' + td[4] + '1').html(addCommas($xdata.AW2 * 1.000));
            $('.' + td[5] + '1').html($xdata.P2 * 1.00);
            $('.' + td[6] + '1').html(addCommas(parseInt($xdata.AT1) + parseInt($xdata.AT2)));
            $('.' + td[7] + '1').html(addCommas(parseInt($xdata.AW1 * 1.000) + parseInt($xdata.AW2 * 1.000)));

            return data;
        },
        complete: function() {
            $('.dataQ-loader').hide();
        }
    });
}

function jLoadDataDump() {
    return $.ajax({
        cache: false,
        type: "POST",
        dataType: "json",
        url: jPath + "&mode=data-dump",
        beforeSend: function() {
            $('.loader1').html(jLoader);
        },
        success: function(data, status) {

            $xdata = data.data[0];
            let td = ['t1', 'w1', 'p1', 't2', 'w2', 'p2', 'st', 'sw'];

            if (data.data.length == 0) return;
            $('.' + td[0] + '2').html(addCommas($xdata.AT1));
            $('.' + td[1] + '2').html('-');
            $('.' + td[2] + '2').html($xdata.P1 * 1.00);
            $('.' + td[3] + '2').html(addCommas($xdata.AT2));
            $('.' + td[4] + '2').html('-');
            $('.' + td[5] + '2').html($xdata.P2 * 1.00);
            $('.' + td[6] + '2').html(addCommas(parseInt($xdata.AT1) + parseInt($xdata.AT2)));
            $('.' + td[7] + '2').html('-');


            if (data_out_dump.AT1 == 0) data_out_dump.AT1 = 1;
            if (data_out_dump.AT2 == 0) data_out_dump.AT2 = 1;

            $('.' + td[0] + '3').html(addCommas(data_out_dump.AT1 * 1 + $xdata.AT1 * 1));
            $('.' + td[1] + '3').html(addCommas((data_out_dump.AT1 * ($xdata.AW1 * 1.000)) + data_out_dump.AW1 * 1.000));
            $('.' + td[2] + '3').html(addCommas(0.00));

            $('.' + td[3] + '3').html(addCommas(data_out_dump.AT2 * 1 + $xdata.AT2 * 1));
            $('.' + td[4] + '3').html(addCommas((data_out_dump.AT2 * ($xdata.AW2 * 1.000)) + data_out_dump.AW2 * 1.000));
            $('.' + td[5] + '3').html(addCommas(0.00));

            $('.' + td[6] + '3').html(addCommas(parseInt(data_out_dump.AT1) + parseInt(data_out_dump.AT2) + parseInt($xdata.AT1) + parseInt($xdata.AT2)));
            $('.' + td[7] + '3').html(addCommas(parseInt(data_out_dump.AW1 * 1.000) + parseInt(data_out_dump.AW2 * 1.000) + parseInt($xdata.AW1 * 1.000) + parseInt($xdata.AW2 * 1.000)));

            return data;
        },
        complete: function() {
            $('.dataQ-loader').hide();
        }
    });
}

function jLoadDataQIN() {
    return $.ajax({
        cache: false,
        type: "POST",
        dataType: "json",
        url: jPath + "&mode=data-in",
        beforeSend: function() {
            $('.loader1').html(jLoader);
        },
        success: function(data, status) {

            $xdata = data.data[0];
            if (data.data.length == 0) return;

            let td = ['t1', 'w1', 'p1', 't2', 'w2', 'p2', 'st', 'sw'];

            $('.' + td[0] + '4').html(addCommas($xdata.AT1));
            $('.' + td[1] + '4').html(addCommas($xdata.AW1 * 1.000));
            $('.' + td[2] + '4').html($xdata.P1 * 1.00);
            $('.' + td[3] + '4').html(addCommas($xdata.AT2));
            $('.' + td[4] + '4').html(addCommas($xdata.AW2 * 1.000));
            $('.' + td[5] + '4').html($xdata.P2 * 1.00);
            $('.' + td[6] + '4').html(addCommas(parseInt($xdata.AT1) + parseInt($xdata.AT2)));
            $('.' + td[7] + '4').html(addCommas(parseInt($xdata.AW1 * 1.000) + parseInt($xdata.AW2 * 1.000)));

            return data;
        },
        complete: function() {
            $('.dataQ-loader').hide();
        }
    });
}

function jLoadDataQOUT() {
    return $.ajax({
        cache: false,
        type: "POST",
        dataType: "json",
        url: jPath + "&mode=data-out",
        beforeSend: function() {
            $('.loader1').html(jLoader);
        },
        success: function(data, status) {

            $xdata = data.data[0];
            if (data.data.length == 0) return;

            let td = ['t1', 'w1', 'p1', 't2', 'w2', 'p2', 'st', 'sw'];

            $('.' + td[0] + '5').html(addCommas($xdata.AT1));
            $('.' + td[1] + '5').html(addCommas($xdata.AW1 * 1.000));
            $('.' + td[2] + '5').html($xdata.P1 * 1.00);
            $('.' + td[3] + '5').html(addCommas($xdata.AT2));
            $('.' + td[4] + '5').html(addCommas($xdata.AW2 * 1.000));
            $('.' + td[5] + '5').html($xdata.P2 * 1.00);
            $('.' + td[6] + '5').html(addCommas(parseInt($xdata.AT1) + parseInt($xdata.AT2)));
            $('.' + td[7] + '5').html(addCommas(parseInt($xdata.AW1 * 1.000) + parseInt($xdata.AW2 * 1.000)));

            return data;
        },
        complete: function() {
            $('.dataQ-loader').hide();
        }
    });
}



















/** QQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQQ ***/
function jLoadDataQ() {
    return $.ajax({
        type: "POST",
        dataType: "json",
        url: jPath + "&mode=data-q",
        beforeSend: function() {
            $('.dataQ-loader').show();
        },
        success: function(dataQ, status) {
            if (dataQ.data)
                addRowQ(dataQ.data);
            return dataQ;
        },
        complete: function() {
            $('.dataQ-loader').hide();
        }
    });
}

function jLoadDataCane() {
    return $.ajax({
        type: "POST",
        dataType: "json",
        url: jPath + "&mode=data-cane",
        beforeSend: function() {
            $('.dataCane-loader').show();
        },
        success: function(dataCane, status) {
            jLog(status);
            if (dataCane.data)
                $.each(dataCane.data, function(key, value) {
                    addRowCane(value);
                });
            return dataCane;
        },
        complete: function() {
            $('.dataCane-loader').hide();
        }
    });
}

function addRowQ(arrQ) {
    let body = $("#dataQ").find('tbody');
    let row, td;
    let a = 0,
        b = 0,
        c = 0,
        d = 0;
    $.each(arrQ, function(key, value) {

        row = $('<tr>');
        td = '' +
            '<td class="text-center">' + value.FQBY + '</td>' +
            '<td class="text-right">' + addCommas(value.Q_ALL) + '</td>' +
            '<td class="text-right">' + addCommas(value.Q_IN) + '</td>' +
            '<td class="text-right">' + addCommas(value.Q_OUT) + '</td>' +
            '<td class="text-right">' + addCommas(value.Q_NO) + '</td>';

        a += value.Q_ALL * 1;
        b += value.Q_IN * 1;
        c += value.Q_OUT * 1;
        d += value.Q_NO * 1;

        row.append(td);
        body.append(row);
    });

    row = $('<tr style="background-color:#c2d6d6">');
    td = '' +
        '<td class="text-center bb"> รวมทุกลานจอด </td>' +
        '<td class="text-right bb">' + addCommas(a) + '</td>' +
        '<td class="text-right bb">' + addCommas(b) + '</td>' +
        '<td class="text-right bb">' + addCommas(c) + '</td>' +
        '<td class="text-right bb">' + addCommas(d) + '</td>';
    row.append(td);
    body.append(row);

}

function addRowCane(arrCane) {
    let body = $("#dataCane").find('tbody');
    let row = $('<tr>');
    let ST = arrCane.T01 * 1 + arrCane.T02 * 1;
    let SW = arrCane.W01 * 1.000 + arrCane.W02 * 1.000;
    let td = '' +
        '<td class="">ยอดประจำวัน</td>' +
        '<td class="text-right">' + addCommas(arrCane.T01) + '</td>' +
        '<td class="text-right">' + addCommas(parseFloat(arrCane.W01).toFixed(3)) + '</td>' +
        '<td class="text-right">' + ((SW > 0) ? (parseFloat(arrCane.W01) * 100 / SW).toFixed(2) : 0.00) + '</td>' +

        '<td class="text-right">' + addCommas(arrCane.T02) + '</td>' +
        '<td class="text-right">' + addCommas(parseFloat(arrCane.W02).toFixed(3)) + '</td>' +
        '<td class="text-right">' + ((SW > 0) ? (parseFloat(arrCane.W02) * 100 / SW).toFixed(2) : 0.00) + '</td>' +

        '<td class="text-right">' + addCommas(ST) + '</td>' +
        '<td class="text-right">' + addCommas(SW.toFixed(3)); + '</td>';

    row.append(td);
    body.append(row);

    row = $('<tr >');
    let S_ST = arrCane.ST01 * 1 + arrCane.ST02 * 1;
    let S_SW = arrCane.SW01 * 1.000 + arrCane.SW02 * 1.000;
    td = '' +
        '<td class=" " >ยอดสะสม</td>' +
        '<td class="text-right">' + addCommas(arrCane.ST01) + '</td>' +
        '<td class="text-right">' + addCommas(parseFloat(arrCane.SW01).toFixed(3)) + '</td>' +
        '<td class="text-right">' + ((S_SW > 0) ? (parseFloat(arrCane.SW01) * 100 / S_SW).toFixed(2) : 0.00) + '</td>' +

        '<td class="text-right">' + addCommas(arrCane.ST02) + '</td>' +
        '<td class="text-right">' + addCommas(parseFloat(arrCane.SW02).toFixed(3)) + '</td>' +
        '<td class="text-right">' + ((S_SW > 0) ? (parseFloat(arrCane.SW02) * 100 / S_SW).toFixed(2) : 0.00) + '</td>' +

        '<td class="text-right">' + addCommas(S_ST) + '</td>' +
        '<td class="text-right">' + addCommas(S_SW.toFixed(3)); + '</td>';

    row.append(td);
    body.append(row);

    row = $('<tr style="background-color:#c2d6d6">');
    td = '' +
        '<td class=" bb">ยอดรวมทั้งหมด</td>' +
        '<td class="text-right bb">' + addCommas(arrCane.T01 * 1 + arrCane.ST01 * 1) + '</td>' +
        '<td class="text-right bb">' + addCommas((parseFloat(arrCane.W01) + parseFloat(arrCane.SW01)).toFixed(3)) + '</td>' +
        '<td class="text-right bb">' + (((S_SW + SW) > 0) ? ((parseFloat(arrCane.W01) + parseFloat(arrCane.SW01)) * 100 / (S_SW + SW)).toFixed(2) : 0.00) + '</td>' +

        '<td class="text-right bb">' + addCommas(arrCane.ST02 * 1.000 + arrCane.T02 * 1.000) + '</td>' +
        '<td class="text-right bb">' + addCommas((parseFloat(arrCane.W02) + parseFloat(arrCane.SW02)).toFixed(3)) + '</td>' +
        '<td class="text-right bb">' + (((S_SW + SW) > 0) ? ((parseFloat(arrCane.W02) + parseFloat(arrCane.SW02)) * 100 / (S_SW + SW)).toFixed(2) : 0.00) + '</td>' +

        '<td class="text-right bb">' + addCommas((S_ST + ST)) + '</td>' +
        '<td class="text-right bb">' + addCommas((S_SW + SW).toFixed(3)); + '</td>';

    row.append(td);
    body.append(row);


}

function addCommas(nStr) {
    nStr += '';
    x = nStr.split('.');
    x1 = x[0];
    x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}