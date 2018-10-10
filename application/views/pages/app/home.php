<div class="col-md-12">
    <div id="contenido-main" class="portlet light <?php echo !$this->input->get() ? 'initial' : NULL ?>">
        <div style="width:100%">
            <?php $this->load->view('bloques/logo.php'); ?>
            <?php
                $data['distancialimits'] = $distancialimits;
                $data['pricelimits'] = $pricelimits;
                $this->load->view('bloques/searchbar.php',$data);
            ?>
            <?php if (!$this->input->get() ) : ?>
            <a href="<?php echo base_url() ?>app/nuevo" class="organizadores-btn"><img src="<?php echo base_url()?>assets/global/imgs/botones/organizadores.gif" alt="Publica tu evento gratis"></a>
            <?php endif ?>
        </div>
        <?php if ($this->input->get() ) : ?>
        <div class="portlet-body">
            <div class="todo-container lista-eventos">
                <div class="row">
                    <label class="ordenar col-sm-12"><span>Ordenar</span><select form="main_search" onchange="this.form.submit()" data-form="main_search" name="order" form="main_search" id="order_results" class="select">
                        <option <?php echo $this->input->get('order') == 'nombre ASC' ? 'selected' : NULL ?> value="nombre ASC">Nombre</option>
                        <option <?php echo $this->input->get('order') == 'tipo ASC' ? 'selected' : NULL ?> value="tipo ASC">Tipo</option>
                        <option <?php echo $this->input->get('order') == 'fecha ASC' ? 'selected' : NULL ?> value="fecha ASC">Fecha</option>
                        <option <?php echo $this->input->get('order') == 'distancia ASC' ? 'selected' : NULL ?> value="distancia ASC">Distancia</option>
                        <option <?php echo $this->input->get('order') == 'lugar ASC' ? 'selected' : NULL ?> value="lugar ASC">Lugar</option>
                        <option <?php echo $this->input->get('order') == 'monto ASC' ? 'selected' : NULL ?> value="monto ASC">Precio</option>
                    </select></label>
                    <div id="filtros" class="col-md-3">
                        <div class="todo-head">
                            <span class="filtrar">Filtrar por:</span><button type="submit" form="main_search" id="filtros_btn">aplicar <span></span> filtros</button>
                        </div>
                        <ul class="todo-tasks-content alternatefill caracteristicasLista row">
                            <?php 
                            $get_c = $this->input->get('c');
                            foreach ($filters['caracteristicas'] AS $filtersItem ) : ?>
                                <?php
                                if($get_c)
                                {
                                    $checked = in_array($filtersItem['caracteristica_id'],$get_c) ? ' checked ' : NULL;
                                }
                                else
                                {
                                    $checked = NULL;
                                }
                                    
                                echo '<li class="fitler-item col-xs-12 col-sm-6 col-md-12">
                                    <input 
                                        '.$checked.'
                                        name="c[]" 
                                        value="'.$filtersItem['caracteristica_id'].'" 
                                        form="main_search" type="checkbox" id="c_'.$filtersItem['caracteristica_id'].'">
                                    <label for="c_'.$filtersItem['caracteristica_id'].'">
                                        <img 
                                        src="'.base_url().$filtersItem['caracteristica_icono'].'"> 
                                        '.$filtersItem['caracteristica_nombre'].'
                                    </label> 
                                </li>' ?>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div id="resultados" class="col-md-9">
                        <div class="todo-head">
                            <h3><span class="texto-azul">Estas carreras encontramos para vos:</span></span></h3>
                        </div>
                        <ul class="todo-tasks-content alternatefill">
                            <?php 
                            if(isset($eventos_results))
                            foreach($eventos_results as $evento_id => $evento) : ?>
                            <li class="ajax_modal todo-tasks-item evento-item <?php echo $evento['suspendido'] == 1 ? 'suspendido' : NULL ?> <?php echo $evento['reprogramado'] == 1 ? 'reprogramado' : NULL ?>" data-href="<?php echo base_url().'app/evento/'.$evento['evento_id'] ?>/modal">
                                    <div class="event_photo" >
                                        <img src="<?php echo base_url().$evento['imagen'] ?>" alt="">
                                    </div>
                                    <h4 class="todo-inline">
                                        <?php echo $evento['nombre'] ?>
                                    </h4>
                                    <div>
                                        <?php echo $evento['tipo_grupo'] ?>: <?php echo $evento['tipo'] ?> - <?php echo $evento['lugar'] ?> - <?php echo implode('km, ',explode(',',$evento['distancias_concat'])) ?>Km - <?php echo cstm_get_date($evento['fecha']) ?>
                                    </div>
                                    <div class="caracteristicasLista" >
                                        <?php foreach ($evento['caracteristicas'] AS $caracteristicasItem ) : ?>
                                        <img src="<?php echo base_url().$caracteristicasItem['caracteristica_icono'] ?>" alt="<?php echo $caracteristicasItem['caracteristica_nombre'] ?>" title="<?php echo $caracteristicasItem['caracteristica_nombre'] ?>">
                                        <?php endforeach ?>
                                    </div><!-- Close div.caracteristicasLista -->
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
                            </li>
                            <?php endforeach ?>
                        </ul>

                        <?php echo $this->layouts->paginacion($eventos) ?>

                        <?php if ($this->input->get() ) : ?>
                                <?php $this->load->view('bloques/footer.php'); ?>    
                        <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif ?>
    </div>
</div>