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
                                <div class="portlet-title tabbable-line">
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

                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab_general" data-toggle="tab" aria-expanded="true"> General</a></li>
                                        <li><a href="#tab_caracteristicas" data-toggle="tab" aria-expanded="false"> Caracteristicas </a></li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <div id="tab_general" class="tab-pane active">
                                            <?php if(isset($form_general)) array2form($form_general) ?>
                                        </div>
                                        <div id="tab_caracteristicas" class="tab-pane">
                                            <ul class="caracteristicasLista row" style="padding:0">
                                                <?php foreach ($caracteristicas AS $caracteristicasItem ) : ?>
                                                <li class="fitler-item col-xs-12 col-sm-6 col-md-12">
                                                    <div style="border-bottom:1px solid #EEE; padding-bottom:15px;">
                                                        <img src="<?php echo base_url().$caracteristicasItem['caracteristica_icono'] ?>"> 
                                                        <?php echo $caracteristicasItem['caracteristica_nombre'] ?>
                                                        <?php if ($caracteristicasItem['estado'] !== FALSE )  : ?>
                                                            <a href="<?php echo base_url().'ajax/caracteristicas_ajax/eliminar/'.$evento['evento_id'].'/'.$caracteristicasItem['caracteristica_id'] ?>" class="ajax_call btn btn-sm btn-danger pull-right">Quitar</a>
                                                        <?php else: ?>
                                                        <a href="<?php echo base_url().'ajax/caracteristicas_ajax/asignar/'.$evento['evento_id'].'/'.$caracteristicasItem['caracteristica_id'] ?>" class="ajax_call btn btn-sm btn-info pull-right">Asignar</a>
                                                        <?php endif ?>
                                                    </div>
                                                </li>
                                                <?php endforeach ?>     
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>