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
  /*
  $data = $_dbmy->select_query("
    SELECT d.que_id
          ,d.fcucode
          ,d.fcuname
          ,d.datetime_in 
          ,d.truck_number
          , concat(d.truck_type , ' - ' , d.truck_type_name) as truck_type_name 
          ,d.cane_type_name
     FROM sque_paid as d
    WHERE d.fyear = '6162' and d.sque_special = 1
  ");*/

  $SOFTPROSql = "SELECT 
  [FITEMNO],
  (SELECT [FGRPCODE] FROM [dbo].[RD01CUST] AS rd WHERE [rd].[FCUCODE] = [DD11RRDT].[FCONTCODE] ) AS FGRPCODE
  ,[FCONTCODE]
  ,(SELECT [FCUNAMET] FROM [dbo].[RD01CUST] AS rd WHERE [rd].[FCUCODE] = [DD11RRDT].[FCONTCODE] ) AS FNAMET
  , concat(CONVERT(date,[FQDATE],111), ' ',  [FQTIME]) AS FDATE
  ,[FVEHICLENO]
  ,(SELECT [sd].[FSUBTYPEDS] FROM [dbo].[SD04SUBM] sd WHERE [sd].[FSUBTYPE] = [dbo].[DD11RRDT].[FSUBTYPE]) AS [FSUBTYPE_NAME]
  , CONCAT( LEFT([FCARDNO],4) , '-',   SUBSTRING([FCARDNO],5,2), '-',  right([FCARDNO],2) ) AS FCARDNO
  
  FROM [dbo].[DD11RRDT] 
  WHERE [FYEAR] = '{$_dblib->get_year()}'
  AND [FCARDNO] <> ''
  ";
  // init datatable
  // WHERE d.fyear = '{$_SESSION['CUR_YEAR']}' and d.sque_special = 1
  $data = $_dblib->get_data_softpro2($SOFTPROSql);
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['FITEMNO'];
     $x[] = $v['FGRPCODE'];
     $x[] = $v['FCONTCODE'];
     $x[] = $v['FNAMET'];
     $x[] = $v['FDATE'];
     $x[] = $v['FVEHICLENO'];
     $x[] = $v['FSUBTYPE_NAME'];
     $x[] = $v['FCARDNO'];
     $new_data[] = $x;
    // $db_data[$v['user_id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  //$arr['data'] = array();
  echo json_encode($arr);
}
