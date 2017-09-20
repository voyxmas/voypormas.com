<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"><?php echo $layout_title ?>
                    </h3>
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