<?php
include_once("../../app.php");

if ($_fn->is_ajax()) {
  if (isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"])) { //Checks if action value exists
    $action = trim($_REQUEST['mode']);
    switch($action) { //Switch case for value of action
      case "datatable":
        show();
      break;
      case "add-users":
        add_cartype();
      break;
      case "edit-users":
        edit_cartype();
      break;
      case "del-users":
        del_cartype();
      break;


    }
  }
}

function del_cartype(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "tb_car_type";
  $_dbmy->del($tbname," cartype_id = '{$params['id']}' ");
  echo json_encode($params);
}

function add_cartype(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "tb_car_type";
  // check
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('car_type','car_type_max','fyear');
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }
  if(!$params['error']){
    $data = array(
      "car_type"=> $params['car_type']
      ,"car_type_max"=> $params['car_type_max']
      ,"fyear"=> $params['fyear']
      );
    $_dbmy->add_db($tbname,$data);
  }
  echo json_encode($params);
}

function edit_cartype(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "tb_car_type";
  // check
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('car_type','car_type_max');
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }

  if(!$params['error']){

    $data = array(
      "cartype_id"=> $params['car_type']
      ,"cartype_value"=> $params['car_type_max']
    );

    $_dbmy->update_db($tbname,$data, " cartype_id = '{$params['id']}' ");

    $params['chk'] = $chk_user_login;
    $params['data'] = $data;
  }
  echo json_encode($params);
}

function show(){
  global $_dblib,$_fn,$_dbmy;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->getMYSQLValueAll('tb_car_type','*'," 1=1 order by cartype_id  ");
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['cartype_id'];
     $x[] = $v['cartype_name'];
     $x[] = $v['cartype_value'];
     $new_data[] = $x;
     $db_data[$v['cartype_id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  echo json_encode($arr);
}
