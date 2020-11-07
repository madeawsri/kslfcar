<?php
   class DbLibs extends FnBase
   {
       public $m_fyear;
       public $a_kslq;
       public function __construct()
       {
           $this->m_fyear = $this->get_year();
           $this->a_kslq = $this->getKslQ();
       }
       /************************** CHECKING ALERT Q */
       public function getCodeFarm(){
        global $_dbmy;
        return $_dbmy->getMYSQLValue('syscheck','val','id=1');
       }
       public function getLockTime(){ // HR
        global $_dbmy;
          return $_dbmy->getMYSQLValue('syscheck','val','id = 2');
       }
       public function getMaxSent(){
        global $_dbmy;
        return $_dbmy->getMYSQLValue('syscheck','val','id = 3');
       }
       public function getPersent(){
        global $_dbmy;
        return $_dbmy->getMYSQLValue('syscheck','val','id=4');
       }
      /**************************** CHECKING ALERT Q */
      private function getCheckStatus($id=1){
        global $_dbmy;
        return (int)$_dbmy->getMYSQLValue('syscheck','fact','id='.$id);
      }
      public function isCheckFarm(){ return ($this->getCheckStatus(1)); }
      public function isCheckHr(){ return ($this->getCheckStatus(2)); }
      public function isCheckMaxSent(){ return ($this->getCheckStatus(3)); }
      public function isCheckPerSent(){ return ($this->getCheckStatus(4)); }

      public function isUseCFarm(){ return ( 
          $this->getCheckStatus(5)); 
      }
      
      public function getToDay(){
          return date('Y/m/d');
      }


       public function getKslQ()
       {
           global $_dbmy;
           if(!$this->isUseCFarm()){
              return ['name'=>" <b style='color:red'> ยกเลิก </b> บัตรชาวไร่ ",'id'=>'-'];
           }else{
           $sql = "select id,name,fact from tb_todo where fact = 1 ";
           $data = $_dbmy->getDataAll($sql);
           if ($data) {
               return $data[0];
           } else {
               return false;
           }
        }
       }





       /**
        * =============================
        * REG CAR FARM
        * =============================
        */

       /**
        * MYSQL
        */

       public function getRegCarByZKS($fzks)
       {
           global $_dbmy;
           $fyear = $this->m_fyear;
           $kslq = $this->a_kslq;
           $kslq_id = $kslq['id'];
           if ($kslq_id == 1) {
               return null;
           }
           $zks = explode('/', $fzks);
           $cond = "";
           switch ($kslq_id) {
             case 2:
             $cond = " and RIGHT(rk.zks,4) = '{$zks[2]}' ";
               break;
             case 3:
             $cond = " and  substring(rk.zks,4,2) = '{$zks[1]}' ";
               break;
             case 4:
             $cond = " and left(rk.zks,2) = '{$zks[0]}' ";
               break;
             default: $cond = "";
               break;
           }
           //$codecar = $codecar * 1;
           $sql = "select
                  GROUP_CONCAT(rc.fcucode) as fcucode ,
                  GROUP_CONCAT(rc.fsend) as fsends
             from tb_reg_car as rc , tb_reg_key as rk
            where rk.carno = rc.carno
              and rk.zks=rc.zks
              and rk.fyear=rc.fyear
              and rk.cartype_id = rc.cartype_id
              and rc.fyear = '{$fyear}'
              {$cond}
            ";
           $data = $_dbmy->getDataAll($sql);
           return $data;
       }
       public function getRegCar($keycode)
       {
           global $_dbmy;
           $fyear = $this->m_fyear;
           $kslq = $this->a_kslq;
           $kslq_id = $kslq['id'];
           //$codecar = $codecar * 1;
           $sql = "select concat(rk.ket,'-',rk.keycode) as idx,
                 rc.zks,
                 rc.zks_name,
                 rc.carno,
                 rc.carno as id,
                 rc.carno as text,
                 rc.cartype_id,
                 rc.cartype_text,
                 rc.cartype_val,
                 rc.car_val,
                 rc.fyear,
                 rk.fprint,
                 rk.fpay,
                 GROUP_CONCAT(rc.fcucode) as fcucode ,
                 GROUP_CONCAT(rc.fsend) as fsends,
                 sum(rc.fsend) as fsend,
                 (rc.car_val - sum(rc.fsend)) as xsend,
                (rc.cartype_val like sum(rc.fsend) ) as check_car,
                rk.hcarno

            from tb_reg_car as rc , tb_reg_key as rk
           where rk.carno = rc.carno
             and rk.zks=rc.zks
             and rk.fyear=rc.fyear
             and rk.cartype_id = rc.cartype_id
             and rc.fyear = '{$fyear}'
             and concat(rk.ket,'-',rk.keycode) = '{$keycode}'

           group by rc.zks,rc.zks_name,rc.carno,rc.cartype_id,rc.cartype_text,rc.cartype_val,rc.car_val,rc.fyear,rk.keycode
           order by rk.zks
           ";
           $data = $_dbmy->getDataAll($sql);
           if ($data) {
               return $data[0];
           } else {
               return false;
           }
       }

       public function getListHCar($zks, $s='', $q=0)
       {
           global $_dbmy;
           $fyear = $this->m_fyear;

           //$codecar = $codecar * 1;
           $sql = "select concat(rk.ket,'-',rk.keycode) as idx,
                rc.zks,
                rc.zks_name,
                rc.carno,
                rc.carno as id,
                rc.carno as text,
                rc.cartype_id,
                rc.cartype_text,
                rc.cartype_val,
                rc.car_val,
                rc.fyear,
                rk.fprint,
                rk.fpay,
                GROUP_CONCAT(rc.fcucode) as fcucode ,
                GROUP_CONCAT(rc.fsend) as fsends,
                sum(rc.fsend) as fsend,
                (rc.car_val - sum(rc.fsend)) as xsend,
               (rc.cartype_val like sum(rc.fsend) ) as check_car,
               rk.hcarno

           from tb_reg_car as rc , tb_reg_key as rk
          where rk.carno = rc.carno
            and rk.zks=rc.zks
            and rk.fyear=rc.fyear
            and rk.cartype_id = rc.cartype_id
            and rc.fyear = '{$fyear}'
            and rk.cartype_id = 'C9'
            and rk.zks= '{$zks}'
            and rk.carno like '%{$s}%'
            and rk.carno not in (select hcarno from tb_reg_key as r where r.cartype_id = 'C3' and r.zks = rk.zks )
          group by rc.zks,rc.zks_name,rc.carno,rc.cartype_id,rc.cartype_text,rc.cartype_val,rc.car_val,rc.fyear,rk.keycode
          order by rk.zks
          ";
           if ($q) {
               return $sql;
           }
           $data = $_dbmy->getDataAll($sql);
           return $data;
       }


       public function getCheckHCar($zks, $carno='', $q=0)
       {
           global $_dbmy;
           $fyear = $this->m_fyear;
           $carno = ($carno)?"and rk.carno = '{$carno}'":"";
           //$codecar = $codecar * 1;
           $sql = "select concat(rk.ket,'-',rk.keycode) as id,
               rc.zks,
               rc.zks_name,
               rc.carno,
               rc.cartype_id,
               rc.cartype_text,
               rc.cartype_val,
               rc.car_val,
               rc.fyear,
               rk.fprint,
               rk.fpay,
               GROUP_CONCAT(rc.fcucode) as fcucode ,
               GROUP_CONCAT(rc.fsend) as fsends,
               sum(rc.fsend) as fsend,
               (rc.car_val - sum(rc.fsend)) as xsend,
              (rc.cartype_val like sum(rc.fsend) ) as check_car,
              rk.hcarno

          from tb_reg_car as rc , tb_reg_key as rk
         where rk.carno = rc.carno
           and rk.zks=rc.zks
           and rk.fyear=rc.fyear
           and rk.cartype_id = rc.cartype_id
           and rc.fyear = '{$fyear}'
           and rk.cartype_id = 'C3'
           and rk.zks= '{$zks}'
           {$carno}
         group by rc.zks,rc.zks_name,rc.carno,rc.cartype_id,rc.cartype_text,rc.cartype_val,rc.car_val,rc.fyear,rk.keycode
         order by rk.zks
         ";
           if ($q) {
               return $sql;
           }
           $data = $_dbmy->getDataAll($sql);
           if ($data) {
               $data = $data[0];
           }
           return $data;
       }


       public function getRowRegCar2($carno, $cartype) // 00-0000
       {
           global $_dbmy;
           $fyear = $p_fyear;
           if (!$p_fyear) {
               $fyear = $this->m_fyear;
           }
           //$codecar = $codecar * 1;
           $sql = "select concat(rk.ket,'-',rk.keycode) as id,
                 rc.zks,
                 rc.zks_name,
                 rc.carno,
                 rc.cartype_id,
                 rc.cartype_text,
                 rc.cartype_val,
                 rc.car_val,
                 rc.fyear,
                 rk.fprint,
                 rk.fpay,
                 GROUP_CONCAT(rc.fcucode) as fcucode ,
                 GROUP_CONCAT(rc.fsend) as fsends,
                 sum(rc.fsend) as fsend,
                 (rc.car_val - sum(rc.fsend)) as xsend,
                (rc.cartype_val like sum(rc.fsend) ) as check_car,
                rk.hcarno

            from tb_reg_car as rc , tb_reg_key as rk
           where rk.carno = rc.carno
             and rk.zks=rc.zks
             and rk.fyear=rc.fyear
             and rk.cartype_id = rc.cartype_id
             and rc.fyear = '{$fyear}'
             and rk.cartype_id = '{$cartype}'
             and rk.carno = '{$carno}'
           group by rc.zks,rc.zks_name,rc.carno,rc.cartype_id,rc.cartype_text,rc.cartype_val,rc.car_val,rc.fyear,rk.keycode
           order by rk.zks
       ";
           $data = $_dbmy->getDataAll($sql);
           if ($data) {
               $data = $data[0];
           }
           return $data;
       }

       public function getRowRegCar($codecar) // 00-0000
       {
           global $_dbmy;

           $codecar = explode("-", $codecar);
           $ket = $codecar[0];
           $codecar = $codecar[1];
           $fyear = $p_fyear;
           if (!$p_fyear) {
               $fyear = $this->m_fyear;
           }
           //$codecar = $codecar * 1;
           $sql = "select concat(rk.ket,'-',rk.keycode) as id,
                 rc.zks,
                 rc.zks_name,
                 rc.carno,
                 rc.cartype_id,
                 rc.cartype_text,
                 rc.cartype_val,
                 rc.car_val,
                 rc.fyear,
                 rk.fprint,
                 rk.fpay,
                 GROUP_CONCAT(rc.fcucode) as fcucode ,
                 sum(rc.fsend) as fsend,
                 (rc.car_val - sum(rc.fsend)) as xsend

            from tb_reg_car as rc , tb_reg_key as rk
           where rk.carno = rc.carno
             and rk.zks=rc.zks
             and rk.fyear=rc.fyear
             and rk.cartype_id = rc.cartype_id
             and rc.fyear = '{$fyear}'
             and rk.keycode in ('{$codecar}')
             and rk.zks like '%/{$ket}/%'
           group by rc.zks,rc.zks_name,rc.carno,rc.cartype_id,rc.cartype_text,rc.cartype_val,rc.car_val,rc.fyear,rk.keycode
           order by rk.zks
      ";
           $data = $_dbmy->getDataAll($sql);
           if ($data) {
               $data = $data[0];
           }
           return $data;
       }

       public function ReportRegCar($range_time=array())
       {
           global $_dbmy;
           $fyear = $p_fyear;
           if (!$p_fyear) {
               $fyear = $this->m_fyear;
           }

           if ($range_time && is_array($range_time)) {
               $t1 = str_replace('-', '/', $range_time[0]);
               $t2 = str_replace('-', '/', $range_time[1]);
               $time = " and rk.fdate BETWEEN '{$t1}' and '{$t2}' ";
           }

           $sql = " select concat(rk.ket,'-',rk.keycode) as id,
                rc.zks,
                rc.zks_name,
                rc.carno,
                rc.cartype_id,
                rc.cartype_text,
                rc.cartype_val,
                rc.car_val,
                rc.fyear,
                rk.fprint,
                rk.fpay,
                GROUP_CONCAT(rc.fcucode) as fcucode ,
                sum(rc.fsend) as fsend,
                (rc.car_val - sum(rc.fsend)) as xsend

           from tb_reg_car as rc , tb_reg_key as rk
          where rk.carno = rc.carno
            and rk.zks=rc.zks
            and rk.fyear=rc.fyear
            and rk.cartype_id = rc.cartype_id
            and rc.fyear = '{$fyear}' {$time}
          group by rc.zks,rc.zks_name,rc.carno,rc.cartype_id,rc.cartype_text,rc.cartype_val,rc.car_val,rc.fyear,rk.id
          order by rk.zks
     ";
           $data = $_dbmy->getDataAll($sql);
           return $data;
       }

       public function dbRegCarType($id='')
       {
           global $_dbmy;
           $id = ($id)?" and  id  = '{$id}' ":'';
           $gSQL = "
        SELECT  cartype_id as id,
        concat(cartype_id, '-', cartype_name) AS text,
        cartype_value as type_max
        FROM tb_car_type where 1=1 and cartype_value <> 0
        {$id}
        ";
           $arr = $_dbmy->exec($gSQL);
           return $arr;
       }

       public function dbListRegCar($id='', $top=5)
       {
           global $_dbmy,$_dblib;
           $id = ($id)?"  and carno  like '{$id}%' ":'';
           $gSQL = "
       SELECT concat(carno,'|',cartype_id) as id , concat(carno,' : ' , cartype_id) as text  FROM `tb_reg_car` where fyear = '{$this->m_fyear}' {$id}
group by carno, cartype_id limit {$top}
        ";
           $arr = $_dbmy->exec($gSQL);
           return $arr;
       }

       /*
              public function dbRegCar($id='')
              {
                  global $_dbmy;
                  $id = ($id)?" and carno  = '{$id}' ":'';
                  $gSQL = "
               SELECT  c.car_no as id,
                       c.car_no AS text,
                       t.car_type , t.car_type_max , t.fyear
                from tb_car_type as t , tb_car as c
               where t.id = c.car_type_id and t.fyear = '{$this->m_fyear}'
               {$id}
               ";
                  $arr = $_dbmy->exec($gSQL);
                  return $arr;
              }*/

       /**
        * SOFTPRO
        */
       public function dbZKS($fyear='')
       {
           global $_dblib;
           if (!$fyear) {
               $fyear = $this->m_fyear;
           }
           $sql = "
      SELECT
      concat(SUBSTRING([FSLROUTE],1,2),'/',
                    LEFT(RIGHT(CONCAT('000', CAST(REPLACE([FSMCODE],' ','') AS varchar(4))),4),2),'/',
                    RIGHT(CONCAT('000', CAST(REPLACE([FSMCODE],' ','') AS varchar(4))),4)) AS id ,
          concat(FSMCODE,'-',[FSMNAME]) AS text
        FROM [dbo].[LD01SMAN]
       WHERE [FSLROUTE] <> '' AND
       [FSMCODE] IN (SELECT [rd].[FSMCODE]
         FROM [dbo].[RD01CUST] rd
         WHERE  [rd].[FCUCODE] IN (SELECT [FCUCODE] FROM [dbo].[PD20WOI1] WHERE [FYEAR] = '{$fyear}' )
        GROUP BY [rd].[FSMCODE])
       ORDER BY [FSMCODE]
      ";
           return $_dblib->get_data_softpro($sql);
       }


       /** =============================================================== */


       //***  new project - Q-KSL */

       public function dbQSoftpro($fitemno, $carno)
       {
           global $_dblib;
           $gSQL = "  SELECT FITEMNO FROM [dbo].[DD11RRDT]
      WHERE [FYEAR] ='{$this->m_fyear}'
      AND [FITEMNO] = '{$fitemno}' AND  FVEHICLENO = '{$carno}' ";

           $data = $_dblib->exec_softpro($gSQL);
           return $data[0];
       }

       public function dbFcucode($id='')
       {
           global $_dblib;
           $id = ($id)?" AND rd.FCUCODE = '{$id}' ":"";
           $gSQL = "SELECT top 10
  [rd].[FCUCODE] AS id
  ,CONCAT([rd].[FCUCODE],'-',[rd].[FCUNAME]) AS text
  ,[pd].[FWONO], pd.frqqty
  FROM [dbo].[RD01CUST] rd , [dbo].[PD20WOI1] pd
  WHERE [rd].[FCUCODE] = [pd].[FCUCODE]
  AND [pd].[FYEAR] = '{$this->m_fyear}'
  {$id}
  ORDER BY [rd].[FCUCODE] ";
           return $_dblib->get_data_softpro($gSQL);
       }
       // Edit : 10/01/2019
       public function isQuataMaster($fcucode)
       {
           global $_dblib,$_dbmy,$_fn;
           $softpro_sql = " SELECT CASE WHEN [FGRPCODE] ='' OR [FGRPCODE] IS NULL  THEN rd.[FCUCODE] ELSE rd.[FGRPCODE] END AS FGRPCODE FROM [dbo].[RD01CUST] rd WHERE [rd].[FCUCODE] = '{$fcucode}' ";
           $dataSoftpro = $_dblib->get_data_softpro($softpro_sql);
           if ($dataSoftpro) {
               $fgrpcode = $dataSoftpro[0]['FGRPCODE'];
           } else {
               return false;
           }
           $qcane_sql = "SELECT * FROM `squata_master` WHERE fcucode = '{$fgrpcode}'";
           $dataQcane = $_dbmy->exec($qcane_sql);
           return $dataQcane;
       }
       // End Edit
       public function dbLevels($id='')
       {
           global $_dbmy;
           $id = ($id)?" where level_id  = '{$id}' ":'';
           $gSQL = "
    SELECT level_id AS id,
    concat(level_id, '-', level_name) AS text
    FROM sque_levels
    {$id}
    ";
           $arr = $_dbmy->exec($gSQL);
           return $arr;
       }

       public function dbQtype($id='')
       {
           global $_dbmy;
           $qtypes = array(
               array('id'=>'0','text'=>'0 - รถส่วนตัว'),
               array('id'=>'1','text'=>'1 - รถร่วม'),
               array('id'=>'3','text'=>'3 - รถศูนย์')
            );
           return $qtypes;
       }

       public function dbCanetype($ctype='')
       {
           global $_dblib;
           if ($ctype) {
               $ctype = " AND FSUBTYPE = '{$ctype}' ";
           }
           $sql = "
  SELECT
    [FSUBTYPE] AS id ,
    CONCAT([FSUBTYPE] , '|', [FSUBTYPEDS] , '|', FPDCODE) AS text , *
  FROM  [dbo].[SD04SUBM]
  where CONCAT([FSUBTYPE] , ' ', [FSUBTYPEDS]) LIKE '%{$q}%' {$ctype}
  ORDER BY [FSUBTYPE]
  ";
           return $_dblib->get_data_softpro($sql);
       }


public function dbCheckQCenter($cid,$sid){
    global $_dblib;
    // $cid : id of center 
    // $sid : เลขใบสั่งงาน
    $sql = "SELECT [dd].[FREQNO],[wd].[FTRNNO],wd.[FPRDNO] 
    FROM  [dbo].[DD21FMRQ] AS dd LEFT OUTER JOIN [dbo].[WD12WGRC]  AS wd ON dd.[FREQNO] = [wd].[FCUORDERNO] 
    WHERE [dd].[FCROPYEAR] = '{$this->m_fyear}' 
         AND [dd].[FREQTYPE] = 0 
         AND [wd].[FPRDNO] = '{$cid}'
         AND [dd].[FREQNO] =  '{$sid}' ";
         $data = $_dblib->get_data_softpro($sql);
    return @$data[0];

}

       public function dbCarType()
       {
           global $_dblib;
           $sql = "
  SELECT
    [FVEHICLETY] AS id
    , CONCAT([FVEHICLETY],' - ', [FDESC])  AS text , *
 FROM [dbo].[HD05VEHT]
 ORDER BY [FVEHICLETY]
  ";
           return $_dblib->get_data_softpro($sql);
       }

       public function dbRD01CUST($fcucode)
       {
           global $_dblib;
           $sql = "     SELECT
  CASE  WHEN rd.[FGRPCODE] = ''  THEN  [rd].[FCUCODE]
  ELSE  [rd].[FGRPCODE]  END AS FGRPCODE
  ,[pd].[FWONO]
  ,[rd].[FCUCODE]
  ,[rd].[FCUNAMET] AS FCUNAME
  FROM [dbo].[RD01CUST] rd ,[dbo].[PD20WOI1] pd
  WHERE [rd].[FCUCODE] = [pd].[FCUCODE] AND [pd].[FYEAR] = '{$this->m_fyear}'
  AND [rd].[FCUNAME] = [rd].[FCUNAMET]
  AND rd.FCUCODE = '{$fcucode}'
  ";

           return $_dblib->get_data_softpro($sql);
       }

       /** ข้อมูลแจ้งคิว เป็นรถร่วม */
       public function dbQCarR($trqno='')
       {
           global $_dblib;
           if ($trqno) {
               $trqno = " AND [r].[FREQNO]='{$trqno}' ";
           }
           $sql = "
 SELECT
  [r].[FREQNO],[r].[FDIVCODE],[r].[FCROPYEAR],[r].[FCUCODE]
  ,[c].[FCUNAMET],[r].[FWONO],[h].[FVEHICLENO],[r].[FDRIVER],[h].[FVEHICLETY],[t].[FDESC],[r].[FSHIPRATE],[r].[FSHIPVIA]
  FROM [dbo].[DD21FMRQ] r
  LEFT OUTER JOIN [dbo].[HD05VEHC] h ON r.[FASSETNO]=[h].[FVEHICLENO]
  LEFT OUTER JOIN [dbo].[HD05VEHT] t ON h.[FVEHICLETY]=[t].[FVEHICLETY]
  LEFT OUTER JOIN [dbo].[RD01CUST] c ON r.[FCUCODE]=[c].[FCUCODE]
  WHERE [r].[FCROPYEAR]='{$this->m_fyear}'
    AND  [r].[FREQTYPE]='0' {$trqno}
  ORDER BY [r].[FREQNO]
 ";
           return $_dblib->get_data_softpro2($sql);
       }
       /** Squata Master */
       public function dbSquataMaster($id='')
       {
           global $_dbmy;
           $id = ($id)?" where fcucode  = '{$id}' ":'';
           $gSQL = "
     SELECT * FROM `squata_master`
     {$id}
     ORDER BY `queue_amt` DESC
    ";
           $arr = $_dbmy->exec($gSQL);
           return $arr;
       }
       /** check has data before Q in Sofpro */
       public function is_in_softpro($fiter, $q_type=0)
       {
           global $_dblib,$_fn;
           $fiter = trim($fiter);
           if (!$q_type) {
               $sql = "SELECT FVEHICLENO AS FNAME, FITEMNO
              FROM [dbo].[DD11RRDT]
             WHERE [FYEAR] = '{$this->m_fyear}'
               AND [FVEHICLENO]  = '{$fiter}'
               AND [FWGOUT1] = 0 ";
           } else {
               $sql = "SELECT [FCUORDERNO] AS FNAME , FITEMNO
              FROM [dbo].[DD11RRDT]
             WHERE [FYEAR] = '{$this->m_fyear}'
               AND [FCUORDERNO] = '{$fiter}'  ";
           }

           $chk_data = $_dblib->get_data_softpro2($_fn->ConvertTIS620($sql));
           return $chk_data[0];
       }


       /** Get Q Next in Database SoftPro */
       public function dbNext_Q_Softpro()
       {
           global $_dblib,$_fn;
           $sql = "SELECT TOP 1  [FITEMNO] FROM [dbo].[DD11RRDT] WHERE [FYEAR] = '{$this->m_fyear}' ORDER BY [FITEMNO] DESC";
           $q = $_dblib->get_data_softpro($sql);
           $q = $q[0]['FITEMNO'];
           return $_fn->GET_NEXT_Q_SP($q);
       }

       /** Get Lanjod from IP Address Client */
       public function dbLanjodName()
       {
           global $_fn,$_dbmy;
           $sql = "
    SELECT lanjod_name FROM `ksl_lanjod` where lanjod_ip = '{$_fn->get_client_ip()}'
  ";
           $arr = $_dbmy->exec($sql);
           if($arr)
           return $arr[0]['lanjod_name'];
           else return "X01";
       }



     

       public function list_year()
       {
           $arr = array();
           $iyear = 5859;
           for ($i=$iyear; $i<=8000; $i=$i+101) {
               $x = array();
               $x['id'] = $i;
               $x['text'] = $i;
               $arr['items'][]=$x;
           }
           echo json_encode($arr);
       }
       /*
       select [a].* FROM
       (SELECT
       ISNULL([FSMCODE],'-') AS id,
       CONCAT (
       SUBSTRING([FSLROUTE],1,2),' ',
       SUBSTRING(RIGHT('0000'+CAST(ISNULL([fsmcode],0) AS VARCHAR),4),1,2),' ',
       RIGHT('000'+CAST(ISNULL([fsmcode],0) AS VARCHAR),3)
       ) AS text
       FROM [dbo].[LD01SMAN]
       WHERE [FSLROUTE]<>''
       ) a
       ORDER BY [a].[text]
       */
       public function list_zone_ket_fsmcode($mode='json')
       {
           //echo json_encode($this->get_fsmcodes2());
           $sql = "
      select [a].* FROM
      (SELECT
      ISNULL([FSMCODE],'-') AS id,
      CONCAT (
      SUBSTRING([FSLROUTE],1,2),' ',
      SUBSTRING(RIGHT('0000'+CAST(ISNULL([fsmcode],0) AS VARCHAR),4),1,2),' ',
      RIGHT('000'+CAST(ISNULL([fsmcode],0) AS VARCHAR),3)
      ) AS text
      FROM [dbo].[LD01SMAN]
      WHERE [FSLROUTE]<>''
      ) a
      ORDER BY [a].[text]
      ";
           $data =  $this->get_data_softpro($sql);
           if ($mode == 'json') {
               return  json_encode($this->get_fsmcodes2());
           }
       }

     

       public function get_where_fsmcode()
       {
           global $_fn;
           $data = $this->get_fsmcodes();
           return $_fn->db_to_array($data['items']);
       }

       public function get_server_connects()
       {
           global $_dbmy;
           return  $_dbmy->getMYSQLValueAll("connect_setting", "host_name,user_name,pass_name,db_name,fyear");
       }
       /**
       * @return
       * site_id,
       * site_name,
       * site_desc,
       * site_code,
       * site_cane
       *--------------
       */
       public function get_sites($site_name="")
       {
           global $_dbmy;
           $data = $_dbmy->getMYSQLValueAll(
               'ksl_site',
               'site_id,site_name,site_desc,site_code,site_cane',
               "1=1"
       );
           $new_data = array();
           if ($data) {
               foreach ($data as $k=>$v) {
                   $new_data[$v['site_name']]= $v;
               }
           }
           if ($site_name) {
               return $new_data[strtoupper($site_name)];
           } else {
               return $new_data;
           }
       }

       public function get_site()
       {
           global $GET_SITE_DATA,$GET_SITE_NAME;
           $x_site = strtoupper($GET_SITE_NAME);
           $GET_SITE_DATA = $this->get_sites($x_site);
           return $GET_SITE_DATA;
       }
       public function right($str, $length)
       {
           return substr($str, -$length);
       }
       public function left($str, $length)
       {
           return substr($str, 0, $length);
       }
       public function get_year()
       {
        global $_dbmy;
        return  $_dbmy->getMYSQLValue("connect_setting", "fyear");
       }
       public function get_fwono_frqqty($fyear, $fcucode='')
       {

           $sql = "
         SELECT
            SUM([FRQQTY]) AS FW,
            COUNT([FWONO]) AS NCOUNT
          FROM [dbo].[PD20WOI1]
         WHERE [FYEAR] = '{$fyear}'
       ";
           if ($fcucode) {
               $sql .= " AND fcucode = '{$fcucode}' ";
           }

           return $this->get_data_softpro($sql);
       }
       public function get_fwono_promote($fyear, $fcucode='')
       {
            $sql = "
         SELECT
            isnull(SUM([FRQQTY]),0) AS FW,
            isnull(COUNT([FWONO]),0) AS NCOUNT
          FROM [dbo].[PD20WOI1]
         WHERE [FYEAR] = '{$fyear}' AND FOPPLNFLAG = 'N'
       ";
           if ($fcucode) {
               $sql .= " AND fcucode = '{$fcucode}' ";
           }

           return $this->get_data_softpro($sql);
       }
       public function get_fwono_register_rai($fyear, $fcucode='')
       {
        
           $sql = "
         SELECT
             SUM([FLOTQTY]) AS FW,
            SUM([FNOLOT]) AS NCOUNT
          FROM [dbo].[PD20WOI1]
         WHERE [FYEAR] = '{$fyear}'
       ";
           if ($fcucode) {
               $sql .= " AND fcucode = '{$fcucode}' ";
           }

           return $this->get_data_softpro($sql);
       }
       /**
       * site_n : site name
       * site_d : site desction
       * @return array(site_n , site_d)
       */
       public function get_ksl_sites()
       {
           global $_dbmy,$GET_SITE_NAME;
           return $_dbmy->getMYSQLValueAll("ksl_site", "site_name as site_n,site_desc as site_d ", " site_name <> '{$GET_SITE_NAME}'  ");
       }
       /**
       * GET DATA IN DATABASE SOFTPRO
       */
       public function get_data_softpro($sql)
       {
           global $_fn;
           $db = TableSqlSrv::getInstance();
           $dbSoftpro = $db->getConnection();
           $data = $dbSoftpro->select($sql);
           $data = $_fn->MSSQLEncodeTH2D($data);
           //$dbSoftpro->disconnect();
           return $data;
       }

       /**
       * GET DATA IN DATABASE SOFTPRO
       */
       public function get_data_softpro2($sql)
       {
        global $_fn;
        $db = TableSqlSrv::getInstance();
        $dbSoftpro = $db->getConnection();
        $data = $dbSoftpro->select($sql);
        $data = $_fn->MSSQLEncodeTH2D2($data);
          return $data;
       }

       public function exec_softpro($sql)
       {
        global $_fn;
        $db = TableSqlSrv::getInstance();
        $dbSoftpro = $db->getConnection();
        $sql = $_fn->ConvertTIS620($sql);
        return $dbSoftpro->select($sql);
       }

       public function get_json_softpro($sql)
       {
            return $this->get_data_softpro($sql);
       }

       public function data_to_array($data, $col_key, $col_value)
       {
           $arr = array();
           if ($data) {
               foreach ($data as $k=>$v) {
                   $arr[$v[$col_key]] = $v[$col_value];
               }
           }
           return $arr;
       }

       /**
       * Get view data pagination by database SoftPro
       */
       public function view_json_softpro($table_name, $order_select, $page_min=1, $page_max=20, $field_select="*", $where_selec="1=1")
       {
           $rowNumber = " ROW_NUMBER() OVER (ORDER BY {$order_select} ) AS RowNumber  ";
           $sql = "
       WITH temp_table AS
        (
             select {$field_select} , {$rowNumber}
               from {$table_name}
               where {$where_select}
        )
        SELECT {$field_select} , RowNumber
        FROM temp_table
        WHERE RowNumber BETWEEN {$page_min} AND {$page_max};

       ";
           return $this->get_json_softpro($sql);
       }





   }
