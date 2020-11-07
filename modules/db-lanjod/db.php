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
        add_users();
      break;
      case "edit-users":
        edit_users();
      break;
      case "del-users":
        del_users();
      break;


    }
  }
}

function del_users(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "ksl_lanjod";
  $_dbmy->del($tbname," lanjod_id = '{$params['id']}' ");
  echo json_encode($params);
}

function add_users(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "ksl_lanjod";
  // check 
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array( 'lanjod_name'=>'กรอกชื่อลานจอด','lanjod_ip'=>'กรอกหมายเลขเครื่อง');
  foreach($check_fields as $k=>$v){
    if($_fn->is_blank($params[$k])){
      $params['error'] = 1;
      $params['errfield'] = $k;
      $params['errmsg'] = $v;
    }
  }  
  if(!$params['error']){
    $chk_user_login = $_dbmy->getMYSQLValue($tbname,"count(*)"," lanjod_ip = '{$params['lanjod_ip']}' ");
    if($chk_user_login){
      $params['error'] = 1;
      $params['errmsg'] = ' หมายเลขเครื่องช้ำ !!!';
      $params['errfield'] = 'lanjod_ip';
    }else{
        $data = array(
          "lanjod_name"=> $params['lanjod_name']
          ,"lanjod_ip"=> $params['lanjod_ip']
          ,"site"=> $params['site']
          );
        $_dbmy->add_db($tbname,$data);
    }
  }
  echo json_encode($params);
}

function edit_users(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "ksl_lanjod";
  // check 
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array( 'lanjod_name'=>'กรอกชื่อลานจอด','lanjod_ip'=>'กรอกหมายเลขเครื่อง');
  foreach($check_fields as $k=>$v){
    if($_fn->is_blank($params[$k])){
      $params['error'] = 1;
      $params['errfield'] = $k;
      $params['errmsg'] = $v;
    }
  }  

  if(!$params['error']){
    $chk_user_login = $_dbmy->getMYSQLValue($tbname,"count(*)"," lanjod_ip = '{$params['lanjod_ip']}' ");
    if($chk_user_login){
      $params['error'] = 1;
      $params['errmsg'] = ' หมายเลขเครื่องช้ำ !!!';
      $params['errfield'] = 'lanjod_ip';
    }else{
        $data = array(
          "lanjod_name"=> $params['lanjod_name']
          ,"lanjod_ip"=> $params['lanjod_ip']
          ,"site"=> $params['site']
          );

        $_dbmy->update_db($tbname,$data, " lanjod_id = '{$params['id']}' ");
    }
    $params['chk'] = $chk_user_login;
    $params['data'] = $data;
  }
  echo json_encode($params);
}

function show(){
  global $_dblib,$_fn,$_dbmy;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->getMYSQLValueAll('ksl_lanjod','*'," lanjod_name <> '' order by lanjod_name ");
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['lanjod_id'];
     $x[] = $v['lanjod_name'];
     $x[] = $v['lanjod_ip'];
     $new_data[] = $x;
     $db_data[$v['user_id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  echo json_encode($arr);
}
