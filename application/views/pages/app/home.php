<div class="col-md-12">
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
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
            <div class="col-md-5">
                <div class="todo-head">
                        <h3><span class="todo-grey">Eventos:</span> <span class="font-blue-madison"><?php echo $count ?></span></h3>
                            <a href="<?php echo base_url() ?>app/nuevo" class="btn btn-square btn-sm btn-default pull-right">Publicar mi evento</a>
                    </div>
                    <ul class="todo-tasks-content alternatefill">
                        <?php foreach($eventos as $evento_id => $evento) : ?>
                        <li class="todo-tasks-item">
                            <h4 class="todo-inline">
                                <a data-toggle="modal" href="#todo-task-modal"><?php echo $evento['nombre'] ?></a>
                            </h4>
                            <p class="todo-inline todo-float-r font-blue-madison"><?php /*echo $evento['ubicacion']*/ ?>
                                <span class=""><?php echo $evento['fecha'] ?></span>
                            </p>
                            <div>
                                <?php echo $evento['descripcion'] ?> <?php echo $evento['distancia'] ?>km
                            </div>
                            <div>
                                <i class="fa fa-cutlery" aria-hidden="true"></i>
                            </div>
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

