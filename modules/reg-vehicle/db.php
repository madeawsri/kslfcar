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
        add_v();
      break;
      case "edit-users":
        edit_v();
      break;
      case "del-users":
        del_v();
      break;
	  case "zoneket":
       echo $_dblib->list_zone_ket_fsmcode('json');
		
      break;

    }
  }
}


function del_users(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "sque_user";
  $_dbmy->del($tbname," user_id = '{$params['id']}' ");
  echo json_encode($params);
}

function add_users(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "sque_user";
  // check 
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('user_name','user_login','user_password');
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }  
  if(!$params['error']){
    $chk_user_login = $_dbmy->getMYSQLValue($tbname,"count(*)"," user_login = '{$params['user_login']}' ");
    if($chk_user_login){
      $params['error'] = 1;
      $params['errmsg'] = ' ชื่อผู้ใช้ช้ำๆ กรุณาเปลี่ยนชื่อผู้ใช้ใหม่ !!!';
      $params['errfield'] = 'user_login';
    }else{
        $data = array(
          "user_name"=> $params['user_name']
          ,"user_level"=> $params['user_level']
          ,"user_login"=> $params['user_login']
          ,"user_password"=> md5($params['user_password'])
          //,"last_login"=> time()
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
  $tbname = "sque_user";
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

function show(){
  global $_dblib,$_fn,$_dbmy;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->getMYSQLValueAll('sque_user','*','1=1 order by last_login desc ');
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['user_id'];
     $x[] = $v['user_name'];
     $x[] = $v['user_login'];
     $level = $_dblib->dbLevels($v['user_level']);
     $x[] = $level[0]['text'];
     $x[] = date("Y-m-d H:i:s",$v['last_login']);
     $new_data[] = $x;
     $db_data[$v['user_id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  echo json_encode($arr);
}
