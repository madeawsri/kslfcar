<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="bootstrap default admin template">
    <meta name="viewport" content="width=device-width">
    <title>{page_detail} | {project_name}</title>
    <!-- Favicons -->
    <link rel="shortcut icon" href="{server_path}/template/assets/favicon/favicon.ico" type="image/x-icon" />

    <!-- START GLOBAL CSS -->
    <link rel="stylesheet" href="{server_path}/template/assets/global/plugins/bootstrap/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="{server_path}/template/assets/icons_fonts/elegant_font/elegant.min.css"/>
    <link id="site-color" rel="stylesheet" href="{server_path}/template/assets/layouts/layout-top-menu/css/color/light/color-dodger-blue.min.css"/>
    <link rel="stylesheet" href="{server_path}/template/assets/global/plugins/switchery/dist/switchery.min.css"/>
    <link rel="stylesheet" href="{server_path}/template/assets/global/plugins/perfect-scrollbar/css/perfect-scrollbar.min.css"/>
    <!-- END GLOBAL CSS -->

     <!-- START PAGE PLUG-IN CSS -->
    <link rel="stylesheet" href="{server_path}/template/assets/global/plugins/PACE/themes/black/pace-theme-loading-bar.css"/>
    {loop css_links}<link rel="stylesheet" href="{server_path}{css_link}?v={timex}"/>{end loop}

    <!--<link  rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link  rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css"/>-->

    <!-- END PAGE PLUG-IN CSS -->

    <!-- START TEMPLATE GLOBAL CSS -->
    <link rel="stylesheet" href="{server_path}/template/assets/global/css/components.min.css"/>
    <!-- END TEMPLATE GLOBAL CSS -->

    <!-- START LAYOUT CSS -->
    <link rel="stylesheet" href="{server_path}/template/assets/layouts/layout-top-menu/css/layout.min.css"/>
    <!-- END LAYOUT CSS -->

    <link rel="icon" href="{server_path}/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" type="image/x-icon" href="{server_path}/favicon.ico" />
    <!-- Generated: 2018-04-16 09:29:05 +0200 -->
    <title>{project_name}</title>
    <link rel="stylesheet" href="{server_path}/app.css?v={timex}">
    <link href="{server_path}/assets/fonts/font.css" rel="stylesheet" />

     <script>
      var jModuleName = '{module_name}';
      var jSiteName = '{site_name}';
      var jServerPath = '{server_path}';
    </script>

</head>
<body>
<div class="loader-overlay">
    <div class="loader-preview-area">
        <div class="spinners">
            <div class="loader">
                <div class="rotating-plane"></div>
            </div>
        </div>
    </div>
</div>
<div class="wrapper">
