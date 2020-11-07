<?php
include_once("../../app.php");

if ($_fn->is_ajax()) {
  if (isset($_REQUEST["mode"]) && !empty($_REQUEST["mode"])) { //Checks if action value exists
    $action = trim($_REQUEST['mode']);
    switch($action) { //Switch case for value of action
      case "set-year": 
       $_SESSION['SS_YEAR'] = $_REQUEST['fyear'];
       echo "OK". $_SESSION['SS_YEAR'];
      break;
    }
  }
}
