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
                                        <li><a href="#tab_variantes" data-toggle="tab" aria-expanded="false"> Variantes </a></li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <div id="tab_general" class="tab-pane active">
                                            <?php if(isset($form_general)) array2form($form_general) ?>
                                            
                                            <div class="caracteristicasLista row" >

                                                <div class="col-sm-12">

                                                    <div class="form-group " style="margin-top:30px;"><label class="control-label"><span>Caracteristicas</span></label>
                                                        <div>
                                                        <?php foreach ($caracteristicas AS $caracteristicasItem ) : ?>
                                                        
                                                            <?php if ($caracteristicasItem['estado'] !== FALSE )  : ?>
                                                                
                                                            <a href="<?php echo base_url().'ajax/caracteristicas_ajax/eliminar/'.$evento['evento_id'].'/'.$caracteristicasItem['caracteristica_id'] ?>" class="ajax_call btn btn-sm btn-info  col-xs-6 col-sm-4 col-md-3">
                                                            <img src="<?php echo base_url().$caracteristicasItem['icono'] ?>"><br>
                                                            <?php echo $caracteristicasItem['nombre'] ?></a>
                                                            
                                                            <?php else: ?>
                                                            
                                                            <a href="<?php echo base_url().'ajax/caracteristicas_ajax/asignar/'.$evento['evento_id'].'/'.$caracteristicasItem['caracteristica_id'] ?>" class="ajax_call btn btn-sm btn-default col-xs-6 col-sm-4 col-md-3">
                                                            <img src="<?php echo base_url().$caracteristicasItem['icono'] ?>"><br>
                                                            <?php echo $caracteristicasItem['nombre'] ?></a>
                                                            
                                                            <?php endif ?>

                                                        <?php endforeach ?>  
                                                        </div>
                                                    </div>   
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tab_variantes" class="tab-pane">
                                            <div class="panel-group accordion scrollable" id="accordion2">
                                            <?php foreach ($evento['evento_variantes'] AS $key => $varianteItem ) : ?>

                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#variante<?php echo $key?>"><?php echo $varianteItem['distancia'] ?>Kms </a>
                                                        </h4>
                                                    </div>
                                                    <div id="variante<?php echo $key?>" class="panel-collapse collapse">
                                                        <div class="panel-body ">
                                                            
                                                            <form 
                                                                action="<?php echo base_url() ?>ajax/eventos_ajax/editar_variante/<?php echo $varianteItem['variante_evento_id'] ?>"
                                                                class="ajax_call"
                                                                method="post"
                                                                >
                                                                <div class="form-group">
                                                                    <label class="control-label">
                                                                        <span>Distancia</span>
                                                                    </label>
                                                                    <input name="distancia" class="form-control input-group-z-element distancia z_tooltip" type="text" value="<?php echo $varianteItem['distancia'] ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">
                                                                        <span>Lugar de largada</span>
                                                                    </label>
                                                                    <input name="lugar_largada" class="form-control input-group-z-element lugar_largada z_tooltip" type="text" value="<?php echo $varianteItem['lugar_largada'] ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">
                                                                        <span>Elementos</span>
                                                                    </label>
                                                                    <input name="info" class="form-control input-group-z-element info z_tooltip" type="text" value="<?php echo $varianteItem['info'] ?>">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="control-label">
                                                                        <span>Hora</span>
                                                                    </label>
                                                                    <input name="fechahora" class="form-control input-group-z-element fechahora z_tooltip" type="text" value="<?php echo $varianteItem['fechahora'] ?>">
                                                                </div>
                                                                <button class="btn btn-info" type="submit">Guardar Cambios</button>
                                                            </form>
                                                            <h2>Premios</h2>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Pocision</th>
                                                                        <th>Premio</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($varianteItem['premios'] AS $premio ) : ?>
                                                                    <tr>
                                                                        <td>
                                                                            <input 
                                                                                form="premio_<?php echo $premio['variante_evento_premio_id'] ?>"
                                                                                name="descripcion"
                                                                                class="form-control input-group-z-element"
                                                                                type="text"
                                                                                value="<?php echo $premio['descripcion'] ?>">
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                form="premio_<?php echo $premio['variante_evento_premio_id'] ?>"
                                                                                name="premio"
                                                                                class="form-control input-group-z-element"
                                                                                type="text"
                                                                                value="<?php echo $premio['premio'] ?>">
                                                                        </td>
                                                                        <td>
                                                                            <form 
                                                                                id="premio_<?php echo $premio['variante_evento_premio_id'] ?>"
                                                                                action="<?php echo base_url() ?>ajax/eventos_ajax/editar_premio/<?php echo $premio['variante_evento_premio_id'] ?>"
                                                                                method="post"
                                                                                class="ajax_call">
                                                                                <button type="submit" class="btn btn-info">Actualizar</button>
                                                                                <a 
                                                                                    href="<?php echo base_url() ?>ajax/eventos_ajax/eliminar_premio/<?php echo $premio['variante_evento_premio_id'] ?>"
                                                                                    class="btn btn-danger ajax_call">Eliminar</a>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach ?>
                                                                    <tr>
                                                                        <td>
                                                                            <input 
                                                                                name="descripcion"
                                                                                form="premionew_<?php echo $varianteItem['variante_evento_id'] ?>" 
                                                                                type="text"
                                                                                class="form-control input-group-z-element">
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                name="premio"
                                                                                form="premionew_<?php echo $varianteItem['variante_evento_id'] ?>"
                                                                                type="text"
                                                                                class="form-control input-group-z-element">
                                                                        </td>
                                                                        <td>
                                                                            <form 
                                                                                action="<?php echo base_url() ?>ajax/eventos_ajax/nuevo_premio/<?php echo $varianteItem['variante_evento_id'] ?>"
                                                                                id="premionew_<?php echo $varianteItem['variante_evento_id'] ?>" 
                                                                                class="ajax_call" 
                                                                                method="post">
                                                                                <button type="submit" class="btn btn-info">Nuevo</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                            <h2>Incripcion</h2>
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Fecha</th>
                                                                        <th>Monto</th>
                                                                        <th></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($varianteItem['montos'] AS $monto ) : ?>
                                                                    <tr>
                                                                        <td>
                                                                            <input 
                                                                                form="precio_<?php echo $monto['variante_evento_precio_id'] ?>" 
                                                                                type="date"
                                                                                name="fecha"
                                                                                class="form-control input-group-z-element"
                                                                                value="<?php echo cstm_get_date($monto['fecha'], SYS_DATE_FORMAT) ?>">
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                form="precio_<?php echo $monto['variante_evento_precio_id'] ?>" 
                                                                                type="number"
                                                                                name="monto"
                                                                                class="form-control input-group-z-element"
                                                                                value="<?php echo $monto['monto'] ?>">
                                                                        </td>
                                                                        <td>
                                                                            <form 
                                                                                id="precio_<?php echo $monto['variante_evento_precio_id'] ?>"
                                                                                action="<?php echo base_url() ?>ajax/eventos_ajax/editar_precio/<?php echo $monto['variante_evento_precio_id'] ?>"
                                                                                method="post"
                                                                                class="ajax_call">
                                                                                <button type="submit" class="btn btn-info">Actualizar</button>
                                                                                <a 
                                                                                    class="btn btn-danger ajax_call"
                                                                                    href="<?php echo base_url() ?>ajax/eventos_ajax/eliminar_precio/<?php echo $monto['variante_evento_precio_id'] ?>">Eliminar</a>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    <?php endforeach ?>
                                                                    <tr>
                                                                        <td>
                                                                            <input 
                                                                                form="precionew_<?php echo $varianteItem['variante_evento_id'] ?>" 
                                                                                type="date"
                                                                                class="form-control input-group-z-element"
                                                                                name="fecha">
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                name="monto"
                                                                                class="form-control input-group-z-element"
                                                                                form="precionew_<?php echo $varianteItem['variante_evento_id'] ?>"
                                                                                type="text">
                                                                        </td>
                                                                        <td>
                                                                            <form 
                                                                                action="<?php echo base_url() ?>ajax/eventos_ajax/nuevo_precio/<?php echo $varianteItem['variante_evento_id'] ?>"
                                                                                id="precionew_<?php echo $varianteItem['variante_evento_id'] ?>" 
                                                                                class="ajax_call" 
                                                                                method="post">
                                                                                <button type="submit" class="btn btn-info">Nuevo</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>

                                            <?php endforeach ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>