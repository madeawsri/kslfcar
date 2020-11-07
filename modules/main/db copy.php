<?php
include_once("../../app.php");

switch(trim($_REQUEST['mode'])){
  case "new-customer":
     echo json_encode(newCustomer());
  break;
  case "con-customer":
     echo json_encode(conCustomer());
  break;
  case "sen-customer":
     echo json_encode(senCustomer());
  break;
  case "lan-customer":
     echo json_encode(lanCustomer());
  break;
  case "rec-customer":
     echo json_encode(recCustomer());
  break;
  case "mon-customer":
     echo json_encode(monCustomer());
  break;
}


function newCustomer(){
  global $_dblib,$_fn;

  // before 3 year
  $curYear = $_SESSION['SS_YEAR']*1; // 6162
  $curYear1 = $curYear - 101; // 6061
  $curYear2 = $curYear1 - 101; // 5960
  $curYear3 = $curYear2 - 101; // 5859

  $sqlNewCustomer = "
    SELECT  * 
    FROM (
        SELECT [pd].[FCUCODE],[pd].[FWONO],[pd].[FRQQTY] 
        , ISNULL( (
        SELECT  SUM([X].[fw]) AS FW 
          FROM (
            SELECT [FCONTCODE],SUM([FWEIGHT]) AS fw FROM [dbo].[DD11RRDT] WHERE [FYEAR] = '{$curYear1}' AND [FWGOUT1] > 0 
            GROUP BY [FCONTCODE] 
            UNION ALL 
            SELECT [FCONTCODE],SUM([FWEIGHT]) AS fw FROM [dbo].[DD11RRDT_HIS] WHERE [FYEAR] IN ( '{$curYear2}' , '{$curYear3}' ) AND [FWGOUT1] > 0 
            GROUP BY [FCONTCODE] 
            UNION ALL 
            SELECT [FCONTCODE],SUM([FWEIGHT]) AS fw FROM [dbo].[DD11RRDU_HIS] WHERE [FYEAR] IN ( '{$curYear2}' , '{$curYear3}' ) AND [FWGOUT1] > 0 
            GROUP BY [FCONTCODE]
            ) AS X 
            WHERE [X].[FCONTCODE] = [pd].[FCUCODE]
        GROUP BY [X].[FCONTCODE] 
        ),0) AS FW
        FROM [dbo].[PD20WOI1]  AS pd
        WHERE [pd].[FYEAR] = '{$curYear}'
    ) AS A
    WHERE [A].[FW] = 0 
  ";
   $newCustomerSoftpro = $_dblib->get_data_softpro($sqlNewCustomer);
   $dataNewCustomerSoftpro = array();
   if($newCustomerSoftpro)
   foreach($newCustomerSoftpro as $k=>$v){
      $dataNewCustomerSoftpro[$v['FCUCODE']] = $v['FRQQTY'];
   }
   //------------------------------------------------------------------------
   $sqlNewCustomer = "
    SELECT  * 
    FROM (
        SELECT [pd].[FCUCODE],[pd].[FWONO],[pd].[FRQQTY] 
        , ISNULL( (
        SELECT  SUM([X].[fw]) AS FW 
          FROM (
            SELECT [FCONTCODE],SUM([FWEIGHT]) AS fw FROM [dbo].[DD11RRDT] WHERE [FYEAR] = '{$curYear1}' AND [FWGOUT1] > 0 
            GROUP BY [FCONTCODE] 
            UNION ALL 
            SELECT [FCONTCODE],SUM([FWEIGHT]) AS fw FROM [dbo].[DD11RRDT_HIS] WHERE [FYEAR] IN ( '{$curYear2}' , '{$curYear3}' ) AND [FWGOUT1] > 0 
            GROUP BY [FCONTCODE] 
            UNION ALL 
            SELECT [FCONTCODE],SUM([FWEIGHT]) AS fw FROM [dbo].[DD11RRDU_HIS] WHERE [FYEAR] IN ( '{$curYear2}' , '{$curYear3}' ) AND [FWGOUT1] > 0 
            GROUP BY [FCONTCODE]
            ) AS X 
            WHERE [X].[FCONTCODE] = [pd].[FCUCODE]
        GROUP BY [X].[FCONTCODE] 
        ),0) AS FW
        FROM [dbo].[PD20WOI1]  AS pd
        WHERE [pd].[FYEAR] = '{$curYear}'
    ) AS A
    WHERE [A].[FW] <> 0 
  ";
   $newCustomerSoftproHas = $_dblib->get_data_softpro($sqlNewCustomer);
   //------------------------------------------------------------------------
   $dataNewCustomerSoftproHas = array();
  if($newCustomerSoftproHas)
  foreach($newCustomerSoftproHas as $k=>$v){
     $dataNewCustomerSoftproHas[$v['FCUCODE']] = $v['FRQQTY'];
  }
   //------------------------------------------------------------------------
   $getCurrent = "
   SELECT  [fcucode],[FWONO], 
           CAST( SUM([cnet_values])/COUNT(q_unq_gen_no) AS int) AS cnet_values
     FROM vw_fwono 
    WHERE [crop_year] = '{$curYear}' AND [FWONO] IS NOT NULL AND [flag_status] = 'อนุมัติแล้ว'
    GROUP BY  [fcucode],[FWONO]
   ";
   
  $newCustomerImap = $_dblib->get_data_imap($getCurrent);
  //**************************************************************************
  $dataNewCustomerImap = array();
  $dataNewCustomerImap2 = array();
  if($newCustomerImap)
  foreach($newCustomerImap as $k=>$v){
    if( array_key_exists($v['fcucode'],$dataNewCustomerSoftpro) ){
        if( !array_key_exists($v['fcucode'],$dataNewCustomerSoftproHas) )
           $dataNewCustomerImap[$v['fcucode']] = $v['cnet_values'];
    }else{
        $dataNewCustomerImap2[$v['fcucode']] = $v['cnet_values'];
    }
  }
  $result = $dataNewCustomerImap;
  $result2 = $dataNewCustomerImap2;
  return array("count"=>number_format(count($result)),'sum'=>number_format(array_sum($result)),
               "count2"=>number_format(count($result2)),'sum2'=>number_format(array_sum($result2))
              );
}

function conCustomer(){
  global $_dblib;

  // before 3 year
  $curYear = $_SESSION['SS_YEAR']*1; // 6162
  $curYear1 = $curYear - 101; // 6061
  $curYear2 = $curYear1 - 101; // 5960
  $curYear3 = $curYear2 - 101; // 5859

   //------------------------------------------------------------------------
   $getCurrent = "
   SELECT
   CASE WHEN flag_status = '0' THEN 'รอตรวจสอบ' WHEN flag_status = '1' THEN 'ติดต่อกลับ' WHEN flag_status = '2' THEN 'อนุมัติแล้ว' ELSE '-' END AS   flag_status
   ,COUNT([FWONO]) AS c, SUM([cnet_values]) AS s
   FROM
   (SELECT qt.*, sale_customer.fslroute, [dbo].[sale_customer].[FSMCODE] FROM
   (SELECT quota.flag_status,[ct].*,[dbo].[quota].[cu_unq_gen_no],[dbo].[quota].[crop_year],[dbo].[quota].[cu_fname], [dbo].[quota].[cu_lname],[dbo].[quota].[FWONO],[dbo].[quota].[sl_unq_gen_no],[dbo].[quota].[is_support_req],[dbo].[quota].[cnet_values] FROM
   (SELECT [FCUCODE] FROM [dbo].[customer] WHERE [dbo].[customer].[flag]='0'  GROUP BY  [dbo].[customer].[FCUCODE]) AS ct
   LEFT OUTER JOIN [dbo].[quota] ON [dbo].[quota].[FCUCODE] = [ct].[FCUCODE] WHERE [dbo].[quota].[flag]='0' AND [dbo].[quota].[FWONO] IS NOT NULL AND [dbo].[quota].[crop_year]='{$curYear}') AS qt
   LEFT OUTER JOIN [dbo].[sale_customer] ON [dbo].[sale_customer].[sl_unq_gen_no] = [qt].[sl_unq_gen_no] ) AS st
   LEFT OUTER JOIN [dbo].[ref_fslroute_area] ON [dbo].[ref_fslroute_area].[FSLROUTE] = [st].[fslroute]
   where 1=1
   --AND concat ([zone],[FSMCODE]) <> '09901'
   GROUP BY flag_status
   order by flag_status desc
   ";
  $newCustomerImap = $_dblib->get_data_imap($getCurrent);
  //**************************************************************************
  $arr = array();
  $arr['count'] = number_format($newCustomerImap[0]['c']);
  $arr['sum'] = number_format($newCustomerImap[0]['s']);
  $arr['count1'] = number_format($newCustomerImap[1]['c']);
  $arr['sum1'] = number_format($newCustomerImap[1]['s']);
  $arr['count2'] = number_format($newCustomerImap[2]['c']);
  $arr['sum2'] = number_format($newCustomerImap[2]['s']);
  return $arr;
}

function senCustomer(){
  global $_dblib;

  // before 3 year
  $curYear = $_SESSION['SS_YEAR']*1; // 6162
  $curYear1 = $curYear - 101; // 6061
  $curYear2 = $curYear1 - 101; // 5960
  $curYear3 = $curYear2 - 101; // 5859

   //------------------------------------------------------------------------
   $getCurrent = "
     SELECT [ax].[is_support_req],[ax].[typex],[ax].[bw], COUNT([ax].[fcucode]) AS count_fcucode , SUM([ax].[cnet_values]) AS cnet_values   FROM
    (SELECT *,
    (CASE WHEN aw.[nn]>0
    THEN 'ผ่าน' ELSE 'ยังไม่ทำเรื่อง' end
    ) AS bw
    from
    (
    SELECT fcucode, [is_support_req],  
    (CASE WHEN [is_support_req]='1'
    THEN 'ไม่ขอส่งเสริม' ELSE 'ขอส่งเสริม'
    END) AS typex,
    (CASE WHEN [is_support_req]='1'
    THEN 
    '-'
    ELSE
    (SELECT COUNT(*) FROM quota_support ba WHERE 
    a.[FCUCODE]=ba.[FCUCODE] 
    AND a.[crop_year]=ba.[crop_year]
    AND ba.[flag]='0' AND ba.[flag_status]='3')
    END) AS nn  
    , [a].[cnet_values]
    FROM quota  a WHERE [flag]='0' AND [FWONO] IS NOT NULL AND [crop_year]='{$curYear}'
    ) aw ) ax GROUP BY [ax].[is_support_req],[ax].[typex], [ax].[bw]
    ORDER BY [typex],[bw]
   ";


   $getCurrent = "
   SELECT [ax].[is_support_req],[ax].[typex],[ax].[bw], COUNT([ax].[fcucode]) AS count_fcucode , SUM([ax].[cnet_values]) AS cnet_values   FROM
   (SELECT *,
       (CASE WHEN aw.[nn] > 0   THEN 'ผ่าน' ELSE 'ยังไม่ทำเรื่อง' end   ) AS bw
   FROM (
       SELECT
            [stk].[FCUCODE]
            ,[stk].[is_support_req]
            ,(CASE WHEN stk.[is_support_req]='1'  THEN 'ไม่ขอส่งเสริม' ELSE 'ขอส่งเสริม'  END) AS typex
           ,(CASE WHEN stk.[is_support_req]='1'    THEN    '-'    ELSE   (SELECT COUNT(*) FROM quota_support ba WHERE   stk.[FCUCODE]=ba.[FCUCODE]  AND stk.[crop_year]=ba.[crop_year] AND ba.[flag]='0' AND ba.[flag_status]='3' )   END) AS nn
           ,[cnet_values]
       FROM
            (SELECT st.*, ref_fslroute_area.zone, ref_fslroute_area.ket  FROM
            (SELECT qt.*, sale_customer.fslroute,sale_customer.[FSMCODE] FROM
            (SELECT [ct].*,[dbo].[quota].[cu_unq_gen_no],[dbo].[quota].[crop_year],[dbo].[quota].[cu_fname], [dbo].[quota].[cu_lname],[dbo].[quota].[FWONO],[dbo].[quota].[sl_unq_gen_no],[dbo].[quota].[is_support_req],[dbo].[quota].[create_date] FROM
            (SELECT [FCUCODE] FROM [dbo].[customer] WHERE [dbo].[customer].[flag]='0'  GROUP BY  [dbo].[customer].[FCUCODE]) AS ct
            LEFT OUTER JOIN [dbo].[quota] ON [dbo].[quota].[FCUCODE] = [ct].[FCUCODE] WHERE [dbo].[quota].[flag]='0' AND [dbo].[quota].[FWONO] IS NOT NULL AND [dbo].[quota].[crop_year]='{$curYear}') AS qt
            LEFT OUTER JOIN [dbo].[sale_customer] ON [dbo].[sale_customer].[sl_unq_gen_no] = [qt].[sl_unq_gen_no] ) AS st
            LEFT OUTER JOIN [dbo].[ref_fslroute_area] ON [dbo].[ref_fslroute_area].[FSLROUTE] = [st].[fslroute]) AS stk
            LEFT OUTER JOIN [dbo].[quota] ON [dbo].[quota].[FWONO] = [stk].[FWONO] AND [dbo].[quota].[crop_year] = [stk].[crop_year]
            where 1=1
            --AND concat ([zone],[FSMCODE]) <> '09901'
        ) AS aw) AS ax
        GROUP BY [ax].[is_support_req],[ax].[typex], [ax].[bw]
        ORDER BY [typex],[bw]
   ";



  $newCustomerImap = $_dblib->get_data_imap($getCurrent);
  //**************************************************************************
  $ret = array();
  

  return array( 
                "count"=>number_format($newCustomerImap[0]['count_fcucode']),'sum'=>number_format($newCustomerImap[0]['cnet_values']),
                "count1"=>number_format($newCustomerImap[1]['count_fcucode']),'sum1'=>number_format($newCustomerImap[1]['cnet_values']),
                "count2"=>number_format($newCustomerImap[2]['count_fcucode']),'sum2'=>number_format($newCustomerImap[2]['cnet_values'])
              );
}

/*
SELECT SUM([count_land]) AS n , SUM([gps]) AS t FROM 
Vw_Quota_2
WHERE [crop_year] = '6162'
AND [gps] IS NOT NULL 
*/


function lanCustomer(){
  global $_dblib;

  // before 3 year
  $curYear = $_SESSION['SS_YEAR']*1; // 6162
  $curYear1 = $curYear - 101; // 6061
  $curYear2 = $curYear1 - 101; // 5960
  $curYear3 = $curYear2 - 101; // 5859

   //------------------------------------------------------------------------
 /*  $getCurrent = "
   SELECT isnull(SUM([count_land]),0) AS n , isnull(SUM([gps]),0) AS t FROM 
    Vw_Quota_2
    WHERE [crop_year] = '{$curYear}'
    AND [gps] IS NOT NULL 
   ";
   */

   $getCurrent = "
   SELECT
COUNT([lr_unq_gen_no]) as n ,SUM([land_narea]) as t, SUM([est_scane]) as p
FROM [dbo].[land_register] 
WHERE
[crop_year]='{$curYear}' AND [flag]='0' AND [flag_status]='2'
   ";
  $newCustomerImap = $_dblib->get_data_imap($getCurrent);



  //**************************************************************************
  return array("count"=>number_format($newCustomerImap[0]['n']),
               'sum'=>number_format($newCustomerImap[0]['t'],2),
               "count2"=>number_format($newCustomerImap[0]['p'],2));
}

function recCustomer(){
  global $_dblib;

  // before 3 year
  $curYear = $_SESSION['SS_YEAR']*1; // 6162

  $sql = "
  DECLARE @fyear varchar(4)
  SET @fyear = '{$curYear}'
  SELECT (SELECT [FQTARGET] FROM [dbo].[DD01CRPY] WHERE  [FCROPYEAR] = @fyear) AS VTARGET, 
          isnull( SUM([x].[FW]) , 0 )  AS SUMFW 
  FROM (
    SELECT SUM([FWEIGHT]) AS FW 
      FROM [dbo].[DD11RRDT] 
     WHERE [FYEAR] = @fyear AND  [FWGOUT1] > 0 
     UNION ALL 
    SELECT SUM([FWEIGHT]) AS FW 
      FROM [dbo].[DD11RRDT_HIS] 
     WHERE [FYEAR] = @fyear AND  [FWGOUT1] > 0 
     ) AS x  
  ";
  $data = $_dblib->get_data_softpro($sql);
  
  if($data[0]['VTARGET'])
     $pcent = number_format( ( ($data[0]['SUMFW']*100) / $data[0]['VTARGET'] ) , 2 );
  else 
     $pcent = '00.00';
  
  
  


  return array("count"=>number_format($data[0]['VTARGET']),'sum'=>number_format($data[0]['SUMFW'],2). " ({$pcent}%)" );
  

}

function monCustomer(){
  global $_dblib;

  // before 3 year
  $curYear = $_SESSION['SS_YEAR']*1; // 6162
  $curDate = date('Y-m-d');
  $sql = "
  SELECT  SUM(x.[XAMOUNT]) AS X
  FROM (
  SELECT
         DD23APLG.FSUCODE,
         DD24APWK.FDOCREFNO1,
           CASE WHEN DD23APLG.[FAPTYPE] = 1 THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -  
           CASE WHEN DD23APLG.[FAPTYPE] = 2 THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -
           CASE WHEN DD23APLG.[FAPTYPE] = 3 THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END +
           CASE WHEN DD23APLG.[FAPTYPE] = 4 THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END 
         AS XAMOUNT
      FROM { oj ((((DD23APLG DD23APLG LEFT OUTER JOIN DD24APWK
  DD24APWK ON DD23APLG.FSUCODE = DD24APWK.FSUCODE AND DD23APLG.FSUREFNO =
  DD24APWK.FSUREFNO AND DD23APLG.FCREDITCD = DD24APWK.FCREDITCD AND
  DD23APLG.FCOLPRDNO = DD24APWK.FCOLPRDNO) LEFT OUTER JOIN AD01VEN1 AD01VEN1
  ON DD23APLG.FSUCODE = AD01VEN1.FSUCODE) LEFT OUTER JOIN PD20WOI1 PD20WOI1 ON
  DD24APWK.FDOCREFNO1 = PD20WOI1.FWONO) LEFT OUTER JOIN RD01CUST RD01CUST ON
  AD01VEN1.FSUCODE = RD01CUST.FCUCODE) LEFT OUTER JOIN LD07SLRT LD07SLRT ON
  RD01CUST.FSLROUTE = LD07SLRT.FSLROUTE}
  WHERE DD23APLG.FMDATE <= {ts '{$curDate} 00:00:00.00'}  AND
  [DD24APWK].[FDOCREFNO1] IN (SELECT [FWONO] FROM [dbo].[PD20WOI1] WHERE [FYEAR] = '{$curYear}')
  GROUP BY DD23APLG.[FYEAR],DD23APLG.FSUCODE,DD23APLG.[FAPTYPE],DD23APLG.FSUCODE,DD24APWK.FDOCREFNO1
  ) AS X 
  ";
  $data = $_dblib->get_data_softpro($sql);
  
  $sql1 = "
     SELECT ISNULL(SUM([FWOAMT]),0) AS SMAT FROM [dbo].[PD20WOI1] WHERE [FYEAR] = '{$curYear}' AND [FWOAMT] > 0 AND [FUPDFLAG] ='Y'
  ";
  $data1 = $_dblib->get_data_softpro($sql1);

  $ttt = $data1[0]['SMAT'] + ($data[0]['X']);

  return array("a1"=>number_format($data1[0]['SMAT'],2),'a2'=>number_format($ttt,2) , 'a3'=> number_format(-1*$data[0]['X'],2) );
  

}