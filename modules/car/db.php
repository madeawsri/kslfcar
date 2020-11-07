<?php
include_once("../../app.php");
$tbname = "tb_reg_car";
$tbname2 = "squata_quete_daily";

$tbCarType = "tb_car_type";

if ($_fn->is_ajax()) {
    if (isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"])) { //Checks if action value exists
        $action = trim($_REQUEST['mode']);
        switch ($action) {

case "add_car_val":
$sql = "
 update tb_reg_car as rc , tb_reg_key as rk 
    set rc.car_val   = rc.car_val  + {$_REQUEST['new_carval']}
  where rk.carno = rc.carno and rk.zks=rc.zks 
    and rk.fyear=rc.fyear 
    and rk.cartype_id = rc.cartype_id 
    and rc.fyear = '{$_dblib->get_year()}' 
    and concat(rk.ket,'-',rk.keycode) = '{$_REQUEST['code']}'
";
$update = $_dbmy->execs($sql);
$_REQUEST['ok'] = false;
if($update){
  $_REQUEST['ok'] = true;
}
echo json_encode($_REQUEST);

break;


          //Switch case for value of action
          case "list_hcarno":
              $ret['req']=$_REQUEST;
              $zks = $_REQUEST['zks'];
              $data_hcar = $_dblib->getListHCar($zks);

              $ret = array();
              $ret['data'] = $data_hcar;
              echo json_encode($ret);
          break;

      case "check_hcar":
          $ret['req']=$_REQUEST;
          $zks = $_REQUEST['zks'];
          $carno = $_REQUEST['carno'];
          $data_hcar = $_dblib->getCheckHCar($zks, $carno);
          $sql_data_hcar = $_dblib->getCheckHCar($zks, $carno, 1);
          $ret = array();
          $ret['data'] = $data_hcar;
          $ret['check_hcarno'] = null;
          $ret['sql'] = $sql_data_hcar;
          echo json_encode($ret);

      break;
      case "overket":
        edit_overket();
        //echo json_encode($_REQUEST);
      break;
      case "datatable":
        show();
        break;
      case "add":
        add();
        break;
      case "edit":
        edit();
        break;
        case "del_fcar":
        del_farm();
        break;
      case "del":
        del();
        break;
      case "printq":
        printq();
        break;
      case "check_car":

        $params = explode('|', $_REQUEST['carno']);
        $carno = trim($params[0]);
        $cartype_id = trim($params[1]);
        $_REQUEST['data_send'] = $_REQUEST;
        $_REQUEST['carno'] = $carno;
        $_REQUEST['cartype_id'] = $cartype_id;
        $data_car = $_dblib->getRowRegCar2($carno, $cartype_id);
        $_REQUEST['data_car'] = $data_car;
        echo json_encode($_REQUEST);

        break;

        case "check_cartype":
        $params = explode('|', $_REQUEST['carno']);
        $carno = trim($params[0]);
        $cartype_id = ($_REQUEST['cartype_id'])?trim($_REQUEST['cartype_id']):trim($params[1]);
        $_REQUEST['carno'] = $carno;
        $_REQUEST['cartype_id'] = $cartype_id;
        // car value
        $_REQUEST['cartype_value'] = $_dbmy->getMYSQLValue($tbCarType, " cartype_value ", " cartype_id = '{$cartype_id}'");
        $_REQUEST['data_car'] = $_dblib->getRowRegCar2($carno, $cartype_id);
        if($_REQUEST['data_car']){
          $_REQUEST['cartype_value'] = $_REQUEST['data_car']['car_val'];
        }
        echo json_encode($_REQUEST);
          break;

        case "del_car":
          del();
        break;
        case "check_ket":
          $params = $_REQUEST;
          $_url  = explode("|", $_REQUEST['params']);
          if (count($_url) == 5) {
              $params['_url'] = $_url;
              $fcucode = trim($_url[0]);
              $carno = trim($_url[1]);
              $ket_full = trim($_url[3]);
              $cartype = trim($_url[2]);
              $ket = explode('/', $ket_full);
              $ket = trim($ket[2]);

          } else {
              $params['_url'] = $_url;
              $fcucode = trim($_url[0]);
              $carno = trim($_url[1]);
              $ket_full = trim($_url[2]);
              $cartype = trim($_url[3]);
              $ket = explode('/', $ket_full);
              $ket = trim($ket[2]);
          }

          $chk_ket = (int)$_dbmy->getMYSQLValue($tbname, "count(*)", "   right(zks,4) like '{$ket}' and fcucode = '{$fcucode}'  and fyear= {$_dblib->m_fyear} ");

          $chk_farm = (int)$_dbmy->getMYSQLValue($tbname, "count(*)", "   fcucode = '{$fcucode}'  and fyear= {$_dblib->m_fyear} ");

          $chk_ket_x = (int)$_dbmy->getMYSQLValue("tb_overket", "count(*)", "   ckets like '%{$ket}%' and fcucode = '{$fcucode}'  and fyear= {$_dblib->m_fyear} ");

          $params['chk_farm'] = $chk_farm;
          $params['chk_ket'] = ($chk_farm)?$chk_ket==1:true;

          $params['chk_ket_x'] = (int)$chk_ket_x >= 1;

          $data_ket = $_dbmy->getMYSQLValueAll($tbname
          , "fcucode,carno,zks,cartype_id,fsend"
          , " concat(fcucode,carno,cartype_id) = '{$fcucode}{$carno}{$cartype}' and fyear= {$_dblib->m_fyear} ");

          $params['check_ket'] = ($chk_farm)? $chk_ket : true;

          $data_f = $data_ket;
          $params['data_f'] = $data_f;

          $ket_value = '';
          $x_fsend = array(0);

          // ตรวจสอบ โควต้า ลงทะเบียนช้ำๆ
          $params['fcheck'] = count($data_ket) > 0;

          // จำนวนสัญญาโรงงาน
          $frrqty = $_dblib->dbFcucode($fcucode);
          $frrqty = ($frrqty)?$frrqty[0]['frqqty']:0;
          $params['frrqty'] = $frrqty;

          $fdata = $_dbmy->getMYSQLValueAll($tbname, "zks_name,carno,zks,sum(fsend) as fsend", "  fcucode = '{$fcucode}' and fyear= {$_dblib->m_fyear} group by zks_name,carno,zks,fsend");

          // list kets & sum fsend
          if ($fdata) {
              foreach ($fdata as $v) {
                  $ket_value = "<p class='bb uu text-success' style='font-size:18px;'>".$v['zks_name']."</p>, ";
                  $x_fsend[]=$v['fsend'];
              }
              $ket_value = rtrim($ket_value, ", ");
          }
          // confirm ลงทะเบียนข้ามเขต
          if($ket_value){
            $fcucodex = explode(" - ", $_REQUEST['fname']);
            $fname = $fcucodex[1];
            //$fcucode = $fcucode[0];
            $ket_value .= "<br><a onclick=\"jConfirmKet('{$fcucode}','{$ket_full}','{$fname}');\" type='button' role=\"button\" class='btn btn-warning' > ยืนยัน 'อนุมัติข้ามเขต' ? </a> ";
          }

          $params['data_ket'] = $ket_value;

          $params['xfrrqty'] = array_sum($x_fsend);

          echo json_encode($params);
        break;
    }
    }
}


function edit_overket(){
  global $_dbmy,$_fn,$_dblib;
  $params = $_REQUEST;
  $tbname = "tb_overket";

  $fcucode = $params['fcucode'];
    $fname = $params['fname'];
    $ket = $params['ket'];

    $fdata = $_dbmy->getMYSQLValues('tb_overket','*',"  fcucode = '{$fcucode}' and fyear='{$_dblib->get_year()}'  ");
    $params['fdata'] = $fdata;
    if($fdata){
      // update 
      $kets  = explode(",",$fdata['kets']);
      $kets[] = $ket;
      $ckets = array();
      sort($kets);
      if($kets)
      foreach($kets  as $a=>$b){
         $ckets[]=substr($b,-4);
      }
      sort($ckets);
      $ckets = implode(",",$ckets);
      $kets = implode(",",$kets);

      $data = array(
        "fcucode"=> $fcucode
        ,"kets"=> $kets
        ,"ckets" => $ckets
        ,"edittime"=>time()
        ,"fcuname"=> $fname
        ,"fyear"=> $_dblib->get_year()
        );
     
    $params['status'] = $_dbmy->update_db($tbname,$data, " fyear = '{$_dblib->get_year()}' and fcucode = '{$params['fcucode']}'  ");

    }else{ // inseret new 
      $kets = $ket;
      $ckets = substr($ket,-4);

      $data = array(
        "fcucode"=> $fcucode
        ,"kets"=> $kets
        ,"ckets" => $ckets
        ,"addtime"=>time()
        ,"fcuname"=> $fname
        ,"fyear"=> $_dblib->get_year()
        
        );

        $params['status'] = $_dbmy->add_db($tbname,$data);
    }


 $params['data'] = $data;

  echo json_encode($params);

}

function del()
{
    global $_dbmy, $_fn, $tbname,$_dblib;
    $p = $_REQUEST;
    $key = "{$p['carno']}|{$p['cartype']}|{$p['zks']}|{$_dblib->m_fyear}";
    $sqlCommand = "
       delete from tb_reg_key where  concat(carno,'|',cartype_id,'|',zks,'|',fyear) = '{$key}';
       delete from tb_reg_car where concat(carno,'|',cartype_id,'|',zks,'|',fyear) = '{$key}';
     ";
    $p['sql'] = $sqlCommand;
    $p['ret'] = $_dbmy->execs($sqlCommand);
    echo json_encode($p);
}

function del_farm()
{
    global $_dbmy, $_fn, $tbname;
    $_REQUEST['delete'] = $_dbmy->del($tbname, " carno = '{$_REQUEST['carno']}' and fcucode = '{$_REQUEST['fcucode']}' and cartype_id = '{$_REQUEST['cartypeid']}' and zks = '{$_REQUEST['zks']}'  ");
    $params = $_REQUEST;
    echo json_encode($params);
}

function add()
{
    global $_dbmy, $_fn, $tbname, $_dblib, $tbCarType;

    $params['req'] = $_REQUEST;
    $params['error'] = 0;
    $params['errfield'] = '';
    $params['errmsg'] = '';
    $params['errtype'] = 0;
    $params['status_print'] = 0;

    if (strstr($_REQUEST['fcuname'], "-")) {
        $fcuname = explode("-", $_REQUEST['fcuname']);
        $fcuname = trim($fcuname[1]);
    } else {
        $fcuname = $_REQUEST['fcuname'];
    }

    $data_cartype = $_dbmy->getMYSQLValues($tbCarType, " * ", " cartype_id = '{$_REQUEST['car_type']}'");
    if ($data_cartype) {
        $get_cartype_value = $data_cartype['cartype_value'];
        $get_cartype_year = $_dblib->get_year();
        $get_cartype_text = $data_cartype['cartype_name'];
    }

    $carno = explode('|', $_REQUEST['truck_number']);
    $carno = $carno[0];

    $data_reg_car = array(
      "carno" => $carno,
      "cartype_id" => $_REQUEST['car_type'],
      "cartype_val" => $get_cartype_value,
      "car_val"=>$get_cartype_value,
      "cartype_text"=>$get_cartype_text,
      "fcucode" => $_REQUEST['fcucode'],
      "fcuname" => $fcuname,//str_replace("  ", "", $fcuname),
      "zks" => $_REQUEST['txt_zks'],
      "fsend" => $_REQUEST['fsend'],
      "fdate" => date('d/m/Y H:i', time()),
      "ftime" => time(),
      "fstatus" => "1",
      'fyear' => $_dblib->m_fyear,
      'zks_name'=>$_REQUEST['zksname']
  );
    // check over ket
    $time = time();
    $date = date('d/m/Y', $time);

    $arr_zks = substr($_REQUEST['txt_zks'], 3, 2);
    $sqlKeyCode = "SELECT lpad(count(*)+1,4,'0') as keycode FROM `tb_reg_key` WHERE zks like '%/{$arr_zks}/%' and fyear = {$_dblib->m_fyear} ";
    $params['sql_key_code'] = $sqlKeyCode;
    $keycode = $_dbmy->getDataAll($sqlKeyCode);

    $keycode = $keycode[0]['keycode'];
    $params['keyCode'] = "{$arr_zks}-{$keycode}";

    //insert data
    $hcarno = $_REQUEST['hcarno'];
    $data_reg_car = $_dbmy->insert($tbname, $data_reg_car);
    $str_reg_key = "
    INSERT INTO `tb_reg_key` ( `carno` , `zks`,`fyear`,`cartype_id`,`ftime`,`fdate`,`keycode`,`ket`,`hcarno`)
        SELECT * FROM (select '{$carno}','{$_REQUEST['txt_zks']}','{$_dblib->m_fyear}','{$_REQUEST['car_type']}','{$time}','{$date}', '{$keycode}','{$arr_zks}','{$hcarno}') as tmp
        WHERE NOT EXISTS (SELECT `zks` FROM `tb_reg_key` WHERE `zks` = '{$_REQUEST['txt_zks']}'
        AND `carno` = '{$carno}' and fyear = '{$_dblib->m_fyear}' and cartype_id = '{$_REQUEST['car_type']}' ) LIMIT 1
    ";
    $params['sql_insert_key'] = $str_reg_key;
    $data_reg_key = $_dbmy->execs($str_reg_key);

    $params['insert'] = $data_reg_car;

    echo json_encode($params);
}


function show()
{
    global $_dblib,$_fn,$_dbmy;
    $arr = array();
    $new_data = array();

    $data =  $_dblib->ReportRegCar();
    // init datatable
    if ($data) {
        foreach ($data as $k=>$v) {
            $reset = "";
            $x = array();

            $xzks = $v['zks'];
            $xzks = explode('/', $xzks);

            $x[] = $v['id'];//sprintf("%02s", $xzks[1])."-".$v['id'];
            $x[] = sprintf("%02s", $xzks[0]);
            $x[] = $v['zks_name'];
            $x[] = $v['carno'];
            $x[] = $v['cartype_id'].'-'.$v['cartype_text'];
            $x[] = $v['fcucode'];
            $x[] = $v['fsend'];
            $x[] = $v['xsend'];

            $new_data[] = $x;
            $db_data[$v['cartype_id']] = $x;
        }
    }
    $arr['data'] = $new_data;
    $arr['dbdata'] = $db_data;
    echo json_encode($arr);
}

function edit()
{
    global $_dbmy,$_dblib,$tbname;
    $arr = $_REQUEST;
    $dataUpdate = array("fsend"=>$_REQUEST['fsend']);
    $where = " concat(zks,'|',cartype_id,'|',carno,'|',fcucode) = '{$_REQUEST['id']}' and fyear='{$_dblib->m_fyear}' ";
    $arr['update'] = $_dbmy->update_db($tbname, $dataUpdate, $where);
    echo json_encode($arr);
}
