<div class="content">
  <div class="row">

    <div class="col-md-12" align="right" >
      <!-- fileld-layout form-field-inline float-xs-left -->
      <form action="#" method="post" id="frmFind" class="form-inline " style="padding-bottom: 5px;">


        <div class="form-group button-form-group group-pay">
          <div class="input-group">
            <input type="text" placeholder="รหัสบัตร" class="form-control  " id="codecar" name="codecar" value="" style="width:200px;"   />
          </div>
        </div>


        <div class="input-group group-pay">
<style>
/*@import url("https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css");*/
.btn .badge {
    position: relative;
    top: -1px;
}
.btn-primary .badge {
    color: #337ab7;
    background-color: #fff;
}
.badge {
    display: inline-block;
    min-width: 10px;
    padding: 3px 7px;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    background-color: #777;
    border-radius: 10px;
}
table.dataTable tbody tr.selected {
    background-color: #B0BED9;
}
</style>
          <button type="button" class=" btn btn-primary btn-print">ส่งมอบบัตร  <span class="badge badge-success">0</span></button>
          <!--<input type="button" class="btn btn-success btn-add" value="พิมพ์บัตรทะเบียนชาวไร่ ">-->
        </div>
        <div class="input-group ">
            <button type="button" class="btn btn-info data-card">ข้อมูลการจ่ายบัตร </button>
        </div>
        <div class="input-group ">
            <button type="button" class="btn btn-warning " onClick="window.location.reload();"> จ่ายบัตรใหม่  </button>
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
                <th>จ่ายบัตร</th>
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
  .dataTable tbody tr { cursor: pointer; }
  .modal-dialog { max-width: 600px;}
  .select2-dropdown {
    z-index: 9001;
  }
  .padding_2{
    padding:5px;
  }
</style>
<div class="modal fade" id="mModal" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title"><i class="glyphicon glyphicon-list"></i>เพิ่มข้อมูลประเภทรถ </h5>
      </div>
      <div class="modal-body">


        <!--<div class="errmsg alert alert-warning " style="display:none">
            <span class='bb errmsg'> ชื่อผู้ใช้ช้ำๆ กรุณาเปลี่ยนชื่อผู้ใช้ใหม่ !!! </span>
        </div> -->
        <div class="errmsg alert alert-success " style="display:none">
            <span class='bb errmsg'> ได้ดำเนินการเรียบร้อยแล้ว </span>
        </div>

        <div class="loading" style="display:none;">Loading&#8230;</div>

        <form role="form" id="frmUsers">
          <div class="col-lg-12 padding_2 hide">
            <label for="temp" class="required-field">temp : </label>
            <input type="text"  value="-" class="form-control" name="temp" id="temp">
          </div>

          <div class="col-lg-7 padding_2">
            <label for="car_type" class="required-field">ประเภทรถ <span style='color:red'> * </span></label>
            <select class="select2 form-control" id="car_type" name="car_type">
              <option value='' selected> </option>
              {cartype_option}
            </select>
          </div>
          <!--
          <div class="col-lg-5 padding_2">
            <label for="user_level" class="required-field">ระดับผู้ใช้งาน:</label>
            <select class="select2 form-control" id="user_level" name="user_level">
              {levels_option}
            </select>
          </div> -->
          <div class="col-lg-6 padding_2">
            <label for="user_login" class="required-field">ลิมิตในการส่งอ้อย : <span style='color:red'> * </span></label>
            <input type="text" placeholder="" maxlength='5'  class="form-control" name="car_type_max">
          </div>
          <div class="col-lg-6 padding_2" style="display:none;">
            <label for="user_password" class="required-field label_user_password">ฤดูหีบ : <span style='color:red'> * </span></label>
            <input type="text" placeholder=""  class="form-control" name="fyear">
          </div>

          <div class="col-lg-12 hide ">
            <label for="temp2" class="required-field">temp : </label>
            <input type="text" class="form-control" name="temp2" id="temp2">
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-success" id="btn-save" onclick="submitForm();" value="บันทึก" />
        <input type="button" class="btn btn-warning" id="btn-cancel" data-dismiss="modal" value="ยกเลิก" />
        <input type="button" class="btn btn-info" id="btn-edit"  value="แก้ไข" />
        <input type="button" class="btn btn-danger" id="btn-del"  value="ลบ" />
      </div>
    </div>
  </div>
</div>
<style>
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
