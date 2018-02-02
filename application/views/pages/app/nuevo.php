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
                    <?php array2form($form_organizador) ?>
                </div><!-- Close div#formContainer -->
            </div>
        </div>
        <?php endif ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_terminos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Terminos y condiciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php echo $terminos ?>
      </div>
      <div class="modal-footer">
        <button id="decline_terms" type="button" class="btn btn-danger" data-dismiss="modal">No acepto</button>
        <button id="accept_terms" type="button" class="btn btn-primary" data-dismiss="modal">Acepto</button>
      </div>
    </div>
  </div>
</div>