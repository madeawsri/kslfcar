<?php
include_once("../../app.php");

if ($_fn->is_ajax()) {
    if (isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"])) { //Checks if action value exists
        $action = trim($_REQUEST['mode']);
        switch ($action) { //Switch case for value of action
      case "datatable":
        show();
      break;
      case "add-users":
        //add_cartype();
      break;
      case "edit-users":
        //edit_cartype();
      break;
      case "del-users":
        //del_cartype();
      break;
      case "upd_setting":
        $p = $_REQUEST;
        $id = $p['id'];
        //$sql = "update `syscheck` set fact = case when  id  = {$id}  then 1  else 0 end ";
        $sql = "update `syscheck` set fact = !fact where  id  = {$id} ";

        $ok = $_dbmy->select_query($sql);
        $ret['res'] = $ok;
        $ret['sql'] = $sql;
        echo json_encode($ret);
      break;
      case "upd_val":
      $p = $_REQUEST;
        $id = $p['id'];
        $val = $p['val'];
        $sql = "update `syscheck` set val = {$p['val']} where  id  = {$id} ";

        $ok = $_dbmy->select_query($sql);
        $ret['res'] = $ok;
        $ret['sql'] = $sql;
        echo json_encode($ret);
      break;

    }
    }
}
/*
function del_cartype()
{
    global $_dbmy,$_fn;
    $params = $_REQUEST;
    $tbname = "tb_car_type";
    $_dbmy->del($tbname, " cartype_id = '{$params['id']}' ");
    echo json_encode($params);
}

function add_cartype()
{
    global $_dbmy,$_fn;
    $params = $_REQUEST;
    $tbname = "tb_car_type";
    // check
    $params['error'] = 0;
    $params['errfield'] = '';
    $check_fields = array('car_type','car_type_max','fyear');
    foreach ($check_fields as $v) {
        if ($_fn->is_blank($params[$v])) {
            $params['error'] = 1;
            $params['errfield'] = $v;
        }
    }
    if (!$params['error']) {
        $data = array(
      "car_type"=> $params['car_type']
      ,"car_type_max"=> $params['car_type_max']
      ,"fyear"=> $params['fyear']
      );
        $_dbmy->add_db($tbname, $data);
    }
    echo json_encode($params);
}

function edit_cartype()
{
    global $_dbmy,$_fn;
    $params = $_REQUEST;
    $tbname = "tb_car_type";
    // check
    $params['error'] = 0;
    $params['errfield'] = '';
    $check_fields = array('car_type','car_type_max');
    foreach ($check_fields as $v) {
        if ($_fn->is_blank($params[$v])) {
            $params['error'] = 1;
            $params['errfield'] = $v;
        }
    }

    if (!$params['error']) {
        $data = array(
      "cartype_id"=> $params['car_type']
      ,"cartype_value"=> $params['car_type_max']
    );

        $_dbmy->update_db($tbname, $data, " cartype_id = '{$params['id']}' ");

        $params['chk'] = $chk_user_login;
        $params['data'] = $data;
    }
    echo json_encode($params);
}
*/
function show()
{
    global $_dblib,$_fn,$_dbmy;
    $arr = array();
    $new_data = array();
    $data = $_dbmy->getMYSQLValueAll('syscheck', '*', " 1=1 order by id  ");
    // init datatable
    if ($data) {
        foreach ($data as $k=>$v) {
            $reset = "";
            $x = array();
            $x[] = $v['id'];
            $x[] = $v['name'];
            if($v['id'] != 5)
               $x[] = $v['val'] . "<div class='pull-right btn btn-info btn-sm'><a href='javascript:;' onclick=\"jEdit('{$v['id']}','{$v['val']}');\" > แก้ไข </a></div>";
            else 
               $x[] = "";
            
               $x[] = (($v['fact'])?" ตรวจสอบ ":" <b style='color:red;'> ไม่ </b> ตรวจสอบ "). 
            "<div class='pull-right  btn btn-warning btn-sm'><a href='javascript:;' onclick=\"jEditStatus('{$v['id']}');\" > แก้ไข </a></div>";
            

            
            $new_data[] = $x;
            $db_data[$v['id']] = $x;
        }
    }
    $arr['data'] = $new_data;
    $arr['dbdata'] = $db_data;
    echo json_encode($arr);
}
