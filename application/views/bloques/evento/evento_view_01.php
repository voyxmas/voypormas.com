<?php if ($estado == 0 AND $organizador_id == $this->session->organizador['organizacion_id'] ) : ?>
    <?php if ( !$activo ) : ?>
        <div id="noAprobadoAlert" class="alert alert-danger">
            <strong>Recuerda que debes validar tu email para publicar con nosotros</strong>
            <p>Te enviamos un email a <?php echo $organizacion_email ?> con las instrucciones para hacerlo.</p> 
        </div><!-- Close div#noAprobadoAlert -->
    <?php endif ?>
    <div id="noAprobadoAlert" class="alert alert-warning">
        Este evento aún no se ha aprobado, sólamente vos podés ver esta publicación mientras esté en revisión.
    </div><!-- Close div#noAprobadoAlert -->
<?php endif ?>

<div class="container">
	<?php if ($estado != 2 ) : ?>
        <?php if ($estado == 1 || ($estado == 0 AND $organizador_id == $this->session->organizador['organizacion_id'] ))     : ?>
        <div class="row">
            <h2 id="titulo" >
                <?php echo $nombre ?>
            </h2><!-- Close h2#titulo -->
            <small id="evento_tipo" >
                <?php echo $tipo ?>
            </small><!-- Close small#evento_tipo -->
            <small id="evento_fecha" >
                - <?php echo cstm_get_date($fecha) ?>
            </small><!-- Close small#evento_fecha -->
            <small id="lugar" >
                - <?php echo $lugar ?>
            </small><!-- Close small#lugar -->

            <img src="<?php echo base_url().$imagen ?>" alt="<?php echo $nombre ?>">

            <div class="caracteristicasLista" >
                <?php foreach ($caracteristicas AS $caracteristicasItem ) : ?>
                    <img src="<?php echo base_url().$caracteristicasItem['caracteristica_icono'] ?>" alt="<?php echo $caracteristicasItem['caracteristica_nombre'] ?>" title="<?php echo $caracteristicasItem['caracteristica_nombre'] ?>">
                <?php endforeach ?>
            </div><!-- Close div.caracteristicasLista -->
            
            <div id="variantes" >
                <h3>Incripciones</h3>
                <table class="table col-sm-12">
                    <thead>
                        <tr>
                            <th rowspan="2">Distancias</th>
                            <th colspan="<?php echo count($variantes[0]['inscripcion']) ?>">Períodos de inscripción</th>
                        </tr>
                        <tr>
                            <?php foreach ($variantes[0]['inscripcion'] AS $item ) : ?>
                            <th>desde <?php echo cstm_get_date($item['fecha']) ?></th>
                            <?php endforeach ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($variantes AS $varriantesItem ) : ?>
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
                <h3>Premios</h3>
                <table class="table col-sm-12">
                    <thead>
                        <tr>
                            <th>Distancia</th>
                            <th>Criterio / posición</th>
                            <th>Premio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($variantes AS $varriantesItem ) : ?>
                            <?php $i=0; foreach ($varriantesItem['premios'] AS $premios ) : $i++?>
                            <tr>                        
                                <?php if ($i===1 ) : ?>
                                <td rowspan="<?php echo count($varriantesItem['premios']) ?>">
                                    <?php echo $varriantesItem['distancia'] ?>Km
                                </td>    
                                <?php endif ?><td><?php echo $premios['descripcion'] ?></td>
                                <td><?php echo is_numeric($premios['premio']) ? '$' : NULL ?><?php echo $premios['premio'] ?></td>
                            </tr>
                            <?php endforeach ?>
                        <?php endforeach ?> 
                    </tbody>
                </table>
                <h3>Elementos obligatorios</h3>
                <table class="table col-sm-12">
                    <thead>
                        <tr>
                            <th>Distancia</th>
                            <th>Elementos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($variantes AS $varriantesItem ) : ?>
                            <tr>
                                <td><?php echo $varriantesItem['distancia'] ?>km</td>
                                <td><?php echo $varriantesItem['info'] ? $varriantesItem['info'] : 'Ninguna' ?></td>
                            </tr>
                        <?php endforeach ?> 
                    </tbody>
                </table>

                <?php if ($participantes_destacados ) : ?>
                <h3>Correrán</h3>
                <?php echo $participantes_destacados ?>
                <?php endif ?>
            </div><!-- Close div#variantes -->
        </div>
        <?php else: ?>
            Este evento aún no se ha aprobado
        <?php endif ?>
    <?php else: ?>
        Este evento no existe
    <?php endif ?>
</div>