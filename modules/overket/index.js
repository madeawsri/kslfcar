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
    jLog(row);
   // dbdata = [];
   // dbdata = ret_data.dbdata[row[0]];
   // data_info(row, dbdata);
   data_info(row,null);
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
  $('#mModal').find("#btn-del").hide();

  
  // $('#mModal').find("input[name='car_type_max']").val(data_row[2]);
  //$('#mModal').find("input[name='fyear']").val(data_row[3]).prop('readonly', true);
  
  $('#mModal').modal({
    show: true
  });

  $('#mModal').on('shown.bs.modal', function () {
    $(".loading").hide();
    $('input:text:visible:first', this).focus();

    var objfcucode = jSelect2Data_new($('#mModal').find('#fcucode'), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));
    setTimeout(function () {


      var newOption = new Option(data_row[0] + " - " + data_row[1], data_row[0], false, false);
      objfcucode.append(newOption).attr('readonly', 'readonly').trigger("change"); //.attr('readonly', 'readonly')
    }, 100);

    var jsonData = (function () {
      var result;
      $.ajax({
        type: 'POST',
        url: jPath + "&mode=getdata&fcucode=" + data_row[0],
        dataType: 'json',
        async: false,
        success: function (data) {
          result = data;
        }
      });
      return result;
    })();

    jSelect2NoJson_multi($('#mModal'), 'txt_zks').val(jsonData.zks.split(',')).trigger("change");


  });



  $('#btn-edit').click(function() {
    var formData = JSON.stringify($('#mModal').find("#frmUsers").serialize());
  if($('#mModal').find('#fcucode').select2('data').length){
     var fcuname = $('#mModal').find('#fcucode').select2('data')[0].text;
     var fcucode = $('#mModal').find('#fcucode').select2('data')[0].id;
  }
  $.ajax({
    type: 'POST',
    url: jPath + "&mode=edit-users&fcuname="+fcuname,
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
        msg = '';
        if(ret.errfield == 'fcucode'){
          msg = " กรุณากรอกข้อมูล โควต้า/ ชื่อ-กสุล ";
        }//else if(ret.errfield == 'txt_zks') {
         // msg = " กรุณากรอกข้อมูล โซน/เขต/สาย ";
       // }
        jAlert({'text':msg});
        

        return false;
      } else {
        //getObjectModal('.fcucode').val('').trigger("change");
        //getObjectModal('#txt_zks').val('').trigger("change");
        table.ajax.reload();
        jAlert({'text':"แก้ไขข้อมูลเรียบร้อยแล้ว."});
         return false;
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

    $('input:text:visible:first', this).focus();
    jSelect2Data_new($('#mModal').find('.fcucode'), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));
    jSelect2NoJson_multi($('#mModal'), 'txt_zks').val('').trigger("change");
    
  });
}
function getObjectModal(str){
  return $('#mModal').find(str);
}
$('.modal').on('hidden.bs.modal', function() {
  $('.modal').find('form')[0].reset();
});

/** Create Form */
function submitForm() {
  var formData = JSON.stringify($('#mModal').find("#frmUsers").serialize());
  if($('#mModal').find('#fcucode').select2('data').length){
     var fcuname = $('#mModal').find('#fcucode').select2('data')[0].text;
     var fcucode = $('#mModal').find('#fcucode').select2('data')[0].id;
  }
  $.ajax({
    type: 'POST',
    url: jPath + "&mode=add-users&fcuname="+fcuname,
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
        msg = '';
        if(ret.errfield == 'fcucode'){
          msg = " กรุณากรอกข้อมูล โควต้า/ ชื่อ-กสุล ";
        }else if(ret.errfield == 'txt_zks') {
          msg = " กรุณากรอกข้อมูล โซน/เขต/สาย ";
        }
        jAlert({'text':msg});
        

        return false;
      } else  if (ret.status == -1) {
        jAlert({'text':" โควต้านี้ "+fcucode+" ได้มีการอนุมัติไปแล้ว. "});
        getObjectModal('.fcucode').val('').trigger("change");
        getObjectModal('#txt_zks').val('').trigger("change");

      } else {
        getObjectModal('.fcucode').val('').trigger("change");
        getObjectModal('#txt_zks').val('').trigger("change");

        table.ajax.reload();
       
      }
    }
  });
}