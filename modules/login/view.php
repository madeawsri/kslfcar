
<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from backend.themesadmin.com/backend/admin_top_menu/default/admin_default/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 06 Jun 2018 06:12:26 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="description" content="bootstrap default admin template">
    <meta name="viewport" content="width=device-width">
    <title>Login | {project_name}</title>
	<!-- Favicons -->
    <link rel="shortcut icon" href="{server_path}/template/assets/favicon/favicon.ico" type="image/x-icon" />

    <!-- START GLOBAL CSS -->
    <link rel="stylesheet" href="{server_path}/template/assets/global/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{server_path}/template/assets/icons_fonts/elegant_font/elegant.min.css" />


    <link rel="stylesheet" href="{server_path}/template/assets/pages/global/css/global.css" />
    <!-- END GLOBAL CSS -->

    <!-- START PAGE PLUG-IN CSS -->
    
    <link id="site-color" rel="stylesheet" href="{server_path}/template/assets/layouts/layout-top-menu/css/color/light/color-dodger-blue.min.css"/>
    <!-- END PAGE PLUG-IN CSS -->

    <!-- START TEMPLATE GLOBAL CSS -->
    <link rel="stylesheet" href="{server_path}/template/assets/global/css/components.css" />
    
    <!-- END TEMPLATE GLOBAL CSS -->

    <!-- START LAYOUT CSS -->
    <link rel="stylesheet" href="{server_path}/template/assets/layouts/layout-top-menu/css/layout.min.css" />
    <link rel="stylesheet" href="{server_path}/template/assets/pages/login/login-v2/css/login_v2.css" />
    <!-- END LAYOUT CSS -->
    <link href="{server_path}/assets/fonts/font.css" rel="stylesheet" />
    <link rel="stylesheet" href="{server_path}/template/assets/global/plugins/bootstrap-select/dist/css/bootstrap-select.min.css"/>
    <script>
      var jModuleName = '{module_name}'; 
      var jSiteName = '{site_name}'; 
      var jServerPath = '{server_path}'; 
      var jServerName = '{server_name}';
    </script>
    
</head>
<body>
<!-- START CONTENT -->
<div class="login_v2">
    <div class="login_v2_main">
        <div class="login_v2_contain">
            <div class="login_v2_form text-xs-center">
                <!--<i class="login_v2_profile_icon icon icon_lock_alt"></i>-->
                
                  <img src="{server_path}/assets/images/logoireport.png" 
                  class='img-responsive img-rounded ' 
                  alt="logo"
                  style="max-width: 40%;height: auto;margin-bottom:20px">

                <!--<h5>{project_name}</h5>-->
                <form action="#" id="form-validation" method="post">

                <div class=" hide ">
                  <select class=" j_obj_sites login_v2_text_field  bootstrap-select btn-primary " 
                     style="width: 100%; font-size:15px;  height:50px;
                     color: white; background-color: ;
                     /*font-family: Helvetica Neue,Helvetica,Arial,sans-serif;*/  ">
                     
                      <option value="{site_name}" selected>ฐานข้อมูล-{site_desc}</option>
                      {loop sites}
                      <option value="{site_n}" >ฐานข้อมูล-{site_d}</option>
                      {end loop}
                      
                  </select>
                </div>

                
                    <div class="login_v2_text_field">
                        <input type="text" id='login_name' name='login_name' placeholder=" user name">
                        <i class="icon icon_desktop"></i>
                    </div>
                    <div class="login_v2_text_field">
                        <input type="password" id='pass_name' name='pass_name' placeholder="Password">
                        <i class="icon icon_key"></i>
                    </div>
                    <div class="checkbox-login login_v2_check " style="display: none">
                        <div class="checkbox-squared">
                            <input value="None" id="checkbox-squared1" name="check" type="checkbox">
                            <label for="checkbox-squared1"></label>
                            <span>Remember me</span>
                        </div>
                    </div>
                    <div class="login_v2_forget_text" style="display: none">
                        <a href="forgot_password_v2.html">Forgot password?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block"> เข้าใช้งาน </button>
                    
                </form>
            </div>
            <div class="login_v2_reserved_text text-xs-center bold-fonts">
                <p>Copyright&copy; <?=date("Y")?>, <b>{project_name}</b> All Rights Reserved.</p>
            </div>
        </div>
    </div>
</div>
<!-- END CONTENT -->


<!-- START CORE JAVASCRIPT -->
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/tether/dist/js/tether.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/bootstrap/dist/js/bootstrap.min.js"></script>

<script type="text/javascript" src="{server_path}/template/assets/pages/global/js/global.min.js"></script>
<!-- END CORE JAVASCRIPT -->
<!-- START PAGE PLUGINS -->
<script type="text/javascript"  src="{server_path}/template/assets/global/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<!-- END PAGE PLUGINS -->

<!-- START PAGE PLUGIN JS -->
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/PACE/pace.min.js"></script>
<!-- END PAGE PLUGIN JS -->

<!-- START PAGE JAVASCRIPT -->
<script type="text/javascript" src="{server_path}/template/assets/global/js/global/global_validation.js"></script>
<!-- END PAGE JAVASCRIPT -->
<!-- START INDEX MODULE -->
<script type="text/javascript" src='{server_path}/modules/{module_name}/index.js?v={timex}' ></script>
<!-- END INDEX MODULE -->
</body>

</html>