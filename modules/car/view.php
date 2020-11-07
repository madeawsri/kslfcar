<div class="content">
  <div class="row">

    <div class="col-md-12" align="right">
      <!-- fileld-layout form-field-inline float-xs-left -->
      <form action="#" method="post" id="frmFind" class="form-inline" style="padding-bottom: 5px;">


        <div class="input-group"><input type="button" class="btn btn-success btn-add" value="ลงทะเบียน">
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
      .dataTables_filter {
        display: none;
      }

      .dataTable tbody tr td:nth-child(1) {
        text-align: center;
      }

      .dataTable tbody tr td:nth-child(2) {
        text-align: left;
      }

      .dataTable tbody tr td:nth-child(3) {
        text-align: left;
      }

      .dataTable tbody tr td:nth-child(5) {
        text-align: center;
      }
    </style>
    <!-- TABLE -->
    <div class="row">
      <div class="col-md-12" id="content-panel">
        <div class="table-responsive">
          <!--"-->
          <table style="width:100%" id="customer-table" class="dataTable table table-bordered table-condensed table-hover">
            <thead>
              <tr class="">
                <th>เขต-รหัสบัตร</th>
                <th>โซน</th>
                <th>นักสำรวจ</th>
                <th>ทะเบียนรถ</th>
                <th>ประเภทรถ</th>
                <th>โควต้าที่บรรทุก</th>
                <th>รวมแจ้งส่งอ้อยในคัน</th>
                <th>ยอดคงเหลือในคัน</th>
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

<div class="modal fade" id="mModal" role="dialog" style="font-family: 'Pridi', serif;">
  <div class="modal-dialog" role="document" >
    <div class="modal-content" style="width:900px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title"><i class="glyphicon glyphicon-list"></i> ลงทะเบียนรถ : รหัสบัตร ( <span class="keycode">00-0000</span> ) </h5>
      </div>
      <div class="modal-body" style="width:900px;">


        <div class="errmsg alert alert-warning " style="display:none">
          <span class='bb errmsg'>-</span>
        </div>
        <div class="loading" style="display:none;">Loading&#8230;</div>

        <form role="form" id="frm" style='font-size:18px'>
          <div class='frm-add'>
            <div class="col-lg-12 padding_2 hide">
              <label for="temp" class="required-field">temp : </label>
              <input type="text" value="-" class="form-control" name="temp" id="temp">
            </div>

            <div class="col-lg-8">
              <div class="col-lg-4 padding_2  ">
                <label for="temp" class="required-field">ทะเบียนรถ <span style='color:red'> * </span></label>
                <select class="select2 form-control carno" id="truck_number" name="truck_number">
                </select>
              </div>

              <div class="col-lg-4 padding_2 step1" style="display:none;">
                <label for="car_type" class="required-field">ประเภทรถ <span style='color:red'> * </span></label>
                <select class="select2 form-control" id="car_type" name="car_type">
                  <option value='' selected> </option>
                  {truck_type}
                </select>
              </div>

              <div class="col-lg-4 padding_2 step2" style="display:none;">
                <label for="txt_zks" class="required-field">โซน/ เขต/ สาย <span style='color:red'> * </span></label>
                <select class="select2 form-control form-group-lg" id="txt_zks" name="txt_zks">
                  {zoneketsai}
                </select>
              </div>

              <div class='panel-fcucode' style="display:none;">

                <div class="col-lg-3 padding_2 div-hcarno " style="display:none;">
                  <label for="fcucode" class="required-field">ทะเบียนหัวพ่วง <span style='color:red'> * </span> </label>
                  <select class="select2 form-control hcarno" id="hcarno" name="hcarno">
                  </select>
                </div>

                <div class="col-lg-6 padding_2 ">
                  <label for="fcucode" class="required-field">โควต้า/ชื่อ-สกุล: <span style='color:red'> * </span></label>
                  <select class="select2 form-control fcucode" id="fcucode" name="fcucode">
                  </select>
                  <small class='svalue' style="display:none;">สัญญา <span class="bb uu fsvalue" style="color:green;">0000</span> ตัน คงเหลือ <span class="bb uu text-warning fsxvalue">0000</span> ตัน </small>
                </div>



                <div class="col-lg-3 padding_2 ">
                  <label for="fcucode" class="required-field">จำนวนตัน <span style='color:red'> * </span> </label>
                  <input type="number" onClick="this.select();" value="20" style="" class="form-control" name="fsend" id="fsend">
                </div>

                <div class="col-lg-3 padding_2 pull-right">
                  <label for="fcucode" class="required-field"> &nbsp; </label>
                  <input type="button" id="btnRegCar" onclick="submitForm();" value=" ลงทะเบียน " style="" class="btn btn-primary form-control" name="addfresult" id="addfresult">
                </div>

              </div>

            </div>

            <div class="col-lg-4" style=" border-width:1px;  border-left-style:solid;">
              <div class="col-lg-12 padding_2">
                <p class="fstatus">-ลงทะเบียนรถชาวไร่-</p>
                <div class="loader">
                </div>
              </div>
            </div>

            <div class="row col-lg-12 padding_2" style="border-width:1px;  border-top-style:solid;">
              <p class="fstatus"> ข้อมูลชาวไร่   
              </p>
              <style>
                .badge-round {
                  font-size: 14px;
                  text-align: center;
                  font-weight: 900;
                }

                .bin-color {
                  color: red;
                }

                .ton-color {
                  color: green;
                }

                select[readonly].select2-hidden-accessible+.select2-container {
                  pointer-events: none;
                  touch-action: none;
                }

                select[readonly].select2-hidden-accessible+.select2-container .select2-selection {
                  background: #eee;
                  box-shadow: none;
                }

                select[readonly].select2-hidden-accessible+.select2-container .select2-selection__arrow,
                select[readonly].select2-hidden-accessible+.select2-container .select2-selection__clear {
                  display: none;
                }
              </style>
              <div class="fresult"></div>
            </div>

            <!-- temp ????? -->
            <div class="col-lg-12 hide ">
              <label for="temp2" class="required-field">temp : </label>
              <input type="text" class="form-control" name="temp2" id="temp2">
            </div>
          </div>
          <div class="edit-frm" style="display:none;">

            <div class="col-lg-6 padding_2 ">
              <div class="loader2"></div>
            </div>
            <div class="col-lg-6 padding_2 ">
              <div class="loader3"></div>
            </div>
            <div class="col-lg-6 padding_2 ">

              <label for="fcucode" class="required-field">จำนวนตัน: </label>
              <input type="number" onClick="this.select();" value="20" style="" class="form-control" name="fsend2" id="fsend2">


            </div>

          </div>
        </form>



      </div>
      <div class="modal-footer">
      <div class="pull-left"> <span style="font-size:16px;"> สัญญาทั้งหมด <span class="text-success total-farm"> 0 </span> ตัน </span></div>
        <input type="button" class="btn btn-success" id="btn-addnew" value=" ลงทะเบียนใหม่ " />
        <input type="button" class="btn btn-success" id="btn-save" value=" พิมพ์บัตร " />
        <input type="button" class="btn btn-warning" id="btn-cancel" data-dismiss="modal" value="ยกเลิก" />
        <input type="button" class="btn btn-info" id="btn-edit" value="แก้ไข" />
        <input type="button" class="btn btn-danger" id="btn-del" value="ยกเลิกทะเบียน" />
        <input type="button" class="btn btn-info" id="btn-carval" value="อนุมัติยอดส่ง" />
      </div>
    </div>
  </div>
</div>


<!--
<p style='font-size:16px' class='show_qdate'><span class='bb' style='color:#ff3300'>**แจ้งคิวได้หลังวันที่
  </span>
  (<span class='bb qdate' style='color:#0000cc'>00/00/0000 00:00:00</span>)</p>
<p style='font-size:16px' class='show_quata_s'><span class='bb' style='color:red'>**กลุ่มโควต้าพิเศษ</span>
  กรุณาตรวจสอบใบออกคิว
  ลงวันที่ (<span class='bb date_ka' style='color:red'></span>)</p>
 -->
