
<?php if ($evento['suspendido'] == 1 ) : ?>
<span class="label_evento_suspendido">
    <span>suspendido</span>
</span>
<?php endif ?>
<?php if ($evento['reprogramado'] == 1 ) : ?>
<span class="label_evento_reprogramado">
    <span>reprogr</span>
</span>
<?php endif ?>
<div class="modal-body">
    <?php if ($evento['imagen'] ) : ?>
    <div>
        <img class="evento-imagen" src="<?php echo base_url().$evento['imagen'] ?>" alt="">
    </div>
    <?php endif ?>
    <h2 class="evento-titulo"><a href="<?php echo base_url() ?>app/evento/<?php echo $evento['evento_id'] ?>"><?php echo $evento['nombre'] ?></a></h2>
    
    <p class="evento-bajada"><?php echo $evento['tipo_grupo'] ?>: <?php echo $evento['tipo'] ?><br>
    <?php echo cstm_get_date($evento['fecha']) ?><br>
    <?php echo $evento['lugar'] ?><br>
    Organización: <a id="more_org_info" data-toggle="collapse" href="#organizador_info" aria-expanded="false" aria-controls="organizador_info" class="collapsed"> <?php echo strtoupper($evento['organizacion'][0]['nombre']) ?></a>
    </p>
    <div id="organizador_info" class="collapse">
        <h3>Organizador</h3>
        <div id="Organizacion" class="row">
            <div class="col-sm-6 col-md4"><span>Nombre de la organización:</span> <?php echo $evento['organizacion'][0]['nombre'] ?></div>
            <?php if ($evento['organizacion'][0]['email_public'] == 1 ) : ?>
            <div class="col-sm-6 col-md4"><span>Email:</span> <?php echo emailtolink($evento['organizacion'][0]['email']) ?></div>
            <?php endif ?>
            <?php if ($evento['organizacion'][0]['tel_public'] == 1 ) : ?>
            <div class="col-sm-6 col-md4"><span>Teléfono:</span> <?php echo teltolink($evento['organizacion'][0]['tel']) ?></div>
            <?php endif ?>
            <?php if ($evento['organizacion'][0]['web'] ) : ?>
            <div class="col-sm-6 col-md4"><span>Web:</span> <?php echo urltolink($evento['organizacion'][0]['web']) ?></div>
            <?php endif ?>
            <?php if ($evento['organizacion'][0]['inicio_actividades'] ) : ?>
            <div class="col-sm-6 col-md4"><span>Inicio de actividades:</span> <?php echo cstm_get_date($evento['organizacion'][0]['inicio_actividades']) ?></div>
            <?php endif ?>
            
        </div><!-- Close div#Organizacion -->
        <?php if ( is_array($evento['representantes']) AND !empty($evento['representantes']) ) : ?>
        
            <h3>Representantes</h3>
            <?php foreach ($evento['representantes'] AS $representante ) : ?>
                <?php if ($representante['publico'] == 1 ) : ?>
                <p class="card">
                    <span class="card-body">
                        <span class="nombre btn btn-xs">Nombre: <?php echo $representante['nombre'] ?></span><br>
                        <span class="telefono btn btn-xs">Teléfono:</span> <span class="telefono"><?php echo teltolink($representante['tel']) ?></span>
                        <span class="email btn btn-xs">Email:</span> <span class="email"><?php echo emailtolink($representante['email']) ?></span><br>
                    </span>
                </p>
                <p class="card">
                    <span class="card-body">
                        <span class="nombre btn btn-xs">Nombre: <?php echo $representante['nombre'] ?></span><br>
                        <span class="telefono btn btn-xs">Teléfono:</span> <span class="telefono"><?php echo teltolink($representante['tel']) ?></span>
                        <span class="email btn btn-xs">Email:</span> <span class="email"><?php echo emailtolink($representante['email']) ?></span><br>
                    </span>
                </p>
                <?php endif ?>
            <?php endforeach ?>
    
        <?php endif ?>
    </div>

    <?php if (count($evento['caracteristicas']) > 0 ) : ?>

    <div class="caracteristicasLista">
        <?php foreach ($evento['caracteristicas'] AS $caracteristicaItem ) : ?>
        <img src="<?php echo base_url().$caracteristicaItem['caracteristica_icono'] ?>" alt="<?php echo $caracteristicaItem['caracteristica_nombre'] ?>" title="<?php echo $caracteristicaItem['caracteristica_nombre'] ?>">
        <?php endforeach ?>
    </div>
        
    <?php endif ?>
    <h3>Inscripción</h3>
    <div id="inscripiones" >
        <div id="tabla_inscripciones" >
            <table class="table col-sm-12">
                <thead>
                    <tr>
                        <th class="fixed"></th>
                        <th class="fixed azul" colspan="<?php echo count($evento['variantes'][0]['inscripcion']) ?>">Periodos de inscripcion</th>
                    </tr>
                    <tr>
                        <th class="fixed azul">Distancias</th>
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
                            <td class="azul">$<?php echo $inscripcion['monto'] ?></td>
                            <?php endforeach ?>
                        </tr>
                    <?php endforeach ?> 
                </tbody>
            </table>
            &nbsp;
            <input class="move prev btn btn-basic" value="<" type="button" data-direction="prev" />
            <input class="move next btn btn-basic" value=">" type="button" data-direction="next" />
        </div><!-- Close div#tabla_inscripciones -->
    </div><!-- Close div#inscripiones -->
    <?php
    $attr['Texto'] = 'Inscribite!'; 
    $attr['Class'] = 'Inscripciones'; 
    $attr['Icono'] = FALSE; 
    ?>
    <p><?php echo urltolink($evento['inscripciones_link'],$attr) ?></p>
    <?php echo $evento['inscripciones_con_links'] ? "<p>Otras formas de inscripcion: ".$evento['inscripciones_con_links']."</p>" : NULL?>
    <h3>Info por distancia</h3>
    <div id="variantes" >
        <?php foreach ($evento['variantes'] AS $varianteItem ) : ?>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title"><?php echo $varianteItem['distancia'] ?>Km</h3>
                <p class="separador"><strong>Largada:</strong> <?php echo $varianteItem['lugar_largada'] ?> (<?php echo cstm_get_date($evento['fecha']) ?> <?php echo cstm_get_time($varianteItem['fechahora']) ?>)</p>
                <p class="separador"><strong>Entrega de kit:</strong> <?php echo $varianteItem['kit_lugar'] ?> (<?php echo cstm_get_datetime($varianteItem['kit_hora']) ?>)</p>
                <?php if ( !empty($varianteItem['premios']) ) : ?>  
                <?php if ( !empty($varianteItem['info']) ) : ?>  
                <p class="separador elementos_obligatorios">Elementos obligatorios: 
                    <?php echo nl2br($varianteItem['info']) ?>
                <?php endif ?>
                <p class="separador">Premios: <br>
                <?php foreach ($varianteItem['premios'] AS $premio ) : ?>
                    <span class="criterio"><?php echo $premio['descripcion'] ?> :</span><span class="montos"><?php echo $premio['premio'] ?></span> <br> 
                <?php endforeach ?></p>
                <?php endif ?>
            </div>
        </div>
        <?php endforeach ?>
    </div><!-- Close div#variantes -->

    <?php if ($evento['participantes_destacados']!="") : ?>
    <h3>Corredores destacados</h3>
    <?php foreach (explode(',',$evento['participantes_destacados']) AS $participante ) : ?>
    <p class="participante"><?php echo $participante ?></p>
    <?php endforeach ?>
    <?php endif ?>
    <div class="redes_sociales_modal">
        <h3>Compratí este evento</h3>
        <a class="social-button fa fa-facebook" href="https://www.facebook.com/sharer/sharer.php?u=voypormas.com/app/evento/<?php echo $evento['evento_id'] ?>" target="_blank"></a>
        <a class="social-button fa fa-twitter" href="https://twitter.com/home?status=voypormas.com/app/evento/<?php echo $evento['evento_id'] ?>" target="_blank"></a>
        <a class="social-button fa fa-google-plus" href="https://plus.google.com/share?url=voypormas.com/app/evento/<?php echo $evento['evento_id'] ?>" target="_blank"></a>
        <a class="social-button fa fa-whatsapp" href="https://wa.me/?text=<?php echo urlencode(base_url().'app/evento/'.$evento['evento_id'])?>" target="_blank"></a>
    </div>
    <?php if (is_array($evento['organizacion'][0]['redes_sociales']) AND !empty($evento['organizacion'][0]['redes_sociales']) ) : ?>
    <div class="redes_sociales_modal">
        <h3>Mas sobre esta carrera <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">+</a></h3>
        <div id="collapseExample" class="collapse">
            <?php foreach ($evento['organizacion'][0]['redes_sociales'] AS $red ) : ?>
                <?php echo "<a class='social-button fa ".$red['icono-class']."' href='".$red['link']."' title='".$red['red']."' target='_blank'></a>" ?>
            <?php endforeach ?>
            <?php if ($evento['organizacion'][0]['tel_public'] == 1 AND !empty($evento['organizacion'][0]['tel']) ) : ?>
                <a class='fa fa-phone social-button' target='_blank' href="tel:<?php echo $evento['organizacion'][0]['tel'] ?>" title="<?php echo $evento['organizacion'][0]['tel'] ?>"></a>
            <?php endif ?>
            <?php if ($evento['organizacion'][0]['email_public'] == 1 AND !empty($evento['organizacion'][0]['email']) ) : ?>
                <a class='fa fa-envelope social-button' target='_blank' href="mailto:<?php echo $evento['organizacion'][0]['email'] ?>" title="<?php echo $evento['organizacion'][0]['email'] ?>"></a>
            <?php endif ?>
        </div>
    </div>
    <?php endif ?>
    

</div>
<div class="modal-footer">
    <?php if ($is_modal) : ?>
    <button type="button" class="btn default" data-dismiss="modal">Cerrar</button>
    <?php else: ?>
    <a href="<?php echo base_url() ?>" class="btn default" data-dismiss="modal">Volver</a>
    <?php endif ?>
</div>