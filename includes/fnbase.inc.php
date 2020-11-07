<?php
  class FnBase
  {
      public function str_to_time($str, $comma="/")
      {
          if (strstr($str, $comma)) {
              return $str;
          } else {
              $a1 = substr_replace($str, $comma, 4, 0);
              $a1 = substr_replace($a1, $comma, 7, 0);
              return strtotime($a1);
          }
      }

      /*********** new project - Q-KSL */
      public function html_select_option($data)
      {
          /*
          array2d = id,text
          */
          $p = "<option value='%s'> %s </option>";
          $html = '';
          if ($data) {
              foreach ($data as $k=>$v) {
                  $html .= sprintf($p, $v['id'], $v['text']);
              }
          }
          return $html;
      }
      /*** คิวถัดไป Softpro */
      public function GET_NEXT_Q_SP($i="00000")
      {
          global $_fn;
          $i = sprintf('%04s', $i);
          $p = substr($i, 0, 1);
          $i_next = substr($i, 1, 5)*1 + 1;
          $prefixs = array(
      '9' => 'A'    ,'A' => 'B'    ,'B' => 'C'    ,'C' => 'D'    ,'D' => 'E'    ,'E' => 'F'    ,'F' => 'G'    ,'G' => 'H'
      ,'H' => 'I'    ,'I' => 'J'    ,'J' => 'K'    ,'K' => 'L'    ,'L' => 'M'    ,'M' => 'N'    ,'N' => 'O'    ,'O' => 'P'
      ,'P' => 'Q'    ,'Q' => 'R'    ,'R' => 'S'    ,'S' => 'T'    ,'T' => 'U'    ,'U' => 'V'    ,'V' => 'W'    ,'W' => 'X'
      ,'X' => 'Y'    ,'Y' => 'Z'
      ,'0'=>'1','1'=>'2','2'=>'3','3'=>'4'
      ,'4'=>'5','5'=>'6','6'=>'7','7'=>'8','8'=>'9'
    );
          if ($i_next > 9999) {
              $i = $prefixs[$p].'0001';
          } else {
              $i = $p.sprintf('%04s', $i_next);
          }
          return $i;
      }
      /** วันที่กะ */
      public function GET_DATE_KA($today=1, $ret_date=1)
      {
          if ($today) {
              $today = time();
          }
          $y = strtotime(date("Y-m-d 15:00:00", strtotime("-1 days")));
          $d = strtotime(date("Y-m-d 15:01:00", time()));
          $date_ka = strtotime("+1 days");
          if ($today > $y && $today < $d) {
              $date_ka = time();
          }
          if ($ret_date) {
              return date("Y-m-d", $date_ka);
          } else {
              return $date_ka;
          }
      }
      /** วันที่กะ เพิ่ม */
      public function GET_DATE_NEXT_KA($n, $ret_date=1)
      {
          if (!$ret_date) {
              return strtotime("+{$n} days {$this->GET_DATE_KA()}");
          } else {
              return date("Y-m-d", strtotime("+{$n} days {$this->GET_DATE_KA()}"));
          }
      }
      /** IP Address Client */
      public function get_client_ip()
      {
          return getenv('HTTP_CLIENT_IP')?:getenv('HTTP_X_FORWARDED_FOR')?:getenv('HTTP_X_FORWARDED')?:getenv('HTTP_FORWARDED_FOR')?:getenv('HTTP_FORWARDED')?:getenv('REMOTE_ADDR');
      }

      const _LEVEL_USERS = 1;
      const _LEVEL_CHECK = 2;
      const _LEVEL_ADMIN = 3;

      public function isUser()
      {
          return self::_LEVEL_USERS == $_SESSION['uid']['user_level'];
      }
      public function isCheck()
      {
          return self::_LEVEL_CHECK == $_SESSION['uid']['user_level'];
      }
      public function isAdmin()
      {
          return self::_LEVEL_ADMIN == $_SESSION['uid']['user_level'];
      }
      public function ssLoginName()
      {
          return $_SESSION['uid']['user_login'];
      }

      /********* *********************** */
      /********* *********************** */
      /********* *********************** */
      public function db_to_array($datas, $field = 'id')
      {
          $arr = array();
          foreach ($datas as $k=>$v) {
              $arr[] = $v[$field];
          }
          return $this->array_to_sql_in($arr);
      }

      public function get_site_name()
      {
          global $GET_SITE_NAME;
          return $GET_SITE_NAME;
      }

      public function thai_date_and_time($time)
      {   // 19 ธันวาคม 2556 เวลา 10:10:43
          global $thai_day_arr,$thai_month_arr;
          $thai_date_return = date("j", $time);
          $thai_date_return.=" ".$thai_month_arr[date("n", $time)];
          $thai_date_return.= " ".(date("Y", $time)+543);
          $thai_date_return.= " เวลา ".date("H:i:s", $time);
          return $thai_date_return;
      }
      public function thai_date_and_time_short($time)
      {   // 19  ธ.ค. 2556 10:10:4
          global $thai_day_arr,$thai_month_arr_short;
          $thai_date_return = date("j", $time);
          $thai_date_return.="&nbsp;&nbsp;".$thai_month_arr_short[date("n", $time)];
          $thai_date_return.= " ".(date("Y", $time)+543);
          $thai_date_return.= " ".date("H:i:s", $time);
          return $thai_date_return;
      }
      public function thai_date_short($time)
      {   // 19  ธ.ค. 2556
          global $thai_day_arr,$thai_month_arr_short;
          $thai_date_return = date("j", $time);
          $thai_date_return.="&nbsp;&nbsp;".$thai_month_arr_short[date("n", $time)];
          $thai_date_return.= " ".(date("Y", $time)+543);
          return $thai_date_return;
      }
      public function thai_date_fullmonth($time)
      {   // 19 ธันวาคม 2556
          global $thai_day_arr,$thai_month_arr;
          $thai_date_return = date("j", $time);
          $thai_date_return.=" ".$thai_month_arr[date("n", $time)];
          $thai_date_return.= " ".(date("Y", $time)+543);
          return $thai_date_return;
      }
      public function thai_date_short_number($time)
      {   // 19-12-56
          global $thai_day_arr,$thai_month_arr;
          $thai_date_return = date("d", $time);
          $thai_date_return.="-".date("m", $time);
          $thai_date_return.= "-".substr((date("Y", $time)+543), -2);
          return $thai_date_return;
      }

      /**
      <?=time()?><br />
      <?=thai_date_and_time(time())?><br />
      <?=thai_date_and_time_short(time())?><br />
      <?=thai_date_short(time())?><br />
      <?=thai_date_fullmonth(time())?><br />
      <?=thai_date_short_number(time())?><br />
      **/
      public function array_to_sql_in($arr)
      {
          return "'".implode("','", $arr)."'";
      }

      public function next_cane_year($fyear)
      {
          //return $fyear[2].$fyear[3].(($fyear[2].$fyear[3])+1);
      }

      public function format_number($n, $f=2)
      {
          return number_format($n, $f);
      }

      public function redirect($path)
      {
          echo "<script>window.location='{$path}';</script>";
      }

      public function is_login()
      {
          global $SERVER_PATH_HOME;
          if (isset($_SESSION['uid'])) {
              //echo " LOGIN OK. ".$SERVER_PATH_HOME."/main.ksl";
              $this->redirect($SERVER_PATH_HOME."/paidscan2.ksl");
          } else {
              //echo " LOGIN NOT OK.";
          }
      }

      public function is_not_login()
      {
          global $SERVER_PATH_HOME;

          if (!isset($_SESSION['uid'])) {
              $this->redirect($SERVER_PATH_HOME."/login.ksl");
          }
          /*
          if(isset($_SESSION['uid']['site']) && $_SESSION['uid']['site'] !== @$_GET['site']){
            session_destroy();
            $this->redirect($SERVER_PATH_HOME);
          }*/
      }

      public function GETVARS()
      {
          global $GET_SITE_NAME,$GET_OPTION_NAME,$GET_VIEW_NAME;
          $GET_OPTION_NAME = strtolower(@$_GET['option']);
          $GET_VIEW_NAME = strtolower(@$_GET['view']);
          $GET_SITE_NAME = (@$_GET['site'])?strtolower(@$_GET['site']):Config::SITE_ROOT;
          return $GET_SITE_NAME;
      }

      /**
      * check module_exists
      * - defualt modules 'main'
      *
      * @mname  - modoule name
      *
      * @return string module path
      */

      public function GETMODULENAME()
      {
          if (trim(strtolower(@$_GET['option']))) {
              return trim(strtolower(@$_GET['option']));
          } else {
              return Config::MODULE_ROOT;
          }
      }

      public function GETMODULEPATH()
      {
          global $GET_MODULE_PATH,$GET_MODULE_FILE,$GET_OPTION_NAME,$GET_SITE_NAME,$GET_MODULE_NAME;
          $GET_MODULE_NAME = $GET_OPTION_NAME;
          $GET_MODULE_PATH = './modules/'.$GET_MODULE_NAME.'/index.php';
          if (trim($GET_MODULE_NAME)) {
              if (!file_exists($GET_MODULE_PATH)) {
                  $GET_MODULE_NAME = Config::MODULE_ROOT;
                  $GET_MODULE_PATH = './modules/'.$GET_MODULE_NAME.'/index.php';
              }
          } else {
              $GET_MODULE_NAME = Config::MODULE_ROOT;
              $GET_MODULE_PATH =  './modules/'.$GET_MODULE_NAME.'/index.php';
          }

          //echo $GET_SITE_NAME.'--'.$GET_MODULE_NAME." -- ".$GET_MODULE_PATH;
          return $GET_MODULE_PATH;
      }


      public function vvArray($data)
      {
          echo "<pre>";
          print_r($data);
          echo "</pre>";
      }
      public function ConvertUTF8($value)
      {
          return iconv(mb_detect_encoding($value, mb_detect_order(), true), "UTF-8", $value); // แก้ปัญหา ข้อมูลประเภทอ้อย ชื่อไทย
       //iconv('tis-620','utf-8',$value);
      }
      public function ConvertUTF8_Old($value)
      {
          $x = @iconv('tis-620', 'utf-8', $value);
          if (!$x) {
              $x = $this->ConvertUTF8($value);
          }
          return $x;
      }
      public function ConvertTIS620($value)
      {
          return iconv('utf-8', 'tis-620', $value);
      }

      public function MSSQLEncodeTH($ar)
      { // for 1D
          $rows = array();
          foreach ($ar as $key => $value) {
              # code...
              $rows[$key] = $this->ConvertUTF8($value);
          }
          return $rows;
      }

      public function MSSQLEncodeTH2D($arr)
      {  // for 2D
          $rows = array();
          if ($arr) {
              foreach ($arr as $row) {
                  $rows[] = $this->MSSQLEncodeTH($row);//array_map('utf8_encode', $row);
              }
          }
          return $rows;
      }

      public function MSSQLEncodeTH2D2($arr)
      {  // for 2D
          $rows = array();
          if ($arr) {
              foreach ($arr as $row) {
                  $rows[] = $this->MSSQLEncodeTH2($row);//array_map('utf8_encode', $row);
              }
          }
          return $rows;
      }

      public function MSSQLEncodeTH2($ar)
      { // for 1D
          $rows = array();
          foreach ($ar as $key => $value) {
              # code...
              $rows[$key] =  $this->ConvertUTF8($this->ConvertUTF8_old($value));
          }
          return $rows;
      }


      public static function MacAddress()
      {
          $mac = substr(exec('getmac'), 0, 17);
          return $mac;
      }


      public function UniqueMachineID($salt = "")
      {
          if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
              $temp = sys_get_temp_dir().DIRECTORY_SEPARATOR."diskpartscript.txt";
              if (!file_exists($temp) && !is_file($temp)) {
                  file_put_contents($temp, "select disk 0\ndetail disk");
              }
              $output = shell_exec("diskpart /s ".$temp);
              $lines = explode("\n", $output);
              $result = array_filter($lines, function ($line) {
                  return stripos($line, "ID:")!==false;
              });
              if (count($result)>0) {
                  $result = array_shift(array_values($result));
                  $result = explode(":", $result);
                  $result = trim(end($result));
              } else {
                  $result = $output;
              }
          } else {
              $result = shell_exec("blkid -o value -s UUID");
              if (stripos($result, "blkid")!==false) {
                  $result = $_SERVER['HTTP_HOST'];
              }
          }
          return md5($salt.md5($result));
      }

      public function is_blank($value)
      {
          return empty($value) && !is_numeric($value);
      }

      //Function to check if the request is an AJAX request
      public function is_ajax()
      {
          return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
      }
  }
