<div class="col-md-12">
    <div class="portlet light ">
        <div>
            <?php $this->load->view('bloques/logo.php'); ?>
            <?php
                $data['distancialimits'] = $distancialimits;
                $data['pricelimits'] = $pricelimits;
                $this->load->view('bloques/searchbar.php',$data);
            ?>
        </div>
        <div class="portlet-body">
        <div class="todo-container">
            <div class="row">
                <div id="filtros" class="col-md-3">
                    <div class="todo-head">
                        <h3><span class="todo-grey">Filtros:</span></h3>
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
                                <img 
                                    src="'.base_url().$filtersItem['caracteristica_icono'].'"> 
                                <label for="c_'.$filtersItem['caracteristica_id'].'">'.$filtersItem['caracteristica_nombre'].'</label> 
                                <span class="badge badge-pill badge-warning">'.$filtersItem['count'].'</span>
                            </li>' ?>
                        <?php endforeach ?>
                    </ul>
                    <div class="row">
                        <button type="submit" form="main_search" class="btn btn-square todo-bold">Aplicar filtros</button>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="todo-head">
                        <h3><span class="todo-grey">Eventos:</span> <span class="font-blue-madison"><?php echo $count ?></span></h3>
                        <div class="sort pull-right">
                            <select name="order" form="main_search" id="order_results" class="select">
                                <option <?php echo $this->input->get('order') == 'nombre ASC' ? 'selected' : NULL ?> value="nombre ASC">Nombre</option>
                                <option <?php echo $this->input->get('order') == 'tipo ASC' ? 'selected' : NULL ?> value="tipo ASC">Tipo</option>
                                <option <?php echo $this->input->get('order') == 'fecha ASC' ? 'selected' : NULL ?> value="fecha ASC">Fecha</option>
                                <option <?php echo $this->input->get('order') == 'distancia ASC' ? 'selected' : NULL ?> value="distancia ASC">Distancia</option>
                                <option <?php echo $this->input->get('order') == 'lugar ASC' ? 'selected' : NULL ?> value="lugar ASC">Lugar</option>
                                <option <?php echo $this->input->get('order') == 'precio ASC' ? 'selected' : NULL ?> value="precio ASC">Precio</option>
                            </select><button type="submit" form="main_search" class="btn btn-outline-secondary" type="button">Ordenar</button>
                        </div>
                        <a href="<?php echo base_url() ?>app/nuevo" class="btn btn-square btn-sm btn-default pull-right">Publicar <span class="hidden-md-down">mi evento</span></a>
                    </div>
                    <ul class="todo-tasks-content alternatefill">
                        <?php foreach($eventos as $evento_id => $evento) : ?>
                        <li class="todo-tasks-item evento-item">
                            <h4 class="todo-inline">
                                <a class="ajax_modal" href="<?php echo base_url().'app/evento/'.$evento['evento_id'] ?>/modal"><?php echo $evento['nombre'] ?></a>
                            </h4>
                            <div>
                                <?php echo $evento['lugar'] ?> - <?php echo implode('km, ',explode(',',$evento['distancias_concat'])) ?>Km - <?php echo $evento['fecha'] ?>
                            </div>
                            <div class="caracteristicasLista" >
                                <?php foreach ($evento['caracteristicas'] AS $caracteristicasItem ) : ?>
                                    <img src="<?php echo base_url().$caracteristicasItem['caracteristica_icono'] ?>" alt="<?php echo $caracteristicasItem['caracteristica_nombre'] ?>" title="<?php echo $caracteristicasItem['caracteristica_nombre'] ?>">
                                <?php endforeach ?>
                            </div><!-- Close div.caracteristicasLista -->
                        </li>
                        <?php endforeach ?>
                    </ul>

                    <?php echo $this->layouts->paginacion($eventos) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>