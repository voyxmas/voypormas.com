<!-- BEGIN PAGE HEADER-->
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="<?php echo base_url().'admin' ?>">Inicio</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="#">Settings</a>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                                    <i class="fa fa-angle-down"></i>
                                </button>
                                <ul class="dropdown-menu pull-right" role="menu">
                                    <li><a href="<?php echo base_url().'admin/settings' ?>">Administrar</a></li>
                                    <li><a href="javascript:history.go(-1)">Volver</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END PAGE HEADER-->
                    <div class="row">
                        <div class="col-md-12 col-sm-12">

                            <div id="portlet" class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption">
                                        <span class="caption-subject font-dark bold uppercase">Settings</span>
                                    </div>
                                    <ul class="nav nav-tabs">
                                        <?php foreach ($settings AS $grupo => $settingsItem ) : ?>
                                        <li class="active">
                                            <a href="#setting_tab_<?php echo $settingsItem[0]['setting_grupo_id'] ?>" data-toggle="tab" aria-expanded="true"> <?php echo $grupo ?> </a>
                                        </li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <?php $i=0; foreach ($settings AS $grupo => $settingsItem ) : $i++ ?>
                                        <div class="tab-pane <?php echo $i===1 ? 'active' : NULL ?>" id="setting_tab_<?php echo $settingsItem[0]['setting_grupo_id'] ?>">
                                            <?php foreach ($settingsItem AS $settingsItemItem ) : ?>
                                                <form class="ajax_call" action="<?php echo base_url() ?>ajax/settings_ajax/save" method="post">
                                                <strong><?php echo $settingsItemItem['nombre'] ?></strong>
                                                <input type="hidden" name="setting_id" value="<?php echo $settingsItemItem['setting_id'] ?>">
                                                <?php echo_input($settingsItemItem,FALSE,array('name'=>'valor'));  ?>
                                                <input class="btn btn-default" type="submit" value="guardar cambios">
                                                </form>
                                            <?php endforeach ?>
                                        </div>
                                        <?php endforeach ?>
                                        
                                        
                                    </div>
                                </div>
                            </div><!-- Close div#portlet -->


                            <div id="portlet" class="portlet light ">
                                <div class="portlet-title tabbable-line">
                                    <div class="caption">
                                        <span class="caption-subject font-dark bold uppercase">Nuevo Setting</span>
                                    </div>
                                </div>
                                <div class="portlet-body">

                                    <form class="ajax_call row" action="<?php echo base_url() ?>ajax/settings_ajax/new_setting" method="post">
                                        <label class="col-sm-3"> Nombre del setting
                                            <input class="col-sm-12 form-control" type="text" name="setting_name">
                                        </label>
                                        <label class="col-sm-3"> Valor a guardar
                                            <input class="col-sm-12 form-control" type="text" name="setting_value">
                                        </label>
                                        <label class="col-sm-3"> Tipo de valor
                                            <select name="setting_type" class="form-control">
                                                <option value="input">Texto corto</option>
                                                <option value="textarea">Texto largo</option>
                                                <option value="check">Checkbox</option>
                                                <option value="radio">Radio</option>
                                            </select>
                                        </label>
                                        <label class="col-sm-3"> &nbsp;
                                            <input class="btn btn-default form-control" type="submit" value="guardar cambios">
                                        </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>