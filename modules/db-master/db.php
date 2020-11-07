<?php
include_once("../../app.php");
$tbname = "squata_master";
if ($_fn->is_ajax()) {
  if (isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"])) { //Checks if action value exists
    $action = trim($_REQUEST['mode']);
    switch($action) { //Switch case for value of action
      case "datatable":
        show();
      break;
      case "add":
        add();
      break;
      case "edit":
        edit();
      break;
      case "del":
        del();
      break;


    }
  }
}

function del(){
  global $_dbmy,$_fn,$tbname;
  $params = $_REQUEST;
  $_dbmy->del($tbname," squata_id = '{$params['id']}' ");
  echo json_encode($params);
}

function add(){
  global $_dbmy,$_fn,$tbname;
  $params = $_REQUEST;
  // check 
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('fcucode','queue_amt');
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }  
  if(!$params['error']){
    $chk_user_login = (int)$_dbmy->getMYSQLValue($tbname,"count(*)"," fcucode = '{$params['fcucode']}' ");
    if($chk_user_login){
      $params['error'] = 1;
      $params['errmsg'] = ' ชาวไร่นี้มีอยู่ในระบบแล้ว !!!';
      $params['errfield'] = 'fcucode';
    }else{
        $fname = explode("-", $params['fname']);
        $fname = trim($fname[1]);
        $data = array(
          "fcucode"=> $params['fcucode']
          ,"fcuname"=> $fname
          ,"queue_amt"=> $params['queue_amt']
          ,"last_update"=> time()
          ,"site"=> $params['site']
          );
        $_dbmy->add_db($tbname,$data);
    }
  }
  $params['table_name'] = $tbname;
  echo json_encode($params);
}

function edit(){
  global $_dbmy,$_fn,$tbname;
  $params = $_REQUEST;
  // check 
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('fcucode','queue_amt');
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }  
  if(!$params['error']){
    $chk_user_login = (int)$_dbmy->getMYSQLValue($tbname,"count(*)"," fcucode = '{$params['fcucode']}' and squata_id <> '{$params['id']}' ");
    if($chk_user_login){
      $params['error'] = 1;
      $params['errmsg'] = ' ชาวไร่นี้มีอยู่ในระบบแล้ว !!!';
      $params['errfield'] = 'fcucode';
    }else{
        $fname = explode("-", $params['fname']);
        $fname = trim($fname[1]);
        $data = array(
          "fcucode"=> $params['fcucode']
          ,"fcuname"=> $fname
          ,"queue_amt"=> $params['queue_amt']
          ,"last_update"=> time()
          );
        //$_dbmy->add_db($tbname,$data);
        $_dbmy->update_db($tbname,$data, " squata_id = '{$params['id']}' ");
    }
  }
  $params['table_name'] = $tbname;
  echo json_encode($params);
}
/*
function edit(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  // check 
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('user_name','user_login');
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }  
  if(!$params['error']){
    
    $chk_user_login = 
    (int)$_dbmy->getMYSQLValue($tbname,"count(*)"
    ," user_login = '{$params['user_login']}' and user_id <> '{$params['id']}' ");
    if($chk_user_login){
      $params['error'] = 1;
      $params['errmsg'] = ' ชื่อผู้ใช้ช้ำๆ กรุณาเปลี่ยนชื่อผู้ใช้ใหม่ !!!';
      $params['errfield'] = 'user_login';
    }else{
        $data = array(
          "user_name"=> $params['user_name']
          ,"user_level"=> $params['user_level']
          ,"user_login"=> $params['user_login']
          //,"last_login"=> time()
          );

        if($params['user_password']){
          $params['user_password'] = md5($params['user_password']);
          $data['user_password'] = $params['user_password']; 
        }

        $_dbmy->update_db($tbname,$data, " user_id = '{$params['id']}' ");
    }
    $params['chk'] = $chk_user_login;
    $params['data'] = $data;
  }
  echo json_encode($params);
}
*/

function show(){
  global $_dblib,$_fn,$_dbmy,$tbname;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->getMYSQLValueAll($tbname,'*','1=1 order by update_by desc ');
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['squata_id'];
     $x[] = $v['fcucode'];
     $x[] = $v['fcuname'];
     $x[] = number_format($v['queue_amt']);
     $x[] = date("Y-m-d H:i:s",$v['last_update']);
     $x[] = $v['update_by'];
     $new_data[] = $x;
     $db_data[$v['user_id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  echo json_encode($arr);
}
