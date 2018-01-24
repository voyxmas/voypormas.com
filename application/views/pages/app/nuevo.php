<div class="col-md-12">
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <?php $this->load->view('bloques/logo.php'); ?>
        </div>
        
        <?php if ($organizador_is_logged_in ) : ?>

        <div class="portlet-body">
            <div class="todo-container">
                <div class="todo-head">
                    <h3><span class="todo-grey">Datos del organizador:</span> <span class="font-blue-madison"></span></h3>
                </div>
                <div class="todo-body">
                    <?php $this->load->view('bloques/perfil/card_01.php',$organizador) ?>
                </div>
            </div>
        </div>

        <div class="portlet-body">
            <div class="todo-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="todo-head">
                            <h3><span class="todo-grey">Cargar un evento nuevo:</span> <span class="font-blue-madison"></span></h3>
                        </div>
                        <div id="formContainer" >
                            <?php array2form($form_evento) ?>
                        </div><!-- Close div#formContainer -->
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>

        <div class="portlet-body">
            <div class="todo-container">
                <div class="todo-head">
                    <h3><span class="todo-grey">Datos del organizador:</span> <span class="font-blue-madison"></span></h3>
                </div>
                <div id="formContainer" >
                    <div class="form-group ">
                        <label class="control-label">
                            <span>Ya tienes unacuenta con nosotros?</span> 
                        </label>
                        <label class="radio-inline"><input type="radio" name="caracteristica_id[]" value="1">Si, quiero loguearme</label>
                        <label class="radio-inline"><input type="radio" name="caracteristica_id[]" value="1">No, quiero crear una cuenta</label>
                    </div>
                    <?php array2form($form_organizador) ?>
                </div><!-- Close div#formContainer -->
            </div>
        </div>
        <?php endif ?>
    </div>
</div>

