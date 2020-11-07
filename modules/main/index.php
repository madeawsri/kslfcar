<?php
  $_layout->include_plugin('css', array(
              Config::CSS_FONT_AWESOME,
              Config::CSS_MORRIS,
              Config::CSS_FLAT_PICKER,
              Config::CSS_OWL_CAROUSEL,
              Config::CSS_OWL,
            ));
  $_layout->include_plugin('js', array(
              Config::JS_OWL_CAROUSEL,
              Config::JS_FLAT_PICKER,
            ));

  //$max_year = $_dbmy->getMYSQLValue('irp_year','max(year_code)');
  //$max_year_next = $_fn->next_cane_year($max_year);

  $_layout->_var('site_desc', $GET_SITE_DATA['site_desc'])
          ->_var('page_detail', 'ข้อมูลภาพรวม ปีการผลิต '.$_SESSION['CUR_YEAR'])
          ->_var('max_year', $_SESSION['CUR_YEAR'])
          ->render_page();
