var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jPathElsx = jServerPath + "/modules/" + jModuleName + "/xlsx.php?site=" + jSiteName;
var jPathJson = jServerPath + "/libs/jsonDb.php?site=" + jSiteName;

var jPathMain = jPath;
! function(document, window, $) {
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

    jConfirmInfo({
      text: "ต้องการเปลี่ยน ระดับการแจ้งคิวเป็น " + ret_data.dbdata[row[0]][1]
    }).then(function(ok) {
      if (ok) {
        jAjax(jPath, 'upd_setting', {
          id: ret_data.dbdata[row[0]][0]
        }).then(function(data) {
          jLog(data);
          if (data.res) {
            $('.lbl_kslq').html(ret_data.dbdata[row[0]][1] + " (" + ret_data.dbdata[row[0]][0] + ")");
            jAlert({
              text: "เปลี่ยนเรียบร้อยแล้ว"
            });
          } else {
            jAlert({
              text: "ไม่สำเร็จ.",
              type: 'error'
            });
          }
          table.ajax.reload();


        });
      }
    });

    //data_info(row, dbdata);

  });
  $("#searchbox").keyup(function() {
    table.search(this.value).draw();
  });
}

$('.btn-add').click(function() {
  add_users();
});

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
  //**
  $('#mModal').find("#btn-del").hide();

  $('#mModal').find("#car_type").val(data_row[0]).select2().attr('readonly', 'readonly');

  $('#mModal').find("input[name='car_type_max']").val(data_row[2]);
  //$('#mModal').find("input[name='fyear']").val(data_row[3]).prop('readonly', true);

  $('#mModal').on('show.bs.modal', function(event) {
    $(".loading").hide();
  });

  $('#mModal').modal({
    show: true
  });

  $('#mModal').on('shown.bs.modal', function() {
    jSelect2NoJson('mModal', 'car_type');
  });

  $('#btn-edit').click(function() {
    var formData = JSON.stringify($('#mModal').find("#frmUsers").serialize());
    $.ajax({
      type: 'POST',
      url: jPath + "&mode=edit-users&id=" + data_row[0],
      data: formData,
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        jLog(ret);
        $(".loading").hide();
        if (ret.error) {
          $('#mModal').find('.alert-warning').show().fadeOut(2000);
          setTimeout(function() {
            //$('#mModal').find("input[name='"+ret.errfield+"']").select().focus();
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

  $('#btn-del').click(function(e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: jPath + "&mode=del-users&id=" + data_row[0],
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
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

function add_users() {
  // show the modal onload.
  $(".loading").show();
  $('#mModal').modal('hide').remove();
  var myClone = myBackup.clone();
  $('body').append(myClone);
  $('#mModal').find("#btn-save").show();
  $('#mModal').find("#btn-cancel").show();
  $('#mModal').find("#btn-edit").hide();
  $('#mModal').find("#btn-del").hide();
  $('#mModal').on('show.bs.modal', function(event) {
    $(".loading").hide();
  });
  $('#mModal').modal({
    show: true
  });
  $('#mModal').on('shown.bs.modal', function() {
    /*  $('input:text:visible:first', this).focus();
      $('#mModal').find('#user_level').select2({
        placeholder: 'Select',
        width: '100%',
        dropdownParent: $('#mModal')
      });*/
    jSelect2NoJson('mModal', 'car_type');
  });
}
$('.modal').on('hidden.bs.modal', function() {
  $('.modal').find('form')[0].reset();
});

/** Create Form */
function submitForm() {
  var formData = JSON.stringify($('#mModal').find("#frmUsers").serialize());
  $.ajax({
    type: 'POST',
    url: jPath + "&mode=add-users",
    data: formData,
    dataType: 'json',
    cache: false,
    beforeSend: function() {
      $(".loading").show();
    },
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
        //$('#mModal').modal('hide');
        $('.modal').find('form')[0].reset();
        $('#mModal').find("input[name='user_name']").select().focus();
        //showData(null);
        table.ajax.reload();
      }
    }
  });
}