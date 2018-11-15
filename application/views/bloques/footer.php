<footer class="row">
    <div id="publicar_evento_footer" class="col-sm-12">
        <div class="hold hidden-sm hidden-xs hidden-xxs">
            <a href="<?php echo base_url() ?>app/nuevo" class="organizadores-btn"><img src="<?php echo base_url()?>assets/global/imgs/botones/organizadores.gif" alt="Publica tu evento gratis"></a>
        </div><div class="hold">
        <a href="<?php echo base_url() ?>app/nuevo" class="icono-footer organizador-button hidden-md hidden-lg hidden-xl">Organizadores</a> 
        <?php if (!empty($redes_socales_vxm)) : foreach ($redes_socales_vxm AS $red ) : ?>
            <?php echo "<a class='icono-footer social-button fa ".$red['icono-class']."' href='".$red['link']."' title='".$red['red']."' target='_blank'></a>" ?>
        <?php endforeach; endif ?>
        </div>
    </div>
</footer>