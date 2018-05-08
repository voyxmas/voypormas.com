<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <?php echo $this->layouts->print_tags(); ?>

    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/bootstrap.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/wNumb.js" type="text/javascript"></script>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/open-sans.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/css/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/css/nouislider.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/main.css" rel="stylesheet" type="text/css" />
    <!-- END THEME LAYOUT STYLES -->

    <!-- BEGIN PAGE LEVEL INCLUDES -->
    <?php echo $this->layouts->print_includes('head'); ?>
    <!-- END PAGE LEVEL INCLUDES -->
    <meta name="viewport" content="width=device-width, user-scalable=no">

</head>

<body class="<?php echo !empty($body_class) ? $body_class : NULL;  ?>">

    <?php echo $content ?>
           
    
    <!-- END CORE PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/nouislider.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/plugins/scripts/bootstrap-select.min.js" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <?php echo $this->layouts->print_includes('foot'); ?>
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/librerias/AjaxForms/ajaxforms.js" > </script>
    <!-- END PAGE LEVEL PLUGINS -->

    <?php if (isset($debug) AND ENVIRONMENT == "development") : ?>
        <pre><?php print_r($debug) ?></pre>
    <?php endif ?>

</body>
</html>
