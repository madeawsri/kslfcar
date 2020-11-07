 <div class="user-dropdown">
    <div class="btn-group">
        <a href="#" class="user-header dropdown-toggle" data-toggle="dropdown"
           data-animation="slideOutUp" aria-haspopup="true"
           aria-expanded="false">
            <img src="{server_path}/template/assets/global/image/user.jpg" alt="Profile image"/>
        </a>
        <div class="dropdown-menu drop-profile">
            <div class="userProfile">
                <img src="{server_path}/template/assets/global/image/user.jpg" alt="Profile image"/>
                <h6>{user_name}</h6>
                <p>{user_position}</p>
            </div>
            <div class="dropdown-divider"></div>
            <a class="btn left-spacing link-btn hide" href="#" role="button">Link</a>
            <a class="btn left-second-spacing link-btn hide" href="#" role="button">Link 2</a>
            <a class="btn btn-primary float-xs-right right-spacing logout" href="{server_path}/{site_name}/logout.ksl"
               role="button">ออกจากระบบ</a>
        </div>
    </div>
</div>
