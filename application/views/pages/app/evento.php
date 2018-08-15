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

                    <div class="col-md-12">
                        <div class="todo-body">
                            <?php $this->load->view('pages/app/evento_modal.php',$evento) ?>
                        </div>
                    </div>
            </div>
        </div>
    </div>

