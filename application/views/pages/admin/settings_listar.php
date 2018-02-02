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
                                    <li><a href="<?php echo base_url().'admin/settings/nuevo' ?>">Cargar</a></li>
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
                                        <li>
                                            <a href="<?php echo base_url() ?>admin/settigns/nuevo_grupo">Nuevo</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="portlet-body">
                                    <div class="tab-content">
                                        <?php $i=0; foreach ($settings AS $grupo => $settingsItem ) : $i++ ?>
                                        <div class="tab-pane <?php echo $i===1 ? 'active' : NULL ?>" id="setting_tab_<?php echo $settingsItem[0]['setting_grupo_id'] ?>">
                                            <?php foreach ($settingsItem AS $settingsItemItem ) : ?>
                                                <?php
                                                echo_input($settingsItemIte m);
                                                ?>
                                            <?php endforeach ?>
                                        </div>
                                        <?php endforeach ?>
                                        
                                    </div>
                                </div>
                            </div><!-- Close div#portlet -->
                        </div>
                    </div>