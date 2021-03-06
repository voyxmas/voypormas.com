
<!DOCTYPE html>
<!-- 
Template Name: Metronic - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 4.5.6
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <?php $this->layouts->print_tags() ?>
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/open-sans.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/font-awesome.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/blue.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/main.css" rel="stylesheet" type="text/css" id="style_color" />
        <!-- END THEME LAYOUT STYLES -->
        <?php echo $this->layouts->print_includes('head'); ?>
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid admin">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    
                        <img src="<?php echo base_url().APP_ASSETS_FOLDER?>/global/imgs/logos/logo-full.png" alt="ADMIN" class="logo-admin" /> </a>

                        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN PAGE TOP -->
                <div class="page-top">
                    
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">
                            <!-- BEGIN NOTIFICATION DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="icon-bell"></i>
                                    <span class="badge badge-default"> <?php echo $notifications_count ?> </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="external">
                                        <h3>
                                            <span class="bold"><?php echo $notifications_count ?></span> notificaciones</h3>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" style="height: 250px; overflow:auto;" data-handle-color="#637283">
                                        <?php foreach ($notifications AS $notificationsItem ) : ?>
                                            <li>
                                                <a href="<?php echo base_url().'admin/eventos/ver/'.$notificationsItem['evento_id'] ?>">
                                                    <span style="font-size:11px; color:#CCC; display:block;"><?php echo timespan(strtotime($notificationsItem['creado']), time(),1) . ' ago'; ?></span>
                                                    <span class="details">
                                                        <?php echo($notificationsItem['nombre']) ?>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endforeach ?>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- END NOTIFICATION DROPDOWN -->
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <!--<img alt="" class="img-circle" src="../assets/layouts/layout2/img/avatar3_small.jpg" />-->
                                    <span class="username username-hide-on-mobile"> <?php echo loggedin_username() ?> </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li>
                                        <a class="ajax_call" href="<?php echo base_url() ?>ajax/login_ajax/logout"> <i class="icon-key"></i> Log Out </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                            <!-- BEGIN QUICK SIDEBAR TOGGLER -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <!-- END QUICK SIDEBAR TOGGLER -->
                        </ul>
                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END PAGE TOP -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- END SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <?php $this->load->view('layout/blocks/menues/main_lateral') ?>
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
            </div>
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    
                    <?php echo $content ?>

                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
            <!-- BEGIN QUICK SIDEBAR -->
            <!-- END QUICK SIDEBAR -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner">
                
            </div>
            <div class="scroll-to-top">
                <i class="icon-arrow-up"></i>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
<script src="../assets/global/plugins/respond.min.js"></script>
<script src="../assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <?php echo $this->layouts->print_includes('foot'); ?>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/librerias/AjaxForms/ajaxforms.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <!-- END THEME LAYOUT SCRIPTS -->
        <?php if (isset($debug) AND ENVIRONMENT === 'development') : ?>
        <pre><?php print_r($debug) ?></pre>
        <?php endif ?>
    </body>

</html>