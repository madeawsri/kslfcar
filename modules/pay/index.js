var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jPathElsx = jServerPath + "/modules/" + jModuleName + "/xlsx.php?site=" + jSiteName;
var jPathJson = jServerPath + "/libs/jsonDb.php?site=" + jSiteName;
var jPathCard = jServerPath + "/modules/" + jModuleName + "/card.php?site=" + jSiteName;
var jPathCardRp = jServerPath + "/modules/" + jModuleName + "/rp.php?site=" + jSiteName;

/*
jConfirm('Are you sure?').then(function(ok) {
  if (ok) {
    jLog("OK");
  }
});*/

setTimeout(function() {
    $('#codecar').focus();
}, 1000);


var jPathMain = jPath;
! function(document, window, $) {
    "use strict";
    /*---- Select2 Zone Ket ----*/
    //showData();
    addPay();
}(document, window, jQuery);
var table;
/*
var timezoneToUse = 'Asia/Bangkok';
flatpickr('input[name="daterange"]', {
  mode: "range",
  dateFormat: "d-m-Y",
}); */

function addPay(frmOject) {
    if (frmOject)
        var param = frmOject.serialize();
    table = $('#customer-table').DataTable({
        destroy: true,
        select: true,
        "order": [
            [0, "asc"]
        ],
        "pageLength": 8,
        "lengthChange": false,
        "pagingType": "full_numbers",
        "oLanguage": {
            "oPaginate": {
                "sFirst": "<", // This is the link to the first page
                "sPrevious": "«", // This is the link to the previous page
                "sNext": "»", // This is the link to the next page
                "sLast": ">" // This is the link to the last page
            }
        },
        /*
        "ajax": {
          "url": jPathMain + "&mode=addpay&" + param,
          "type": "POST",
          "complete": function(json) {
            ret_data = json.responseJSON;
            jLog(json.responseJSON);
          }
        }
        */
    });
}

var counter = 1;

function addRow(data) {
    var zks = data.zks.split('/');
    table.row.add([
        data.id,
        zks[0],
        data.zks_name,
        data.carno,
        data.cartype_id + " " + data.cartype_text,
        data.fcucode,
        data.xsend,
        data.fpay
    ]).draw(false);
}

$('#codecar').on('keyup', function(e) {

    if (!parseInt($(this).val()))
        return false;
    //jLog("No Enter : " + $(this).val());
    if (e.keyCode === 13) {
        jPayCheck = true;
        var data = jValue(jPath, "getrow", {
            'codecar': $(this).val()
        });

        var data_table = table.column(0).data().unique();

        //jLog(data.id);
        //jLog(data_table.join(','));

        if (data.id != undefined) {
            if ($.inArray(data.id, data_table) === -1) {
                addRow(data);
                $('.badge-success').text(counter);
                counter++;
            } else {
                jAlert({
                    type: 'warning',
                    text: "รหัสบัตร " + data.id + " มีอยู่ในรายการเรียบร้อยแล้ว."
                });
                setTimeout(function() {
                    $('.swal2-confirm').focus();
                }, 100);
            }
        } else {
            jAlert({
                type: 'warning',
                text: "ไม่พบข้อมูล"
            });
            setTimeout(function() {
                $('.swal2-confirm').focus();
            }, 100);
        }

        $(this).val('');

    }
});


function showData(frmOject) {
    if (frmOject)
        var param = frmOject.serialize();
    table = $('#customer-table').DataTable({
        destroy: true,
        select: true,
        "order": [
            [0, "asc"]
        ],
        "pageLength": 8,
        "lengthChange": false,
        "pagingType": "full_numbers",
        "oLanguage": {
            "oPaginate": {
                "sFirst": "<", // This is the link to the first page
                "sPrevious": "«", // This is the link to the previous page
                "sNext": "»", // This is the link to the next page
                "sLast": ">" // This is the link to the last page
            }
        },
        "ajax": {
            "url": jPathMain + "&mode=datatable&" + param,
            "type": "POST",
            "complete": function(json) {
                ret_data = json.responseJSON;
                jLog(json.responseJSON);
            }
        }
    });
    $('#customer-table tbody').on('click', 'tr', function(e) {
        e.preventDefault();
    });

    $("#searchbox").keyup(function() {
        table.search(this.value).draw();
    });



}

var jPayCheck = true;

$('.data-card').click(function() {
    jPayCheck = false;
    $('.group-pay').hide();
    $(this).hide();
    showData();
});


$('.btn-print').click(function() {

    var iPrint = table.rows('.selected').data().length;
    /// select datea range
    var data_code = table.column(0).data().unique();
    var keyString = data_code.join('|');
    var icar = data_code.length;

    if (icar == 0 || !jPayCheck)
        return false;

    jConfirmInfo({
        text: 'ยืนยันการจ่ายบัตรรถชาวไร่ จำนวน ' + icar + ' บัตร ?'
    }).then(function(ok) {
        if (ok) {
            var data = jValue(jPath, "uppay", {
                'key_carno': keyString
            });
            jLog(data);
            if (data.uppay) {
                swal(
                    'Success!',
                    'ได้จ่ายบัตรรถชาวไร่เรียบร้อยแล้ว.',
                    'success'
                ).then(okay => {
                    if (okay) {
                        $('.btn-warning').click();
                    }
                });

            }
        }
    });




});