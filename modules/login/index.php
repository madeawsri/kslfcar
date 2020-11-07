<?php
  $_fn->is_login(); 
  $_template->assign('sites',$_dblib->get_ksl_sites());
  $_template->assign('site_desc',$GET_SITE_DATA['site_desc']);
  $_template->parse("./modules/{$GET_MODULE_NAME}/view.php");
?>