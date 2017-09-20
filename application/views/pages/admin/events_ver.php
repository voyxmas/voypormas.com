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
                                <a href="index.html"># 1</a>
                            </li>
                        </ul>
                    </div>
                    <!-- END PAGE HEADER-->

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                      <span class="caption-subject bold uppercase font-dark"><?php echo $evento['nombre'] ?></span> 
                                      <small class="caption-helper">Publico entre <?php echo date(APP_DATE_FORMAT,strtotime($evento['publicar_desde'])) ?> y <?php echo date(APP_DATE_FORMAT,strtotime($evento['publicar_hasta'])) ?></small>
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
                                  <p class="margin-top-20"><?php echo nl2br($evento['descripcion']) ?></p>

                                  <ul class="list-unstyled margin-top-10 margin-bottom-10 row">
                                    <?php foreach($caracteristicas as $caracteristica): ?>
                                    <li class="col-sm-6 col-md-4 col-xl-3"><i class="fa fa-check"></i> <?php echo $caracteristica['caracteristica_nombre'] ?> </li>
                                    <?php endforeach ?>
                                  </ul>

                                  <div class="pricing row margin-top-20">   
                                    <?php $i=0; foreach($precios as $precio): $i++?>
                                    <div class="col-sm-12 col-md-6 col-lg-3 margin-top-20"> 
                                      <div class="bg-blue-madison bg-font-blue-madison head">
                                        <?php if(check_permissions('admin','events_editar_precio')): ?>
                                        <button type="button" class="btn btn-info btn-xs">Editar</button>
                                        <?php endif ?>
                                        <div style="text-align:right">$<?php echo $precio['monto'] ?></div>
                                      </div>
                                      <small><?php echo date(APP_DATE_FORMAT,strtotime($precio['desde'])) ?> - <?php echo date(APP_DATE_FORMAT,strtotime($precio['hasta'])) ?></small>
                                    </div>
                                    <?php endforeach ?>
                                  </div>

                                </div>

                            </div>
                        </div>
                    </div>