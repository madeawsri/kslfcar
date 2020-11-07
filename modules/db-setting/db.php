<?php
include_once("../../app.php");

if ($_fn->is_ajax()) {
  if (isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"])) { //Checks if action value exists
    $action = trim($_REQUEST['mode']);
    switch($action) { //Switch case for value of action
      case "datatable":
        show();
      break;
      case "edit-users":
        edit_users();
      break;
    }
  }
}

function edit_users(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "connect_setting";
  // check 
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('host_name','user_name','pass_name','db_name');
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }  

  if(!$params['error']){
    
    //$chk_user_login = 
    //(int)$_dbmy->getMYSQLValue($tbname,"count(*)"
    //," user_login = '{$params['user_login']}' and user_id <> '{$params['id']}' ");
    //if($chk_user_login){
    //  $params['error'] = 1;
    //  $params['errmsg'] = ' ชื่อผู้ใช้ช้ำๆ กรุณาเปลี่ยนชื่อผู้ใช้ใหม่ !!!';
    //  $params['errfield'] = 'user_login';
    //}else{
        $data = array(
          "host_name"=> $params['host_name']
          ,"user_name"=> $params['user_name']
          ,"pass_name"=> $params['pass_name']
          ,"db_name"=> $params['db_name']
          ,'fyear'=>$params['fyear']
      
          );

      $check =   $_dbmy->update_db($tbname,$data, " id = 1 ");
    //}
    $params['chk'] = $check;
    $params['data'] = $data;
  }
  echo json_encode($params);
}

function show(){
  global $_dblib,$_fn,$_dbmy;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->getMYSQLValueAll('connect_setting','*'," 1=1");
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['host_name'];
     $x[] = $v['user_name'];
     $x[] = $v['pass_name'];
     $x[] = $v['db_name'];
     $x[] = $v['fyear'];

     $new_data[] = $x;
     $db_data[$v['id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  echo json_encode($arr);
}
