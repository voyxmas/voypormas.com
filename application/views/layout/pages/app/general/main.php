<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title><?php echo !empty($layout_title) ? $layout_title : NULL;  ?></title>
<meta name="description" content="<?php echo !empty($layout_description) ? $layout_description : NULL;  ?>" />

<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/bootstrap.min.js" type="text/javascript"></script>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/open-sans.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
<!-- END THEME GLOBAL STYLES -->
<!-- BEGIN THEME LAYOUT STYLES -->
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/toastr/css/toastr.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/main.css" rel="stylesheet" type="text/css" />
<!-- END THEME LAYOUT STYLES -->

<!-- BEGIN PAGE LEVEL INCLUDES -->
<?php echo $this->layouts->print_includes('head'); ?>
<!-- END PAGE LEVEL INCLUDES -->
<meta name="viewport" content="width=device-width, user-scalable=no">

</head>

<body class="<?php echo !empty($body_class) ? $body_class : NULL;  ?> page-header-menu-fixed">

  <div class="page-header">
      <!-- BEGIN HEADER TOP -->
      <div class="page-header-top">
          <div class="container">
              <!-- BEGIN LOGO -->
              <div class="page-logo">
                  <a href="<?php echo base_url() ?>"><img height="50" style="height:50px; margin:12px 0 0 0" class="logo-default" src="http://civsalta.com.ar/img/logo-texto.png"></a>
                  <div class="menu-toggler sidebar-toggler"> </div>
              </div>
              <!-- END LOGO -->
              <!-- BEGIN RESPONSIVE MENU TOGGLER -->
              <a href="javascript:;" class="menu-toggler"></a>
              <!-- END RESPONSIVE MENU TOGGLER -->
              <!-- BEGIN TOP NAVIGATION MENU -->
              <?php $this->load->view('layout/blocks/menues/top_menu') ?>
              <!-- END TOP NAVIGATION MENU -->
          </div>
      </div>
      <!-- END HEADER TOP -->
      <!-- BEGIN HEADER MENU -->
      <div class="page-header-menu" style="display: block;">
          <div class="container">
              <!-- BEGIN MEGA MENU -->
              <!-- DOC: Apply "hor-menu-light" class after the "hor-menu" class below to have a horizontal menu with white background -->
              <!-- DOC: Remove data-hover="dropdown" and data-close-others="true" attributes below to disable the dropdown opening on mouse hover -->
              <div class="hor-menu  ">
                  <?php $this->load->view('layout/blocks/menues/left_menu') ?>
              </div>
              <!-- END MEGA MENU -->
          </div>
      </div>
      <!-- END HEADER MENU -->
  </div>

  <div class="page-container">
      <div class="page-content-wrapper">

          <!-- BEGIN PAGE TITLE -->
          <div class="page-head">
            <div class="container">
              <div class="page-title">
                <h1><?php if (isset($layout_title)) echo $layout_title; ?>
                    <?php if (isset($data['CURRENT_PAGE'])) echo '<small>'.$data['CURRENT_PAGE'].'</small>'; ?>
                </h1>
              </div>
            </div>
          </div>
          <!-- END PAGE TITLE -->

          <!-- BEGIN PAGE CONTENT BODY -->
          <div class="page-content">
            <!-- BEGIN PAGE CONTENT BODY -->
            <div class="container">
            <?php echo $content ?>
            </div>
            <!-- END PAGE CONTENT BODY -->
          </div>
          <!-- END PAGE CONTENT BODY -->
      </div>
  </div>
           
  <!-- BEGIN CORE PLUGINS -->
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/js.cookie.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
  <!-- END CORE PLUGINS -->
  <!-- BEGIN THEME GLOBAL SCRIPTS -->
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/app.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/bootbox.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/toastr/js/toastr.min.js" type="text/javascript"></script>
  <!-- END THEME GLOBAL SCRIPTS -->
  <!-- BEGIN THEME LAYOUT SCRIPTS -->
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/layouts/layout3/scripts/layout.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/pages/scripts/ui-modals.min.js" type="text/javascript"></script>
  <!-- END THEME LAYOUT SCRIPTS -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <?php echo $this->layouts->print_includes('foot'); ?>
  <!-- END PAGE LEVEL PLUGINS -->

  <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/custom/scripts/ajaxforms.js" > </script>

  <?php if (isset($_GET['profiler']) AND ENVIRONMENT != 'production'): ?>
    
    <pre>
        <?php $vars = $this->_ci_cached_vars; unset($vars['content']); echo '<h4>$data</h4><pre>'. print_r($vars,true) . '</pre>'; ?>
    </pre>
    
  <?php endif ?>

</body>
</html>
