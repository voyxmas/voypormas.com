<div class="col-md-12" style="min-height: 100%; display: flex; align-items: center;">
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <?php $this->load->view('bloques/logo.php'); ?>
        </div>

        <!-- if not loged in asks for email and pass -->
        <!-- else if profile not complete show form to compelte profile -->
        <!-- else (if logged and profile complete) show add event form -->
        
        <div class="portlet-body">
            <div class="todo-container">
            <?php if (!$organizador_is_logged_in ) : // si no esta logueado muestro el formulario de logueo ?>
                <?php $this->load->view('bloques/perfil/login',array('form_organizador'=>$form_organizador)) ?>            
            <?php elseif (!$profile_ok) : // si esta logueado y el perfil no esta completo, pido los datos del perfil ?>
                <p style="font-weight:bold">¡YA CASI SOS PARTE DE VOY POR MÁS!</p>
                <small>Los datos que tildés como PUBLICOS los podran ver todos, los PRIVADOS solos los veremos nosotros. Los que no tengan esta opcion son PUBLICOS por predeterminado</small>
                <?php $this->load->view('bloques/perfil/card_01',$organizador) ?>            
                <?php $this->load->view('bloques/perfil/organizador_add',array('form_organizador'=>$form_organizador_details)) ?>            
            <?php else : // si esta logueado y el perfil de organizador esta completo, pido los datos del evento ?>
                <?php $this->load->view('bloques/perfil/card_01',$organizador) ?>            
                <?php $this->load->view('bloques/evento/evento_add',array('form_evento'=>$form_evento)) ?>            
            <?php endif ?>

            </div>
        </div>

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