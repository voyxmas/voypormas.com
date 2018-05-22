<div class="modal-header">
    <button type="button" data-dismiss="modal" aria-hidden="true">x</button>
</div>
<div class="modal-body">
    <?php if ($evento['imagen'] ) : ?>
    <img src="<?php echo base_url().$evento['imagen'] ?>" alt="">
    <?php endif ?>
    <h2><?php echo $evento['nombre'] ?></h2>
    <?php if (count($evento['caracteristicas']) > 0 ) : ?>

    <div class="caracteristicasLista">
        <?php foreach ($evento['caracteristicas'] AS $caracteristicaItem ) : ?>
        <img src="<?php echo base_url().$caracteristicaItem['caracteristica_icono'] ?>" alt="<?php echo $caracteristicaItem['caracteristica_nombre'] ?>" title="<?php echo $caracteristicaItem['caracteristica_nombre'] ?>">
        <?php endforeach ?>
    </div>
        
    <?php endif ?>
    <p><?php echo $evento['tipo_grupo'] ?>: <?php echo $evento['tipo'] ?></p>
    <p><?php echo cstm_get_date($evento['fecha']) ?></p>
    <p><?php echo $evento['lugar'] ?></p>
    <h3>Inscripción</h3>
    <div id="incripiones" >
        <p><?php echo urltolink($evento['inscripciones_link']) ?></p>
        <p><?php echo $evento['inscripciones_con_links'] ?></p>
        <table class="table col-sm-12">
            <thead>
                <tr>
                    <th class="fixed">Distancias</th>
                    <?php foreach ($evento['variantes'][0]['inscripcion'] AS $item ) : ?>
                    <th>desde <?php echo cstm_get_date($item['fecha']) ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evento['variantes'] AS $varriantesItem ) : ?>
                    <tr>
                        <td class="fixed"><?php echo $varriantesItem['distancia'] ?>Km</td>
                        <?php foreach ($varriantesItem['inscripcion'] AS $inscripcion ) : ?>
                        <td>$<?php echo $inscripcion['monto'] ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?> 
            </tbody>
        </table>
    </div><!-- Close div#incripiones -->
    <input class="move" type="button" value="prev" />
    <input id="current" type="button" value="current"/>
    <input class="move" type="button" value="next" />

    <h3>Info por distancia</h3>
    <div id="variantes" >
        <?php foreach ($evento['variantes'] AS $varianteItem ) : ?>
        <h3><?php echo $varianteItem['distancia'] ?>Km</h3>
        <p>Lugar de largada: <?php echo $varianteItem['lugar_largada'] ?> (<?php echo cstm_get_date($evento['fecha']) ?> <?php echo cstm_get_time($varianteItem['fechahora']) ?>)</p>
        <p>Entrega de kit: <?php echo $varianteItem['kit_lugar'] ?> (<?php echo cstm_get_datetime($varianteItem['kit_hora']) ?>)</p>
        <?php if ( !empty($varianteItem['premios']) ) : ?>  
        <p>Premios: 
        <?php foreach ($varianteItem['premios'] AS $premio ) : ?>
            <?php echo $premio['descripcion'] ?> : <?php echo $premio['premio'] ?> - 
        <?php endforeach ?></p>
        <?php endif ?>
        <?php if ( !empty($varianteItem['info']) ) : ?>  
        <p>Elementos obligatorios: 
            <?php echo $varianteItem['info'] ?>
        <?php endif ?>
        
        <?php endforeach ?>
    </div><!-- Close div#variantes -->

    <h3>Organizador</h3>
    <div id="Organizacion" class="row">
        <div class="col-sm-6 col-md4"><span>Organización:</span> <?php echo $evento['organizacion'][0]['nombre'] ?></div>
        <?php if ($evento['organizacion'][0]['email_public'] == 1 ) : ?>
        <div class="col-sm-6 col-md4"><span>Email:</span> <?php echo emailtolink($evento['organizacion'][0]['email']) ?></div>
        <?php endif ?>
        <?php if ($evento['organizacion'][0]['tel_public'] == 1 ) : ?>
        <div class="col-sm-6 col-md4"><span>Teléfono:</span> <?php echo teltolink($evento['organizacion'][0]['tel']) ?></div>
        <?php endif ?>
        <div class="col-sm-6 col-md4"><span>Web:</span> <?php echo urltolink($evento['organizacion'][0]['web']) ?></div>
        <div class="col-sm-6 col-md4"><span>Inicio de actividades:</span> <?php echo cstm_get_date($evento['organizacion'][0]['inicio_actividades']) ?></div>
        <?php if (is_array($evento['organizacion'][0]['redes_sociales']) AND !empty($evento['organizacion'][0]['redes_sociales']) ) : ?>
        <div class="col-sm-12"><span>Redes sociales:</span><br>
            
            <?php foreach ($evento['organizacion'][0]['redes_sociales'] AS $red ) : ?>
                <?php echo "<a class='evento-perfil-redes-sociales fa ".$red['icono-class']."' href='".$red['link']."' title='".$red['red']."' target='_blank'></a>" ?>
            <?php endforeach ?>
        </div>
        <?php endif ?>
    </div><!-- Close div#Organizacion -->

    <?php if ( is_array($evento['representantes']) AND !empty($evento['representantes']) ) : ?>
        
    <h3>Representantes</h3>
    <?php foreach ($evento['representantes'] AS $representante ) : ?>
        <?php if ($representante['publico'] == 1 ) : ?>
        <p>
            Nombre: <?php echo $representante['nombre'] ?><br>
            Teléfono: <?php echo teltolink($representante['tel']) ?><br>
            Email: <?php echo emailtolink($representante['email']) ?><br>
        </p>
        <?php endif ?>
    <?php endforeach ?>
    
    <?php endif ?>

    <?php if ($evento['participantes_destacados']!="") : ?>
    <h3>Corredores destacados</h3>
    <p><?php echo $evento['participantes_destacados'] ?></p>
    <?php endif ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>

<script type="text/javascript">
let columnsMagic = {
    settings: {
        colMinWidth: 300,
        container: $("div#incripiones"),
        colShow: 1,
        colPos: 1,
        colCount: $('tbody > tr:first-child td:not(.fixed)').size()
    },
    init: function() {
        this.defineColumns();
        this.showHideCols(this.settings.colPos, this.settings.colShow);
    },
    showHideCols: function() {
        let rangeOffset = $('tbody > tr:first-child > td.fixed').size();
        let rangeStart = this.settings.colPos + rangeOffset;
        let rangeEnd = rangeStart + this.settings.colShow - 1;
        $('#current').val((rangeStart - rangeOffset) + ' a ' + (rangeEnd - rangeOffset) + ' de ' + this.settings.colCount);
        // start + end
        let showSelector = $('tbody > tr:not(.fixed) > td,thead > tr:not(.fixed) > th');
        let hideSelector = $('tbody > tr:not(.fixed) > td:not(.fixed):nth-child(n+' + (rangeEnd + 1) + '), tbody > tr:not(.fixed) > td:not(.fixed):nth-child(-n+' + (rangeStart - 1) + '), thead > tr:not(.fixed) > th:not(.fixed):nth-child(n+' + (rangeEnd + 1) + '), thead > tr:not(.fixed) > th:not(.fixed):nth-child(-n+' + (rangeStart - 1) + ')');
  
        showSelector.show();
        hideSelector.hide();
    },
    defineColumns: function() {
        let containerWidth = this.settings.container.width();
        this.settings.colShow = Math.floor(containerWidth / this.settings.colMinWidth);
        let finalColWidth = Math.floor(containerWidth / this.settings.colShow);
        $('tbody > tr > td').width(finalColWidth);
    },
    next: function() {
        if (columnsMagic.settings.colPos + columnsMagic.settings.colShow - 1 < columnsMagic.settings.colCount)
            columnsMagic.settings.colPos++;
        this.showHideCols(this.settings.colPos, this.settings.colShow);
    },
    previous: function() {
        if (columnsMagic.settings.colPos != 1)
            columnsMagic.settings.colPos--;
        this.showHideCols(this.settings.colPos, this.settings.colShow);
    }
};
setTimeout(() => {
    columnsMagic.init();
}, 1);
$(document).on('click', '.move,#current', function() {
    let move = $(this).val();
    switch (move) {
        case 'next':
            columnsMagic.next();
            console.log('next');
            break;
        case 'prev':
            columnsMagic.previous();
            console.log('prev');
            break;
    }
});
  
</script>