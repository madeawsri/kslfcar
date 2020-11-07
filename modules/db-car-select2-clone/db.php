<?php
include_once("../../app.php");
$tbname = "sque_paid";
$tbname2 = "squata_quete_daily";

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
      case "printq":
        printq();
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
  global $_dbmy,$_fn,$tbname,$_dblib;
  
  $params['req'] = $_REQUEST;

  // init data q
  if(!$err_softpro_has){

      $params['error'] = 0;
      $params['errfield'] = '';
      $params['errmsg'] = '';
      $params['errtype'] = 0;
      $params['status_print'] = 0;

      if(!$params['paid_type']) // ประเภทคิว "ส่วนตัว"
        $check_fields = array('fcucode'=>'กรุณากรอกโควต้า','truck_number'=>'กรุณากรอกทะเบียนรถ','truck_type'=>'เลือกประเภทรถ','cane_type'=>'เลือกประเภทอ้อย');
      else
        $check_fields = array('truck_no'=>'กรุณากรอกเลขที่ใบสั่งงาน ??AQ?????? !!!','cane_type'=>'เลือกประเภทอ้อย');

      if($check_fields)   
      foreach($check_fields as $k=>$v){
        if($_fn->is_blank($params[$k])){
          if($k=='fcucode' || $k=='truck_no' ){
            $params['errtype'] = 1;
          }else{
            $params['errtype'] = 0;
          }
          $params['error'] = 1;
          $params['errfield'] = $k;
          $params['errmsg'] = $v;
        }
      }  

      if(!$params['error']){
        
        

      }

    }
  $params['table_name'] = $tbname;
  echo json_encode($params);
}

function chk_q_softpro($Q,$carno){
  global $_dblib;
  $check_q_softpro = $_dblib->dbQSoftpro($Q,$carno);
  if($check_q_softpro)
    return 1;
  else 
    return 0;
}

function show_myql(){
  global $_dblib,$_fn,$_dbmy,$tbname;
  $arr = array();
  $new_data = array();
  $data = $_dbmy->getMYSQLValueAll('sque_paid','*','1=1 order by que_id desc ');
  // init datatable
  if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['que_id'];
     $x[] = $v['fcucode'];
     $x[] = $v['paid_type'];
     $x[] = $v['truck_number'];
     $x[] = $v['cane_type'];
     $x[] = $v['datein']." ".$v['timein'];
     $x[] = $v['over_que'];
     $x[] = str_replace("-","/", $v['fvoudate'] ) ;
     $new_data[] = $x;
     $db_data[$v['user_id']] = $x;
   }
  $arr['data'] = $new_data;
  $arr['dbdata'] = $db_data;
  //$arr['data'] = array();
  echo json_encode($arr);
}

function show(){
   global $_dblib, $ARR_STATUS_Q,$ARR_STATUS_Q_TYPE;
   $sql = "
   
   ";
   $data = $_dblib->get_data_softpro2($sql);
   if($data)
   foreach($data as $k=>$v){
     $reset = "";
     $x = array();
     $x[] = $v['FITEMNO'];
     $x[] = $v['FVEHICLENO'];
     $x[] = $v['FQDATE'];
     $x[] = $v['FSUBTYPE'];
     $x[] = $v['FNAME'];
     $x[] = $v['FCARTYPE'];

     $data_quata_spacial = $_dblib->dbSquataMaster($v['FCONTCODE']);
     if($data_quata_spacial)
       $x[] = $ARR_STATUS_Q_TYPE[$v['FVEHTYPE']];
     else 
       $x[] = $ARR_STATUS_Q_TYPE[$v['FVEHTYPE']];

    if($v['FCARDNO']<>'')
      $x[] = $v['FQBY']. " S";
    else
      $x[] = $v['FQBY'];

     $x[] = $ARR_STATUS_Q[$v['STATUS_Q']];
     $new_data[] = $x;
     
   }
   if($new_data)
     $arr['data'] = $new_data;
   else   
     $arr['data'] = array();

  echo json_encode($arr);
}


function printq(){
  global $_dbmy,$_dblib,$_fn;
  $params = $_REQUEST;
  $data = $_dbmy->getMYSQLValues('sque_paid',"*"
  ," fyear = '6162' and que_id = '{$params['id']}'  ");
  // get data form softpro
  
  $sql = "
  SELECT 
    [dt].[FQBY] AS que_location
    ,[dt].[FITEMNO] AS que_id
    , CONVERT(varchar,[dt].[FVOUDATE],111) AS fvoudate
    , CONVERT(varchar, [dt].[FQDATE] ,111) AS datein
    ,[dt].[FQTIME] AS timein
    ,[dt].[FCONTCODE] AS fcucode
    , (SELECT TOP 1 [rd].[FCUNAMET] FROM [dbo].[RD01CUST] AS rd WHERE [rd].[FCUCODE] = [dt].[FCONTCODE]) AS fcuname
    ,[dt].[FVEHICLENO] AS truck_number
    ,( SELECT CONCAT([hd].[FVEHICLETY],' ', [hd].[FDESC]) FROM [dbo].[HD05VEHT] AS hd WHERE hd.[FVEHICLETY]= [dt].[FVEHICLETY]) AS truck_type_name
    ,[dt].[FREMARK] AS truck_head
    ,(SELECT CONCAT([sd].[FSUBTYPE], ' ' , [sd].[FSUBTYPEDS]) FROM [dbo].[SD04SUBM] sd WHERE [sd].[FSUBTYPE] =  [dt].[FSUBTYPE]) AS cane_type_name
    ,[dt].[FCUORDERNO] AS truck_no
    ,(SELECT [FSHIPRATE] FROM DD21FMRQ AS dd WHERE [dd].[FCROPYEAR]  = '6162' AND [dd].[FREQNO] = [dt].[FCUORDERNO]) AS shiprate
    ,FCARDNO AS sque_special

  FROM [dbo].[DD11RRDT] dt 
  WHERE [dt].[FYEAR] = '6162' and FITEMNO = '{$params['id']}' 
  ";
  $data = $_dblib->get_data_softpro2($sql);
  $data = $data[0];
  
  $data['fvoudate'] =  date('d/m/Y',$_fn->str_to_time($data['sque_special']));  //date('d/m/Y',strtotime($data['fvoudate']));
  $data['sque_special'] = ($data['sque_special']<>'')?1:0;
  
  // change format data to screen
  $data['datein'] = date('d/m/Y',strtotime($data['datein']));
  $params['data_paid'] = $data;
  $params['sql'] = $sql;
  echo json_encode($params);
}
