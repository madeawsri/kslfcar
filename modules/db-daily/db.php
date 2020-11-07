<?php
include_once("../../app.php");
$tbname = "squata_quete_daily";
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
      case "del-all":
        $_dbmy->select_query("TRUNCATE  squata_quete_daily ;");
        echo json_encode($_REQUEST);
      break;


    }
  }
}

function del(){
  global $_dbmy,$_fn,$tbname;
  $params = $_REQUEST;
  $_dbmy->del($tbname," transaction_id = '{$params['id']}' ");
  echo json_encode($params);
}

function edit(){
  global $_dbmy,$_fn,$tbname;
  $params = $_REQUEST;
  // check 
  $params['error'] = 0;
  $params['errfield'] = '';
  
  $data = array(
    "queue_amt"=> $params['queue_amt']
    ,"queue_remain"=> $params['queue_remain']
    ,'queue_date'=> $params['queue_date'] //str_replace('-','/', $params['queue_date'])
    ,"queue_last_time"=> time()
    ,'update_by' => $_SESSION['uid']['user_login']
    );
  //$_dbmy->add_db($tbname,$data);
  $params['sql'] = $_dbmy->update_db($tbname,$data, " transaction_id = '{$params['id']}' ",1);
  $_dbmy->select_query($params['sql']);

  $params['table_name'] = $tbname;
  echo json_encode($params);
}


function show(){
  global $_dblib,$_fn,$_dbmy,$tbname;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->select_query("
    SELECT d.transaction_id
          ,m.fcucode
          ,m.fcuname
          ,m.queue_amt as amt0
          ,d.queue_amt as amt1
          ,d.queue_remain
          ,d.queue_date
     FROM squata_quete_daily as d , squata_master as m 
    WHERE m.fcucode = d.fgrpcode
  ");
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['transaction_id'];
     $x[] = $v['fcucode'];
     $x[] = $v['fcuname'];
     $x[] = $v['queue_date'];
     $x[] = number_format($v['amt0']);
     $x[] = number_format($v['amt1']);
     $x[] = $v['queue_remain'];
     $new_data[] = $x;
     $db_data[$v['user_id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  echo json_encode($arr);
}
