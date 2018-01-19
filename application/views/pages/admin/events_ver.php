<!-- BEGIN PAGE HEADER-->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="<?php echo base_url().'admin' ?>">Inicio</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="<?php echo base_url().'admin/eventos' ?>">Events</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="#"><?php echo $evento['nombre'] ?></a>
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
                                        <span class="caption-subject bold uppercase font-dark"><?php echo $evento['nombre'] ?></span> 
                                    </div>
                                    <div class="actions">
                                        <div class="btn-group btn-group-circle">
                                            <a href="<?php echo base_url() ?>ajax/eventos_ajax/aprobar/<?php echo $evento['evento_id'] ?>" class="ajax_call btn btn-outline green btn-sm <?php echo $evento['estado'] == 1 ? 'active' : NULL ?>">Publico</a>
                                            <a href="<?php echo base_url() ?>ajax/eventos_ajax/desactivar/<?php echo $evento['evento_id'] ?>" class="ajax_call btn btn-outline blue btn-sm <?php echo $evento['estado'] == 0 ? 'active' : NULL ?>">Nuevo</a>
                                            <a href="<?php echo base_url() ?>ajax/eventos_ajax/rechazar/<?php echo $evento['evento_id'] ?>" class="ajax_call btn btn-outline red btn-sm <?php echo $evento['estado'] == 2 ? 'active' : NULL ?>">Rechazado</a>
                                        </div>
                                        <?php if(check_permissions('admin', 'events/editar')): ?>
                                        <a href="<?php echo base_url() ?>admin/eventos/editar/<?php echo $evento['evento_id'] ?>" class="btn btn-outline btn-circle blue btn-sm">Editar</a>
                                        <?php endif ?>
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    
                                    <?php if(isset($form)) array2form($form) ?>

                                </div>
                            </div>
                        </div>
                    </div>