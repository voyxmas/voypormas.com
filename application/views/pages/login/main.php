<!-- BEGIN LOGO -->
<div class="logo">
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->

<div class="content">
    <!-- BEGIN LOGIN FORM -->
    <form class="login-form ajax_call" action="<? echo base_url().APP_AJAX_FOLDER ?>/login_ajax" method="post">
        <h3 class="form-title">Iniciar sesión</h3>
        <div class="form-group">
            <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
            <label class="control-label visible-ie8 visible-ie9">Nombre</label>
            <div class="input-icon">
                <i class="fa fa-user"></i>
                <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Username" name="username" /> 
            </div>
        </div>
        <div class="form-group">
            <label class="control-label visible-ie8 visible-ie9">Contraseña</label>
            <div class="input-icon">
                <i class="fa fa-lock"></i>
                <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> 
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn green"/>
        </div>

    </form>
    <!-- END LOGIN FORM -->
</div>
