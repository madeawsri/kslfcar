<div class="content">
  <div class="row">

    <div class="col-md-12" align="right" >
      <!-- fileld-layout form-field-inline float-xs-left -->
      <form action="#" method="post" id="frmFind" class="form-inline" style="padding-bottom: 5px;">
        <div class="input-group hide"><input type="button" class="btn btn-success btn-add" value="เพิ่มข้อมูล">
        </div>
        <div class="input-group hide"><input type="button" class="btn btn-danger btn-del-all" value="ลบข้อมูลทั้งหมด">
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

      .dataTable tbody tr td:nth-child(1) {
        text-align: center;
      }

      .dataTable tbody tr td:nth-child(2) {
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
                <th>คิวที่</th>
                <th>โควต้าหลัก</th>
                <th>โควต้า</th>
                <th>ชื่อ-นามสกุล</th>
                <th>วันที่แจ้งคิว</th>
                <th>ทะเบียน</th>
                <!--<th>ประเภทคิว</th>-->
                <th>ประเภทอ้อย</th>
                <th>วันที่ปล่อยคิว</th>
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
        <h5 class="modal-title"><i class="glyphicon glyphicon-list"></i>ล็อกโควต้าพิเศษและจำนวนคิวต่อวัน </h5>
      </div>
      <div class="modal-body">
         

        <div class="errmsg alert alert-warning " style="display:none">
            <span class='bb errmsg'> ชื่อผู้ใช้ช้ำๆ กรุณาเปลี่ยนชื่อผู้ใช้ใหม่ !!! </span>
        </div>
        <div class="errmsg alert alert-success " style="display:none">
        <!--<span aria-hidden="true">&times;</span>-->
            <span class='bb errmsg'> ได้ดำเนินการเรียบร้อยแล้ว </span>
        </div>

        <div class="loading" style="display:none;">Loading&#8230;</div>

        <form role="form" id="frm">
          <div class="col-lg-12 padding_2 hide">
            <label for="temp" class="required-field">temp : </label>
            <input type="text"  value="-" class="form-control" name="temp" id="temp">
          </div>
          <!-- temp ????? -->
          <div class="col-lg-7 padding_2">
            <label for="user_level" class="required-field">โควต้า/ชื่อ-สกุล: <span style='color:red'> * </span></label>
            <select class="select2 form-control" id="fcucode" name="fcucode" disabled>

            </select>
          </div>
          <div class="col-lg-5 padding_2">
            <label for="queue_date" class="required-field">วันที่กะแจ้งคิว : </label>
            <input type="date" placeholder="วันที่กะแจ้งคิว"   class="form-control" onclick="$(this).select();"  name="queue_date">
          </div>
          <div class="col-lg-4 padding_2">
            <label for="queue_amt" class="required-field">คิวที่ใช้ต่อวัน : </label>
            <input type="number" placeholder="คิวที่ใช้ต่อวัน"  class="form-control" onclick="$(this).select();"  name="queue_amt">
          </div>
          <div class="col-lg-4 padding_2">
            <label for="queue_remain" class="required-field">คิวที่ใช้ตคงเหลือต่อวัน : </label>
            <input type="number" placeholder="คิวที่ใช้ตคงเหลือต่อวัน"  class="form-control" onclick="$(this).select();"  name="queue_remain">
          </div>

          <!-- temp ????? -->
          <div class="col-lg-12 hide ">
            <label for="temp2" class="required-field">temp : </label>
            <input type="text" class="form-control" name="temp2" id="temp2">
          </div>

        </form>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-success" id="btn-save" onclick="submitForm();" value="บันทึก" />
        <input type="button" class="btn btn-info" id="btn-edit"  value="แก้ไข" />
        <input type="button" class="btn btn-danger" id="btn-del"  value="ลบ" />
        <input type="button" class="btn btn-warning" id="btn-cancel" data-dismiss="modal" value="ยกเลิก" />
      </div>
    </div>
  </div>
</div>