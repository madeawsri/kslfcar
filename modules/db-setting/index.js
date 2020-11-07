var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jPathElsx = jServerPath + "/modules/" + jModuleName + "/xlsx.php?site=" + jSiteName;
var jPathJson = jServerPath + "/libs/jsonDb.php?site=" + jSiteName;

var jPathMain = jPath;
! function(document, window, $) {
    "use strict";
    showData();
}(document, window, jQuery);
var table;

function showData(frmOject) {
    if (frmOject)
        var param = frmOject.serialize();
    table = $('#customer-table').DataTable({
        destroy: true,
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
        var row = table.row(this).data();
        dbdata = [];
        dbdata = ret_data.dbdata[row[0]];
        data_info(row, dbdata);

    });
    $("#searchbox").keyup(function() {
        table.search(this.value).draw();
    });
}



var myBackup = $('#mModal').clone();

function data_info(data_row, dbdata) {
    $('#mModal').modal('hide').remove();
    var myClone = myBackup.clone();
    $('body').append(myClone);

    $(".loading").show();
    $('#mModal').find('.modal-title').append(':' + data_row[0]);
    $('#mModal').find("#btn-save").hide();
    $('#mModal').find("#btn-cancel").hide();
    $('#mModal').find("#btn-edit").show();
    $('#mModal').find("#btn-del").hide();


    $('#mModal').find("input[name='host_name']").val(data_row[0]);
    $('#mModal').find("input[name='user_name']").val(data_row[1]);
    $('#mModal').find("input[name='pass_name']").val(data_row[2]);
    $('#mModal').find("input[name='db_name']").val(data_row[3]);
    $('#mModal').find("input[name='fyear']").val(data_row[4]);


    $('#mModal').on('show.bs.modal', function(event) {
        $(".loading").hide();
    });
    $('#mModal').modal({ show: true });
    $('#mModal').on('shown.bs.modal', function() {

    });

    $('#btn-edit').click(function() {

        var formData = JSON.stringify($('#mModal').find("#frmUsers").serialize());
        $.ajax({
            type: 'POST',
            url: jPath + "&mode=edit-users&id=" + data_row[0],
            data: formData,
            dataType: 'json',
            cache: false,
            beforeSend: function() { $(".loading").show(); },
            success: function(ret) {
                jLog(ret);
                $(".loading").hide();
                if (ret.error) {
                    $('#mModal').find('.alert-warning').show().fadeOut(2000);
                    setTimeout(function() {
                        $('#mModal').find("input[name='" + ret.errfield + "']").select().focus();
                    }, 200);
                    return false;
                } else {
                    //-- complate
                    table.ajax.reload();
                    $('#mModal').modal('hide');
                    window.location.href = jServerPath + "/" + jSiteName + "/logout.ksl";
                }
            }
        });

    });
}