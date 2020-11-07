<ul class="menu-list live-search-list">
    <li>
        <a href="{server_path}/{site_name}">
            <span class="icon_desktop header-icon" aria-hidden="true"></span>Dashboard<span class=" " aria-hidden="true"></span>
        </a>
    </li>



    <li class='{hide_p_user}'><a href="{server_path}/{site_name}/paidscan2.ksl"> <i class="fa fa-shopping-cart header-icon" aria-hidden="true"></i> แจ้งคิวรถชาวไร่/รถร่วม</a></li>
    
    <li class="{hide_p_check}">
        <a href="javascript:void(0)">
            <span class="icon_document header-icon" aria-hidden="true"></span>รายงาน<span class="arrow_carrot-down header-arrow-down" aria-hidden="true"></span>
        </a>
        <ul class="dropNav ">
            <li><a href="{server_path}/{site_name}/rp-paid.ksl">- จ่ายคิว S </a></li>
            <li class='hide'><a href="{server_path}/{site_name}/rp-daily.ksl">- โควต้าที่ได้รับคิว S </a></li>
            <li class='hide'><a href="{server_path}/{site_name}/#">- คิว S ตามทะเบียนรถ</a></li>
        </ul>
    </li>

    <li class="{hide_topheader}">
        <a href="javascript:void(0)">
            <span class="icon_document header-icon" aria-hidden="true"></span>ตั้งค่าแจ้งคิว<span class="arrow_carrot-down header-arrow-down" aria-hidden="true"></span>
        </a>
        <ul class="dropNav ">
            <li><a href="{server_path}/{site_name}/settingcheck.ksl">เงื่อนไขการแจ้งคิว</a></li>
            <li><a href="{server_path}/{site_name}/setting.ksl">ระดับโควต้าในแจ้งคิว</a></li>
            
            <li><a href="{server_path}/{site_name}/db-master.ksl">จำนวนคิวที่ให้แต่ละวัน</a></li>
            <li><a href="{server_path}/{site_name}/db-daily.ksl">จัดการคิวประจำวัน</a></li>
            <li><a href="{server_path}/{site_name}/db-disquata.ksl">โควต้าไม่ตรวจสอบตัน</a></li>
        </ul>
    </li>

    <li class="{hide_topheader}">
        <a href="javascript:void(0)">
            <span class="icon_document header-icon" aria-hidden="true"></span>ตั้งค่าลงทะเบียน<span class="arrow_carrot-down header-arrow-down" aria-hidden="true"></span>
        </a>
        <ul class="dropNav ">
            <li><a href="{server_path}/{site_name}/cartype.ksl">จัดการประเภทรถ</a></li>
            <li><a href="{server_path}/{site_name}/car.ksl">ลงทะเบียนรถ</a></li>
            <li><a href="{server_path}/{site_name}/reg.ksl">พิมพ์บัตรทะเบียนรถ</a></li>
            <li><a href="{server_path}/{site_name}/pay.ksl">จ่ายบัตร</a></li>
            <li><a href="{server_path}/{site_name}/overket.ksl">อนุมัติข้ามเขต</a></li>
        </ul>
    </li>

    <li class="{is_admin}">
        <a href="javascript:void(0)">
            <span class="icon_document header-icon" aria-hidden="true"></span>ผู้ดูแลระบบ<span class="arrow_carrot-down header-arrow-down" aria-hidden="true"></span>
        </a>
        <ul class="dropNav ">
            <li><a href="{server_path}/{site_name}/db-setting.ksl">เชื่อมต่อฐานข้อมูล Softpro</a></li>
            <li><a href="{server_path}/{site_name}/db-users.ksl">ผู้ใช้และสิทธิ์</a></li>            
            <li><a href="{server_path}/{site_name}/db-lanjod.ksl">จัดการลานจอด</a></li>
            
        </ul>
    </li>


</ul>