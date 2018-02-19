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
                        <a href="<?php echo base_url() ?>app/nuevo" class="btn btn-square btn-sm btn-default pull-right">Publicar mi evento</a>
                    </div>
                    <ul class="todo-tasks-content alternatefill">
                        <?php foreach($eventos as $evento_id => $evento) : ?>
                        <li class="todo-tasks-item">
                            <h4 class="todo-inline">
                                <a data-toggle="modal" href="<?php echo base_url().'app/evento/'.$evento['evento_id'] ?>"><?php echo $evento['nombre'] ?></a>
                            </h4>
                            <p class="todo-inline todo-float-r font-blue-madison"><?php /*echo $evento['ubicacion']*/ ?>
                                <span class=""><?php echo $evento['fecha'] ?></span>
                            </p>
                            <div>
                                <?php echo $evento['descripcion'] ?> <?php echo $evento['distancia'] ?>km
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

