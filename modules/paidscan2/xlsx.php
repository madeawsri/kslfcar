<?php
include_once("../../includes/xlsxwriter.class.php");
include_once("../../app.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
  $date1 = $_REQUEST['txtdate1'];
  $date2 = $_REQUEST['txtdate2'];
$filename = "หนี้สินชาวไร่.xlsx";
header('Content-disposition: attachment; filename="'.XLSXWriter::sanitize_filename($filename).'"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$head_style = array( 'font'=>'Arial','font-size'=>8,'font-style'=>'bold', 
                  'fill'=>'#eee', 'halign'=>'center', 'border'=>'left,right,top,bottom');
$row_x_style = array( 'font'=>'Arial','font-size'=>10,'font-style'=>'', 
            'fill'=>'#f0f7d7', 'halign'=>'', 'border'=>'left,right,top,bottom');
$row_y_style = array( 'font'=>'Arial','font-size'=>10,'font-style'=>'', 
            'fill'=>'', 'halign'=>'', 'border'=>'left,right,top,bottom');


$writer = new XLSXWriter();
	

$head_rows1 = array("เขต","โควต้า" , "ชื่อ-สกุล","หนี้ทั้งหมด ณ วันที่ {$date1} " ,
                    "ยอดหนี้สิน ณ วันที่ {$date2} : ตัดค่าอ้อยสด","","","","","","","","","","รวมหนี้คงค้าง");

$head_rows2 = array("","","","",
            "ดอกเบี้ย","ปุ๋ย.","สารเคมี","เงินเกี้ยว","รวมทั้งหมด","เกี้ยว ปี5859","เกี้ยว ปี5960","เกี้ยว ปี6061","รวมโครงการ","หนี้+เกี้ยว",
            "");

// init datatable
$rows = getDataTable();

$sheet1='data_ireport';
// format column
$writer->writeSheetHeader($sheet1, ["string",], ['widths'=>[5,10,30,30,10]]);
//$writer->writeSheetHeader($sheet1, $head_format, $suppress_header_row = true);
// init column
$writer->writeSheetRow($sheet1, $head_rows1, $head_style );
$writer->writeSheetRow($sheet1, $head_rows2, $head_style );
// data table
if($rows)
  foreach($rows as $k=>$row) {
    if($k%2==0)
      $writer->writeSheetRow($sheet1, $row,$row_y_style);
    else
      $writer->writeSheetRow($sheet1, $row,$row_x_style);
  }
// init header
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=0, $end_row=1, $end_col=0);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=1, $end_row=1, $end_col=1);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=2, $end_row=1, $end_col=2);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=3, $end_row=1, $end_col=3);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=4, $end_row=0, $end_col=13);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=14, $end_row=1, $end_col=14);

$writer->writeToStdOut();

function getDataTable(){
  global $_dblib,$_fn;
  $fyear = $_REQUEST['fyear'];
  $fyears = array();
  $fyears[2] = $fyear;
  $fyears[1] = $fyear - 101;
  $fyears[0] = $fyears[1] - 101;
  
  $date1 = $_REQUEST['txtdate1'];
  $date2 = $_REQUEST['txtdate2'];
  
  $txt_date1 = $_fn->thai_date_fullmonth(strtotime($date1));
  $txt_date2 = $_fn->thai_date_fullmonth(strtotime($date2));
  
  $kets = $_REQUEST['fsmcode'];
  if(isset($kets)){
    $fsmcode =   $_fn->array_to_sql_in($kets);
    $fsmcode = " AND [X].[FSMCODE] IN ({$fsmcode}) ";
  }
  
  $arr  = array();
  $arr['send'] = $_REQUEST;
  $arr['txtdate1'] = $txt_date1;
  $arr['txtdate2'] = $txt_date2;
  
  $sql = "
  SELECT  [X].[FSMCODE],
            [X].[FCUCODE],
            [X].[FCUNAME],
            ABS(SUM([X].[XAMOUNT])) AS XSUM,
            SUM([X].[M373]) AS M373,
            SUM([X].[M362]) AS M362,
            SUM([X].[M363]) AS M363,
            SUM([X].[M361]) AS M361,
            SUM([X].[M5859_372]) AS M5859_372,
            SUM([X].[M5960_372]) AS M5960_372,
            SUM([X].[M6061_372]) AS M6061_372
            
            FROM (
        SELECT
         RD01CUST.[FSMCODE],
         DD23APLG.FSUCODE  AS FCUCODE,
         RD01CUST.[FCUNAME] AS FCUNAME,
         DD23APLG.FCREDITCD AS FCREDITCD,
         CASE WHEN DD23APLG.[FAPTYPE] = 1
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%' 
                     AND  DD23APLG.FMDATE <= {ts '{$date1} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -  
         CASE WHEN DD23APLG.[FAPTYPE] = 2 
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%'  
                     AND DD23APLG.FMDATE <= {ts '{$date1} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -
         CASE WHEN DD23APLG.[FAPTYPE] = 3 
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%'  
                     AND DD23APLG.FMDATE <= {ts '{$date1} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END +
         CASE WHEN DD23APLG.[FAPTYPE] = 4 
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%'  
                     AND DD23APLG.FMDATE <= {ts '{$date1} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END   AS XAMOUNT,
         
          CASE WHEN DD23APLG.FCREDITCD IN ('361') 
                      AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%' 
                      AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'}
                   THEN   SUM(DD23APLG.FAMOUNT) ELSE 0 END AS M361, 
         CASE WHEN DD23APLG.FCREDITCD IN ('362') 
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%' 
                     AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'}
                   THEN   SUM(DD23APLG.FAMOUNT) ELSE 0  END AS M362,
         CASE WHEN DD23APLG.FCREDITCD IN ('363') 
                    AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%' 
                    AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'}
                   THEN   SUM(DD23APLG.FAMOUNT) ELSE 0  END AS M363,
         CASE WHEN DD23APLG.FCREDITCD IN ('373') 
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%' 
                     AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'}
                    THEN   SUM(DD23APLG.FAMOUNT) ELSE 0  END AS M373,

         CASE WHEN DD23APLG.[FAPTYPE] = 1 
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[0]}%' 
                     AND DD23APLG.FCREDITCD IN ('372')
                     AND  DD23APLG.FMDATE <={ts '{$date2} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -  
         CASE WHEN DD23APLG.[FAPTYPE] = 2 
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[0]}%'  
                     AND DD23APLG.FCREDITCD IN ('372')
                     AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -
         CASE WHEN DD23APLG.[FAPTYPE] = 3
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[0]}%'  
                     AND DD23APLG.FCREDITCD IN ('372')
                    AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END +
         CASE WHEN DD23APLG.[FAPTYPE] = 4 
                    AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[0]}%'  
                    AND DD23APLG.FCREDITCD IN ('372')
                    AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                  THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END   AS M5859_372,
                                                                  
         CASE WHEN DD23APLG.[FAPTYPE] = 1 
                    AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[1]}%' 
                    AND DD23APLG.FCREDITCD IN ('372')
                    AND  DD23APLG.FMDATE <={ts '{$date2} 00:00:00.00'} 
                    THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -  
         CASE WHEN DD23APLG.[FAPTYPE] = 2 
                     AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[1]}%' 
                     AND DD23APLG.FCREDITCD IN ('372')
                     AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -
         CASE WHEN DD23APLG.[FAPTYPE] = 3 
                    AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[1]}%'  
                    AND DD23APLG.FCREDITCD IN ('372')
                    AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                  THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END +
         CASE WHEN DD23APLG.[FAPTYPE] = 4 
                 AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[1]}%'  
                 AND DD23APLG.FCREDITCD IN ('372')
                 AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END   AS M5960_372,
                                                                  
         CASE WHEN DD23APLG.[FAPTYPE] = 1 
                    AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%' 
                    AND DD23APLG.FCREDITCD IN ('372')
                    AND  DD23APLG.FMDATE <={ts '{$date2} 00:00:00.00'} 
                  THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -  
         CASE WHEN DD23APLG.[FAPTYPE] = 2 
                    AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%'  
                    AND DD23APLG.FCREDITCD IN ('372')
                    AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                   THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END -
         CASE WHEN DD23APLG.[FAPTYPE] = 3 
                    AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%'  
                    AND DD23APLG.FCREDITCD IN ('372')
                    AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                  THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END +
         CASE WHEN DD23APLG.[FAPTYPE] = 4 
                    AND DD24APWK.FDOCREFNO1 LIKE '{$fyears[2]}%'  
                    AND DD23APLG.FCREDITCD IN ('372')
                    AND  DD23APLG.FMDATE <= {ts '{$date2} 00:00:00.00'} 
                  THEN  SUM(DD23APLG.[FAMOUNT]) ELSE 0 END   AS M6061_372
           FROM
            { oj (DD23APLG DD23APLG LEFT OUTER JOIN DD24APWK DD24APWK ON
                DD23APLG.FSUCODE = DD24APWK.FSUCODE AND
            DD23APLG.FSUREFNO = DD24APWK.FSUREFNO AND
            DD23APLG.FCREDITCD = DD24APWK.FCREDITCD AND
            DD23APLG.FCOLPRDNO = DD24APWK.FCOLPRDNO)
             LEFT OUTER JOIN RD01CUST RD01CUST ON
                DD23APLG.FSUCODE = RD01CUST.FCUCODE}
        WHERE
            DD23APLG.[FSUCODE] IN ( 
              SELECT [FCUCODE] FROM [dbo].[RD01CUST] 
               WHERE [FCUCODE] IN (SELECT [FCUCODE] FROM [dbo].[PD20WOI1] WHERE [FYEAR] = '{$fyears[2]}') )
        GROUP BY DD23APLG.FCREDITCD,DD23APLG.[FAPTYPE],DD23APLG.[FMDATE], 
                        DD23APLG.FSUCODE,DD24APWK.FDOCREFNO1,RD01CUST.[FCUNAME],
                        RD01CUST.[FSMCODE]) AS X
        WHERE [X].[XAMOUNT] > 0 {$fsmcode}
        GROUP BY [X].[FCUCODE],[X].[FCUNAME],[X].[FSMCODE]
        
  ";

  $data = array('','','','','',
                '','','','','',
                '','','','','',);
  
  $dbData = $_dblib->get_data_softpro($sql);
  $arr['debug'] = $dbData;
  if($dbData)
  foreach($dbData as $k=>$v){
    $x = array();
    $x[]=$v['FSMCODE'];
    $x[]=$v['FCUCODE'];
    $x[]=$v['FCUNAME'];
    $x[]=$_fn->format_number($v['XSUM']);
    $x[]=$_fn->format_number($v['M373']);
    $x[]=$_fn->format_number($v['M362']);
    $x[]=$_fn->format_number($v['M363']);
    $x[]=$_fn->format_number($v['M361']);
    $x[]=$_fn->format_number($v['M361']+$v['M362']+$v['M363']+$v['M737']);
    $x[]=$_fn->format_number($v['M5859_372']);
    $x[]=$_fn->format_number($v['M5960_372']);
    $x[]=$_fn->format_number($v['M6061_372']);
    $x[]=$_fn->format_number($v['M5859_372']+$v['M5960_372']+$v['M6061_372']);
    $xsum = $_fn->format_number($v['M361']+$v['M362']+$v['M363']+$v['M737']+$v['M5859_372']+$v['M5960_372']+$v['M6061_372']);
    $x[] = $xsum;
    $x[] = $xsum;
        
    $arr['data'][] = $x;
  }
  else
    $arr['data'][] = $data;
  
  //$arr['sqlx'] = $sql;
  //echo json_encode($arr);
  return $arr['data'];
}
exit(0);