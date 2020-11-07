<?php
include_once("../../app.php");
$tbname = "sque_paid";
$tbname2 = "squata_quete_daily";

if ($_fn->is_ajax()) {
    if (isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"])) { //Checks if action value exists
        $action = trim($_REQUEST['mode']);
        switch ($action) { //Switch case for value of action
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
      case "getcarno":
      getcarno();
      break;
    }
    }
}

function getcarno()
{
    global $_dbmy,$_fn,$_dblib;
    $params = $_REQUEST;
    $keycode = $_REQUEST['keycode'];
    $qtype = $_REQUEST['qtype'];
    $dataCar = $_dblib->getRegCar($keycode);

    $keyFcucode = '';
    if ($dataCar) {
        $arKeyFcuCode = array();
        $dataCarKslQ = $_dblib->getRegCarByZKS($dataCar['zks']);
        if ($dataCarKslQ) {
            $dataCar['fcucode'] .= ",".$dataCarKslQ[0]['fcucode'];
        }
        $keyFcucode = explode(',', $dataCar['fcucode']);
        $keyFcucode = array_unique($keyFcucode);
        $keyFcucode = "'".implode("','", $keyFcucode)."'";
    }

    $dataCar['key_fcucode'] = $keyFcucode;
    $params['dataCar'] = $dataCar;

    $params['checkq'] = $_dblib->is_in_softpro($dataCar['carno'], $qtype);
    $params['msg_hasQ']='';
    if ($params['checkq']) {
        $params['msg_hasQ'] = "มีคิวที่ <span class='bb uu'>{$params['checkq']['FITEMNO']}</span>   ทะเบียนรถ : <span class='bb uu'>{$params['checkq']['FNAME']}</span>";
    }
    echo json_encode($params);
}

function del()
{
    global $_dbmy,$_fn,$tbname;
    $params = $_REQUEST;
    $_dbmy->del($tbname, " squata_id = '{$params['id']}' ");
    echo json_encode($params);
}

function add()
{
    global $_dbmy,$_fn,$tbname,$_dblib;
    $data_paid =array();
    $data_softpro=array();
    $mysql_s = array();
    $softpro_s = array();
    $params = $_REQUEST;
    $err_softpro_has = 0;
    $err_softpro_msg = "";

    // cut cane type
    //$params['fcanetype'] = substr($params['fcanetype'],0,25);

    // check data in softpro
    $err_q = array();
    $err_q[0] = " มีทะเบียน '%s' นี้ในระบบแล้ว ! คิวที่ %s";
    $err_q[1] = " มีเลขที่ใบสั่งงาน '%s' นี้ในระบบแล้ว ! คิวที่ %s";
    if ($params['paid_type']) {
        $check_data = $_dblib->is_in_softpro($params['truck_no'], $params['paid_type']);
        $params['err_softpro_field'] = 'truck_no';
        $params['err_softpro_type'] = '1';
    } else {
        $check_data = $_dblib->is_in_softpro(trim($params['truck_number']), $params['paid_type']);
        $params['err_softpro_field'] = 'truck_number';
        $params['err_softpro_type'] = '0';
    }

    if ($check_data) {
        $err_softpro_has = 1;
        $err_softpro_msg = sprintf($err_q[$params['paid_type']], $check_data['FNAME'], $check_data['FITEMNO']);
    }
    $params['err_softpro_has'] = $err_softpro_has;
    $params['err_softpro_msg'] = $err_softpro_msg;

    $fuser = strtoupper($_fn->ssLoginName());

    // init data q
    if (!$err_softpro_has) {
        $params['error'] = 0;
        $params['errfield'] = '';
        $params['errmsg'] = '';
        $params['errtype'] = 0;
        $params['status_print'] = 0;

        if ($params['paid_type']==0) { // ประเภทคิว "ส่วนตัว"
            $check_fields = array('fcucode'=>'กรุณากรอกโควต้า','truck_number'=>'กรุณากรอกทะเบียนรถ','truck_type'=>'เลือกประเภทรถ','cane_type'=>'เลือกประเภทอ้อย');
            if($params['truck_type'] == 'C4'){
                $check_fields['truck_head'] = 'กรุณากรอกหัวลาก';
            }
        } elseif ($params['paid_type']==0) { // ประเภทคิว "ส่วนตัว"
            $check_fields = array('truck_no'=>'กรุณากรอกเลขที่ใบสั่งงาน ??AQ?????? !!!','cane_type'=>'เลือกประเภทอ้อย');
        }else {
            $check_fields = array('truck_no'=>'กรุณากรอกเลขที่ใบสั่งงาน ??AQ?????? !!!','cane_type'=>'เลือกประเภทอ้อย','center_no'=>'กรุณาเลือกศูนย์ขนถ่าย');
        }

        if ($check_fields) {
            foreach ($check_fields as $k=>$v) {
                if ($_fn->is_blank($params[$k])) {
                    if ($k=='fcucode' || $k=='truck_no') {
                        $params['errtype'] = 1;
                    } else {
                        $params['errtype'] = 0;
                    }
                    $params['error'] = 1;
                    $params['errfield'] = $k;
                    $params['errmsg'] = $v;
                }
            }
        }

        if (!$params['error']) {
            $qnext = $_dblib->dbNext_Q_Softpro();
            $lanjod = $_dblib->dbLanjodName();
            $date_ka = $_fn->GET_DATE_KA();
            $params['date_ka'] = $date_ka;
            /** Over Filed */
            $over_que = "N";
            $over_que_date = "";
            ///********* */
            $datein = date("Y/m/d");
            $timein = date("H.i");


            $params['fcanetype_x'] = $params['fcanetype'];
            $fcanetype = explode("|", $params['fcanetype']);
            $canecode = trim($fcanetype[0]);
            $fpdcode = trim($fcanetype[2]);
            $fcanetype = trim($fcanetype[1]);
            $params['fcanetype'] = $canecode." ".$fcanetype;


            if (!$params['paid_type']) { // ประเภทคิว "ส่วนตัว"

                $fname = explode("|", $params['fcucode']);
                $params['fcucode'] = trim($fname[0]);
                $data_rd01cust = $_dblib->dbRD01CUST($params['fcucode']);

                if ($data_rd01cust) {
                    $params['fwono'] = $data_rd01cust[0]['FWONO'];
                    $params['fgrpcode'] = $data_rd01cust[0]['FGRPCODE'];
                    $params['fname'] = $data_rd01cust[0]['FCUNAME'];
                    $fwono = $params['fwono'];
                    $fname = $params['fname'];
                    $fgrpcode = $params['fgrpcode'];
                }

                $params['fvname'] = str_replace('-', '', $params['fvname']);

                // Edit : 07-01-2019
                // เงื่อนไขไม่คิดรถเล็ก ในการแจ้งคิว พิเศษ
                $CAR_TYPE_IS_Q_S = array("C1","C3","C4","C9");
                if (in_array(trim($params['truck_type']), $CAR_TYPE_IS_Q_S)) {
                    // ตรวจสอบโควต้าพิเศษ  Squata Master
                    $data_quata_spacial = $_dblib->dbSquataMaster($params['fgrpcode']);
                } else {
                    $data_quata_spacial = null;
                }

                $params['data_quata_spacial'] = $data_quata_spacial;
                // End Edit : 07-01-2019

                // value of spacial Quata
                $queue_amt = (int)$data_quata_spacial[0]['queue_amt'];
                $params['main_queue_amt'] = $queue_amt;
                $queue_remain = $queue_amt -1;

                $data_daily = $_dbmy->getMYSQLValues('squata_quete_daily', '*', "  fgrpcode = '{$params['fgrpcode']}' and queue_date = '{$date_ka}'  ");

                if ($data_daily) {
                    $params['check_queue_amt']  = $data_daily['queue_amt'];
                    $params['check']='1';
                    $params['check_queue_remain'] = $data_daily['queue_remain'];
                    if ($params['check_queue_remain'] <= 0) {
                        $over_que = "Y";
                    }

                    $data_daily_insert = array(
                'queue_last_time'=>time()
                ,"queue_date"=> $date_ka //curent date // วันที่ตัดกะ
                ,"fcucode"=> $params['fcucode']   // โควต้า sp
                ,"queue_amt"=> $queue_amt  // จำนวนคิวให้ต่อวัน จาก Squata_Master
                ,"queue_remain"=> $queue_remain // จำนวนคิวที่เหลือ จะลดเมื่อมีการแจ้งคิวใน sp
                ,'fyear'=> $_dblib->m_fyear
                ,'fgrpcode'=> $fgrpcode
              );
                } else { // รภร่วม
                    $params['check']='2';
                    $sql_check = " SELECT * FROM `squata_quete_daily` WHERE fyear = '{$_dblib->m_fyear}' and fgrpcode = '{$params['fgrpcode']}' order by queue_date DESC limit 1 ";
                    $data_daily = $_dbmy->exec($sql_check);

                    if ($data_daily) {
                        $data_daily = $data_daily[0];
                        $params['check_queue_amt']  = $data_daily['queue_amt'];
                        $params['data_check_2'] = $data_daily;
                        $params['check_queue_remain'] = $params['main_queue_amt'] + $data_daily['queue_remain'];

                        if ($data_daily['queue_remain'] < -1) {
                            $over_que = "Y";
                        }
                    }
                    if ($data_daily) {
                        $data_daily_insert = array(
                    'queue_last_time'=>time()
                    ,"queue_date"=> $date_ka //curent date // วันที่ตัดกะ
                    ,"fcucode"=> $params['fcucode']   // โควต้า sp
                    ,"queue_amt"=> $queue_amt  // จำนวนคิวให้ต่อวัน จาก Squata_Master
                    ,"queue_remain"=> $params['check_queue_remain']-1 // จำนวนคิวที่เหลือ จะลดเมื่อมีการแจ้งคิวใน sp
                    ,'fyear'=>$_dblib->m_fyear
                    ,'fgrpcode'=> $fgrpcode
                  );
                    } else { // กะวันใหม่
                        $data_daily_insert = array(
                  'queue_last_time'=>time()
                  ,"queue_date"=> $date_ka //curent date // วันที่ตัดกะ
                  ,"fcucode"=> $params['fcucode']   // โควต้า sp
                  ,"queue_amt"=> $queue_amt  // จำนวนคิวให้ต่อวัน จาก Squata_Master
                  ,"queue_remain"=> $queue_amt-1 // จำนวนคิวที่เหลือ จะลดเมื่อมีการแจ้งคิวใน sp
                  ,'fyear'=>$_dblib->m_fyear
                  ,'fgrpcode'=> $fgrpcode
                );
                    }
                }

                if ($data_quata_spacial) {
                    if ($over_que == 'Y') {
                        $a = abs($params['check_queue_remain']-1) + abs($queue_amt); // remain + amt ของล่าสุุด
                  $b = abs($params['main_queue_amt']); // amt จำนวนคิวตั้งตั้น
                  $zz = number_format(($a/$b), 2)."";
                        $z = number_format(($a/$b), 2)."";
                        $z = str_replace(",", "", $z);
                        $z = explode(".", $z);
                        $z = $z[0];
                        $R = ($a % $b==0)?$z-1:$z;
                        $params['number_over_que_date'] = "a=".abs($params['check_queue_remain']-1)."+".$queue_amt ." :  {$a}/{$b} = ".($a/$b)." = ".$z." = ".$R;
                        $over_que_date = $_fn->GET_DATE_NEXT_KA($R);
                    }

                    if ($params['check'] == 1) {
                        // init command update squata_quete_daily
                        $mysql_s[] = $_dbmy->update('squata_quete_daily', " queue_remain = queue_remain -1 , queue_last_time = '".time()."' ", " fgrpcode  = '{$params['fgrpcode']}' and queue_date= '{$date_ka}'  ", 1);
                    // check to Over_Que ถ้า น้อยกว่า 0 เท่ากับ Y นอกนั้น N
                    } else { // new
              // init command insert squata_quete_daily
              $mysql_s[] = $_dbmy->add_db('squata_quete_daily', $data_daily_insert, 1);
                    }
                }

                $data_paid = array(
            "fcucode"=> $params['fcucode']
          ,"fcuname"=>  str_replace(trim($params['fcucode']), '', $fname)
          ,"site"=> $params['site']
          ,"paid_type"=> $params['paid_type'] // ประเภทคิว 0,1
          ,"que_id"=> $qnext  // คิว sp
          ,"datetime_in"=> time() // วันเวลาเข้าขอคิว sp
          ,"que_location"=> $lanjod // ลานจอด
          ,"truck_type"=> $params['truck_type'] // ประเภทรถ
          ,"truck_number"=> trim($params['truck_number']) // ทะเบียนรถ
          ,"truck_head"=> $params['truck_head'] // หัวลาก
          ,"cane_type"=> $params['cane_type'] // ประเภทอ้อย
          ,'fyear' => $_dblib->m_fyear // ฤดูหีบ
          ,'truck_no' => $params['truck_no'] // เลขที่ใบสั่งงาน AQ
          ,"datein"=> $datein
          ,"timein"=> $timein
          ,'fvoudate' => $date_ka
          ,"over_que"=> $over_que // Y=คิวที่เกินจำนวนจ่ายในวัน, N=ปกติ
          ,"over_que_date"=> $over_que_date // จำนวนวันที่ปล่อยจากลานจอดเข้าชั่งได้
          ,'fuser'=>$fuser
          ,'sque_special'=> ($over_que_date <> "")?1:0
          ,'cane_type_name'=>$params['fcanetype']
          ,'truck_type_name'=>$params['fvname']

        );
                // init command insert sque_paid
                //$mysql_s[] = $_dbmy->add_db('sque_paid',$data_paid,1);
                $data_paid['over_que_date'] = date('d/m/Y', $_fn->str_to_time(str_replace('-', '', $over_que_date)));

                $params['shiprate'] = "-";
            } else { // ประเภทคิว "รถร่วม" หรือ ศูนย์

                $fwono = explode("|", $params['truckno']);
                $fwono =  substr(trim($fwono[6]), 0, 10);

                $data_before = $_dblib->dbQCarR($params['truck_no']);
                $data_before = $data_before[0];
                $data_master = $_dblib->dbSquataMaster($data_before['FCUCODE']);
                $data_rd01cust = $_dblib->dbRD01CUST($data_before['FCUCODE']);

                $data_master = $data_master[0];
                $queue_amt = $data_master['queue_amt'];
                $fdriver = $data_before['FDRIVER'];
                $fshiprate = $data_before['FSHIPRATE'];
                $fshipvia = $data_before['FSHIPVIA'];

                $data_paid = array();
                $data_paid['que_id'] = $qnext;
                $data_paid['fyear'] = $_dblib->m_fyear;
                $data_paid['cane_type'] = $params['cane_type'];
                $data_paid['fcucode'] = $data_before['FCUCODE'];
                $data_paid['truck_no'] = $data_before['FREQNO'];
                $data_paid['truck_number'] = $data_before['FVEHICLENO'];
                $data_paid['truck_type'] = $data_before['FVEHICLETY'];
                $data_paid['paid_type'] = $params['paid_type'];
                $data_paid['truck_head'] = '';
                $data_paid['fcuname'] = $data_before['FCUNAMET'];
                $params['shiprate'] = $data_before['FSHIPRATE'];
                $data_paid['shiprate'] = $data_before['FSHIPRATE'];
                $data_paid['site'] = $params['site'];
                $data_paid['paid_type'] = $params['paid_type'];
                $data_paid['datetime_in'] = time();
                $data_paid['que_location'] = $lanjod;
                $data_paid['datein'] = $datein;
                $data_paid['timein'] = $timein;
                $data_paid['fvoudate'] =  $date_ka;
                $data_paid['over_que'] = 'N';
                $data_paid['over_que_date'] = '';
                $data_paid['fuser'] = $fuser;
                $data_paid['sque_special'] = ($over_que_date <> "")?1:0;
                $data_paid['cane_type_name'] = $params['fcanetype'];
                $data_paid['truck_type_name'] = $params['fvname'];

                $fwpntno = 'OK';
            }

            $data_softpro = array(
                "FITEMNO" => $data_paid['que_id'], "FYEAR" => $_dblib->m_fyear // CUR_YEAR
                , "FPRDNO" => "01", "FVOUDATE" =>  str_replace("-", "/", $data_paid['fvoudate']), "FMOVECODE" => "19", "FSUBTYPE" => $data_paid['cane_type'], "FCONTCODE" => $data_paid['fcucode']  // FCUCODE
                , "FVEHICLENO" => $data_paid['truck_number'] //truck_number
                , "FVEHICLETY" => $data_paid['truck_type'] //truck_type
                , "FVEHTYPE" => $data_paid['paid_type'] // type Q
                , "FDRIVER" => $fdriver, "FPDCODE" => $fpdcode, "FREMARK" => $data_paid['truck_head'] // truck_head
                , "FENTDATE" => date('Y/m/d'), "FUSER" => $fuser //'EKK' // demo fix 'EKK'
                , "FPRJCODE" => "WG" //
                , "FDIVCODE" => "01" // รหัสโรงงาน
                , "FQUOTANO" => $fwono // FWONO
                , "FATAX" => "0", "FDELBY" => $fshipvia  // FSHIPVIA
                , "FQDATE" => $datein, "FQTIME" => $timein, "FUPDDATE" => $datein, "FUPDTIME" => date("H:i:s"), "FUPDBY" => $fuser //'EKK' // user demo 'ekk'
                , "FQBY" => $lanjod //  demo
                , "FTRNNO" => '', "FGDCODE" => '', "FINVNO" => '', "FJONO" => '', "FBINLOC" => '', "FVEHPART" => '0', "FTIMEIN" => '00.00', "FTIMEOUT" => '00.00', "FWGNOIN" => '', "FWGNOOUT" => '', "FQCASHNO" => '', "FDUMPTIME" => '00.00', 'FCARDNO' => ($over_que_date <> "") ? str_replace('-', '', $over_que_date) : ''
            );

            if ($fwpntno == 'OK') {


                $data_paid['fwpntno']='';
                $data_paid['paid_type'] = $params['paid_type'];
                if(($params['paid_type']==3)){
                    // ALTER TABLE `sque_paid` ADD `fwpntno` VARCHAR(30) NOT NULL COMMENT 'ใบขนถ่าย' AFTER `cane_type_name`, ADD `quloaded` VARCHAR(10) NOT NULL COMMENT 'สถานะอัพโหลดศูนย์' AFTER `fwpntno`;
                    $cid = $_REQUEST['center_no'];
                    $tid = $_REQUEST['truck_no'];
                    $chkQCenter =  $_dblib->dbCheckQCenter($cid,$tid); // ตรวจสอบว่า เลขที่ใบสั่งงานนี้มีการ อัพโหลดข้อมูลมาโรงงานหรือยัง.
                    if($chkQCenter){
                        // mysql
                        $data_paid['fwpntno'] = $chkQCenter['FTRNNO'];
                        $data_paid['quploaded'] = "CNY";
                        // softpro
                        $data_softpro["FWPNTNO"] = $chkQCenter['FTRNNO'];
                        $data_softpro["FVEHTYPE"] = '3'; // ประเภทคิวศูนย์
                        $data_softpro["FBINLOC3"] = 'CNY'; // สถานะศูนย์อัพโหลดเข้าโรงงานปกติ
                    }else{
                        // mysql 
                        $data_paid['fwpntno'] = '';
                        $data_paid['quploaded'] = "CNN";
                        $data_paid['paid_type'] = '1'; // แจ้งคิวจากรถศูนย์ เป็น รถร่วม
                        //softpro
                        $data_softpro["FWPNTNO"] = '';
                        $data_softpro["FVEHTYPE"] = '1'; // ประเภทคิวรถร่วม
                        $data_softpro["FBINLOC3"] = 'CNN'; // สถานะศูนย์อัพโหลดเข้าโรงงาน (ผิด) ปกติ
                    }
                 }else{
                    $data_softpro["FWPNTNO"] = '';
                 }
                 $data_softpro["FCUORDERNO"] = $data_paid['truck_no'];

            }

            // change format data to screen
            $data_paid['datein'] = date('d/m/Y', strtotime($datein));
            $params['data_paid'] = $data_paid;
            $params['data_daily'] = $data_daily;

            // run insert to database
            $params['rd01cust'] = $data_rd01cust;
            if ($data_rd01cust) {
                // softpro
                $find_q = chk_q_softpro($qnext, $data_paid['truck_number']);
                if ($find_q) {
                    $qnext = $_dblib->dbNext_Q_Softpro();
                }
                if (strlen($qnext) == 5) {
                    $data_softpro['FITEMNO'] = $qnext;
                    $data_paid['que_id'] = $qnext;

                    $params['softpro_s'][] = $_dbmy->add_db('DD11RRDT', $data_softpro, 1);
                    $gSQL_SoftPro = " BEGIN TRANSACTION BEGIN TRY  ". implode(';', $params['softpro_s']). "  COMMIT END TRY BEGIN CATCH ROLLBACK END CATCH ";
                    $params['ins_dt_softpro']=$_dblib->exec_softpro(($gSQL_SoftPro));

                    // init command insert sque_paid
                    $mysql_s[] = $_dbmy->add_db('sque_paid', $data_paid, 1);
                    $params['mysql_s'] = $mysql_s;
                    $gSQL_MySQL = implode(';', $params['mysql_s']);
                    $params['mysql_s'] = $gSQL_MySQL;
                    $_dbmy->execs($gSQL_MySQL);
                    $params['status_print'] = chk_q_softpro($qnext, $data_paid['truck_number']);
                } else {
                    $params['status_print']  = 0;
                }
            } else {
                $params['status_print']  = 0;
            }
            //--- check data before to print
        }
    }
    $params['table_name'] = $tbname;
    echo json_encode($params);
}

function chk_q_softpro($Q, $carno)
{
    global $_dblib;
    $check_q_softpro = $_dblib->dbQSoftpro($Q, $carno);
    if ($check_q_softpro) {
        return 1;
    } else {
        return 0;
    }
}

function show_myql()
{
    global $_dblib,$_fn,$_dbmy,$tbname;
    $arr = array();
    $new_data = array();
    $data = $_dbmy->getMYSQLValueAll('sque_paid', '*', '1=1 order by que_id desc ');
    // init datatable
    if ($data) {
        foreach ($data as $k=>$v) {
            $reset = "";
            $x = array();
            $x[] = $v['que_id'];
            $x[] = $v['fcucode'];
            $x[] = $v['paid_type'];
            $x[] = $v['truck_number'];
            $x[] = $v['cane_type'];
            $x[] = $v['datein']." ".$v['timein'];
            $x[] = $v['over_que'];
            $x[] = str_replace("-", "/", $v['fvoudate']) ;
            $new_data[] = $x;
            $db_data[$v['user_id']] = $x;
        }
    }
    $arr['data'] = $new_data;
    $arr['dbdata'] = $db_data;
    //$arr['data'] = array();
    echo json_encode($arr);
}

function show()
{
    global $_dblib, $ARR_STATUS_Q,$ARR_STATUS_Q_TYPE;
    $sql = "SELECT   [FITEMNO]
   ,[FVEHICLENO]
   , CONCAT( CONVERT(varchar, [FQDATE],111) ,' ', [FQTIME]) AS FQDATE
   ,CONCAT(sd.[FSUBTYPE], ' ', [sd].[FSUBTYPEDS]) AS FSUBTYPE
   ,CONCAT(DD11RRDT.[FCONTCODE], ' ', [rd].[FCUNAMET]) AS FNAME
   ,[FVEHTYPE]
   ,[FQBY]
   ,( SELECT  CONCAT([h].[FVEHICLETY],' ',[h].[FDESC]) FROM [dbo].[HD05VEHT] AS h WHERE [h].[FVEHICLETY] =  DD11RRDT.[FVEHICLETY]) AS FCARTYPE
   ,CASE
      WHEN DD11RRDT.[FWGOUT1] > 0  THEN 2
      WHEN DD11RRDT.[FWGIN1] > 0  THEN 1
    ELSE  0  END AS STATUS_Q
    ,DD11RRDT.[FCONTCODE]
    ,DD11RRDT.FCARDNO

   FROM [dbo].[DD11RRDT]  ,  [dbo].[RD01CUST] rd , [dbo].[SD04SUBM] sd
   WHERE [FYEAR] = '{$_dblib->m_fyear}'
   AND [rd].[FCUCODE] = [dbo].[DD11RRDT].[FCONTCODE]
   AND [sd].[FSUBTYPE] = [dbo].[DD11RRDT].[FSUBTYPE]
   AND [dbo].[DD11RRDT].FCOLPRDNO IS NULL
   AND [dbo].[DD11RRDT].FWGIN1 = 0
   ORDER BY [FITEMNO] DESC";

    $data = $_dblib->get_data_softpro2($sql);
    if ($data) {
        foreach ($data as $k=>$v) {
            $reset = "";
            $x = array();
            $x[] = $v['FITEMNO'];
            $x[] = $v['FVEHICLENO'];
            $x[] = $v['FQDATE'];
            $x[] = $v['FSUBTYPE'];
            $x[] = $v['FNAME'];
            $x[] = $v['FCARTYPE'];

            $data_quata_spacial = $_dblib->dbSquataMaster($v['FCONTCODE']);
            if ($data_quata_spacial) {
                $x[] = $ARR_STATUS_Q_TYPE[$v['FVEHTYPE']];
            } else {
                $x[] = $ARR_STATUS_Q_TYPE[$v['FVEHTYPE']];
            }

            if ($v['FCARDNO']<>'') {
                $x[] = $v['FQBY']. " S";
            } else {
                $x[] = $v['FQBY'];
            }

            //$x[] = $v['FVEHTYPE'];
            $x[] = $ARR_STATUS_Q[$v['STATUS_Q']];
            $new_data[] = $x;
            //$db_data[$v['user_id']] = $x;
        }
    }
    if ($new_data) {
        $arr['data'] = $new_data;
    } else {
        $arr['data'] = array();
    }
    $arr['sql'] = $sql;
    echo json_encode($arr);
}


function printq()
{
    global $_dbmy,$_dblib,$_fn;
    $params = $_REQUEST;
    $data = $_dbmy->getMYSQLValues('sque_paid', "*", " fyear = '{$_dblib->m_fyear}' and que_id = '{$params['id']}'  ");
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
    ,(SELECT [FSHIPRATE] FROM DD21FMRQ AS dd WHERE [dd].[FCROPYEAR]  = '{$_dblib->m_fyear}' AND [dd].[FREQNO] = [dt].[FCUORDERNO]) AS shiprate
    ,FCARDNO AS sque_special

  FROM [dbo].[DD11RRDT] dt
  WHERE [dt].[FYEAR] = '{$_dblib->m_fyear}' and FITEMNO = '{$params['id']}'
  ";
    $data = $_dblib->get_data_softpro2($sql);
    $data = $data[0];

    $data['fvoudate'] =  date('d/m/Y', $_fn->str_to_time($data['sque_special']));  //date('d/m/Y',strtotime($data['fvoudate']));
    $data['sque_special'] = ($data['sque_special']<>'')?1:0;

    // change format data to screen
    $data['datein'] = date('d/m/Y', strtotime($data['datein']));
    $params['data_paid'] = $data;
    $params['sql'] = $sql;
    echo json_encode($params);
}
