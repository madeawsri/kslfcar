<div class="content">
  <div class="row">

    <div class="col-md-12" align="right" >
      <!-- fileld-layout form-field-inline float-xs-left -->
      <form action="#" method="post" id="frmFind" class="form-inline " style="padding-bottom: 5px;">
        <div class="input-group "><input type="button" class="btn btn-success btn-add" value="เพิ่มข้อมูล">
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
                <th>โควต้า</th>
                <th>ชื่อ-สกุล</th>
                <th>อนุมัติข้ามเขต</th>
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
        <h5 class="modal-title"><i class="glyphicon glyphicon-list"></i>อนุมัติลงทะเบียนข้ามเขต </h5>
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

          <div class="col-lg-6 padding_2 ">
                  <label for="fcucode" class="required-field">โควต้า/ชื่อ-สกุล: <span style='color:red'> * </span></label>
                  <select class="select2 form-control fcucode" id="fcucode" name="fcucode">
                  </select>
                </div>

          <div class="col-lg-12 padding_2 ">
                <label for="txt_zks" class="required-field">โซน/ เขต/ สาย <span style='color:red'> * </span></label>
                <select class="select2 form-control form-group-lg" id="txt_zks" name="txt_zks[]">
                  {zoneketsai}
                </select>
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

.label,
.badge {
  background-color: #b5b5b5;
  color: #fff;
  font-size: 12px;
  font-size: 0.75rem;
  line-height: 2;
  font-weight: bold;
  padding: 3px 7px;
  text-align: center;
  vertical-align: baseline;
  white-space: nowrap;
}

.label {
  border-radius: 4px;
  display: inline;
}

.badge {
  border-radius: 10px;
  display: inline-block;
  padding: 1px 7px;
}

.label[href]:hover, .label[href]:focus {
  color: #fff;
  cursor: pointer;
  text-decoration: none;
}

a.badge:hover, a.badge:focus {
  color: #fff;
  cursor: pointer;
  text-decoration: none;
}

.label a,
.badge a {
  color: #fff;
  cursor: pointer;
  text-decoration: none;
}

.label:empty,
.badge:empty {
  display: none;
}
</style>
