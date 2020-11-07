<div class="content">
  <div class="row">

    <div class="col-md-12" align="right">
      <!-- fileld-layout form-field-inline float-xs-left -->
      <form action="#" method="post" id="frmFind" class="form-inline" style="padding-bottom: 5px;">
        <div class="input-group"><input type="button" class="btn btn-success btn-add" value="แจ้งคิว">
        </div>


        <div class="form-group button-form-group">
          <div class="input-group">
            <input type="text" class="form-control" id="searchbox" placeholder="ค้นหา...">
            <span class="input-group-btn">
              <button class="btn btn-secondary" type="button"> <i class="fa fa-search"></i> </button>
            </span>
          </div>
        </div>

      </form>

    </div>

    <div class="panel-action-section hide" style="padding-right: 5px">
      <ul class="right-action float-xs-right">
        <li>
          <a href="javascript:void(0)" data-toggle="panel-refresh"><i class="icon_refresh" aria-hidden="true"></i></a>
        </li>
        <li><a href="javascript:void(0)" data-toggle="panel-full"><i class="arrow_expand"></i></a></li>
      </ul>
    </div>
    <style>
      /*
  table.dataTable tbody td {
    word-break: break-word;
    vertical-align: top;
}*/
      .dataTables_filter {
        display: none;
      }

      /*
      .dataTable tbody tr td:nth-child(1) {
        text-align: center;
      }
      .dataTable tbody tr td:nth-child(2) {
        text-align: left;
      }
      .dataTable tbody tr td:nth-child(3) {
        text-align: left;
      }
      .dataTable tbody tr td:nth-child(4) {
        text-align: left;
      }
      .dataTable tbody tr td:nth-child(5) {
        text-align: left;
      }
*/
    </style>
    <!-- TABLE -->
    <div class="row">
      <div class="col-md-12" id="content-panel">
        <div class="table-responsive">
          <!--"-->
          <table style="width:100%" id="customer-table"
            class="dataTable table table-bordered table-condensed table-hover">
            <thead>
              <tr class="">
                <th>โซน-เขต-สาย</th>
                <th>ทะเบียนรถ</th>
                <th>ประเภทอ้อย</th>
                <th>โควต้า</th>
                <th>ยอดส่ง</th>
                <th>วันที่ลงทะเบียน</th>
                <th>สถานะ</th>
              </tr>
            </thead>
          </table>

        </div>
      </div>
    </div>
    <!-- END TABLE-->
  </div>
</div>



<style>
  .dataTable tbody tr {
    cursor: pointer;
  }

  .modal-dialog {
    max-width: 800px;
  }

  .select2-dropdown {
    z-index: 9001;
  }

  .padding_2 {
    padding: 5px;
  }

  .select2-results__option {
    font-size: 14px;
  }

  .select2-container {
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
    position: relative;
    vertical-align: middle;
  }
</style>

<div class="modal fade" id="mModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title"><i class="glyphicon glyphicon-list"></i> ลงทะเบียนรถร่วม </h5>
      </div>
      <div class="modal-body" >


        <div class="errmsg alert alert-warning " style="display:none">
          <span class='bb errmsg'>-</span>
        </div>
        <div class="loading" style="display:none;">Loading&#8230;</div>

        <form role="form" id="frm" style='font-size:18px'>
          <div class="col-lg-12 padding_2 hide">
            <label for="temp" class="required-field">temp : </label>
            <input type="text" value="-" class="form-control" name="temp" id="temp">
          </div>

          <div class="col-lg-7">

          

          <div class="col-lg-4 padding_2 paid_type0">
            <label for="temp" class="required-field">ทะเบียนรถ <span style='color:red'> * </span></label>
            <input type="text" value="" class="form-control" name="truck_number" id="truck_number">
          </div>

          <div class="col-lg-4 padding_2">
            <label for="txt_zks" class="required-field">โซน/ เขต/ สาย <span style='color:red'> * </span></label>
            <select class="select2 form-control form-group-lg" id="txt_zks" name="txt_zks">
              {zoneketsai}
            </select>
          </div>
          
          <div class="col-lg-4 padding_2 paid_type0">
            <label for="car_type" class="required-field">ประเภทรถ <span style='color:red'> * </span></label>
            <select class="select2 form-control" id="car_type" name="car_type">
              <option value='' selected> </option>
              {truck_type}
            </select>
          </div>

        <div class='panel-fcucode'>
          <div class="col-lg-9 padding_2 paid_type0">
            <label for="fcucode" class="required-field">โควต้า/ชื่อ-สกุล: <span style='color:red'> * </span></label>
            <select class="select2 form-control fcucode" id="fcucode" name="fcucode[]">
            </select>
          </div>

          <div class="col-lg-3 padding_2 paid_type0">
            <label for="fcucode" class="required-field">จำนวนตัน: </label>
            <input type="number" onClick="this.select();" value="0" style="" class="form-control" name="fsend[]" id="fsend" >
          </div>
        </div>

        <div class="div_fcucode" >
        <div class="col-lg-9 padding_2 paid_type0">
        <select class="select2 form-control fcucode" id="fcucode" name="fcucode[]">
            </select>
          </div>

          <div class="col-lg-3 padding_2 paid_type0">
            <input type="number" onClick="this.select();" value="0" style="" class="form-control" name="fsend[]" id="fsend" >
          </div>
        </div>

        </div>
        <div class="col-lg-5"  style=" border-width:1px;  border-left-style:solid;">

          <div class="col-lg-12 padding_2" >
            <p class="fstatus">-ลงทะเบียนรถชาวไร่-</p>
            <div class="loader">
              <p style='font-size:14px;padding:0.3px;'>โซน/ เขต/ สาย : <span class='bb uu lbl_zks' >00000</span></p>
              <p style='font-size:14px;padding:0.3px;'>ทะเบียนรถ : <span class='bb uu lbl_carno' >xx-01234</span></p>
              <p style='font-size:14px;padding:0.3px;'>ประเภทรถ : <span class='bb uu lbl_cartype' >xxxxx xxx </span></p>
              <p style='font-size:14px;padding:0.3px;'>จำนวนส่งอ้อยทั้งหมด : <span class='bb uu lbl_max' >00000</span></p>
            </div>
          </div>
          </div>

          <!-- temp ????? -->
          <div class="col-lg-12 hide ">
            <label for="temp2" class="required-field">temp : </label>
            <input type="text" class="form-control" name="temp2" id="temp2">
          </div>

        </form>

        <div class='print_bill' id='print_bill' style="zoom:.6;display:none;" align='center'>
          <table width='380px' border='0'>
            <tr>
              <td colspan='3' align='center'> <span style='font-weight:;'>บริษัท น้ำตาลขอนแก่น จำกัด (มหาชน)</span>
              </td>
            </tr>
            <tr>
              <td align='center'> ใบแจ้งคิว </td>
              <td rowspan='2' colspan='2' align='center' valign='middle'>
                <div id='qbarcode'></div>
              </td>
            </tr>
            <tr>
              <td align='center' valign='top'> ลานจอด <span id='lanjod'
                  style='font-weight:bold;font-size:30px;'>A</span>
              </td>
            </tr>
            <tr>
              <td align='center' valign='top'> <span style='font-weight:bold'><span
                    style='font-weight:bold;font-size:28px;'> <span id='divcode'>01</span>/<span
                      id="qcode">E9786</span></span></span>
              </td>
              <td align='center' bgcolor='#000' style='color:#fff' valign='top' class='qspacial'> <span
                  style='font-weight:bold;font-size:30px;' class='qspacial'>S</span>
              </td>
              <td align='center' bgcolor='#000' style='color:#fff' valign='top' class='qspacial'> <span
                  style='font-weight:bold' class='qspacial'>วันที่ปล่อย <span id='datep'>30/03/2018</span>
                </span></td>
            </tr>
            <tr>
              <td colspan='3' align='left'> วันที่เข้า <span style='font-weight:bold' id='datein'> 29/03/2018 </span>
                เวลาเข้า <span style='font-weight:bold' id='timein'>13.52</span> น.
              </td>
            </tr>
            <tr>
              <td colspan='3' align='left'> เลขที่ชาวไร่ <span style='font-weight:bold' id='fname'>0100012 นายพรมมา
                  โมมา</span></td>
            </tr>
            <tr>
              <td colspan='3' align='left'> ทะเบียนรถ <span style='font-weight:bold;font-size:22px;' id='fvname'>165 หัว
                  รถพ่วง
                  (หางพ่วง)</span></td>
            </tr>
            <tr>
              <td> หัวลาก
                <span style='font-weight:bold;padding-left:5px;' id='fhname'> - </span></td>
              <td colspan='2' widht='70%' valign='middle'> ประเภท <span style='font-weight:bold;padding-left:5px;'
                  id='fsname'>02-อ้อยไฟไหม้</span></td>
            </tr>
            <tr>
              <td colspan='3' align='left'> ใบร้องขอ <span style='font-weight:bold' id='facode'>18AQ27255</span></td>
            </tr>

            <tr>
              <td> เลขหาง <span style='font-weight:bold;padding-left:5px;' id='fzname'> 168(หัว) </span></td>
              <td colspan='2' valign='middle'> ค่าบรรทุกต่อตัน <span style='font-weight:bold;padding-left:5px;'
                  id='fbaht'>180</span></td>
            </tr>
            <tr>
              <td colspan='3' valign='top'> ลายเซ็น <br><br><br><br><br><br>
                <hr>
              </td>
            </tr>
          </table>
        </div>



      </div>
      <div class="modal-footer">
        <p style='font-size:16px' class='show_qdate'><span class='bb' style='color:#ff3300'>**แจ้งคิวได้หลังวันที่
          </span>
          (<span class='bb qdate' style='color:#0000cc'>00/00/0000 00:00:00</span>)</p>
        <p style='font-size:16px' class='show_quata_s'><span class='bb' style='color:red'>**กลุ่มโควต้าพิเศษ</span>
          กรุณาตรวจสอบใบออกคิว
          ลงวันที่ (<span class='bb date_ka' style='color:red'></span>)</p>
        <!--<input type="button" class="btn btn-primary" id="btn-print" onclick="submitForm();" value="พิมพ์/บันทึก" />-->
        <input type="button" class="btn btn-info" id="btn-add-fcucode" value=" เพิ่มโควต้า " />
        <input type="button" class="btn btn-success" id="btn-save" onclick="submitForm();" value="บันทึก/พิมพ์" />
        <input type="button" class="btn btn-warning" id="btn-cancel" data-dismiss="modal" value="ยกเลิก" />
        <input type="button" class="btn btn-info" id="btn-edit" value="แก้ไข" />
        <input type="button" class="btn btn-danger" id="btn-del" value="ลบ" />
      </div>
    </div>
  </div>
</div>