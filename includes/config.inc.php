<?PHP
global $project_name;
global $thai_day_arr,$thai_month_arr,$thai_month_arr_short;
$thai_day_arr = array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์"); 
$thai_month_arr = array(   
        "0"=>"",   
        "1"=>"มกราคม",   
        "2"=>"กุมภาพันธ์",   
        "3"=>"มีนาคม",   
        "4"=>"เมษายน",   
        "5"=>"พฤษภาคม",   
        "6"=>"มิถุนายน",    
        "7"=>"กรกฎาคม",   
        "8"=>"สิงหาคม",   
        "9"=>"กันยายน",   
        "10"=>"ตุลาคม",   
        "11"=>"พฤศจิกายน",   
        "12"=>"ธันวาคม"  );   
$thai_month_arr_short=array(   
        "0"=>"",   
        "1"=>"ม.ค.",   
        "2"=>"ก.พ.",   
        "3"=>"มี.ค.",   
        "4"=>"เม.ย.",   
        "5"=>"พ.ค.",   
        "6"=>"มิ.ย.",    
        "7"=>"ก.ค.",   
        "8"=>"ส.ค.",   
        "9"=>"ก.ย.",   
        "10"=>"ต.ค.",   
        "11"=>"พ.ย.",   
        "12"=>"ธ.ค.");
class Config
{
    const KSL_NAME = "โรงงานน้ำตาลขอนแก่น";
    const PROJECT_NAME = 'KSL : Q-Cane ';
    const MODULE_ROOT = 'login';
    const SITE_ROOT = 'np';
    const MY_HOST = "localhost";
    const MY_USER = "root";
    const MY_PASS = "kslitc123";//"kslitc@1234";
    const MY_DB = "regcar_db";
    const URL_SERVER_NAME = 'kslfcar';

    /**
    * Dashboard
    */
    const CSS_FONT_AWESOME = '/template/assets/global/plugins/font-awesome/css/font-awesome.min.css';
    const CSS_MORRIS = '/template/assets/global/plugins/morris.js/morris.min.css';
    const CSS_FLAT_PICKER = '/template/assets/global/plugins/flatpickr/dist/flatpickr.min.css';
    const CSS_OWL_CAROUSEL = '/template/assets/global/plugins/owl.carousel/dist/assets/owl.carousel.min.css';
    const CSS_OWL = '/template/assets/global/plugins/owl.carousel/dist/assets/owl.theme.default.min.css';

    const JS_OWL_CAROUSEL = '/template/assets/global/plugins/owl.carousel/dist/owl.carousel.min.js';
    const JS_FLAT_PICKER = '/template/assets/global/plugins/flatpickr/dist/flatpickr.min.js';
    /**
    * Advanced Elements
    */
    const CSS_BOOTSTRAP_SELECT = '/template/assets/global/plugins/bootstrap-select/dist/css/bootstrap-select.min.css';
    const CSS_SELECT_2 = '/template/assets/global/plugins/select2/dist/css/select2.min.css';
    const CSS_BOOTSTRAP_TOUCHSPIN = '/template/assets/global/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css';
    const CSS_JQUERY_TIME_PICKER = '/template/assets/global/plugins/jt.timepicker/jquery.timepicker.css';
    const CSS_BOOTSTRAP_COLOR_PICKER  = '/template/assets/global/plugins/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css';
    //CONST CSS_FLAT_PICKER
    const JS_BOOTSTRAP_SELECT = '/template/assets/global/plugins/bootstrap-select/dist/js/bootstrap-select.min.js';
    const JS_SELECT_2 = '/template/assets/global/plugins/select2/dist/js/select2.min.js';
    const JS_TYPEAHEAD_JQUERY = '/template/assets/global/plugins/typeahead.js/dist/typeahead.jquery.min.js';
    const JS_BLOOD_HOUND = '/template/assets/global/plugins/typeahead.js/dist/bloodhound.min.js';
    const JS_BOOTSTRAP_TOUCHSPIN = '/template/assets/global/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js';
    const JS_JQUERY_TIME_PICKER = '/template/assets/global/plugins/jt.timepicker/jquery.timepicker.min.js';
    const JS_BOOTSTRAP_COLOR_PICKER = '/template/assets/global/plugins/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js';
    //CONST JS_FLAT_PICKER

    /* BOOTSTRAP TABLE */
    const CSS_BOOTSTRAP_TABLE = '/template/assets/global/plugins/bootstrap-table/dist/bootstrap-table.min.css';
    const JS_BOOTSTRAP_TABLE = '/template/assets/global/plugins/bootstrap-table/dist/bootstrap-table.min.js';
    const JS_BOOTSTRAP_TABLE_MOBILE = '/template/assets/global/plugins/bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile.min.js';
    
    /* BOOTBOX ALERT */
    const CSS_CUSTOMBOX = '/template/assets/global/plugins/custombox/dist/custombox.min.css';
    const JS_BOOTBOX = "/template/assets/global/plugins/bootbox.js/bootbox.js";
    const JS_ANCHOR = "/template/assets/global/plugins/anchor-js/anchor.min.js";
    const JS_CUSTOMBOX = '/template/assets/global/plugins/custombox/dist/custombox.min.js';
    
    /*  SWEAT ALERT    */
    const CSS_SWEET_ALERT_2 = '/template/assets/global/plugins/sweetalert2/dist/sweetalert2.min.css';
    const JS_SWEET_ALERT2_2  = "/template/assets/global/plugins/sweetalert2/dist/sweetalert2.min.js";
    
    /* DATATABLE 4  */
    const CSS_DATATABLES_BOOTSTRAP3 = "/assets/datatable/jquery.dataTables.min.css";
    const CSS_DATATABLES_BOOTSTRAP4 = "/template/assets/global/plugins/datatables/media/css/dataTables.bootstrap4.min.css";
    const CSS_RESPONSIVE_BOOTSTRAP4 = "/template/assets/global/plugins/datatables-responsive/css/responsive.bootstrap4.min.css";
    const CSS_SCROLLER_BOOTSTRAP4 = "/template/assets/global/plugins/datatables-scroller/css/scroller.bootstrap4.min.css";
    
    const JS_JQUERY_DATATABLES3 = "/assets/datatable/jquery.dataTables.min.js";
    const JS_JQUERY_DATATABLES = "/template/assets/global/plugins/datatables/media/js/jquery.dataTables.min.js";
    const JS_DATATABLES_BOOTSTRAP4 = "/template/assets/global/plugins/datatables/media/js/dataTables.bootstrap4.min.js";
    const JS_DATATABLES_RESPONSIVE = "/template/assets/global/plugins/datatables-responsive/js/dataTables.responsive.js";
    const JS_RESPONSIVE_BOOTSTRAP4 = "/template/assets/global/plugins/datatables-responsive/js/responsive.bootstrap4.js";
    const JS_DATATABLES_SCROLLER = "/template/assets/global/plugins/datatables-scroller/js/dataTables.scroller.js";
    
    const CSS_BOOTSTRAP_DIALOG = "/assets/bootstrap/css/bootstrap-dialog.min.css";
    const JS_BOOTSTRAP_DIALOG = "/assets/bootstrap/js/bootstrap-dialog.min.js";
    static function init_datatables(){
      return array("CSS"=>array(
                                  self::CSS_DATATABLES_BOOTSTRAP4,
                                  self::CSS_RESPONSIVE_BOOTSTRAP4,
                                  self::CSS_SCROLLER_BOOTSTRAP4,
                                  ),
                                  "JS"=>array(
                                    self::JS_JQUERY_DATATABLES,
                                    self::JS_DATATABLES_BOOTSTRAP4,
                                    self::JS_DATATABLES_RESPONSIVE,
                                    self::JS_DATATABLES_SCROLLER,
                                    )                                  
                                  );
    }
    
    static function init_bootstrap_dialog(){
      return array("CSS"=>array(
          self::CSS_BOOTSTRAP_DIALOG,
          ),
          "JS"=>array(
            self::JS_BOOTSTRAP_DIALOG,
            )                                  
          );
    }
    static function _set_global_var($k,$v){
      global ${$k};
      ${$k}=$v;
    }
    static function _get_global_var($k){
      global ${$k};
      return ${$k};
    }











}