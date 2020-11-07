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
      case "getdata":
         $p = $_REQUEST;
         $p['zks']  = $_dbmy->getMYSQLValue('tb_overket','kets'," fyear='{$_dblib->get_year()}' and fcucode = '{$p['fcucode']}' ");
         echo json_encode($p);
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
  global $_dbmy,$_fn,$_dblib;
  $params = $_REQUEST;
  $tbname = "tb_overket";
  // check
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('fcucode','txt_zks');
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }
  if(!$params['error']){

    $params['fcuname'] = explode(' - ',$params['fcuname']);
    $params['fcuname'] = $params['fcuname'][1];

    $kets  = implode(",",$params['txt_zks']);
    $ckets = array();
    if($params['txt_zks'])
    foreach($params['txt_zks']  as $a=>$b){
       $ckets[]=substr($b,-4);
    }
    sort($ckets);
    $ckets = implode(",",$ckets);

    $data = array(
      "fcucode"=> $params['fcucode']
      ,"kets"=> $kets
      ,"ckets" => $ckets
      ,"addtime"=>time()
      ,"fcuname"=> $params['fcuname']
      ,"fyear"=> $_dblib->get_year()
      
      );

$chk_fcucode = (int)$_dbmy->getMYSQLValue('tb_overket','count(*)'," fyear = '{$_dblib->get_year()}' and fcucode = '{$params['fcucode']}'  ");
if($chk_fcucode)
$params['status'] = -1;
else    
$params['status'] = $_dbmy->add_db($tbname,$data);

  }
  echo json_encode($params);
}

function edit_cartype(){
  global $_dbmy,$_fn,$_dblib;
  $params = $_REQUEST;
  $tbname = "tb_overket";
  $params['txt_zks'] = ($params['txt_zks'])?$params['txt_zks']:array();
  // check
  $params['error'] = 0;
  $params['errfield'] = '';
  $check_fields = array('fcucode'); //,'txt_zks'
  foreach($check_fields as $v){
    if($_fn->is_blank($params[$v])){
      $params['error'] = 1;
      $params['errfield'] = $v;
    }
  }
  if(!$params['error']){

    $params['fcuname'] = explode(' - ',$params['fcuname']);
    $params['fcuname'] = $params['fcuname'][1];

    $kets  = implode(",",$params['txt_zks']);
    $ckets = array();
    if($params['txt_zks'])
    foreach($params['txt_zks']  as $a=>$b){
       $ckets[]=substr($b,-4);
    }
    sort($ckets);
    $ckets = implode(",",$ckets);

    $data = array(
      "fcucode"=> $params['fcucode']
      ,"kets"=> $kets
      ,"ckets" => $ckets
      ,"edittime"=>time()
      ,"fcuname"=> $params['fcuname']
      ,"fyear"=> $_dblib->get_year()
      
      );

//$chk_fcucode = (int)$_dbmy->getMYSQLValue('tb_overket','count(*)'," fyear = '{$_dblib->get_year()}' and fcucode = '{$params['fcucode']}'  ");
//if($chk_fcucode)
//$params['status'] = -1;
//else    
$params['status'] = $_dbmy->update_db($tbname,$data, " fyear = '{$_dblib->get_year()}' and fcucode = '{$params['fcucode']}'  ");

  }
  echo json_encode($params);
}

function show(){
  global $_dblib,$_fn,$_dbmy;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->getMYSQLValueAll('tb_overket','*'," 1=1 and fyear='{$_dblib->get_year()}' order by fcucode  ");
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";

     $x = array();
     $x[] = $v['fcucode'];
     $x[] = $v['fcuname'];
     $x[] = tags($v['ckets']);
     $new_data[] = $x;
     $db_data[$v['cartype_id']] = $x;

   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  echo json_encode($arr);
}

function tags($data){
  $ret = '';
  $data = explode(',',$data);
  if($data)
  foreach($data as $k=>$v){
    //$v = substr($v,-4);
    $ret .= " <span class=\"label\">{$v}</span> ";
  }
  return $ret;
}
