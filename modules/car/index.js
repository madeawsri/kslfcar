var jPath = jServerPath + "/modules/" + jModuleName + "/db.php?site=" + jSiteName;
var jPathElsx = jServerPath + "/modules/" + jModuleName + "/xlsx.php?site=" + jSiteName;
var jPathJson = jServerPath + "/libs/jsonDb.php?site=" + jSiteName;

var jPathCard = jServerPath + "/modules/" + jModuleName + "/card.php?site=" + jSiteName;


var jPathMain = jPath;
! function(document, window, $) {
  "use strict";


  /*---- Select2 Zone Ket ----*/
  showData();
}(document, window, jQuery);


var _car_max = 0 ;
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
      //'url': '//cdn.datatables.net/plug-ins/1.10.19/i18n/Thai.json',
      "oPaginate": {
        "sFirst": "<", // This is the link to the first page
        "sPrevious": "«", // This is the link to the previous page
        "sNext": "»", // This is the link to the next page
        "sLast": ">" // This is the link to the last page
      },
      //"sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว",
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
    var ftype = row[4].split('-');
    var carno = row[3];
    jAdd(carno + '|' + ftype[0]);
  });

  $("#searchbox").keyup(function() {
    table.search(this.value).draw();
  });
}

$('.btn-add').click(function() {
  jAdd();
});

var myBackup = $('#mModal').clone();
/**
 * FUNTION ADD
 */
function jAdd(p_carno) {
  // show the modal onload.
  $(".loading").show();
  $('#mModal').modal('hide').remove();
  var myClone = myBackup.clone();
  $('body').append(myClone);
  $('.show_quata_s').hide();
  $('.show_qdate').hide();
  $('#mModal').find('#frm').show();
  $('#mModal').find('.print_bill').hide();
  $('#mModal').find("#btn-save").hide();
  $('#mModal').find("#btn-cancel").show();
  $('#mModal').find("#btn-edit").hide();
  $('#mModal').find("#btn-del").hide();
  $('#mModal').find("#btn-carval").hide();
  $('#mModal').on('show.bs.modal', function(event) {
    $(".loading").hide();
  });
  $('#mModal').modal({
    show: true
  });

  $('#mModal').on('shown.bs.modal', function() {
    // z-index: 99999;
    $('input:text:visible:first', this).focus();
    jSelect2Data_new($('#mModal').find('.fcucode'), 'เลือกชาวไร่', jPathJson, 'rd01cust', $('#mModal'));
    jSelect2Data_tags($('#mModal').find('.carno'), 'เลือกทะเบียนรถ', jPathJson, 'regcar_db', $('#mModal'));
    jSelect2NoJson('mModal', 'txt_zks');
    jSelect2NoJson('mModal', 'car_type');

    if (p_carno) {
      jLog(p_carno);
      var carX = p_carno.split('|');
      var newOption = new Option(carX[0] + ' : ' + carX[1], p_carno, true, true);
      getObjModal('#truck_number').append(newOption).select2().trigger('change');
    } else {
      getObjModal('#truck_number').trigger('change').select2('open');
    }

  });
  $('#truck_number').on('select2:select', function(e) {

    jLog($(this).val());

  });
  getObjModal('.loader').html("<p style='font-size:14px;padding:0.3px;color:red;'>-ไม่พบข้อมูล-</p>");
  getObjModal('.step1').hide();
  getObjModal('.step2').hide();
  getObjModal('.panel-fcucode').hide();
  getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);

  /*******************
   ***** STEP #1 *****
   *******************/
  getObjModal('#truck_number').change(function(e) {
    $('.total-farm').html(0);
    var carno = $(this).val();
    //jLog('Action truck_number to Change!! value is = ' + carno);
    if (!carno) return false;
    $('#mModal').find('.step2').hide();
    $.ajax({
      type: 'POST',
      url: encodeURI(jPath + "&mode=check_car&carno=" + carno + ""),
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        jLog("truck number changed");
        jLog(ret);
        $(".loading").hide();
        info_car('', ret.carno, '', '');
        $('.total-farm').html(ret.data_car.fsend);

        getObjModal('.step1').show();
        if (Object.keys(ret.data_car).length > 0) {
          var zks = ret.data_car.zks.split('/');
          getObjModal('.keycode').html( (ret.data_car.id)); //zks[1] + "-" +
          getObjModal('#car_type').val(ret.data_car.cartype_id).trigger('change');

          getObjModal('#btn-del').show();
          getObjModal('#btn-carval').show();
          check_data_car(ret);

        } else {
          getObjModal('#car_type').val('').trigger('change').select2('open');
          getObjModal('#btn-del').hide();
          getObjModal('#btn-carval').hide();
        }


      }
    });
  });
  /*******************
   ***** STEP #2 *****
   *******************/
  var _xsend = 0;
  
  getObjModal('#car_type').change(function(e) {
    jLog('cartype changed');
    var carno = $('#mModal').find('#truck_number').select2('data')[0].id;
    var cartype_text = $('#mModal').find('#car_type').select2('data')[0].text;
    var cartype_id = $(this).val();
    if (!cartype_id) return false;
    $.ajax({
      type: 'POST',
      url: encodeURI(jPath + "&mode=check_cartype&carno=" + carno + "&cartype_id=" + cartype_id),
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        
        _xsend = ret.data_car.xsend;
        _car_max = ret.cartype_value;
        jLog('set _car_max '+_car_max);
        jLog(ret);
        getObjModal('.step2').show();
        zks = '';
        car_value = '';

        if (Object.keys(ret.data_car).length > 0) {

          cartype_text = ret.data_car.cartype_text;
          zks = ret.data_car.zks;
          car_value = ret.data_car.fsend;
          getObjModal('#txt_zks').removeAttr('readonly').val(zks).attr('readonly', 'readonly').trigger('change');
          getObjModal('#car_type').removeAttr('readonly').val(ret.data_car.cartype_id).attr('readonly', 'readonly'); //.trigger('change');
          getObjModal('#truck_number').attr('readonly', 'readonly');

          var fcartype = $('#mModal').find('#car_type').select2('data')[0].text;
          var car_max = ret.data_car.car_val - ret.data_car.fsend;

          

          if (car_max <= 0) {
            stop_inputform();
          }

          info_car(ret.data_car.zks, ret.data_car.carno, fcartype, car_max,_car_max)

          if (!getObjModal('.fresult').text()) {
            jLog('update fresult -> cartype_change.');

            var _fcucode = ret.data_car.fcucode.split(',');
            var _fsend = ret.data_car.fsends.split(',');
            dataF = _fcucode;
            jQuery.each(dataF, function(i, val) {
              fcucode = val;
              fton = _fsend[i];
              FnNewElementFarm(fcucode, fton, ret.data_car.carno, ret.data_car.zks, ret.data_car.cartype_id);
            });
          }
          //jLog('Cartype_id' + ret.cartype_id);
          if (ret.cartype_id == 'C3') {
            getObjModal('.div-hcarno').show();
            var newOption = new Option(ret.data_car.hcarno, ret.data_car.hcarno, false, false);
            $('#hcarno').append(newOption).val(ret.data_car.hcarno).attr('readonly', 'readonly').trigger('change');


          } else {
            getObjModal('.div-hcarno').hide();
          }


        } else {
          car_value = ret.cartype_value;
          getObjModal('#txt_zks').val('').trigger('change').select2('open');
          info_car(zks, ret.carno, cartype_text, car_value,_car_max);
        }



        $(".loading").hide();
      }
    });
  });
  /*******************
   ***** STEP #3 *****
   *******************/
  getObjModal('#txt_zks').change(function(e) {
    jLog(" zks is change");

    //if($(".lbl_max").text())
    
 

    var carno = $('#mModal').find('#truck_number').select2('data')[0].id;
    carno = carno.split('|');
    carno = carno[0];
    var cartype_text = $('#mModal').find('#car_type').select2('data')[0].text;
    var cartype_id = $('#mModal').find('#car_type').select2('data')[0].id;
    var zks = $(this).val();
    if (!zks) return false;
    var lblZKS = getObjModal('.lbl_zks').text();


    getObjModal('.panel-fcucode').show();

    if (cartype_id == "C3") {
      // checking data hcar
      var check_hcarno = jValue(jPath, 'check_hcar', {
        zks: zks,
        carno: carno
      });

      // has data hcar
      if (is_value(check_hcarno.data.hcarno)) {

        getObjModal('.div-hcarno').show();
        var data_hcarno = jSelect2Data_new($('#mModal').find('.hcarno'), 'เลือกหัวพ่วง', jPathJson + "&zks=" + zks, 'list_head_carno', $('#mModal'));
        getObjModal('.hcarno').val(check_hcarno.data.hcarno).trigger("change");

        getObjModal('#truck_number').attr('readonly', 'readonly').attr("disabled", true);
        getObjModal('#car_type').attr('readonly', 'readonly').attr("disabled", true);
        getObjModal('#txt_zks').attr('readonly', 'readonly').attr("disabled", true);

      } else {



        var listHCarno = jValue(jPath, 'list_hcarno', {
          zks: zks
        });

        if (listHCarno.data.length) {
          jLog('Has HCarno ');
          getObjModal('.div-hcarno').show();
          jSelect2Data_tags(getObjModal('#hcarno'), 'เลือกทะเบียนหัว', jPathJson + "&zks=" + zks, 'list_head_carno', $('#mModal'));
          $('#hcarno').trigger('change').select2('open');
        } else {

          jLog('No HCarno ');
          setTimeout(function() {
            swal({
              icon: 'Warning!',
              text: 'กรุณาลงทะเบียนรถหัวพ่วงก่อน.',
              type: 'warning',
              allowEnterKey: true // default value
            }).then(okay => {
              if (okay) {
                $('#btn-addnew').click();
              }
            });
          }, 10);

        }


      }


    } else {
      jLog('open fcucode : _xsend = ' + _xsend);
      getObjModal('.div-hcarno').hide();
      

      getObjModal('#truck_number').attr('readonly', 'readonly').attr("disabled", true);
      getObjModal('#car_type').attr('readonly', 'readonly').attr("disabled", true);
      getObjModal('#txt_zks').attr('readonly', 'readonly').attr("disabled", true);

      jLog(getObjModal('.lbl_cartype').html());
      jLog(_xsend);
      if(_xsend > 0 || _xsend == undefined)
         getObjModal('#fcucode').val('').trigger('change').select2('open');
         //_xsend = 0;

    }

    getObjModal('.lbl_zks').text($(this).val());
    getObjModal('.lbl_zks_name').text($(this).find("option:selected").text());

  });

  getObjModal('.hcarno').change(function() {
    jLog('hcarno is change');
    if (!$(this).val()) return;

    if(_xsend > 0)
      getObjModal('#fcucode').val('').trigger('change').select2('open');
  });

  getObjModal('#fcucode').change(function() {
    
    jLog('fcucode is change');
    if (!$(this).val()) return;
    //jLog($(this).val());
    var fcartype_id = $('#mModal').find('#car_type').select2('data')[0].id;
    var fzks_id = $('#mModal').find('#txt_zks').select2('data')[0].id;
    var fzks_text = $('#mModal').find('#txt_zks').select2('data')[0].text;
    var carno = $('#truck_number').val();
    var fcucode = $(this).val();
    var fname = $('#mModal').find('#fcucode').select2('data')[0].text;
    var params = fcucode + "|" + carno + "|" + fzks_id + "|" + fcartype_id ;
    $.ajax({
      type: 'POST',
      url: encodeURI(jPath + "&mode=check_ket&params=" + params + "&fname="+fname),
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        $(".loading").hide();
        getObjModal('.svalue').show();
        getObjModal('.fsvalue').text(ret.frrqty);
        getObjModal('.fsxvalue').text(ret.frrqty - ret.xfrrqty);
        jLog(ret);
        
        if (ret.fcheck) {
          swal('ได้ลงทะเบียนเรียบร้อยแล้ว', 'โควต้า ' + fcucode, 'success');
          pass_inputform();
          getObjModal('.svalue').hide();
          getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);
          return;
        } else if (!ret.check_ket ) {

          if(!ret.chk_ket_x){

            swal('ไม่สามารถลงทะเบียนข้ามเขตได้.', 'โควต้า ' + fcucode + ' ได้ลงทะเบียนเขต <span class="bb uu ">' + ret.data_ket + '</span>', 'warning');
            pass_inputform();
            getObjModal('.svalue').hide();
            getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);
            return;
          }

        }
        setTimeout(function() {
          getObjModal('#fsend').select().focus();
        }, 100);
      }
    });

  });

  getObjModal('#fsend').keyup(function(e) {
    var xvalue = parseInt(getObjModal('.fsxvalue').text(), 10);
    var fsend = parseInt($(this).val(), 10);
    if (e.keyCode == 13) {
      if (fsend <= 0 || fsend > xvalue) {
        swal('ไม่อนุญาตใส่จำนวนตันมากกว่าจำนวนสัญญาคงเหลือ.', 'จำนวนสัญญาคงเหลือ ' + xvalue + ' ตัน', 'warning');
        getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);
        $(this).select().focus();
        return;
      }
      getObjModal('#btnRegCar').removeAttr('readonly').attr("disabled", false).focus();
    }
  });

  // action printer card for register
  getObjModal('#btn-save').click(function() {

    var zks = getObjModal('#txt_zks').select2('data')[0].id;
    var carno = getObjModal('#truck_number').select2('data')[0].id;
    var cartype = getObjModal('#car_type').select2('data')[0].id;

    $.ajax({
      type: 'POST',
      url: encodeURI(jPathCard + "&mode=check_car&zks=" + zks + "&carno=" + carno + "&cartype=" + cartype),
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        jLog(ret);
        $(".loading").hide();
        //var w = window.open(ret.data, '_blank');

        const win = window.open("", "_blank");
        let html = '';

        html += '<html>';
        html += '<body style="margin:0!important">';
        html += '<embed width="100%" height="100%" src="' + ret.data + '" type="application/pdf" />';
        html += '</body>';
        html += '</html>';

        setTimeout(() => {
          win.document.write(html);
        }, 0);

      }
    });




  });

  getObjModal('#btn-addnew').click(function() {
    add_new();
  });

  getObjModal('#btn-del').click(function() {

    var carno = getObjModal("#truck_number").val();
    carno = carno.split('|');
    carno = carno[0];
    var cartype = getObjModal('#car_type').val();
    var zks = getObjModal('#txt_zks').val();

    jConfirmDelete({
      text: "คุณต้องการยกเลิก ทะเบียนรถ '" + carno + "' "
    }).then(function(ok) {
      if (ok) {
        var data = jPost(jPath, "del_car", {
          carno: carno,
          cartype: cartype,
          zks: zks
        });
        table.ajax.reload();
        getObjModal('#btn-cancel').click();
        jLog(data);
      }
    });


  });

/*** update CAR_VAL */
// https://www.fera.ai/bower_components/sweetalert2/
async function jEditCarVal() {
  let vMax = getObjModal('.lbl_max_x').html();
  swal({
    input: 'number', // can be also 'email', 'password', 'select', 'radio', 'checkbox', 'textarea', 'file'
    text: ' ยอดส่งเดิมทั้งหมด ' + vMax + ' ตัน ',
    inputValue: vMax,
    inputPlaceholder: ' กรอกจำนวนที่ต้องการเพิ่ม (ตัน) ',
    confirmButtonText: 'อนุมัติยอดส่ง &rarr;',
  }).then(function (result) {

    swal({
      title: "ยืนยันการอนุมัติเพิ่มยอดส่ง ?",
      html: "ยอดส่งเดิม คือ " + vMax + " ตัน เพิ่มขั้น  " + result + " ตัน  <br> รวมทั้งหมด " + (vMax * 1 + result * 1) + " ตัน ",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "อนุมัติยอดส่ง",
      closeOnConfirm: false
    }).then(
     function (ok) {
        if (ok){
          let CodeID = getObjModal('.keycode').text();
          let new_data =  $.ajax({
            type: 'POST',
            async:false,
            url: jPath + "&mode=add_car_val&code="+CodeID+"&new_carval="+ (result * 1),
            contentType: "application/json",
            dataType: 'json'  }).responseJSON;
          
          
         if(new_data.ok){
          
            swal("ได้ดำเนินการอนุมัติยอดส่งร้อยเรียบแล้ว", "success").then(function () {
              setTimeout(add_new(), 100);
              table.ajax.reload();
            }).catch(swal.noop);

         }

          
        }
      }).catch(swal.noop);
  }).catch(swal.noop);

}

  function add_new() {

    getObjModal('.keycode').html('00-0000');
    getObjModal('#btn-del').hide();
    getObjModal('#btn-carval').hide();
    getObjModal('#btn-save').hide();
    getObjModal('#car_type').removeAttr('readonly');
    getObjModal('#txt_zks').val('').removeAttr('readonly');
    getObjModal('#fcucode').val('').removeAttr('readonly');
    getObjModal('#fsend').val('20').removeAttr('readonly');
    


    jSelect2Data_tags($('#mModal').find('.carno'), 'เลือกทะเบียนรถ', jPathJson, 'regcar_db', $('#mModal'));

    jSelect2NoJson('mModal', 'txt_zks');
    jSelect2NoJson('mModal', 'car_type');
    getObjModal('.loader').html("<p style='font-size:14px;padding:0.3px;color:red;'>-ไม่พบข้อมูล-</p>");
    getObjModal('.step1').hide();
    getObjModal('.step2').hide();
    getObjModal('.panel-fcucode').hide();
    getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);
    getObjModal('.fresult').html('');

    getObjModal('#truck_number').removeAttr('readonly').attr("disabled", false);
    getObjModal('#truck_number').empty().trigger('change').select2('open');
  }

  
  getObjModal('#btn-carval').click(function () {
    jEditCarVal();
    
  });


}

/** function reg car */
function stop_inputform() {
  getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);
  getObjModal('#fsend').attr('readonly', 'readonly').val('0').trigger('change');
  getObjModal(".fcucode").attr('readonly', 'readonly').val('').trigger('change');
}

function pass_inputform() {
  jLog("pass input");
  getObjModal('#btnRegCar').removeAttr('readonly').attr("disabled", false);
  getObjModal('#fsend').removeAttr('readonly').val('0').trigger('change');
  getObjModal(".fcucode").removeAttr('readonly').val('').trigger('change');
}

function info_car(zks, carno, cartype, value_max,_car_max) {
  if ($('#mModal').find('#txt_zks').select2('data')[0] != undefined)
    var zks_name = $('#mModal').find('#txt_zks').select2('data')[0].text;
  zks_name = (zks_name) ? zks_name : '';

  

  getObjModal('.loader').html("<p style='font-size:14px;padding:0.3px;'>โซน/ เขต/ สาย : <span class='bb uu lbl_zks'>" + zks + "</span></p>" +
    "<p style='font-size:14px;padding:0.3px;'>นักสำรวจ : <span class='bb uu lbl_zks_name'>" + zks_name + "</span></p>" +
    "<p style='font-size:14px;padding:0.3px;'>ทะเบียนรถ : <span class='bb uu lbl_carno'>" + carno + "</span></p>" +
    "<p style='font-size:14px;padding:0.3px;'>ประเภทรถ : <span class='bb uu lbl_cartype'>" + cartype + "</span></p>" +
    "<p style='font-size:14px;padding:0.3px;'>ยอดส่ง <span class='bb uu lbl_max_x text-info'>" + _car_max + "</span> ตัน  คงเหลือ <span class='bb uu lbl_max text-warning'>" + value_max + "</span> ตัน </p>");
}

function check_data_car(ret) {
  jLog('show f*');
  jLog(ret.check_data);
  chkData = ret.check_data;
  getObjModal('.fresult').html('');
  if (chkData) {
    jLog(chkData);
    getObjModal('.step1').show();
    getObjModal('.step2').show();


    //getObjModal('#truck_number').attr('readonly', 'readonly');
    getObjModal('#truck_number').attr('readonly', 'readonly');
    getObjModal('#car_type').removeAttr('readonly').val(chkData.cartype_id).select2().attr('readonly', 'readonly');
    getObjModal('#txt_zks').removeAttr('readonly').val(chkData.zks).select2().attr('readonly', 'readonly').trigger('change');

    var fcartype = $('#mModal').find('#car_type').select2('data')[0].text;
    var fcartype_id = $('#mModal').find('#car_type').select2('data')[0].id;
    var car_max = chkData.car_val - chkData.fsend;
jLog('car_val = ' + chkData.car_val );
    if (car_max <= 0) {
      stop_inputform();
    }

    info_car(chkData.zks, chkData.carno, fcartype, car_max,_car_max)

    //-- display farn
    dataF = ret.data_f;
    jQuery.each(dataF, function(i, val) {
      fcucode = val.fcucode;
      fton = val.fsend;
      FnNewElementFarm(fcucode, fton, chkData.carno, chkData.zks, chkData.cartype_id);
    });
  }
}

/** Create Form */
function submitForm() {

  getObjModal('#truck_number').attr("disabled", false);
  getObjModal('#car_type').attr("disabled", false);
  getObjModal('#txt_zks').attr("disabled", false);

  var formData = JSON.stringify(getObjModal("#frm").serialize());

  var fcartype = getObjModal('#car_type').select2('data')[0].text;
  var fcuname = null;
  if (getObjModal('#fcucode').val())
    fcuname = getObjModal('#fcucode').select2('data')[0].text;

  var fcartype_id = getObjModal('#car_type').select2('data')[0].id;
  var fzks_id = getObjModal('#txt_zks').select2('data')[0].id;
  var carno = getObjModal('#truck_number').val();
  var fzks_text = getObjModal('#txt_zks').select2('data')[0].text;

  var hcarno = getObjModal('#hcarno').val();
  var fsend = getObjModal('#fsend').val();




  if (!is_value(fcuname)) {
    swal(
      'Warning!',
      'กรุณาเลือกโควต้า.',
      'warning'
    ).then(okay => {
      if (okay) {
        getObjModal('#fcucode').val('').trigger('change').select2('open');
      }
    });
  } else if (parseInt(fsend) <= 0) {
    swal(
      'Warning!',
      'กรอกข้อมูล มากกว่า 0 ',
      'warning'
    ).then(okay => {
      if (okay) {
        getObjModal('#fsend').select().focus();
      }
    });
  } else if (fcartype_id == 'C3' && !is_value(hcarno)) {

    swal(
      'Warning!',
      'กรุณาลงทะเบียนรถหัวพ่วงก่อน.',
      'warning'
    ).then(okay => {
      if (okay) {
        getObjModal('#hcarno').val('').trigger('change').select2('open');
      }
    });

  } else {



    setTimeout(function() {
      $.ajax({
        type: 'POST',
        url: encodeURI(jPath + "&mode=add&fvname=" + fcartype + "&fcuname=" + fcuname + "&zksname=" + fzks_text),
        data: formData,
        dataType: 'json',
        cache: false,
        beforeSend: function() {
          getObjModal('#btn-save').hide();
          $(".loading").show();
        },
        success: function(ret) {
          jLog(" Add Database. ");
          jLog(ret);
          $(".loading").hide();

          var fcucode = ret.req.fcucode;
          var fton = ret.req.fsend;

          $('.total-farm').html(parseInt(fton)+parseInt($('.total-farm').text()));
          FnNewElementFarm(fcucode, fton, carno, fzks_id, fcartype_id);

          // update value car max
          calc_car_value(fton, false);

          getObjModal('.svalue').hide();
          getObjModal(".fcucode").val('').trigger('change').select2('open');
          getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);

          // Lock Reg Car
          getObjModal('#truck_number').attr('readonly', 'readonly');
          getObjModal('#txt_zks').attr('readonly', 'readonly');
          getObjModal('#car_type').attr('readonly', 'readonly');

          if (fcartype_id == 'C3')
            getObjModal('#hcarno').attr('readonly', 'readonly');

          var keycode = getObjModal('.keycode');
          if (keycode.html() == '00-0000')
            keycode.html(ret.keyCode);

          //getObjModal('#btn-save').show();
          table.ajax.reload();

        }
      });
    }, 100);

  }





}

function calc_car_value(fton, up = true) {


  lbl_max = parseInt($('.lbl_max').text(), 10);
  fton = parseInt(fton, 10);
  value_max = (up) ? lbl_max + fton : lbl_max - fton;
  getObjModal('.lbl_max').text(value_max);

  if (value_max <= 0) { // stop input
    stop_inputform();
  } else {
    pass_inputform();
  }
  return value_max;

}

function jAct_delete(obj, carno, zks, cartype, fton) {
  if (confirm('คุณต้องการลบข้อมูลหรือไม่? ' + obj)) {

    $.ajax({
      type: 'POST',
      url: encodeURI(jPath + "&mode=del_fcar&carno=" + carno + "&zks=" + zks + "&cartypeid=" + cartype + "&fcucode=" + obj),
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        var k_val = -parseInt(fton)+parseInt($('.total-farm').text());
        $('.total-farm').html(k_val);
        //$('.lbl_max').html(k_val);

        jLog(ret);
        $(".loading").hide();
        $('.id' + obj).remove();

        // update value car max
        calc_car_value(fton);

        table.ajax.reload();


      }
    });


  }
  jLog("delete id : " + obj);
}

function FnNewElementFarm(fcucode, fton, carno, zks, cartype) {
  getObjModal('.fresult').append(`
  <div class="col-lg-3 ppp"><div class="badge-round"><span class="rs_fcucode">0000000</span> <span class='ton-color bb uu rs_ton'>1,000</span> ตัน </div>
</div>`);
  $('.ppp').last().find(".rs_fcucode").html(fcucode);
  $('.ppp').last().find(".rs_ton").html(fton);
  $('.ppp').last().find('.badge-round').append('<a href="javascript:jAct_delete(\'' + fcucode + '\',\'' + carno + '\',\'' + zks + '\',\'' + cartype + '\',\'' + fton + '\');" class="act_bin"><i class="bin-color bb fa fa-trash-o"></i></a>');
  $('.ppp').last().addClass('id' + fcucode);
}
/**
 * ========================================================
 * ========================================================
 */

$('.modal').on('hidden.bs.modal', function() {
  //$('.modal').find('form')[0].reset();
  //location.reload();
});

function isNanToZero(x) {
  return isNaN(x) ? 0 : x;
}

function getObjModal(txtObj) {
  return $('#mModal').find(txtObj);
}
/**
 * ========================================================
 * EDIT FORM
 * ========================================================
 */
function info_edit(zks, carno, cartype, value_max, value_maxx) {
  getObjModal('.loader2').html("<p style='font-size:22px;padding:0.3px;'>โซน/ เขต/ สาย : <span class='bb uu lbl_zks'>" + zks + "</span></p>" +
    "<p style='font-size:22px;padding:0.3px;'>ทะเบียนรถ : <span class='bb uu lbl_carno'>" + carno + "</span></p>" +
    "<p style='font-size:22px;padding:0.3px;'>ประเภทรถ : <span class='bb uu lbl_cartype'>" + cartype + "</span></p>" +
    "<p style='font-size:22px;padding:0.3px;'>สัญญาสูงสุด : <span class='bb uu lbl_cartype text-success'>" + value_maxx + "</span> ตัน</p>" +
    "<p style='font-size:22px;padding:0.3px;'>ยอดส่งคงเหลือ : <span class='bb uu lbl_max text-warning'>" + value_max + "</span> ตัน </p>");
}

function info_edit_farm(fcucode, fcuname, val1, val2) {
  getObjModal('.loader3').html("<p style='font-size:22px;padding:0.3px;'>โควต้า : <span class='bb uu lbl_fcucode'>" + fcucode + "</span></p>" +
    "<p style='font-size:22px;padding:0.3px;'>ชื่อ-สกุล : <span class='bb uu lbl_fcuname'>" + fcuname + "</span></p>" +
    "<p style='font-size:22px;padding:0.3px;'>สัญญาโรงงาน : <span class='bb uu lbl_svalue text-success'>" + val1 + "</span>  ตัน </p>" +
    "<p style='font-size:22px;padding:0.3px;'>สัญญาคงเหลือ : <span class='bb uu lbl_sxvalue text-warning'>" + val2 + "</span> ตัน </p>");
}


function jConfirmKet(fcucode,ket,fname){
   //jLog(fcucode + ',' + ket);

   
  jConfirmInfo({
    text: 'ยืนยันการอนุมัติข้ามเขต โควต้า '+ fcucode + ' ส่งเขต ' + ket +'  ? '
  }).then(function(ok) {
    if (ok) {
      var data = jValue(jPath, "overket", {
        'fcucode': fcucode,
        'ket':ket,
        'fname':fname
      });
      jLog(data);
     /* if (data.uppay) {
        swal(
          'Success!',
          'ได้จ่ายบัตรรถชาวไร่เรียบร้อยแล้ว.',
          'success'
        ).then(okay => {
          if (okay) {
            $('.btn-warning').click();
          }
        });

      }*/
    }
  });


}


