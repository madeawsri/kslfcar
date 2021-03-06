<?php
include "../app.php";
$q =  $_fn->ConvertTIS620($_REQUEST['q']);
switch ($_REQUEST['mode']) {
  case "regcar_db":
    echo json_encode(genJsonToSelect2($_dblib->dbListRegCar($_REQUEST['q'])));
  break;
  case "list_head_carno":
       $zks = $_REQUEST['zks'];
    echo json_encode(genJsonToSelect2($_dblib->getListHCar($zks, $_REQUEST['q'])));
  break;

  case "rd01cust":
      $gSQL = " SELECT top 5
      rd.FCUCODE AS id
      ,CONCAT([rd].[FCUCODE],' - ',[rd].[FCUNAMET]) AS text
      ,[pd].[FWONO]
      FROM [RD01CUST] rd , [PD20WOI1] pd
      WHERE [rd].[FCUCODE] = [pd].[FCUCODE]
      AND [pd].[FYEAR] = '{$_dblib->m_fyear}'
      AND CONCAT([rd].[FCUCODE],'-',[rd].[FCUNAME]) like '%{$q}%'
      AND rd.FCUCODE IN
          (SELECT [FCONTCODE]  FROM [dbo].[OD50RCVD] WHERE [FREFNO1] IS NOT NULL AND [FREFNO1] <> ''
            GROUP BY [FCONTCODE],[FREFNO1]  HAVING  LEN(LTRIM(RTRIM([FREFNO1]))) = 10)
      ORDER BY [rd].[FCUCODE] ";

      echo json_encode(genJsonToSelect2($_dblib->get_json_softpro($gSQL)));

  break;

  case "rd01cust-scan":
  $fcucode = $_REQUEST['fcucode'];

  
      $gSQL = " SELECT top 5
      rd.FCUCODE AS id
      ,CONCAT([rd].[FCUCODE],' - ',[rd].[FCUNAMET]) AS text
      ,[pd].[FWONO]
      FROM [RD01CUST] rd , [PD20WOI1] pd
      WHERE [rd].[FCUCODE] = [pd].[FCUCODE]
      AND [pd].[FYEAR] = '{$_dblib->m_fyear}'
      AND CONCAT([rd].[FCUCODE],'-',[rd].[FCUNAME]) like '%{$q}%'
      AND rd.FCUCODE IN ({$fcucode})
      AND rd.FCUCODE IN
          (SELECT [FCONTCODE]  FROM [dbo].[OD50RCVD] WHERE [FREFNO1] IS NOT NULL AND [FREFNO1] <> ''
            GROUP BY [FCONTCODE],[FREFNO1]  HAVING  LEN(LTRIM(RTRIM([FREFNO1]))) = 10)
      ORDER BY [rd].[FCUCODE] ";

      echo json_encode(genJsonToSelect2($_dblib->get_json_softpro($gSQL)));

  break;


  case "chk5000T":
  $fcucode = $_REQUEST['fcucode'];
  $track_number = trim($_REQUEST['track_number']);
  $maxVal = $_dblib->getMaxSent();
  $chkDisable = (int)$_dbmy->getMYSQLValue('sque_disquata', 'count(*)', " fyear='{$_dblib->m_fyear}' and quata = '{$fcucode}'   ");
  if ($chkDisable) {
      $maxVal = 1000000;
  }

  $gSQL = "  DECLARE @y varchar(4)
  SET @y = '{$_dblib->m_fyear}'
  SELECT
       isnull(SUM(x.FW),0) AS FW
     , (x.FC) AS FC
     ,  ((([x].[FC])) * sum(x.CC))+SUM(x.FW)   AS FWX
     , SUM([x].[CC]) AS FCX
     , CASE WHEN ((([x].[FC])) * sum(x.CC))+SUM(x.FW)  >= {$maxVal}
        THEN   'NO'    ELSE  'OK'   END  AS  'STATUS'
  FROM (
  SELECT
   isnull(SUM([FWEIGHT]),0) AS FW
  ,(SELECT round(AVG([FWEIGHT]),3) FROM  [dbo].[DD11RRDT] AS t WHERE [t].[FYEAR]=@y AND [t].[FCONTCODE] = [dbo].[DD11RRDT].[FCONTCODE]  AND [t].[FWGOUT1] > 0 )  AS FC
   ,(SELECT COUNT([FITEMNO]) FROM  [dbo].[DD11RRDT] AS t WHERE [t].[FYEAR]=@y AND [t].[FCONTCODE] = [dbo].[DD11RRDT].[FCONTCODE]  AND [t].[FWGOUT1] = 0 )  AS CC
     FROM   [dbo].[DD11RRDT]
  WHERE [FYEAR]=@y AND [FCONTCODE] = '{$fcucode}'   AND [FWGOUT1] > 0
  GROUP BY [FCONTCODE]
  ) AS x
  GROUP BY [x].[FC]";
            $data = $_dblib->get_data_softpro($gSQL);
            if (!$data) {
                $data = array();
            }
   // Edit: 06/03/2019 *** 120%
   $fw_t = is_null($data[0]['FW'])?0:$data[0]['FW'];

   $sql_frqqty = "SELECT [FRQQTY]  FROM [dbo].[PD20WOI1] WHERE [FCUCODE] = '{$fcucode}' AND [FYEAR] ='{$_dblib->m_fyear}'";
   $frqqty = 0;
   $db_frqqty = $_dblib->get_data_softpro($sql_frqqty);
   if ($db_frqqty) {
       $frqqty = $db_frqqty[0]['FRQQTY'];
   }


   $sql_fw_u = "SELECT  isnull(SUM([FWEIGHT]),0) AS fw FROM [dbo].[DD11RRDU] WHERE [FCONTCODE] = '{$fcucode}' AND [FWGOUT1] > 0 AND [FYEAR] ='{$_dblib->m_fyear}'";
   $data_fw_u = $_dblib->get_data_softpro($sql_fw_u);
   $fw_u = 0;
   if ($data_fw_u) {
       $fw_u = $data_fw_u[0]['fw'];
   }
   // end
   $ret = array();
   // Edit: 06/03/2019 *** 120%
   if ($frqqty) {
       $percen_fw = (($fw_t+$fw_u)/$frqqty)*100;
   } else {
       $percen_fw = 0;
   }
   $ret['fw_tu'] = array(
     'fw_t'=>$fw_t,
     'fw_u'=>$fw_u,
     'frqqty'=>$frqqty,
     'percen_fw'=>$percen_fw
   );

   $data[0]['fw_tu'] = ($fw_t+$fw_u);
   if (!$data[0]['STATUS']) {
       $data[0]['STATUS']="OK";
   }

   $ret['detail'] = $data[0];
   // Edit : 10/01/2019
   $fdateka = date("d/m/Y", strtotime($_fn->GET_DATE_KA()));
   $ret['is_master'] = ($_dblib->isQuataMaster($fcucode))?$fdateka:'';
   // ------ End Edit -----
   $ret['send'] = $_REQUEST;

  $maxTime = $_dblib->getLockTime();

   // Edit : 15/01/2019
   $sqlQchk = "SELECT    TOP 1  [FITEMNO] , [FCONTCODE],[FVEHICLENO],
   CONVERT([varchar], CONVERT([datetime], CONCAT( CONVERT([date],[FDATEOUT],111) ,' ',  REPLACE( [FTIMEOUT],'.',':'),':00')) ,120)  AS FDATEOUT,
   convert([varchar],DATEADD (hh,{$maxTime}, CONVERT([datetime], CONCAT( CONVERT([date],[FDATEOUT],111) ,' ',  REPLACE( [FTIMEOUT],'.',':'),':00')) ) ,120)  AS FDATEOUT_4HR,
   convert([varchar],GETDATE(),120) AS FCURDATE ,
   CASE WHEN (DATEADD (hh,{$maxTime}, CONVERT([datetime], CONCAT( CONVERT([date],[FDATEOUT],111) ,' ',  REPLACE( [FTIMEOUT],'.',':'),':00')) )  <= GETDATE())
   THEN 1 ELSE 0 END   AS checkdate
   FROM [dbo].[DD11RRDT]
   WHERE  [FYEAR] = '{$_dblib->m_fyear}' AND   [FWGOUT1] > 0  AND [FVEHICLENO] = '{$track_number}'
   ORDER BY [FITEMNO] DESC"; // AND [FCONTCODE] = '{$fcucode}'

   $ret['sql_qchktime']  = $sqlQchk;
   $dataCheckTime =  $_dblib->get_data_softpro2($_fn->ConvertTIS620($sqlQchk));
   $ret['ret_data'] = $dataCheckTime;
   if ($dataCheckTime) {
      $ret['qchktime'] = $dataCheckTime[0];
   } else {
      $ret['qchktime']  = array('checkdate'=>1);
   }
   // End Edit

   //--- Check duplicate car no
   $sql = "SELECT COUNT([dt].fitemno) as carno FROM [dbo].[DD11RRDT] AS dt WHERE dt.[FVEHICLENO] = '{$track_number}' AND [dt].[FWGOUT1] = 0 ";
   $check_carno = $_dblib->get_data_softpro2($_fn->ConvertTIS620($sql));
   if ($check_carno) {
       $ret['check_carno'] = (int)$check_carno[0]['carno'];
   } else {
       $ret['check_carno'] = 0;
   }

   $ret['xpersent'] = $_dblib->getPersent();


  $ret['isCheckFarm'] = $_dblib->isCheckFarm();
  $ret['isCheckHr'] = $_dblib->isCheckHr();
  $ret['isCheckMaxSent'] = $_dblib->isCheckMaxSent();
  $ret['isCheckPerSent'] = $_dblib->isCheckPerSent();



   echo json_encode($ret);

  break;
  case "fsmcode":
    $gSQL = "
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
    WHERE [a].[text] LIKE '%{$q}%'
    ORDER BY [a].[text]
    ";
    echo json_encode(genJsonToSelect2($_dblib->get_json_softpro($gSQL)));
  break;

  case "canetype":
      $sql = "
      SELECT
        [FSUBTYPE] AS id ,
        CONCAT([FSUBTYPE] , ' ', [FSUBTYPEDS]) AS text
      FROM  [dbo].[SD04SUBM]
      where CONCAT([FSUBTYPE] , ' ', [FSUBTYPEDS]) LIKE '%{$q}%'
      ORDER BY [FSUBTYPE]
      ";
    echo json_encode(genJsonToSelect2($_dblib->get_json_softpro($sql)));
  break;

  case "freqno":
    $sql="SELECT top 4 
    r.[FREQNO] as id
    ,concat(r.[FREQNO], '|', h.FVEHICLENO , '|',[h].[FVEHICLETY],'|',[t].[FDESC],'|',c.FCUCODE,'|',c.FCUNAMET,'|',p.FWONO ) AS text
    FROM [dbo].[DD21FMRQ] r
    RIGHT OUTER JOIN [dbo].[HD05VEHC] h ON r.[FASSETNO]=[h].[FVEHICLENO]
    LEFT OUTER JOIN [dbo].[HD05VEHT] t ON h.[FVEHICLETY]=[t].[FVEHICLETY]
    LEFT OUTER JOIN [dbo].[RD01CUST] c ON r.[FCUCODE]=[c].[FCUCODE]
    LEFT OUTER JOIN [dbo].[PD20WOI1] p  ON p.[FCUCODE]=[c].[FCUCODE]
    WHERE [r].[FCROPYEAR] = '{$_dblib->m_fyear}'
     AND  [r].[FREQTYPE]='0'
     AND concat(r.[FREQNO],'-',c.FCUCODE,' ',c.FCUNAMET, '-', h.FVEHICLENO )  LIKE '%{$q}%'
     AND [p].[FYEAR] = '{$_dblib->m_fyear}'
     AND [r].[FREQNO]  not IN (SELECT dt.[FCUORDERNO] FROM [dbo].[DD11RRDT] AS dt WHERE [dt].[FYEAR] = '{$_dblib->m_fyear}' AND [dt].[FCUORDERNO] IS NOT NULL  ) 
     AND c.FSMCODE2 <> 'CNT' "; //  ;
    echo json_encode(genJsonToSelect2($_dblib->get_json_softpro($sql)));
  break;

  case "freqno-center":
    $sql="SELECT top 4 
    r.[FREQNO] as id
    ,concat(r.[FREQNO], '|', h.FVEHICLENO , '|',[h].[FVEHICLETY],'|',[t].[FDESC],'|',c.FCUCODE,'|',c.FCUNAMET,'|',p.FWONO ) AS text
    FROM [dbo].[DD21FMRQ] r
    RIGHT OUTER JOIN [dbo].[HD05VEHC] h ON r.[FASSETNO]=[h].[FVEHICLENO]
    LEFT OUTER JOIN [dbo].[HD05VEHT] t ON h.[FVEHICLETY]=[t].[FVEHICLETY]
    LEFT OUTER JOIN [dbo].[RD01CUST] c ON r.[FCUCODE]=[c].[FCUCODE]
    LEFT OUTER JOIN [dbo].[PD20WOI1] p  ON p.[FCUCODE]=[c].[FCUCODE]
    WHERE [r].[FCROPYEAR] = '{$_dblib->m_fyear}'
     AND [c].[FCUCODE] = '{$_REQUEST['cncode']}'
     AND  [r].[FREQTYPE]='0'
     AND concat(r.[FREQNO],'-',c.FCUCODE,' ',c.FCUNAMET, '-', h.FVEHICLENO )  LIKE '%{$q}%'
     AND [p].[FYEAR] = '{$_dblib->m_fyear}'
     AND [r].[FREQNO]  not IN (SELECT dt.[FCUORDERNO] FROM [dbo].[DD11RRDT] AS dt WHERE [dt].[FYEAR] = '{$_dblib->m_fyear}' AND [dt].[FCUORDERNO] IS NOT NULL   ) 
     AND c.FSMCODE2 = 'CNT'   "; //
     //echo $sql;
    echo json_encode(genJsonToSelect2($_dblib->get_json_softpro($sql)));
  break;



   case "center":
    $sql = " SELECT   [FDMARK] AS id , CONCAT([FCUXREF],'|',[FDESC]) AS text FROM [dbo].[RD01CXRF]  WHERE CONCAT([FCUXREF],[FDESC]) LIKE '%{$q}%'   ";
    echo json_encode(genJsonToSelect2($_dblib->get_json_softpro($sql)));
   break;

}

function genJsonToSelect2($data)
{
    $arr = array();
    if ($data) {
        foreach ($data as $reg) {
            $arr[] = array("id"   => $reg['id'],
                   "text" => addslashes($reg['text']));
        }
    }
    return $arr;
}
