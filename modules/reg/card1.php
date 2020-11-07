
<?php
include_once("../../app.php");
require('../../libs/tcpdf/tcpdf.php');
require('../../libs/tcpdf/tcpdf_barcodes_1d.php');


$iPages = array();
if ($_REQUEST['dtime']) {
    $dtime = $_REQUEST['dtime'];
    $dtime = explode('|', $dtime);
    $datax['dtime'] = $dtime;
    $dtime[0] = str_replace('-', '/', $dtime[0]);
    $dtime[1] = str_replace('-', '/', $dtime[1]);

    $sql_get_key = "SELECT GROUP_CONCAT(keycode) as id  FROM `tb_reg_key` WHERE  fdate BETWEEN '{$dtime[0]}' and '{$dtime[1]}' ";
    $datax['sql_get_key'] = $sql_get_key;
    $data_key = $_dbmy->getDataAll($sql_get_key);
    if ($data_key) {
        $keys =  str_replace(',', '|', $data_key[0]['keycode']);
    } else {
        exit(0);
    }
    $ppp = $keys;
    $iPages = explode("|", $ppp);
} else {
    if ($_REQUEST['idkey']) {
        $ppp =  $_REQUEST['idkey'];// "1|3|5|2";
        $iPages = explode("|", $ppp);
    }
}

//update status print count
$key_update = implode(",", $iPages);
$_dbmy->select_query("update tb_reg_key set fprint = fprint + 1 where keycode in ({$key_update}) ");

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeaderFont(array('thsarabun', 'B', 12));
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetFont('thsarabun', '', 14);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
//set auto page breaks
//$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

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
$data = array();
$html_page = '';
if ($iPages) {
    foreach ($iPages as $k=>$v) {
        /*
           GET DATA FORM DATABASE
        */
        //$sql = "select lpad(rk.id,5,'0') as code, rc.* from tb_reg_car as rc , tb_reg_key as rk where rk.carno = rc.carno and rk.zks=rc.zks and rk.fyear=rc.fyear and rk.id = {$v} order by zks ";
        //$fdata = $_dbmy->getDataAll($sql);
        $fdata = $_dblib->getRowRegCar(str_replace("'", "", $v));
        $data['fdata'][]  = $fdata;
        // zone-kets
        $zk = explode('/', $fdata[0]['zks']);
        $s = $zk[2];
        $k = sprintf("%02s", $zk[1]);
        $zk = "{$zk[0]}/ {$k}";
        // zks name
        $zks_name = explode('-', $fdata[0]['zks_name']);
        $zks_name = trim($zks_name[1]);
        // key (id)
        $keyBarcode = $fdata[0]['id'];
        $params = $pdf->serializeTCPDFtagParameters(array($keyBarcode, 'C39', '', '', 70, 15, 0.4,
array('position'=>'S', 'border'=>false, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'thsarabun', 'fontsize'=>10, 'stretchtext'=>4), 'N'));
        $fcucode='';
        if ($fdata) {
            foreach ($fdata as $kk=>$vv) {
                $x = substr($vv['fcucode'], -5);
                $o = substr($vv['fcucode'], 2);
                $fcucode .= "{$o}<span style=\"color:gray;\">{$x}</span> &nbsp;&nbsp;&nbsp;&nbsp;";
            }
        }



        $html = <<<EOD
<table class="tb-main">
  <tr>
    <td style="margin-right:10px;">
      <table cellspacing="0" cellpadding="5" width="100%">
        <tr class="border_bottom">
          <td width="30%">
            <img src="../../libs/tcpdf/images/LogoKSL.png" width="60px" height="35px" />
          </td>
          <td width="70%" valign="top" align="center">
            <span class="top-card">บัตรประจำรถบรรทุกปี {$fdata[0]['fyear']} </span><br>
            บริษัท น้ำตาลขอนแก่น จำกัด (มหาชน)
          </td>
        </tr>
      </table>
      <table cellspacing="0" cellpadding="3" width="100%" style="font-size:10px;line-height:0.9;">
        <tr class="border_bottom2">
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
      <table cellspacing="2" cellpadding="3" width="100%" style=" font-size:16px;line-height:0.8;">
        <tr>
          <td align="">
            {$fcucode}
          </td>
        </tr>
      </table>
    </td>
    <td>
      <table cellspacing="2" cellpadding="" width="100%">
        <tr>
          <td>
            <div style="text-align:center;font-weight:bold;font-size:18px;">คำแนะนำ</div>
            <span>&nbsp;</span> 1. โปรดแสดงบัตรทุกครั้งเมื่อนำอ้อยเข้าแจ้งคิว <br>
            2. เมื่อทำบัตรหายต้องแจ้งทำบัตรใหม่ที่ฝ่ายไร่ <br>
            3. ทางโรงงานขอสงวนสิทธิ์ในการเปลี่ยนแปลง <br> เงื่อนไขหรือยกเลิกให้ใช้บัตรโดยมิต้องแจ้งให้ทราบล่วงหน้า <br>
            <span>&nbsp;&nbsp;&nbsp;</span>
            <tcpdf method="write1DBarcode" params="{$params}"></tcpdf>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>0
EOD;
        if (count($iPages) > 4) {
            if (($k+1)%4 == 0) {
                $html .= '<br pagebreak="true"/>';
            }
        }

        $pdf->writeHTMLCell(0, 0, '', '', $style.$html, 0, 1, 0, true, '', true);
    }
}
ob_end_clean();






$js = "
  print();
";
// Add Javascript code
$pdf->IncludeJS($js);





$ret = $pdf->Output('n.pdf', 'S');
$ret = base64_encode($ret);



$data['data'] = $ret;
$data['send'] = $_REQUEST;
$data['has_data'] = count($iPages);


echo json_encode($data);


?>
