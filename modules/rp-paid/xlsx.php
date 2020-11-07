<?php
include_once("../../includes/xlsxwriter.class.php");
include_once("../../app.php");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
  $date1 = $_REQUEST['txtdate1'];
  $date2 = $_REQUEST['txtdate2'];
$filename = "รายงานคิว S.xlsx";
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
	

$head_rows1 = array("คิวที่",
                    "โควต้าหลัก",
                    "โควต้ารอง",
                    "ชื่อ-สกุล",
                    "วันที่แจ้งคิว",
                    "ทะเบียน",
                    "ประเภทอ้อย",
                    "วันที่ปล่อยรถ");
/*
$head_rows2 = array("","","","",
            "ดอกเบี้ย","ปุ๋ย.","สารเคมี","เงินเกี้ยว","รวมทั้งหมด","เกี้ยว ปี5859","เกี้ยว ปี5960","เกี้ยว ปี6061","รวมโครงการ","หนี้+เกี้ยว",
            "");
*/
// init datatable
$rows = getDataTable();

$sheet1='data_ireport';
// format column
$writer->writeSheetHeader($sheet1, ["string",], ['widths'=>[5,10,30]]);
//$writer->writeSheetHeader($sheet1, $head_format, $suppress_header_row = true);
// init column
$writer->writeSheetRow($sheet1, $head_rows1, $head_style );
//$writer->writeSheetRow($sheet1, $head_rows2, $head_style );
// data table
if($rows)
  foreach($rows as $k=>$row) {
    if($k%2==0)
      $writer->writeSheetRow($sheet1, $row,$row_y_style);
    else
      $writer->writeSheetRow($sheet1, $row,$row_x_style);
  }
// init header
/*
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=0, $end_row=1, $end_col=0);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=1, $end_row=1, $end_col=1);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=2, $end_row=1, $end_col=2);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=3, $end_row=1, $end_col=3);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=4, $end_row=0, $end_col=13);
  $writer->markMergedCell($sheet1, $start_row=0, $start_col=14, $end_row=1, $end_col=14);
*/
$writer->writeToStdOut();

function getDataTable(){
  global $_dblib,$_fn;

  $arr  = array();
  
  $sql = "
  SELECT 
  [FITEMNO],
  (SELECT [FGRPCODE] FROM [dbo].[RD01CUST] AS rd WHERE [rd].[FCUCODE] = [DD11RRDT].[FCONTCODE] ) AS FGRPCODE
  ,[FCONTCODE]
  ,(SELECT [FCUNAMET] FROM [dbo].[RD01CUST] AS rd WHERE [rd].[FCUCODE] = [DD11RRDT].[FCONTCODE] ) AS FNAMET
  , concat(CONVERT(date,[FQDATE],111), ' ',  [FQTIME]) AS FDATE
  ,[FVEHICLENO]
  ,(SELECT [sd].[FSUBTYPEDS] FROM [dbo].[SD04SUBM] sd WHERE [sd].[FSUBTYPE] = [dbo].[DD11RRDT].[FSUBTYPE]) AS [FSUBTYPE_NAME]
  , CONCAT( LEFT([FCARDNO],4) , '-',   SUBSTRING([FCARDNO],5,2), '-',  right([FCARDNO],2) ) AS FCARDNO
    FROM [dbo].[DD11RRDT] 
  WHERE [FYEAR] = '6162'
  AND [FCARDNO] <> ''  
  ORDER BY [FCARDNO]
  ";

  $dbData = $_dblib->get_data_softpro2($sql);
  $arr['debug'] = $dbData;
  if($dbData)
  foreach($dbData as $k=>$v){
    $x = array();
    $x[]=$v['FITEMNO'];
    $x[]=$v['FGRPCODE'];
    $x[]=$v['FCONTCODE'];
    $x[]=$v['FNAMET'];
    $x[]=$v['FDATE'];
    $x[]=$v['FVEHICLENO'];
    $x[]=$v['FSUBTYPE_NAME'];
    $x[]=$v['FCARDNO'];
          
    $arr['data'][] = $x;
  }
  else
    $arr['data'][] = array();
  
  return $arr['data'];
}
exit(0);