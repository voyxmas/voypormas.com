<!-- BEGIN PAGE HEADER-->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="<?php echo base_url().'admin' ?>">Inicio</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="<?php echo base_url().'admin/organizaciones' ?>">Organizaciones</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="#"><?php echo $organizacion['nombre'] ?></a>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="<?php echo base_url().'admin/organizaciones' ?>">Ver Organizaciones</a></li>
                                    <li><a href="<?php echo base_url().'admin/organizaciones/nuevo' ?>">Cargar Organización</a></li>
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
                                        <span class="caption-subject bold uppercase font-dark"><?php echo $organizacion['nombre'] ?></span> 
                                    </div>
                                    <div class="actions">
                                        <div class="btn-group btn-group-circle">
                                            <a href="<?php echo base_url() ?>ajax/organizadores_ajax/aprobar/<?php echo $organizacion['organizacion_id'] ?>" class="ajax_call btn btn-outline green btn-sm <?php echo $organizacion['estado'] == 1 ? 'active' : NULL ?>">Publico</a>
                                            <a href="<?php echo base_url() ?>ajax/organizadores_ajax/rechazar/<?php echo $organizacion['organizacion_id'] ?>" class="ajax_call btn btn-outline red btn-sm <?php echo $organizacion['estado'] == 2 ? 'active' : NULL ?>">Rechazado</a>
                                        </div>
                                        <?php if(check_permissions('admin', 'events/editar')): ?>
                                        <a href="<?php echo base_url() ?>admin/organizacions/editar/<?php echo $organizacion['organizacion_id'] ?>" class="btn btn-outline btn-circle blue btn-sm">Editar</a>
                                        <?php endif ?>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab_general" data-toggle="tab" aria-expanded="true"> General</a></li>
                                        <li><a href="#tab_representantes" data-toggle="tab" aria-expanded="false"> Representantes </a></li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <div id="tab_general" class="tab-pane active">
                                            <?php array2form($form_organizacion); ?>
                                        </div>
                                        <div id="tab_representantes" class="tab-pane">
                                            <h2>Representantes de la organizacion</h2>
                                            <div class="row">
                                                <?php if (is_array($representantes_forms) ) : ?>
                                                <?php foreach ($representantes_forms AS $representante_form ) : ?>
                                                <?php array2form($representante_form); ?>
                                                <?php endforeach ?>
                                                <?php else: ?>
                                                <p class="col-sm-12">No hay representatnes cargaados para esta organizacion</p>
                                                <?php endif ?>
                                            </div>
                                            <h2>Nuevo representate</h2>
                                            <div class="row">
                                                <?php array2form($representante_nuevo_forms) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>