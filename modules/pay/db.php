<?php
include_once("../../app.php");

if ($_fn->is_ajax()) {
    if (isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"])) { //Checks if action value exists
        $action = trim($_REQUEST['mode']);
        switch ($action) { //Switch case for value of action
      case "datatable":
        show();
      break;
      case "add-users":
        add_cartype();
      break;
      case "edit-users":
        edit_cartype();
      break;
      case "del-users":
        del_cartype();
      break;
      case "getrow":
        getRow($_REQUEST['codecar']);
      break;
      case "uppay":
      $_REQUEST['key_carno'] = explode('|', $_REQUEST['key_carno']);
        $key_update = "'".implode("','", $_REQUEST['key_carno'])."'";
        $_REQUEST['uppay'] = $_dbmy->select_query("update tb_reg_key set fpay = fpay + 1 , fpay_time = concat(fpay_time,'|".time()."')  where concat(ket,'-',keycode) in ({$key_update}) and fyear={$_dblib->m_fyear}");
        echo json_encode($_REQUEST);
      break;

}
    }
}

function getRow($codecar)
{
    global $_dbmy,$_dblib;

    $dataRow = $_dblib->getRowRegCar($codecar);
    echo json_encode($dataRow);
}

function del_cartype()
{
    global $_dbmy,$_fn;
    $params = $_REQUEST;
    $tbname = "tb_car_type";
    $_dbmy->del($tbname, " cartype_id = '{$params['id']}' ");
    echo json_encode($params);
}

function add_cartype()
{
    global $_dbmy,$_fn;
    $params = $_REQUEST;
    $tbname = "tb_car_type";
    // check
    $params['error'] = 0;
    $params['errfield'] = '';
    $check_fields = array('car_type','car_type_max','fyear');
    foreach ($check_fields as $v) {
        if ($_fn->is_blank($params[$v])) {
            $params['error'] = 1;
            $params['errfield'] = $v;
        }
    }
    if (!$params['error']) {
        $data = array(
      "car_type"=> $params['car_type']
      ,"car_type_max"=> $params['car_type_max']
      ,"fyear"=> $params['fyear']
      );
        $_dbmy->add_db($tbname, $data);
    }
    echo json_encode($params);
}

function edit_cartype()
{
    global $_dbmy,$_fn;
    $params = $_REQUEST;
    $tbname = "tb_car_type";
    // check
    $params['error'] = 0;
    $params['errfield'] = '';
    $check_fields = array('car_type','car_type_max');
    foreach ($check_fields as $v) {
        if ($_fn->is_blank($params[$v])) {
            $params['error'] = 1;
            $params['errfield'] = $v;
        }
    }

    if (!$params['error']) {
        $data = array(
      "cartype_id"=> $params['car_type']
      ,"cartype_value"=> $params['car_type_max']
    );

        $_dbmy->update_db($tbname, $data, " cartype_id = '{$params['id']}' ");

        $params['chk'] = $chk_user_login;
        $params['data'] = $data;
    }
    echo json_encode($params);
}

function show()
{
    global $_dblib,$_fn,$_dbmy;
    $arr = array();
    $new_data = array();
    $data =  $_dblib->ReportRegCar();
    // init datatable
    if ($data && is_array($data)) {
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
            $x[] = $v['fpay'];

            $new_data[] = $x;
            $db_data[$v['cartype_id']] = $x;
        }
    }
    $arr['data'] = $new_data;
    $arr['dbdata'] = $db_data;
    echo json_encode($arr);
}
