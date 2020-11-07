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
        //add_car();
        //$_REQUEST['car_type']
        $car_no = $_REQUEST['car_no'];
        $db_car = $_dblib->dbRegCar($car_no);
        
        if($db_car){
        // has car 
      
        
        }else{
        // has no car 
        
          

        }

        echo json_encode($_REQUEST);
      break;
      case "edit-users":
        edit_car();
      break;
      case "del-users":
        del_car();
      break;


    }
  }
}

function del_car(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "tb_car";
  $_dbmy->del($tbname," id = '{$params['id']}' ");
  echo json_encode($params);
}

function add_car(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "tb_car";
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

function edit_car(){
  global $_dbmy,$_fn;
  $params = $_REQUEST;
  $tbname = "tb_car";
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

    $_dbmy->update_db($tbname,$data, " id = '{$params['id']}' ");
    
    $params['chk'] = $chk_user_login;
    $params['data'] = $data;
  }
  echo json_encode($params);
}

function show(){
  global $_dblib,$_fn,$_dbmy;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->getMYSQLValueAll('tb_car','*'," fyear={$_SESSION['CUR_YEAR']} order by id  ");
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     
     $x[] = $v['id'];
     $x[] = "";
     $x[] = "";
     $x[] = $v['car_type'];
     $x[] = $v['car_type_max'];
     $x[] = $v['fyear'];

     $new_data[] = $x;
     $db_data[$v['id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  echo json_encode($arr);
}
