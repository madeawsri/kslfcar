

<?php
include_once("../../app.php");
require('../../libs/tcpdf/tcpdf.php');
require('../../libs/tcpdf/tcpdf_barcodes_1d.php');
$datax = array();

$dtime = $_REQUEST['dtime'];
$dtime = explode('|', $dtime);
$datax['dtime'] = $dtime;
$dtime[0] = str_replace('-', '/', $dtime[0]);
$dtime[1] = str_replace('-', '/', $dtime[1]);

$sql_get_key = "SELECT GROUP_CONCAT(id) as id  FROM `tb_reg_key` WHERE  fdate BETWEEN '{$dtime[0]}' and '{$dtime[1]}' ";
$datax['sql_get_key'] = $sql_get_key;
$data_key = $_dbmy->getDataAll($sql_get_key);
if ($data_key) {
    $keys =  str_replace(',', '|', $data_key[0]['id']);
} else {
    exit(0);
}
$ppp = $keys;
$iPages = explode("|", $ppp);

//update status print count
$key_update = implode(",", $iPages);
$_dbmy->select_query("update tb_reg_key set fprint = fprint + 1 where id in ({$key_update}) ");

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default header data
$pdf->SetHeaderData('../../images/LogoKSL.png', PDF_HEADER_LOGO_WIDTH, 'บริษัท น้ำตาลขอนแก่น จำกัด (มหาชน)', "รายงานบัตรประจำรถบรรทุกปี 6263 ประจำวันที่ {$dtime[0]} ถึง {$dtime[1]}");
// set default header data
$pdf->setHeaderFont(array('thsarabun', 'B', 16));
$pdf->setFooterFont(array('thsarabun', 'B', 10));
$pdf->SetFont('thsarabun', '', 14);
// set margins
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP-2, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setPrintHeader(1);
$pdf->setPrintFooter(1);

$style = "
<style>
  .theader {
     text-align: left;
     background-color: #4CAF50;
     color: white;
     font-weight:bold;
  }

</style>
";

$html_page = '';

$tHeader='<table style="border: 1px solid #ddd;" cellpadding="3" cellspacing="1" >
<thead>
  <tr class="theader">
    <th width="8%">รหัสบัตร</th>
    <th width="15%">ทะเบียนรถ</th>
    <th width="18%">ประเภทรถ</th>
    <th width="51%">โควต้าที่บรรทุก</th>
    <th width="10%">แจ้งส่งอ้อย</th>
  </tr>
  </thead>
  <tbody>
';
$tFooter= "
</tbody>
</table>
";



$html_page = $tHeader;
$data =  $_dblib->ReportRegCar($dtime);
$datax['has_data'] = count($data);

//--- group zks to new data
$data_pages = array();
$data_zks = array();
if ($data) {
    foreach ($data as $k=>$v) {
        $data_pages[$v['zks']][] = $v;
        $data_zks[$v['zks']]=$v['zks_name'];
    }
}

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$i=1;
if ($data_pages) {
    foreach ($data_zks as $k=>$v) {
        $body='';
        // Start First Page Group
        $pdf->startPageGroup();
        $pdf->AddPage();

        $zk  = substr($k, 0, -4);
        $zk = trim($zk);
        foreach ($data_pages[$k] as $kk=>$vv) {
            $bg =  (($kk+1)%2==0) ? "background-color: #f2f2f2;":"";
            $body .= '
            <tr style="'.$bg.'" >
              <td align="center" width="8%">'.$vv['id'].'</td>
              <td width="15%">'.$vv['carno'].'</td>
              <td width="18%">'.$vv['cartype_text'].'</td>
              <td width="51%">'.$vv['fcucode'].'</td>
              <td width="10%">'.$vv['fsend'].'</td>
            </tr>';
        }
        $html = <<<EOD
<table  cellpadding="3" cellspacing="1" >
<thead>
<tr><td calspan="5"><b style="font-size:18px;">โซน/เขต/สาย : {$zk}{$v} </b></td></tr>
<tr class="theader">
  <th width="8%">รหัสบัตร</th>
  <th width="15%">ทะเบียนรถ</th>
  <th width="18%">ประเภทรถ</th>
  <th width="51%">โควต้าที่บรรทุก</th>
  <th width="10%">แจ้งส่งอ้อย</th>
</tr>
</thead>
<tbody>{$body}</tbody>
</table>
EOD;


        $pdf->writeHTMLCell(0, 0, '', '', $style.$html, 0, 1, 0, true, '', true);



        $i++;
        ob_end_clean();
    }
}


$js = "
  print();
";
// Add Javascript code
$pdf->IncludeJS($js);

$ret = $pdf->Output('n.pdf', 'S');
$ret = base64_encode($ret);
$ret = $ret;


$datax['data'] = $ret;
$datax['send'] = $_REQUEST;

echo json_encode($datax);


?>
