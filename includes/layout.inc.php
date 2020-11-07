<?php
  class Layout extends Template
  {
      public function login_layout($menu_lists=array())
      {
          $ss_login = $_SESSION['uid'];
          $menu_lists = array(
                          array("name"=>'ข้อมูลส่วนตัว'),
                         );

          $this->assign('login_menu_items', $menu_lists);
          //$this->assign('position',$ss_login['flag_office_name']);
          //$arr_name['fname']=$ss_login['sl_fname'];
          $arr_name['user_name']=$ss_login['name'];
          $arr_name['user_position']=$ss_login['position'];

          $this->assign_array($arr_name);
          $this->assign(
              'login_layout',
              $this->render("./template/layout/login_layout.php")
     );
      }

      public function topheader_layout()
      {
          global $_fn;
          $this->assign('is_admin', 'hide');
          $this->assign('hide_topheader', 'hide');
          $this->assign('hide_p_check', 'hide');
          $this->assign('hide_p_user', 'hide');

          if ($_fn->isAdmin()) {
              $this->assign('is_admin', '');
              $this->assign('hide_topheader', '');
              $this->assign('hide_p_check', '');
              $this->assign('hide_p_user', '');

          } elseif ($_fn->isCheck()) {
              
              $this->assign('hide_p_check', '');
              $this->assign('hide_topheader', '');
              $this->assign('hide_p_user', '');

          } elseif ($_fn->isUser()) {

              $this->assign('hide_p_user', '');

          }

          $topmenu_header = $this->render("./template/layout/topheader_layout.php");
          $this->assign('topmenu_header', $topmenu_header);
      }

      public function panel_layout($title='', $content='', $option='')
      {
          $this->assign('panel_title', $title)
           ->assign('panel_body', $content)
           ->assign('panel_option', $option)
           ->assign('panel_layout', $this->render("./template/layout/panel_layout.php"));
      }

      public function table_layout($title='', $content='', $data = array())
      {
          $this->assign('table_title', $title)
           ->assign('table_body', $content)
           ->assign('table_layout', $this->render("./template/layout/table_layout.php"));
      }

      public function init_vars($data=array())
      {
          $this->assign_array($data);
          return $this;
      }
      public function _var($key, $val)
      {
          $this->assign($key, $val);
          return $this;
      }

      public function _header()
      {
          global $_fn;
          $_fn->is_not_login();
          $this->parse('./template/header.php');
          $this->login_layout();
          $this->topheader_layout();
          $this->parse('./template/topheader.php');
          $this->parse('./template/topmenu.php');
          return $this;
      }
      public function render_page()
      {
          global $GET_MODULE_NAME;
          $this->_header();
          $this->parse("./modules/{$GET_MODULE_NAME}/view.php");
          $this->_footer();
      }
      public function _footer()
      {
          global $DB_SOFTPRO,$_dblib;
          $this->_var('DB_SOFTPRO', $DB_SOFTPRO);
          
          $data_kslq=$_dblib->a_kslq;
          
          $this->_var('KSL_Q', "{$data_kslq['name']} ({$data_kslq['id']})");



          $this->parse('./template/footer.php');
          return $this;
      }
      public function _content()
      {
          global $GET_MODULE_NAME;
          $this->parse("./modules/{$GET_MODULE_NAME}/view.php");
          return $this;
      }
      public function render_view($v_name, $data=array())
      {
          global $GET_MODULE_NAME;
          $this->assign_array($data);
          return $this->render("./modules/{$GET_MODULE_NAME}/{$v_name}.php");
      }



      /**
      * Tag links HTML
      * @param title $link_title
      * @param href $link_href
      * @param class $link_class
      *
      * @return html tag links
      */
      public function link($link_title, $link_href, $link_class='')
      {
          $data = array();
          $data['link_title'] = $link_title;
          $data['link_href'] = $link_href;
          $data['link_class'] = $link_class;
          $this->assign_array($data);
          return $this->render("./template/layout/link_layout.php");
      }

      public function style($data)
      {
          return "<style>{$data}</style>";
      }
  }
