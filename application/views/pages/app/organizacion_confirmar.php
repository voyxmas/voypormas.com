<div class="col-md-12">
    <div class="portlet light ">
        <div class="portlet-title tabbable-line">
            <?php $this->load->view('bloques/logo.php'); ?>
            <?php
                $data['distancialimits'] = $distancialimits;
                $data['pricelimits'] = $pricelimits;
                $this->load->view('bloques/searchbar.php',$data);
            ?>
        </div>
        <div class="portlet-body">
            <div class="todo-container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="todo-body">
                            <?php if ($respuesta === TRUE ) : ?>
                                <div class="alert alert-success">
                                    Email confirmado correctamente
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <p>No confirmado.<p> 
                                    <p><?php echo is_array($e) ? implode('<br>',$e) : NULL ?></p>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

