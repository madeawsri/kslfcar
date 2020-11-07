
<?php
include_once("../../app.php");
require('../../libs/tcpdf/tcpdf.php');
require('../../libs/tcpdf/tcpdf_barcodes_1d.php');

// เรียกใช้ Class TCPDF กำหนดรายละเอียดของหน้ากระดาษ
// PDF_PAGE_ORIENTATION = กระดาษแนวตั้ง
// PDF_UNIT = หน่วยวัดขนาดของกระดาษเป็นมิลลิเมตร (mm)
// PDF_PAGE_FORMAT = รูปแบบของกระดาษเป็น A4
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8');

// กำหนดคุณสมบัติของไฟล์ PDF เช่น ผู้สร้างไฟล์ หัวข้อไฟล์ คำค้น
//$pdf->SetCreator('Mindphp');
//$pdf->SetAuthor('Mindphp Developer');
//$pdf->SetTitle('Mindphp Example 02');
//$pdf->SetSubject('Mindphp Example');
//$pdf->SetKeywords('Mindphp, TCPDF, PDF, example, guide');

// กำหนดรายละเอียดของหัวกระดาษ สีข้อความและสีของเส้นใต้
// PDF_HEADER_LOGO = ไฟล์รูปภาพโลโก้
// PDF_HEADER_LOGO_WIDTH = ขนาดความกว้างของโลโก้

//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Mindphp Example 02', 'This is PDF Header', array(0, 64, 255), array(0, 64, 128));

// กำหนดรายละเอียดของท้ายกระดาษ สีข้อความและสีของเส้น
//$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// กำหนดตัวอักษร รูปแบบและขนาดของตัวอักษร (ตัวอักษรดูได้จากโฟลเดอร์ fonts)
// PDF_FONT_NAME_MAIN = ชื่อตัวอักษร helvetica
// PDF_FONT_SIZE_MAIN = ขนาดตัวอักษร 10
//$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(array('thsarabun', 'B', 12));
//$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// กำหนดระยะขอบกระดาษ
// PDF_MARGIN_LEFT = ขอบกระดาษด้านซ้าย 15mm
// PDF_MARGIN_TOP = ขอบกระดาษด้านบน 27mm
// PDF_MARGIN_RIGHT = ขอบกระดาษด้านขวา 15mm
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
//$pdf->SetMargins(0, 0, 0);

// กำหนดระยะห่างจากขอบกระดาษด้านบนมาที่ส่วนหัวกระดาษ
// PDF_MARGIN_HEADER = 5mm
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetHeaderMargin(0);
// กำหนดระยะห่างจากขอบกระดาษด้านล่างมาที่ส่วนท้ายกระดาษ
// PDF_MARGIN_FOOTER = 10mm
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetFooterMargin(0);

// กำหนดให้ขึ้นหน้าใหม่แบบอัตโนมัติ เมื่อเนื้อหาเกินระยะที่กำหนด
// PDF_MARGIN_BOTTOM = 25mm นับจากขอบล่าง
//$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

// กำหนดตัวอักษรสำหรับส่วนเนื้อหา ชื่อตัวอักษร รูปแบบและขนาดตัวอักษร
$pdf->SetFont('thsarabun', '', 14);

// กำหนดให้สร้างหน้าเอกสาร
$pdf->AddPage();


$style = "
<style>

.tb-main td {
  vertical-align:top;
  border:3px double black;
  width:255px;
  height:150px;
}

tr.border_bottom td {
  border-bottom:1pt solid black;
}

tr.border_bottom2 td {
  border-bottom:1pt solid black;
  border-right:1pt solid black;
}

.top-card {
  font-weight:bold;
  font-size:18px;
}

.txt-value{
  font-weight:bold;
  font-size:16px;
}

.txt-value2{
  font-weight:bold;
  font-size:14px;
}

.fcucode{
  text-align: center;
  font-size: 18px;
}

</style>
";

/*
   GET DATA FORM DATABASE
*/
$carno = explode('|', $_REQUEST['carno']);
$carno = $carno[0];
$cartype = $_REQUEST['cartype'];
$zks = $_REQUEST['zks'];
$fyear = $_dblib->m_fyear;
$fdata = $_dbmy->getMYSQLValueAll(
    'tb_reg_car',
    " (select lpad(id,5,'0') from tb_reg_key as rk where rk.carno = tb_reg_car.carno and rk.zks=tb_reg_car.zks and rk.fyear=tb_reg_car.fyear) as code,tb_reg_car.* ",
    " carno like '{$carno}' and zks = '{$zks}' and cartype_id = '{$cartype}' and fyear = '{$fyear}'  "
);


// zone-kets
$zk = explode('/', $fdata[0]['zks']);
$s = $zk[2];
$k = sprintf("%02s", $zk[1]);
$zk = "{$zk[0]}/ {$k}";
// zks name
$zks_name = explode('-', $fdata[0]['zks_name']);
$zks_name = trim($zks_name[1]);
// key (id)
$keyBarcode = $fdata[0]['code'];//sprintf("%05s", $fdata[0]['code']);
$params = $pdf->serializeTCPDFtagParameters(array($keyBarcode, 'C39', '', '', 70, 15, 0.4,
array('position'=>'S', 'border'=>false, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'thsarabun', 'fontsize'=>10, 'stretchtext'=>4), 'N'));

if ($fdata) {
    foreach ($fdata as $k=>$v) {
        $x = substr($v['fcucode'], -5);
        $fcucode .= "01<span style=\"color:gray;\">{$x}</span> &nbsp;&nbsp;&nbsp;&nbsp;";
    }
}
$html = <<<EOD
<table class="tb-main">
   <tr>
     <td style="margin-right:10px;">


<table cellspacing="0" cellpadding="5" width="100%" >
  <tr  class="border_bottom">
  <td  width="30%">
<img src="../../libs/tcpdf/images/LogoKSL.png"  width="60" height="35" >
  </td>
  <td width="70%" valign="top">
<span class="top-card"> บัตรประจำรถบรรทุกปี {$fdata[0]['fyear']} </span><br>
บริษัท น้ำตาลขอนแก่น จำกัด (มหาชน)
  </td></tr>
</table>

<table cellspacing="0" cellpadding="3" width="100%" style="font-size:10px;line-height:0.9;" >
   <tr  class="border_bottom2">
    <td>
      รหัสรถ <span class="txt-value">{$keyBarcode}</span> <br>
      ทะเบียน <span class="txt-value">{$fdata[0]['carno']}</span> <br>
      ประเภท <span class="txt-value">{$fdata[0]['cartype_id']}-{$fdata[0]['cartype_text']}</span>
    </td>
    <td>
       โซน/ เขต <span class="txt-value">{$zk}</span> <br>
       นักสำรวจ <span class="txt-value">{$s}</span> <br>
       ชื่อ <span class="txt-value2">{$zks_name}</span> <br>
    </td>
   </tr>
</table>

      <table cellspacing="2" cellpadding="3" width="100%"  style=" font-size:16px;line-height:0.8;" >
         <tr >
          <td align="">
{$fcucode}
          </td>
         </tr>
      </table>

     </td>
     <td>
     <table cellspacing="2" cellpadding="" width="100%"   >
        <tr >
         <td >
         <div style="text-align:center;font-weight:bold;font-size:18px;">คำแนะนำ</div>
                 <span>&nbsp;</span> 1. โปรดแสดงบัตรทุกครั้งเมื่อนำอ้อยเข้าแจ้งคิว <br>
                  2. เมื่อทำบัตรหายต้องแจ้งทำบัตรใหม่ที่ฝ่ายไร่ <br>
                  3. ทางโรงงานขอสงวนสิทธิ์ในการเปลี่ยนแปลง <br>  เงื่อนไขหรือยกเลิกให้ใช้บัตรโดยมิต้องแจ้งให้ทราบล่วงหน้า <br>
                 <span>&nbsp;&nbsp;&nbsp;</span><tcpdf method="write1DBarcode" params="{$params}" />
         </td>
        </tr>
     </table>
     </td>
   </tr>
 </table>
EOD;

//$html .= "<tcpdf method=\"write1DBarcode\" params=\"{$params2}\" />";
/*$html2='';
$max = 10;
$page = ceil($max/4);
for ($i=1; $i <= $max; $i++) {
    for ($j=$i; $j<=4; $j++) {
        $html2 .= $html;
    }
    */
    //<br pagebreak="true"/>
    $pdf->writeHTMLCell(0, 0, '', '', $style.$html.'', 0, 1, 0, true, '', true);
  //  $html2 = '';
//}

// กำหนดการแสดงข้อมูลแบบ HTML
// สามารถกำหนดความกว้างความสูงของกรอบข้อความ
// กำหนดตำแหน่งที่จะแสดงเป็นพิกัด x กับ y ซึ่ง x คือแนวนอนนับจากซ้าย ส่วน y คือแนวตั้งนับจากด้านล่าง

ob_end_clean();

$js = "
  print();
";

// Add Javascript code
$pdf->IncludeJS($js);
// กำหนดการชื่อเอกสาร และรูปแบบการแสดงผล
//$pdf->Output('mindphp02.pdf', 'I');




$ret = $pdf->Output('n.pdf', 'S');
$ret = base64_encode($ret);
$ret = 'data:application/pdf;base64,'.$ret;

$data = array();
$data['data'] = $ret;
$data['send'] = $_REQUEST;

echo json_encode($data);

?>
