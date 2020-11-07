
            </section>
            <!-- END RIGHT CONTENT -->
        </div>
    </section>
<!-- END CONTENT -->

    <!-- START FOOTER -->
    <footer id="footer">
       <span class="pull-right">
        Copyright&copy; <?=date("Y")?>, <b>บริษัทน้ำตาลขอนแก่น สาขา {site_desc}</b> All Rights Reserved.
       </span>
       <span class="pull-left">&nbsp;&nbsp;&nbsp;
         ผู้ใช้งานระบบ : <b>{user_name} </b> ตำแหน่ง: <b>{user_position}</b> 
         ปีฤดูหีบ : <b><?=$_SESSION['CUR_YEAR']?></b> ฐานข้อมูล: <b>{DB_SOFTPRO}</b> 
         
         แจ้งคิว: <b class="lbl_kslq text-info">{KSL_Q}</b> 
       </span>
    </footer>
    <!-- END FOOTER -->

</div>

<!-- START CORE JAVASCRIPT -->
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/jquery/dist/jquery.min.js"></script>
<script src='{server_path}/assets/barcode/jquery-barcode.min.js'></script>

<script type="text/javascript" src="{server_path}/template/assets/global/plugins/tether/dist/js/tether.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/switchery/dist/switchery.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/screenfull.js/dist/screenfull.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/classie/classie.js"></script>
<!-- END CORE JAVASCRIPT -->

<!-- START PAGE PLUGIN JS -->
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/PACE/pace.min.js"></script>
<!-- END PAGE PLUGIN JS --

<!-- START PAGE PLUGINS -->
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/raphael/raphael.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/morris.js/morris.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/plugins/moment/min/moment.min.js"></script>
<!--<script type="text/javascript"-->
{loop js_links}<script type="text/javascript" src="{server_path}{js_link}"></script>{end loop}
<!--<script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>-->
<!-- END PAGE PLUGINS -->
<script type="text/javascript" src="{server_path}/libs/jlibs.js?v={timex}"></script>
<!-- START PAGE JAVASCRIPT -->
 <!-- <script type="text/javascript" src="{server_path}/template/assets/app/dashboard.js?v={timex}"></script> -->
 <!-- START INDEX MODULE -->
<script type="text/javascript" src='{server_path}/modules/{module_name}/index.js?v={timex}' ></script>
<!-- END INDEX MODULE -->
<!-- END PAGE JAVASCRIPT -->


<!-- START GLOBAL JAVASCRIPT -->
<script type="text/javascript" src="{server_path}/template/assets/global/js/site.min.js"></script>
<script type="text/javascript" src="{server_path}/template/assets/global/js/site-settings.min.js"></script>
<!-- END GLOBAL JAVASCRIPT -->

<!-- START THEME LAYOUT JAVASCRIPT -->
<script type="text/javascript" src="{server_path}/template/assets/layouts/layout-top-menu/js/layout.min.js"></script>
<!-- END THEME LAYOUT JAVASCRIPT -->



</body>
</html>
