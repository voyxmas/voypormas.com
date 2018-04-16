<!-- BEGIN PAGE HEADER-->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="<?php echo base_url().'admin' ?>">Inicio</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="#">Organizaciones</a>
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
                                <div class="portlet-title">
                                    <div class="caption">
                                      <span class="caption-subject bold uppercase font-dark">Organizaciones</span> 
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    
                                    <div class="row">
                                        <?php foreach($organizaciones as $organizacion): ?>
                                        <div class="col-sm-12" style="margin:5px 0"> 
                                            <span class="mt-action-author" style="line-height:32px"><?php echo $organizacion['nombre'] ?></span>
                                            <?php if ($organizacion['token_activado'] ) : ?>
                                            <span class="badge badge-secondary">Email aun no confirmado</span>
                                            <?php endif ?>
    
                                            <span class="btn-group btn-group-circle pull-right">
                                                <a href="<?php echo base_url()?>ajax/organizadores_ajax/aprobar/<?php echo $organizacion['organizacion_id'] ?>" class="btn btn-outline green btn-sm ajax_call <?php echo $organizacion['estado'] == 1 ? 'active' : NULL ?>"><i class="fa fa-check" aria-hidden="true"></i></a>
                                                <a href="<?php echo base_url()?>admin/organizaciones/editar/<?php echo $organizacion['organizacion_id'] ?>" class="btn btn-outline blue btn-sm "><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                <a href="<?php echo base_url()?>ajax/organizadores_ajax/rechazar/<?php echo $organizacion['organizacion_id'] ?>" class="ajax_call btn btn-outline red btn-sm ajax_call <?php echo $organizacion['estado'] == 2 ? 'active' : NULL ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                                            </span>
                                        </div>
                                        <?php endforeach ?>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>