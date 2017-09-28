<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <?php echo $this->layouts->print_tags(); ?>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/open-sans.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
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

<body class="<?php echo !empty($body_class) ? $body_class : NULL;  ?>">

    <?php echo $content ?>
           
    <!-- BEGIN CORE PLUGINS -->
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/global/scripts/bootstrap.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->

    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->

    <!-- END THEME LAYOUT SCRIPTS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <?php echo $this->layouts->print_includes('foot'); ?>
    <script src="<?php echo base_url().APP_ASSETS_FOLDER ?>/custom/scripts/ajaxforms.js" > </script>
    <!-- END PAGE LEVEL PLUGINS -->

</body>
</html>
