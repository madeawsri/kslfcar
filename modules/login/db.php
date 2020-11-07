<?php
include_once("../../app.php");

$user =  strtolower(trim($_REQUEST['login_name']));
$pass = $_REQUEST['pass_name'];

switch(trim($_REQUEST['mode'])){
  case "login":
      $table='sque_user';
      $pass = md5(trim($pass));
      $data_login = $_dbmy->getMYSQLValues('sque_user',"*"," concat(user_login,user_password) = '{$user}{$pass}'   ");
      
      if($data_login){
        
        $data_login['name'] = $data_login['user_name'];
        $data_login['position'] = $_dbmy->getMYSQLValue('sque_levels','level_name', " level_id = '{$data_login['user_level']}' " );
        $_REQUEST['results'] = $data_login;
        $_dbmy->update_db('sque_user', array('last_login'=>time())," concat(user_login,user_password) = '{$user}{$pass}'   ");
        $_REQUEST['count'] = count($data_login);
      
      }else{

        $_REQUEST['results'] = null;

      }

      $_SESSION['uid'] = $_REQUEST['results'];
  
  break;
 
}

echo json_encode($_REQUEST);
