<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<title><?php echo $layout_title?$layout_title:'';  ?></title>
<meta name="description" content="<?php echo $layout_description?$layout_description:'';  ?>" />

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/open-sans.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/font-awesome.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
<!-- END THEME GLOBAL STYLES -->
<!-- BEGIN THEME LAYOUT STYLES -->
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/main.css" rel="stylesheet" type="text/css" />
<!-- END THEME LAYOUT STYLES -->

<!-- BEGIN PAGE LEVEL INCLUDES -->
<?php echo $this->layouts->print_includes('head'); ?>
<!-- END PAGE LEVEL INCLUDES -->

<!-- controler includes -->
<?php echo $this->layouts->print_includes('head'); ?>
<!-- /controler includes  -->
<meta name="viewport" content="width=device-width, user-scalable=no">

</head>
<body class="login">



<?php echo $content ?>


<!-- END FOOTER -->
<!--[if lt IE 9]>
<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/respond.min.js"></script>
<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/bootstrap.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/bootbox.min.js" type="text/javascript"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/ui-modals.min.js" type="text/javascript"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<?php echo $this->layouts->print_includes('foot'); ?>
<!-- END PAGE LEVEL PLUGINS -->

<script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/ajaxforms.js" > </script>

</body>
</html>
