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
      //Append the external CSS file.
      //frameDoc.document.write('<link href="style.css" rel="stylesheet" type="text/css" />');
      //Append the DIV contents.
      frameDoc.document.write(contents);
      frameDoc.document.write('</body></html>');
      frameDoc.document.close();
      setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();

        //$('#mModal').find('#frm').show();
        $('#mModal').find('.print_bill').hide();
        $('#mModal').modal('hide');
        //$('#mModal').find('#truck_number').select().focus();
        //$('#mModal').find("#fcucode").empty().trigger('change');
        //$('#mModal').find("#cane_type").val('01').trigger('change');
        //$('#mModal').find("#truck_type").val('C1').trigger('change');
        //$('#mModal').find("#paid_type").val('0').trigger('change');
        //$('#mModal').find("input[name='truck_number']").select().focus();


      }, 100);






    }
  });

}

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

    jSelect2Data_new($('#mModal').find('#fcucode'), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));
    jSelect2Data_new($('#mModal').find('#truck_no'), 'เลือกเลขที่สั่งงาน', jPathJson, 'freqno', $('#mModal'));
    jSelect2NoJson('mModal', 'paid_type');
    jSelect2NoJson('mModal', 'cane_type');
    jSelect2NoJson('mModal', 'truck_type');
    //-- เลือกประเภทคิว
    getObjModal('#paid_type').change(function () {
      $('#mModal').find('.alert-warning').hide();
      if ($(this).val() * 1) {
        getObjModal('.paid_type0').hide();
        getObjModal('.paid_type1').show();
        setTimeout(function () {
          jLog('--focus truck_number ---');
          getObjModal('#truck_no').empty().trigger('change').select2('open');
        }, 100);

      } else {
        $('#mModal').find('.alert-warning').hide();
        getObjModal('.paid_type1').hide();
        getObjModal('.paid_type0').show();
        setTimeout(function () {
          jLog('--focus truck_number ---');
          getObjModal('#truck_number').val('').select().focus();
          getObjModal('#fcucode').empty().trigger('change');
          getObjModal('#truck_head').val('');
        }, 100);
      }
    });
  });

  getObjModal('#truck_number').blur(function (e) {
    var track_number = $(this).val();
    if (track_number.trim()) {
      getObjModal('#fcucode').empty().trigger('change');
      getObjModal('#btn-save').hide();
    }
  })


  getObjModal('#fcucode').change(function () {
    var x_fcucode = ($(this).val());
    var track_number = chkQtime();
    getObjModal('#btn-save').hide();

    var imgLoading = "<img src='" + jServerPath + "/assets/images/ajax_load.gif' style=' height: 15px; width:15px' /> กำลังตรวจสอบข้อมูล...";
    getObjModal('.fstatus').html(imgLoading);
    $('.show_quata_s').hide();
    $('.show_qdate').hide();

    $.ajax({
      async: false,
      type: "POST",
      dataType: "json",
      url: jServerPath + "/libs/jsonDb.php", //Relative or absolute path to response.php file
      data: {
        'fcucode': x_fcucode,
        'track_number': track_number,
        'mode': 'chk5000T'
      },
      success: function (data) {
        jLog(data);
        var x_status = '-สถานะการส่งอ้อยชาวไร่-';
        var ret = data;
        var dataQTime = data;
        var checkCarno = data.check_carno;
        if (ret.is_master) {
          $('.date_ka').html(ret.is_master);
          $('.show_quata_s').show();
        } else {
          $('.show_quata_s').hide();
        }

        data = ret.detail;

        if (checkCarno == 1) { // ตรวจสอบทะเบียนช้ำในลาน

          $('.qdate').html('ทะเบียนรถช้ำในลาน.');
          $('.show_qdate').show();
          getObjModal('#btn-save').hide();
          $('.show_quata_s').hide();

        } else if (dataQTime.qchktime.checkdate == 0) { // ตรวจสอบเวลาชั่งออก หลัง 3 ซม.
          $('.qdate').html(dataQTime.qchktime.FDATEOUT_4HR);
          $('.show_qdate').show();
          getObjModal('#btn-save').hide();
          $('.show_quata_s').hide();
        } else {
          $('.show_qdate').hide();
          getObjModal('#btn-save').show();


          // ตรวจสอบ 5000
          if (data) {
            if (data.fw_tu > 0) {
				
              x_status = "โควต้า <b style='color:green'>" + x_fcucode + "</b> นน.ที่ชั่งอ้อยแล้ว  <span class='bb'>" + parseFloat(isNanToZero(data.FW)).toFixed(2) + "</span>  ตัน  นน.เฉลี่ย <span class='bb'>" + isNanToZero(data.FC) + "</span> ตัน/เที่ยว <br> รถแจ้งคิวยังไม่ชั่ง  <span class='bb'>" + isNanToZero(data.FCX) + "</span> คิว  นน.ประมาณ  <span class='bb'>" + parseFloat(isNanToZero(data.FC * data.FCX)).toFixed(2) + "</span> ตัน  รวมน้ำหนักประมาณการ  <span class='bb'>" + parseFloat(isNanToZero(data.FWX)).toFixed(2) + "</span> ตัน " +
                "<br> ส่งตรง  <span class='bb'>" + parseFloat(isNanToZero(ret.fw_tu['fw_t'])).toFixed(2) + "</span> ตัน " + " ส่งศูนย์ฯ  <span class='bb'>" + parseFloat(isNanToZero(ret.fw_tu['fw_u'])).toFixed(2) + "</span> ตัน สัญญาส่ง <span class='bb'>" + parseFloat(isNanToZero(ret.fw_tu['frqqty'])).toFixed(2) + "</span> ตัน ( <b style=color:red>" + parseFloat(isNanToZero(ret.fw_tu['percen_fw'])).toFixed(2) + " % </b> )";
			
              if (data.STATUS == 'OK'  ) { // <= 5000 passed.
                 getObjModal('#btn-save').show();
              } else {
                 getObjModal('#btn-save').hide();
              }
			  
            } else {
              getObjModal('.fstatus').html(x_status);
            }
			
			// 120% for FW T & U
			
			 if(ret.fw_tu['percen_fw'] > 120)
				getObjModal('#btn-save').hide(); 
			
			if(x_fcucode=='0100582' || x_fcucode=='0100631'){
				getObjModal('#btn-save').show(); 
			} else {
				
			}
			
			
          }




        }

        getObjModal('.fstatus').html(x_status);
      }
    });

  });
}
$('.modal').on('hidden.bs.modal', function () {
  $('.modal').find('form')[0].reset();

});

function isNanToZero(x){
  return isNaN(x)?0:x;
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
  //var fname = $('#mModal').find('#fcucode').text();
  var fcartype = $('#mModal').find('#truck_type').select2('data')[0].text;
  var fcanetype = $('#mModal').find('#cane_type').select2('data')[0].text;

  if ($('#mModal').find('#truck_no').select2('data').length > 0)
    var ftrackno = $('#mModal').find('#truck_no').select2('data')[0].text;



  setTimeout(function () {
    $.ajax({
      type: 'POST',
      url: jPath + "&mode=add&fvname=" + fcartype + "&fcanetype=" + fcanetype + "&truckno=" + ftrackno,
      data: formData,
      dataType: 'json',
      cache: false,
      beforeSend: function () {
        $(".loading").show();
      },
      success: function (ret) {
        $('#mModal').find('.alert-warning').hide();
        jLog('--- return add ---');
        jLog(ret);
        $(".loading").hide();
        if (ret.err_softpro_has) {

          $('#mModal').find('.alert-warning span.errmsg').html(ret.err_softpro_msg).show();
          $('#mModal').find('.alert-warning').show();
          if (ret.err_softpro_type == 1)
            $('#mModal').find("#" + ret.err_softpro_field).empty().trigger('change').select2('open');
          else
            $('#mModal').find("input[name='" + ret.err_softpro_field + "']").select().focus();


        } else { // No Error In Softpro 

          if (ret.error) {
            $('#mModal').find('.alert-warning span.errmsg').html(ret.errmsg);
            setTimeout(function () {
              if (ret.errtype == 1) { // select2
                $('#mModal').find("#" + ret.errfield).empty().trigger('change').select2('open');
                $('#mModal').find("#" + ret.errfield).change(function () {
                  $('#mModal').find('.alert-warning').hide();
                });
              } else {
                $('#mModal').find("input[name='" + ret.errfield + "']").select().focus();
                $('#mModal').find("input[name='" + ret.errfield + "']").change(function () {
                  $('#mModal').find('.alert-warning').hide();
                });
              }
              $('#mModal').find('.alert-warning').show();
            }, 200);
            return false;
          } else {
            $('#mModal').find('.alert-warning').hide();
            //-- complate
            //reset all input form
            $('.modal').find('form')[0].reset();
            //reset select2
            $('#mModal').find('#fcucode').empty().trigger('change'); //.select2('open');
            //show data in datatable
            table.ajax.reload();

            if (ret.status_print == 1) {

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
              getObjModal('#datep').html(_print_paid.over_que_date);
              getObjModal('#datein').html(_print_paid.datein);
              getObjModal('#timein').html(_print_paid.timein);
              getObjModal('#fname').html(_print_paid.fcucode + " " + _print_paid.fcuname);
              getObjModal('#fvname').html(_print_paid.truck_number + " " + ret.fvname);
              getObjModal('#fhname').html(_print_paid.truck_head);
              getObjModal('#fsname').html(ret.fcanetype.substring(0, 20));
              getObjModal('#facode').html(_print_paid.truck_no);
              getObjModal('#fzname').html(''); // หาง
              getObjModal('#fbaht').html(ret.shiprate); // ค่าบรรทุก

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
              //Append the external CSS file.
              //frameDoc.document.write('<link href="style.css" rel="stylesheet" type="text/css" />');
              //Append the DIV contents.
              frameDoc.document.write(contents);
              frameDoc.document.write('</body></html>');
              frameDoc.document.close();
              setTimeout(function () {
                window.frames["frame1"].focus();
                window.frames["frame1"].print();
                frame1.remove();

                $('#mModal').find('#frm').show();
                $('#mModal').find('.print_bill').hide();
                //$('#mModal').find('#truck_number').select().focus();
                $('#mModal').find("#fcucode").empty().trigger('change');
                $('#mModal').find("#truck_no").empty().trigger('change');
                $('#mModal').find("#cane_type").val('').trigger('change');
                $('#mModal').find("#truck_type").val('').trigger('change');
                $('#mModal').find("#paid_type").val('0').trigger('change');
                $('#mModal').find("input[name='truck_number']").select().focus();

              }, 100);

              /** Printer Bill All  */

            } else {
              $('#mModal').modal('hide');
              alert(" เกิดข้อผิดพลาด Network Loss!!  ");

            }



          }

        }
      }
    });
  }, 100);



}