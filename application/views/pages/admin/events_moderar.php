<!-- BEGIN PAGE HEADER-->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="<?php echo base_url().'admin' ?>">Inicio</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="<?php echo base_url().'admin/eventos' ?>">Events</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="#">Moderar</a>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="<?php echo base_url().'admin/eventos' ?>">Ver eventos</a></li>
                                    <li><a href="<?php echo base_url().'admin/eventos/moderar' ?>">Moderar eventos</a></li>
                                    <li><a href="<?php echo base_url().'admin/eventos/nuevo' ?>">Cargar eventos</a></li>
                                    <li><a href="javascript:history.go(-1)">Volver</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END PAGE HEADER-->

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                      <span class="caption-subject bold uppercase font-dark">Eventos esperando moderacion</span> 
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    
                                    <div class="mt-actions row">
                                        <?php foreach($eventos_nuevos as $evento): ?>
                                        <div class="mt-action"> 
                                            <div class="mt-action-body">
                                                <div class="mt-action-row">
                                                    <div class="mt-action-info ">
                                                        <div class="mt-action-details ">
                                                            <span class="mt-action-author"><?php echo $evento['nombre'] ?></span>
                                                            <p class="mt-action-desc"><?php echo $evento['descripcion'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-action-datetime ">
                                                        <span class="mt-action-date"><?php echo $evento['fecha'] ?></span>
                                                    </div>
                                                    <div class="mt-action-buttons ">
                                                        <div class="btn-group btn-group-circle">
                                                            <a href="<?php echo base_url()?>ajax/eventos_ajax/aprobar/<?php echo $evento['evento_id'] ?>" class="ajax_call btn btn-outline green btn-sm <?php echo $evento['estado'] == 1 ? 'active' : NULL ?>"><i class="fa fa-check" aria-hidden="true"></i></a>
                                                            <a href="<?php echo base_url()?>admin/eventos/ver/<?php echo $evento['evento_id'] ?>" class="btn btn-outline blue btn-sm "><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                            <a href="<?php echo base_url()?>ajax/eventos_ajax/rechazar/<?php echo $evento['evento_id'] ?>" class="ajax_call btn btn-outline red btn-sm <?php echo $evento['estado'] == 2 ? 'active' : NULL ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach ?>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>