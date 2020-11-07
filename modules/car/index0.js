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
    //var fket = row[0];
    var ftype = row[5].split('-');
    //var fcode = row[3].split('-');
    var carno = row[4];
    //var key = fket + '|' + ftype[0] + '|' + carno + '|' + fcode[0];
    //var keyCar = fket + '|' + ftype[0] + '|' + carno;

    jAdd(carno + '|' + ftype[0]);

    //dbdata = ret_data.data2[key];
    //dbsumcar = ret_data.sumcar[keyCar];
    //dbfff = ret_data.sum_farm[fcode[0]];
    //data_info(row, dbdata, dbsumcar, dbfff, key);

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

        $(".loading").hide();
        info_car('', ret.carno, '', '');
        if (!ret.cartype_id) {
          getObjModal('.step1').show();
          getObjModal('#car_type').val('').trigger('change').select2('open');
        }
        if (ret.data_f.length > 0) {

          getObjModal('#btn-del').show();
          check_data_car(ret);

        } else {
          getObjModal('#btn-del').hide();
        }

      }
    });
  });
  /*******************
   ***** STEP #2 *****
   *******************/
  getObjModal('#car_type').change(function(e) {

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
        jLog(ret);
        getObjModal('.step2').show();
        zks = '';
        car_value = '';
        if (ret.data_c) {
          cartype_text = ret.data_c.cartype_text;
          zks = ret.data_c.zks;
          car_value = ret.data_c.fsend;
          getObjModal('#txt_zks').removeAttr('readonly').val(zks).attr('readonly', 'readonly').trigger('change');
          getObjModal('#car_type').removeAttr('readonly').val(ret.data_c.cartype_id).attr('readonly', 'readonly'); //.trigger('change');
          getObjModal('#truck_number').attr('readonly', 'readonly');

          var fcartype = $('#mModal').find('#car_type').select2('data')[0].text;
          //var fcartype_id = $('#mModal').find('#car_type').select2('data')[0].id;
          var car_max = ret.data_c.cartype_val - ret.data_c.fsend;

          if (car_max <= 0) {
            stop_inputform();
          }

          info_car(ret.data_c.zks, ret.data_c.carno, fcartype, car_max)

          if (!getObjModal('.fresult').text()) {
            jLog('update fresult -> cartype_change.');
            dataF = ret.data_f;
            jQuery.each(dataF, function(i, val) {
              fcucode = val.fcucode;
              fton = val.fsend;
              FnNewElementFarm(fcucode, fton, ret.data_c.carno, ret.data_c.zks, ret.data_c.cartype_id);
            });
          }


        } else {
          car_value = ret.cartype_value;
          getObjModal('#txt_zks').val('').trigger('change').select2('open');
          info_car(zks, ret.carno, cartype_text, car_value);
        }

        $(".loading").hide();
      }
    });
  });
  /*******************
   ***** STEP #3 *****
   *******************/
  getObjModal('#txt_zks').change(function(e) {
    var carno = $('#mModal').find('#truck_number').select2('data')[0].id;
    carno = carno.split('|');
    carno = carno[0];
    var cartype_text = $('#mModal').find('#car_type').select2('data')[0].text;
    var cartype_id = $('#mModal').find('#car_type').select2('data')[0].text;
    var zks = $(this).val();
    if (!zks) return false;
    var lblZKS = getObjModal('.lbl_zks').text();

    getObjModal('.lbl_zks').text($(this).val());
    getObjModal('.lbl_zks_name').text($(this).find("option:selected").text());
    getObjModal('.panel-fcucode').show();
    getObjModal('#fcucode').trigger('change').select2('open');

  });

  getObjModal('#fcucode').change(function() {
    if (!$(this).val()) return;
    //jLog($(this).val());
    var fcartype_id = $('#mModal').find('#car_type').select2('data')[0].id;
    var fzks_id = $('#mModal').find('#txt_zks').select2('data')[0].id;
    var fzks_text = $('#mModal').find('#txt_zks').select2('data')[0].text;
    var carno = $('#truck_number').val();
    var fcucode = $(this).val();
    var params = fcucode + "|" + carno + "|" + fzks_id + "|" + fcartype_id;
    $.ajax({
      type: 'POST',
      url: encodeURI(jPath + "&mode=check_ket&params=" + params),
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
        } else if (!ret.check_ket) {

          swal('ไม่สามารถลงทะเบียนข้ามเขตได้.', 'โควต้า ' + fcucode + ' ได้ลงทะเบียนเขต <span class="bb uu ">' + ret.data_ket + '</span>', 'warning');
          pass_inputform();
          getObjModal('.svalue').hide();
          getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);
          return;
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
    var zks = $('#mModal').find('#txt_zks').select2('data')[0].id;
    var carno = $('#mModal').find('#truck_number').select2('data')[0].id;
    var cartype = $('#mModal').find('#car_type').select2('data')[0].id;

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

  function add_new() {
    getObjModal('#btn-save').hide();
    getObjModal('#car_type').removeAttr('readonly');
    getObjModal('#txt_zks').val('').removeAttr('readonly');
    getObjModal('#truck_number').removeAttr('readonly');
    jSelect2Data_tags($('#mModal').find('.carno'), 'เลือกทะเบียนรถ', jPathJson, 'regcar_db', $('#mModal'));
    jSelect2NoJson('mModal', 'txt_zks');
    jSelect2NoJson('mModal', 'car_type');
    getObjModal('.loader').html("<p style='font-size:14px;padding:0.3px;color:red;'>-ไม่พบข้อมูล-</p>");
    getObjModal('.step1').hide();
    getObjModal('.step2').hide();
    getObjModal('.panel-fcucode').hide();
    getObjModal('#btnRegCar').attr('readonly', 'readonly').attr("disabled", true);
    getObjModal('.fresult').html('');
    getObjModal('#truck_number').val('').trigger('change').select2('open');
  }

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

function info_car(zks, carno, cartype, value_max) {
  if ($('#mModal').find('#txt_zks').select2('data')[0] != undefined)
    var zks_name = $('#mModal').find('#txt_zks').select2('data')[0].text;
  zks_name = (zks_name) ? zks_name : '';
  getObjModal('.loader').html("<p style='font-size:14px;padding:0.3px;'>โซน/ เขต/ สาย : <span class='bb uu lbl_zks'>" + zks + "</span></p>" +
    "<p style='font-size:14px;padding:0.3px;'>นักสำรวจ : <span class='bb uu lbl_zks_name'>" + zks_name + "</span></p>" +
    "<p style='font-size:14px;padding:0.3px;'>ทะเบียนรถ : <span class='bb uu lbl_carno'>" + carno + "</span></p>" +
    "<p style='font-size:14px;padding:0.3px;'>ประเภทรถ : <span class='bb uu lbl_cartype'>" + cartype + "</span></p>" +
    "<p style='font-size:14px;padding:0.3px;'>ยอดส่งคงเหลือ : <span class='bb uu lbl_max'>" + value_max + "</span> ตัน </p>");
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
    var car_max = chkData.cartype_val - chkData.fsend;

    if (car_max <= 0) {
      stop_inputform();
    }

    info_car(chkData.zks, chkData.carno, fcartype, car_max)

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

  var formData = JSON.stringify($('#mModal').find("#frm").serialize());
  var fcartype = $('#mModal').find('#car_type').select2('data')[0].text;
  if ($('#mModal').find('#fcucode'))
    var fcuname = $('#mModal').find('#fcucode').select2('data')[0].text;

  var fcartype_id = $('#mModal').find('#car_type').select2('data')[0].id;
  var fzks_id = $('#mModal').find('#txt_zks').select2('data')[0].id;
  var carno = $('#truck_number').val();
  var fzks_text = $('#mModal').find('#txt_zks').select2('data')[0].text;

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

        jLog(ret);
        $(".loading").hide();

        var fcucode = ret.req.fcucode;
        var fton = ret.req.fsend;
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

        //getObjModal('#btn-save').show();
        table.ajax.reload();

      }
    });
  }, 100);

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
function data_info(data_row, dbdata, dbsumcar, dbfff, fkey) {
  /*
    $('#mModal').modal('hide').remove();
    var myClone = myBackup.clone();
    $('body').append(myClone);

    $(".loading").show();
    //$('#mModal').find('.modal-title').append(':' + data_row[0]);
    $('#mModal').find("#btn-save").hide();
    $('#mModal').find("#btn-cancel").hide();
    $('#mModal').find("#btn-edit").show();
    $('#mModal').find("#btn-del").show();

    $('#mModal').on('show.bs.modal', function(event) {
      $(".loading").hide();
    });

    $('#mModal').modal({
      show: true
    });

    var obj_cartype = data_row[2].split('-');
    var obj_farm = data_row[3].split('-');
    $('#mModal').on('shown.bs.modal', function() {

      getObjModal('#btn-addnew').hide();
      getObjModal('.frm-add').hide();
      getObjModal('.edit-frm').show();
      getObjModal('#fsend2').val(dbdata['fsend']).select().focus();

      info_edit(data_row[0], data_row[1], data_row[2], dbdata['cartype_val'] - dbsumcar, dbdata['cartype_val']);
      info_edit_farm(obj_farm[0], obj_farm[1], dbdata['frrqty'], dbdata['frrqty'] - dbfff);
      jLog(dbdata);
});
*/

  getObjModal('#btn-edit').attr('readonly', 'readonly').attr("disabled", true);
  getObjModal('#fsend2').keyup(function(e) {
    var xvalue = parseInt(getObjModal('.lbl_sxvalue').text(), 10);
    var fsend = parseInt($(this).val(), 10);
    if (e.keyCode == 13) {
      //if (fsend == xvalue) return false;
      if (fsend <= 0 || fsend > xvalue) {
        swal('ไม่อนุญาตใส่จำนวนตันมากกว่าจำนวนสัญญาคงเหลือ.', 'จำนวนสัญญาคงเหลือ ' + xvalue + ' ตัน', 'warning');
        getObjModal('#btn-edit').attr('readonly', 'readonly').attr("disabled", true);
        $(this).select().focus();
        return;
      }
      getObjModal('#btn-edit').removeAttr('readonly').attr("disabled", false).focus();
    }
  });

  getObjModal('#btn-addnew').click(function() {
    add_new();
  });


  $('#btn-edit').click(function() {
    //var formData = JSON.stringify($('#mModal').find("#frmUsers").serialize());
    var fsend = getObjModal('#fsend2').val();
    $.ajax({
      type: 'POST',
      url: jPath + "&mode=edit&id=" + fkey + "&fsend=" + fsend,
      //data: formData,
      dataType: 'json',
      cache: false,
      beforeSend: function() {
        $(".loading").show();
      },
      success: function(ret) {
        jLog(ret);
        $(".loading").hide();
        getObjModal('#btn-edit').attr('readonly', 'readonly').attr("disabled", true);
        table.ajax.reload();
        $('#mModal').modal('hide');
      }
    });

  });

  $('#btn-del').click(function(e) {
    e.preventDefault();
    var carno = getObjModal('#truck_number').val();

    jLog(carno);
    /*
        $.ajax({
          type: 'POST',
          url: jPath + "&mode=del_car&id=" + fkey,
          //data: formData,
          dataType: 'json',
          cache: false,
          beforeSend: function() {
            $(".loading").show();
          },
          success: function(ret) {
            jLog(ret);
            $(".loading").hide();
            //getObjModal('#btn-edit').attr('readonly', 'readonly').attr("disabled", true);
            table.ajax.reload();
            $('#mModal').modal('hide');
          }
        });
    */

  });



}

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