var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jPathElsx = jServerPath + "/modules/" + jModuleName + "/xlsx.php?site=" + jSiteName;
var jPathJson = jServerPath + "/libs/jsonDb.php?site=" + jSiteName;

var jPathMain = jPath;
! function (document, window, $) {
  "use strict";
  /*---- Select2 Zone Ket ----*/
  showData();
}(document, window, jQuery);
var table;

function showData(frmOject) {
  if (frmOject)
    var param = frmOject.serialize();
  table = $('#customer-table').DataTable({
    destroy: true,
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
      "complete": function (json) {
        ret_data = json.responseJSON;
        jLog(json.responseJSON);
      }
    }
  });
  $('#customer-table tbody').on('click', 'tr', function (e) {
    e.preventDefault();
    var row = table.row(this).data();
    dbdata = [];
    dbdata = ret_data.dbdata[row[1]];
    data_info(row, dbdata);

  });
  $("#searchbox").keyup(function () {
    table.search(this.value).draw();
  });
}

$('.btn-add').click(function () {
  jAdd();
});

var myBackup = $('#mModal').clone();

function data_info(data_row, dbdata) {

  $('#mModal').modal('hide').remove();
  var myClone = myBackup.clone();
  $('body').append(myClone);

  jSelect2Data_new($('#mModal').find('#fcucode'), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));

  $(".loading").show();
  $('#mModal').find('.modal-title').append(':' + data_row[1]);
  $('#mModal').find("#btn-save").hide();
  $('#mModal').find("#btn-cancel").hide();
  $('#mModal').find("#btn-edit").show();
  $('#mModal').find("#btn-del").show();

  //jLog(data_row[3]);
  $('#mModal').find("input[name='queue_amt']").val(data_row[2].replace(",", ""));


  $('#mModal').on('show.bs.modal', function (event) {
    $(".loading").hide();
  });
  $('#mModal').modal({
    show: true
  });
  $('#mModal').on('shown.bs.modal', function () {

    $('#mModal').find('#fcucode').append('<option value="' + data_row[1] + '" selected> ' + data_row[1] + ' - ' + data_row[2] + ' </option>');
    $('#mModal').find("input[name='queue_amt']").select().focus();

  });

  $('#btn-edit').click(function () {

    var formData = JSON.stringify($('#mModal').find("#frm").serialize());
    var fname = $('#mModal').find('#fcucode').text();
    $.ajax({
      type: 'POST',
      url: jPath + "&mode=edit&id=" + data_row[0] + '&fname=' + fname,
      data: formData,
      dataType: 'json',
      cache: false,
      beforeSend: function () {
        $(".loading").show();
      },
      success: function (ret) {
        jLog(ret);
        $(".loading").hide();
        if (ret.error) {
          $('#mModal').find('.alert-warning').show().fadeOut(2000);
          setTimeout(function () {
            $('#mModal').find("input[name='" + ret.errfield + "']").select().focus();
          }, 200);
          return false;
        } else {
          //-- complate
          table.ajax.reload();
          $('#mModal').modal('hide');
        }
      }
    });

  });

  $('#btn-del').click(function (e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: jPath + "&mode=del&id=" + data_row[1],
      dataType: 'json',
      cache: false,
      beforeSend: function () {
        $(".loading").show();
      },
      success: function (ret) {
        jLog(ret);
        $(".loading").hide();
        if (ret.error) {
          $('#mModal').find("input[name='" + ret.errfield + "']").select().focus();
          return false;
        } else {
          //-- complate
          table.ajax.reload();
          $('#mModal').modal('hide');
        }
      }
    });
  });

}

function jAdd() {
  // show the modal onload.
  $(".loading").show();

  $('#mModal').modal('hide').remove();
  var myClone = myBackup.clone();
  $('body').append(myClone);

  $('#mModal').find("#btn-save").show();
  $('#mModal').find("#btn-cancel").show();
  $('#mModal').find("#btn-edit").hide();
  $('#mModal').find("#btn-del").hide();
  $('#mModal').on('show.bs.modal', function (event) {
    $(".loading").hide();
  });
  $('#mModal').modal({
    show: true
  });
  $('#mModal').on('shown.bs.modal', function () {
    $('input:text:visible:first', this).focus();

    jSelect2Data_new($('#mModal').find('#fcucode'), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));

  });
}
$('.modal').on('hidden.bs.modal', function () {
  $('.modal').find('form')[0].reset();
});

/** Create Form */
function submitForm() {
  var formData = JSON.stringify($('#mModal').find("#frm").serialize());
  var fname = $('#mModal').find('#fcucode').text();
  $.ajax({
    type: 'POST',
    url: jPath + "&mode=add&fname=" + fname,
    data: formData,
    dataType: 'json',
    cache: false,
    beforeSend: function () {
      $(".loading").show();
    },
    success: function (ret) {
      jLog('--- return add ---');
      jLog(ret);
      $(".loading").hide();
      if (ret.error) {
        $('#mModal').find('.alert-warning').show().fadeOut(2000);
        setTimeout(function () {
          $('#mModal').find("input[name='" + ret.errfield + "']").select().focus();
        }, 200);
        return false;
      } else {
        //-- complate
        //$('#mModal').modal('hide');
        alert("ได้ดำเนินเพิ่มข้อมูลเรียบร้อย.");
        $('.modal').find('form')[0].reset();
        //$('#mModal').find("input[name='user_name']").select().focus();
        $('#mModal').find('#fcucode').empty().trigger('change').select2('open');
        //showData(null);
        table.ajax.reload();
      }
    }
  });
}