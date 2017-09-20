<!-- BEGIN PAGE HEADER-->
<h3 class="page-title"><?php echo $layout_title ?>
                    </h3>
                    <div class="page-bar">
                        <ul class="page-breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="<?php echo base_url().'admin' ?>">Inicio</a>
                                <i class="fa fa-angle-right"></i>
                                <a href="#">Categorias</a>
                            </li>
                        </ul>
                        <div class="page-toolbar">
                          <div class="btn-group pull-right">
                              <button type="button" class="btn btn-fit-height grey-salt dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-delay="1000" data-close-others="true"> Actions
                                  <i class="fa fa-angle-down"></i>
                              </button>
                              <ul class="dropdown-menu pull-right" role="menu">
                                  <li>
                                      <a href="<?php echo base_url() ?>admin/categorias/nuevo">
                                          <i class="icon-plus"></i> Cargar categoria</a>
                                  </li>
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
                                      <span class="caption-subject bold uppercase font-dark">categorias</span> 
                                    </div>
                                </div>
                                <div class="portlet-body">
                                    
                                    <div class="mt-actions row">
                                        <?php foreach($categorias as $categoria): ?>
                                        <div class="mt-action"> 
                                            <div class="mt-action-body">
                                                <div class="mt-action-row">
                                                    <div class="mt-action-info ">
                                                        <div class="mt-action-details ">
                                                            <span class="mt-action-author"><?php echo $categoria['nombre'] ?></span>
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