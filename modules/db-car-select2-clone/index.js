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
    "order": [
      [0, "desc"]
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
      "complete": function (json) {
        ret_data = json.responseJSON;
        jLog(json.responseJSON);
      }
    }
  });

  $('#customer-table tbody').on('click', 'tr', function (e) {
    e.preventDefault();
    var row = table.row(this).data();
    var dbdata = [];
    //dbdata = ret_data.dbdata[row[0]];
    _print(row, dbdata);

  });
  $("#searchbox").keyup(function () {
    table.search(this.value).draw();
  });
}

$('.btn-add').click(function () {
  jAdd();
});

var myBackup = $('#mModal').clone();
function _print(data_row, dbdata) {

  $(".loading").show();
  $('#mModal').modal('hide').remove();
  var myClone = myBackup.clone();
  $('body').append(myClone);
  var formData = [];
  $.ajax({
    type: 'POST',
    url: jPath + "&mode=printq&id=" + data_row[0],
    data: formData,
    dataType: 'json',
    cache: false,
    beforeSend: function () {
      $(".loading").show();
    },
    success: function (ret) {
      jLog(ret);







      $('#mModal').find('#frm').hide();
      $('#mModal').find('.print_bill').show();

      /** Init Bill Before to print */
      var mx = $('#mModal');
      var _print_paid = ret.data_paid;

      getObjModal('#qbarcode').barcode(
        _print_paid.que_id, // Value barcode (dependent on the type of barcode)
        "code128" // type (string)
        , {
          barWidth: 2,
          barHeight: 80,
          fontSize: 20
        }
      );

      getObjModal('#lanjod').html(_print_paid.que_location.substring(0, 1));
      getObjModal('#divcode').html('01');
      getObjModal('#qcode').html(_print_paid.que_id);
      getObjModal('#datep').html(_print_paid.fvoudate);
      getObjModal('#datein').html(_print_paid.datein);
      getObjModal('#timein').html(_print_paid.timein);
      getObjModal('#fname').html(_print_paid.fcucode + " " + _print_paid.fcuname);
      getObjModal('#fvname').html(_print_paid.truck_number + " " + _print_paid.truck_type_name);
      getObjModal('#fhname').html(_print_paid.truck_head);
      getObjModal('#fsname').html(_print_paid.cane_type_name.substring(0, 20));
      getObjModal('#facode').html(_print_paid.truck_no);
      getObjModal('#fzname').html(''); // หาง
      getObjModal('#fbaht').html(_print_paid.shiprate); // ค่าบรรทุก

      getObjModal('.qspacial').hide();
      if (_print_paid.sque_special > 0)
        getObjModal('.qspacial').show();

      /** Printer Bill All */
      var contents = $('#mModal').find('.print_bill').html();
      var frame1 = $('<iframe />');
      frame1[0].name = "frame1";
      frame1.css({
        "position": "absolute",
        "top": "-1000000px"
      });
      $("body").append(frame1);
      var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
      frameDoc.document.open();
      //Create a new HTML document.
      frameDoc.document.write('<html><head><title>DIV Contents</title>');
      frameDoc.document.write('</head><body>');

      frameDoc.document.write(contents);
      frameDoc.document.write('</body></html>');
      frameDoc.document.close();
      setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();

        $('#mModal').find('.print_bill').hide();
        $('#mModal').modal('hide');

      }, 100);






    }
  });

}
/**
 * FUNTION ADD 
 */
function jAdd() {
  // show the modal onload.
  $(".loading").show();
  $('#mModal').modal('hide').remove();
  var myClone = myBackup.clone();
  $('body').append(myClone);
  $('.show_quata_s').hide();
  $('.show_qdate').hide();

  $('#mModal').find('#frm').show();
  $('#mModal').find('.print_bill').hide();

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

    jSelect2Data_new($('#mModal').find('.fcucode'), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));

    //jSelect2Data_new($('#mModal').find('#truck_no'), 'เลือกเลขที่สั่งงาน', jPathJson, 'freqno', $('#mModal'));
    jSelect2NoJson('mModal', 'txt_zks');
    jSelect2NoJson('mModal', 'car_type');
    //-- เลือกประเภทคิว
    getObjModal('#car_type').change(function () {
      jLog("car_type change");

    });
  });

  getObjModal('#fcucode').change(function () {
     jLog('fcucode change')
  });

  
  getObjModal('#fsend').change(function () {
    jLog('fsend change')
  });

  getObjModal('#btn-add-fcucode').click(function(){

    var newRow = $(".div_fcucode:first").clone();
    getObjModal('.panel-fcucode').append(newRow);
    $(newRow).find(".select2-container").remove();
    jSelect2Data_new($(newRow).find(".fcucode"), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));
    $(newRow).find(".fcucode").val('').trigger('change');

  });

}
/**
 * ========================================================
 */








$('.modal').on('hidden.bs.modal', function () {
  $('.modal').find('form')[0].reset();
});

function isNanToZero(x) {
  return isNaN(x) ? 0 : x;
}

function chkQtime() {
  var track_number = getObjModal('#truck_number').val();
  if (!track_number) {
    setTimeout(() => {
      getObjModal('#truck_number').select().focus();
    }, 100);
    return false;
  } else {
    return track_number;
  }
}

function getObjModal(txtObj) {
  return $('#mModal').find(txtObj);
}

/** Create Form */
function submitForm() {

  var formData = JSON.stringify($('#mModal').find("#frm").serialize());
  var fcartype = $('#mModal').find('#car_type').select2('data')[0].text;
  
  setTimeout(function () {
    $.ajax({
      type: 'POST',
      url: jPath + "&mode=add&fvname=" + fcartype ,
      data: formData,
      dataType: 'json',
      cache: false,
      beforeSend: function () {
        $(".loading").show();
      },
      success: function (ret) {
        jLog(ret);
        $(".loading").hide();
      }
    });
  }, 100);



}