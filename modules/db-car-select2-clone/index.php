<?php
  $init_datatables = Config::init_datatables();
  $_layout->include_plugin('css',array_merge( array(
              Config::CSS_FONT_AWESOME,
              Config::CSS_BOOTSTRAP_SELECT,
              Config::CSS_SELECT_2,
              Config::CSS_BOOTSTRAP_TOUCHSPIN,
              Config::CSS_JQUERY_TIME_PICKER,
              Config::CSS_FLAT_PICKER,
              Config::CSS_BOOTSTRAP_TABLE,
              Config::CSS_SWEET_ALERT_2,
            ),$init_datatables['CSS']));
  $_layout->include_plugin('js',array_merge( array(
              Config::JS_BOOTSTRAP_SELECT,
              Config::JS_SELECT_2,
              Config::JS_TYPEAHEAD_JQUERY,
              Config::JS_BLOOD_HOUND,
              Config::JS_JQUERY_TIME_PICKER,
              Config::JS_FLAT_PICKER,
              Config::JS_BOOTSTRAP_TABLE,
              Config::JS_BOOTSTRAP_TABLE_MOBILE,
              Config::JS_SWEET_ALERT2_2,
            ),$init_datatables['JS']));
  
  

  $_layout->_var('site_desc',$GET_SITE_DATA['site_desc'])
          ->_var('page_detail','ลงทะเบียนรถชาวไร่')
          ->_var('zoneketsai',$_fn->html_select_option($_dblib->dbZKS($_SESSION['CUR_YEAR'])))
          ->_var('cane_type',$_fn->html_select_option($_dblib->dbRegCarType()))
          ->_var('truck_type',$_fn->html_select_option($_dblib->dbRegCarType()))
          ->render_page();

?>