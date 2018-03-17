<div class="modal-header">
    <button type="button" data-dismiss="modal" aria-hidden="true">x</button>
</div>
<div class="modal-body">
    <h2><?php echo $evento['nombre'] ?></h2>
    <p><?php echo $evento['tipo'] ?></p>
    <p><?php echo cstm_get_date($evento['fecha']) ?></p>
    <p><?php echo $evento['lugar'] ?></p>
    <h3>Inscripción</h3>
    <div id="incripiones" >
        <table class="table col-sm-12">
            <thead>
                <tr>
                    <th rowspan="2">Distancias</th>
                    <th colspan="<?php echo count($evento['variantes']) ?>">Períodos de inscripción</th>
                </tr>
                <tr>
                    <?php foreach ($evento['variantes'] AS $item ) : ?>
                    <th>desde <?php echo cstm_get_date($item['inscripcion'][0]['fecha']) ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($evento['variantes'] AS $varriantesItem) : ?>
                    <tr>
                        <td>
                            <?php echo $varriantesItem['distancia'] ?>Km
                        </td>
                        <?php foreach ($varriantesItem['inscripcion'] AS $inscripcion ) : ?>
                        <td>$<?php echo $inscripcion['monto'] ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?> 
            </tbody>
        </table>
    </div><!-- Close div#incripiones -->
    <h3>Info por distancia</h3>
    <div id="variantes" >
        <?php foreach ($evento['variantes'] AS $varianteItem ) : ?>
        <h3><?php echo $varianteItem['distancia'] ?>Km</h3>
        <p>Lugar de largada: <?php echo $varianteItem['lugar_largada'] ?></p>
        <?php endforeach ?>
    </div><!-- Close div#variantes -->

    <?php if (count($evento['caracteristicas']) > 0 ) : ?>

    <div class="caracteristicasLista">
        <?php foreach ($evento['caracteristicas'] AS $caracteristicaItem ) : ?>
        <img src="<?php echo base_url().$caracteristicaItem['caracteristica_icono'] ?>" alt="<?php echo $caracteristicaItem['caracteristica_nombre'] ?>" title="<?php echo $caracteristicaItem['caracteristica_nombre'] ?>">
        <?php endforeach ?>
    </div>
        
    <?php endif ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn default" data-dismiss="modal">Close</button>
</div>