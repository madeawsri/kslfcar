var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jPathElsx = jServerPath + "/modules/" + jModuleName + "/xlsx.php?site=" + jSiteName;
var jPathJson = jServerPath + "/libs/jsonDb.php?site=" + jSiteName;

var jPathMain = jPath;
! function (document, window, $) {
  "use strict";
  /*---- Select2 Zone Ket ----*/
  jSelect2Data('.select2-zoneket',jPath+"&mode=zoneket",{placeholder: '-ทุกเขต-',});
  //showData();
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
    var row = table.row( this ).data();
    dbdata = [];
    dbdata = ret_data.dbdata[row[0]];
    data_info(row,dbdata);

   });
  $("#searchbox").keyup(function () {
    table.search(this.value).draw();
  });
}

$('.btn-add').click(function(){
  add_users();
});

var myBackup = $('#mModal').clone();
function data_info(data_row,dbdata){
  $('#mModal').modal('hide').remove();
  var myClone = myBackup.clone();
  $('body').append(myClone);
  $(".loading").show();
  $('#mModal').find('.modal-title').append(':'+data_row[0]);
  $('#mModal').find("#btn-save").hide();
  $('#mModal').find("#btn-cancel").hide();
  $('#mModal').find("#btn-edit").show();
  $('#mModal').find("#btn-del").show();
  
  $('#user_level').val(data_row[3].substring(0,1)).trigger('change');
  //jLog(dbdata[3]);
  $('#mModal').find("input[name='user_name']").val(data_row[1]);
  $('#mModal').find("input[name='user_login']").val(data_row[2]);
  $('#mModal').find(".label_user_password").html('รหัสผ่านใหม่');
  $('#mModal').on('show.bs.modal', function (event) { 
    $(".loading").hide(); 
  });
  $('#mModal').modal({ show: true });
  $('#mModal').on('shown.bs.modal', function () {
    $('#mModal').find("input[name='user_password']").select().focus();
    $('#mModal').find('#user_level').select2({
      placeholder: 'Select',
      width: '100%',
      dropdownParent: $('#mModal')
    });
  });

  $('#btn-edit').click(function(){

    var formData = JSON.stringify($('#mModal').find("#frmUsers").serialize());
    $.ajax({
        type:'POST',
        url  : jPath+"&mode=edit-users&id="+data_row[0],
        data : formData,
        dataType: 'json',
        cache: false,
        beforeSend: function () { $(".loading").show(); },
        success:function(ret){
          jLog(ret);
          $(".loading").hide(); 
          if(ret.error){
            $('#mModal').find('.alert-warning').show().fadeOut(2000);
            setTimeout(function(){
              $('#mModal').find("input[name='"+ret.errfield+"']").select().focus();
            },200)   ;
            return false;
          }else{
            //-- complate
            table.ajax.reload();
            $('#mModal').modal('hide');
          }
        }
    });

  });

  $('#btn-del').click(function(e){
      e.preventDefault();
      $.ajax({
        type:'POST',
        url  : jPath+"&mode=del-users&id="+data_row[0],
        dataType: 'json',
        cache: false,
        beforeSend: function () { $(".loading").show(); },
        success:function(ret){
          jLog(ret);
          $(".loading").hide(); 
          if(ret.error){
            $('#mModal').find("input[name='"+ret.errfield+"']").select().focus();
            return false;
          }else{
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
  $('#mModal').on('show.bs.modal', function (event) { 
    $(".loading").hide(); 
  });
  $('#mModal').modal({ show: true });
  $('#mModal').on('shown.bs.modal', function () {
    $('input:text:visible:first', this).focus();
    $('#mModal').find('#user_level').select2({
      placeholder: 'Select',
      width: '100%',
      dropdownParent: $('#mModal')
    });
  });
}
$('.modal').on('hidden.bs.modal', function(){
  $('.modal').find('form')[0].reset();
});

/** Create Form */
function submitForm(){
    var formData = JSON.stringify($('#mModal').find("#frmUsers").serialize());
      $.ajax({
          type:'POST',
          url  : jPath+"&mode=add-users",
          data : formData,
          dataType: 'json',
          cache: false,
          beforeSend: function () { $(".loading").show(); },
          success:function(ret){
            jLog(ret);
            $(".loading").hide(); 
            if(ret.error){
              $('#mModal').find('.alert-warning').show().fadeOut(2000);
              setTimeout(function(){
                $('#mModal').find("input[name='"+ret.errfield+"']").select().focus();
              },200)   ;
              return false;
            }else{
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

