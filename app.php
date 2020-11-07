<?php
//header('Access-Control-Allow-Origin: *');
session_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");
require_once("includes/config.inc.php");
require_once("includes/fnbase.inc.php");
require_once("includes/mysqli.inc.php");
require_once("includes/sqlsrv.inc.php");
require_once("includes/template.inc.php");
require_once("includes/layout.inc.php");
require_once("includes/tableClass.php");
require_once('libs/table.sqlsrv.php');
require_once('libs/libs.db.php');

global $ARR_STATUS_Q;
$ARR_STATUS_Q = array("แจ้งคิว","ชั่งเข้า","ชั่งออก");


global $ARR_STATUS_Q_TYPE;
$ARR_STATUS_Q_TYPE = array("รถส่วนตัว","รถร่วม",'3'=>"รถศูนย์",'4'=>"รถ Cane4Cash");

global $_dbmy,$_template,$_fn,$SERVER_PATH_HOME,$GET_SITE_NAME,$GET_SITE_DATA;
global $_layout;
$GET_SITE_NAME = (@$_GET['site'])?strtolower(@$_GET['site']):Config::SITE_ROOT;
$FOLDER_PROJECT = 'kslfcar/'; // Folder project
$SERVER_PATH_HOME = "http://".rtrim($_SERVER['SERVER_NAME'], '/')."/".$FOLDER_PROJECT.$GET_SITE_NAME;
Config::_set_global_var('project_name', Config::PROJECT_NAME);
$_dbmy = new DbMySQLi();
$_fn = new FnBase();
// init site name
$_fn->GETVARS();
// init template
$_template = new Template();

$_dblib = new DbLibs();
$_dblib->get_site();
$_layout = new Layout();

/**
*  Get Connection Imap & Softpro
*/
global  $AR_CONFIG_SOFTPRO;
$dbServerConnects = $_dblib->get_server_connects();
$AR_CONFIG_SOFTPRO=$dbServerConnects[0];

$DB_SOFTPRO =  $AR_CONFIG_SOFTPRO['db_name'];  //'TestC3' ;// 'SoftProDataKKS';//"SoftProData{$GET_SITE_DATA['site_code']}";

if (!isset($_SESSION['CUR_YEAR'])) {
    $_SESSION['CUR_YEAR'] = $AR_CONFIG_SOFTPRO['fyear'];//$_dblib->get_year();
}

//$_SESSION['DOWNLOAD_FILE'] = (@$_SESSION['uid']['flag_office_group']==3)?'btn-pdf':'btn-xlsx';
error_reporting(~E_NOTICE);