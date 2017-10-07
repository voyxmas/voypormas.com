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
                                    <div class="row">
                                        <?php array2form($form) ?>
                                    </div>
                                    
                                    <h4 class="margintop">Caracteriticas</h4>
                                    <ul class="list-unstyled list-inline margin-top-10 margin-bottom-10">
                                        <li class="list-inline-item" style="display:block;">
                                            <div class="input-group input-group-sm">
                                                <form action="<?php echo base_url().'ajax/eventos_ajax/add_caracteristica_a_evento/'.$evento['evento_id'] ?>"  method="post" class="ajax_call">
                                                    <select name="caracteristica_id" class="form-control">
                                                        <?php foreach($caracteristicas_options as $item) : ?>
                                                        <option value="<?php echo $item['caracteristica_id'] ?>"><?php echo $item['nombre'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    <button type="submit" class="btn">Agregar caracteristica al evento</button>
                                                </form>
                                            </div>
                                        </li>
                                        <?php foreach($caracteristicas as $caracteristica): ?>
                                        <li class="list-inline-item"><i class="fa fa-check"></i> <?php echo $caracteristica['caracteristica_nombre'] ?> </li>
                                        <?php endforeach ?>
                                    </ul>

                                    <div class="pricing margin-top-20">   
                                        <h4 class="margintop">Aranceles</h4>
                                        <form action="<?php echo base_url().'ajax/eventos_ajax/agregar_precios/'.$evento['evento_id'] ?>" class="ajax_call" method="post">
                                            <table class="table table-hover"> 
                                                <thead>
                                                    <tr>
                                                        <th>Monto</th>
                                                        <th>Inicio</th>
                                                        <th>fin</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=0; foreach($precios as $precio): $i++?>
                                                    <tr>
                                                        <td>$<?php echo $precio['monto'] ?></td>
                                                        <td><?php echo date(APP_DATE_FORMAT,strtotime($precio['desde'])) ?></td>
                                                        <td><?php echo date(APP_DATE_FORMAT,strtotime($precio['hasta'])) ?></td>
                                                        <td>
                                                            <?php if(check_permissions('admin','events_eliminar_tarifa')): ?>
                                                            <a href="<?php echo base_url().'ajax/eventos_ajax/eliminar_precio/'.$precio['precio_evento_id'] ?>" class="btn btn-info btn-xs ajax_call">Eliminar</a>
                                                            <?php endif ?>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach ?>
                                                    <tr>
                                                        <td><input class="form-control " required name="monto" type="number"></td>           
                                                        <td><input class="form-control " required name="desde" type="date"></td>           
                                                        <td><input class="form-control " required name="hasta" type="date"></td>
                                                        <td>
                                                            <?php if(check_permissions('admin','events_agregar_tarifa')): ?>
                                                            <button type="submit" class="btn btn-info">Agregar</button>
                                                            <?php endif ?>
                                                        </td>        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>