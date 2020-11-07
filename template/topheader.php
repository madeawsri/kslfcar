    <!-- START HEADER -->
    <header id="header">
    <div class="header-width">
        <div class="col-xl-9">
            <div class="logo float-xs-left">
                <img src="{server_path}/template/assets/global/image/web-logo.png" alt="logo">
                <!-- <a href="{server_path}/{site_name}"><h3 style="color: #990000;">{project_name}</h3></a> -->
            </div>

            <div class="menucontainer">
                <div class="overlapblackbg"></div>
                <a id="navtoggle" class="animated-arrow"><span></span></a>
                <nav id="nav" class="topmenu" role="navigation">
                    {topmenu_header}
                </nav>
               
            </div>

        </div>



        <div class="col-xl-3 header-right">
            <div class="header-inner-right">
                
            <div class="float-default chat">
            
<!--                    <div class="right-icon">
                        
                        <a href="javascript:void(0)"  data-toggle="dropdown" data-open="true" data-animation="slideOutUp" aria-expanded="true">
                            <i class="bb ss_year" style="font-size:12px" ><?=$_SESSION['SS_YEAR']?></i>
                        </a>
                        <ul class="list-year dropdown-menu userChat ps-container ps-theme-default" 
                        data-plugin="custom-scroll" data-height="200" style="height: 312px;">
                            <?
                              $iyear = $_SESSION['CUR_YEAR'];
                              for($i=$iyear; $i>$iyear-(101*3) ; $i=$i-101){
                            ?>
                            <li>
                                <a href="javascript:void(1)" onclick=" window.location=window.location " class="ac-year" data-toggle="ss-year" ss-year='<?=$i?>' >
                                    <div class="media">
                                        <div class="media-body">
                                            <h5>&raquo; ปีฤดูหีบ <?=$i?></h5>
                                            <div class="status "></div>
                                        </div>
                                    </div>
                                </a>
                            </li> 
                            <? } ?>
                            
                        <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps-scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; right: 0px;"><div class="ps-scrollbar-y" tabindex="0" style="top: 0px; height: 0px;"></div></div></ul>
                    </div>
                </div>

-->
                <div class="float-default chat">
                    <div class="right-icon">
                        <a href="#" data-plugin="fullscreen">
                            <i class="arrow_expand"></i>
                        </a>
                    </div>
                </div>
                <!-- START USER INFO -->
                {login_layout}
                <!-- END USER INFO -->
            </div>
        </div>
    </div>
</header>
    <!-- END HEADER -->
    
<!-- START CONTENT -->
<section id="main" class="container-fluid">
        <div class="row">
            <!-- START RIGHT CONTENT -->
            <section id="content-wrapper">
               
                
