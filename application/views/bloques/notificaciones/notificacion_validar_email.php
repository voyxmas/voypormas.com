<?php if ( !$this->session->organizador['token_activado'] ) : ?>
    <div id="noAprobadoAlert" class="alert alert-danger">
        <strong>Recuerda que debes validar tu email para publicar con nosotros</strong>
        <p>Te enviamos un email a <?php echo $this->session->organizador['email'] ?> con las instrucciones para hacerlo.</p> 
    </div><!-- Close div#noAprobadoAlert -->
<?php endif ?>