var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jPathElsx = jServerPath + "/modules/" + jModuleName + "/xlsx.php?site=" + jSiteName;
var jPathJson = jServerPath + "/libs/jsonDb.php?site=" + jSiteName;

$.getScript(jServerPath + "/assets/js/jquery.mask.js");

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
      "complete": function(json) {
        ret_data = json.responseJSON;
        jLog(json.responseJSON);
      }
    }
  });

  $('#customer-table tbody').on('click', 'tr', function(e) {
    e.preventDefault();
    var row = table.row(this).data();
    var dbdata = [];
    //dbdata = ret_data.dbdata[row[0]];
    _print(row, dbdata);

  });
  $("#searchbox").keyup(function() {
    table.search(this.value).draw();
  });
}

$('.btn-add').click(function() {
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
    beforeSend: function() {
      $(".loading").show();
    },
    success: function(ret) {
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
      setTimeout(function() {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();
        $('#mModal').find('.print_bill').hide();
        $('#mModal').modal('hide');
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
  $('#mModal').on('show.bs.modal', function(event) {
    $(".loading").hide();
  });
  $('#mModal').modal({
    show: true
  });
  $('#mModal').on('shown.bs.modal', function() {

    jLog('load q type : ' + getObjModal('#rType').val());
    if (getObjModal('#rType').val() == 1) { // รถส่วนตัว
      getObjModal('.div_qoata').hide();
      getObjModal('.div_canetype').hide();
    }
    getObjModal('#barcode').mask('00-0000');

    $('input:text:visible:first', this).focus();

    //jSelect2Data_new($('#mModal').find('#fcucode'), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));
    jSelect2Data_new($('#mModal').find('#truck_no'), 'เลือกเลขที่สั่งงาน', jPathJson, 'freqno', $('#mModal'));
    jSelect2NoJson('mModal', 'paid_type');
    jSelect2NoJson('mModal', 'cane_type');
    jSelect2NoJson('mModal', 'truck_type');
    //-- เลือกประเภทคิว
    getObjModal('#paid_type').change(function() {

      $('#mModal').find('.alert-warning').hide();
      if ($(this).val() * 1) {

        jLog('รถร่วม');
        getObjModal('.paid_type0').hide();
        getObjModal('.paid_type1').show();
        //getObjModal('#truck_no').show();
        getObjModal('.div_canetype').show();
        //getObjModal('.paid_type1').hide();

        setTimeout(function() {
          jLog('--focus truck_number ---');
          getObjModal('#truck_no').empty().trigger('change').select2('open');
        }, 100);

      } else {
        jLog('รถส่วนตัว');
        onBarcodeFocus();

        $('#mModal').find('.alert-warning').hide();
        getObjModal('.paid_type1').hide();

        getObjModal('.div_barcode').show();
        getObjModal('.div_qoata').hide();
        getObjModal('.div_canetype').hide();


        setTimeout(function() {
          getObjModal('#fcucode').empty().trigger('change');
          getObjModal('#truck_head').val('');
        }, 100);
      }
    });
  });

  getObjModal('#truck_no').change(function(){
    getObjModal('.div_canetype').show();
  });

  getObjModal('#barcode').keyup(function(e) {
    if (!is_value($(this).val())) {
      onBarcodeFocus();
      return false
    };
    if (e.keyCode == 13) {
      getObjModal('#barcode').blur();
    }
  });

  getObjModal('#barcode').blur(function(e) {
    if (!is_value($(this).val())) {
      onBarcodeFocus();
      return false;
    }
    sendBarcode($(this).val());
  });

  function onBarcodeFocus() {
    setTimeout(function() {
      getObjModal('.lbl_carno').text('');
      getObjModal('.div_qoata').hide();
      getObjModal('.div_canetype').hide();
      getObjModal('#barcode').val('').select().focus();
    }, 100);
  }

  async function sendBarcode(keycode) {

    try {

      const _data = await jAjax(jPath, 'getcarno', {
        keycode: keycode,
        qtype: getObjModal('#paid_type').val()
      });
      jLog(_data);

      if (is_value(_data.checkq)) {
        /*
                jAlert({
                  title: _data.msg_hasQ
                }).then();
                */
        swal(_data.msg_hasQ);
        onBarcodeFocus();

      } else {
        getObjModal('.lbl_carno').text(_data.dataCar['carno']);
        var fcucode = _data.dataCar.key_fcucode;
        if (fcucode) {
          getObjModal('.div_qoata').show();
          jSelect2Data_new($('#mModal').find('#fcucode'), 'เลือกชาวไร่', jPathJson + '&fcucode=' + fcucode, 'rd01cust-scan', $('#mModal'));
          getObjModal('#truck_type').val(_data.dataCar.cartype_id).attr('readonly', 'readonly').trigger('change');
          getObjModal('#truck_number').val(_data.dataCar.carno).attr('readonly', 'readonly');
          getObjModal('#truck_head').val(_data.dataCar.hcarno).attr('readonly', 'readonly');
          getObjModal('#fcucode').empty().trigger('change').select2('open');

          var carno = getObjModal('#truck_number').val();
          var cartype_id = getObjModal('#truck_type').val();
          var hcarno = getObjModal('#truck_head').val();
          jLog(carno + ", " + cartype_id + ", " + hcarno);
        } else {
          jAlertWarning({
            title: "ไม่พบข้อมูลรหัสบัตรรถชาวไร่ !!!"
          }).then(function(ok) {
            if (ok)
              onBarcodeFocus();
          });
        }

      }
    } catch (err) {
      alert("ระบบมีปัญหา ติดต่อเจ้าที่ หรือ กดปุ่ม F5 ");
      jLog(err);
    }
  }

  $('#frm').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });

  getObjModal('#rType').click(function() {

    actQ(0);
  });

  getObjModal('#rType1').click(function() {

    actQ(1);
  });

  function actQ(valx) {
    getObjModal('#paid_type').val(valx).trigger('change');
  }



  getObjModal('#fcucode').change(function() {
    if (!is_value($(this).val())) return false;
    getObjModal('.div_canetype').show();

    jLog("fcucode is Changed.");

    var x_fcucode = ($(this).val());
    var track_number = chkQtime();
    //  getObjModal('#btn-save').hide();

    var imgLoading = "<img src='" + jServerPath + "/assets/images/ajax_load.gif' style=' height: 15px; width:15px' /> กำลังตรวจสอบข้อมูล...";
    getObjModal('.fstatus').html(imgLoading);
    $('.show_quata_s').hide();
    $('.show_qdate').hide();
    var x_status = '-สถานะการส่งอ้อยชาวไร่-';

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
      success: function(data) {
        getObjModal('.fstatus').html(x_status);
        getObjModal('#cane_type').val('').trigger('change').select2('open');

      }
    });
  });
}

$('.modal').on('hidden.bs.modal', function() {
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

  var inputBarcode = getObjModal('#barcode').val();
  if (!is_value(inputBarcode) && getObjModal('#rType').val() != 1) {
    swal('รหัสบัตรว่าง.');
    onBarcodeFocus();
  }

  jLog("Submit Form");

  var formData = JSON.stringify($('#mModal').find("#frm").serialize());
  var fcartype = $('#mModal').find('#truck_type').select2('data')[0].text;
  var fcanetype = $('#mModal').find('#cane_type').select2('data')[0].text;

  if ($('#mModal').find('#truck_no').select2('data').length > 0)
    var ftrackno = $('#mModal').find('#truck_no').select2('data')[0].text;

  setTimeout(function() {
    $.ajax({
      type: 'POST',
      url: jPath + "&mode=add&fvname=" + fcartype + "&fcanetype=" + fcanetype + "&truckno=" + ftrackno,
      data: formData,
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {

        $('#mModal').find('.alert-warning').hide();
        jLog('--- return add ---');
        jLog(ret);
        $(".loading").hide();
        //return false;
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
            setTimeout(function() {
              if (ret.errtype == 1) { // select2
                $('#mModal').find("#" + ret.errfield).empty().trigger('change').select2('open');
                $('#mModal').find("#" + ret.errfield).change(function() {
                  $('#mModal').find('.alert-warning').hide();
                });
              } else {
                $('#mModal').find("input[name='" + ret.errfield + "']").select().focus();
                $('#mModal').find("input[name='" + ret.errfield + "']").change(function() {
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
              setTimeout(function() {
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