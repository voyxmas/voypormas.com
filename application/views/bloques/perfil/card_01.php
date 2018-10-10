<div class="portlet-body col-sm-12">
    <div class="todo-container">
        <div class="todo-head">
            <span class="titulo-grande-liviano">Bienvenido!</span> <span class="font-blue-madison"></span>
        </div>
        <div class="todo-body">
            <div class="well well-sm">
                <div class="media">
                    <?php
                    /*
                    <a class="thumbnail pull-left" href="#">
                        <img class="media-object" src="http://critterapp.pagodabox.com/img/user.jpg">
                    </a>
                    */
                    ?>
                    <div class="media-body">
                        <a href="<?php echo base_url() ?>ajax/organizadores_ajax/logout" class="ajax_call btn btn-default pull-right">Cerrar session</a>
                        <?php
                        /* 
                        <h4 class="media-heading">Cuál es tu nombre? <a href="#" class="btn btn-defaul">editar</a></h4>
                        */
                        ?>
                        <p><strong>Nombre: </strong><?php echo $organizador['nombre'] ?></p>
                        <p><strong>Ubicación: </strong><?php echo $organizador['ciudad'] ?>, <?php echo $organizador['provincia'] ?></p>
                        <p><strong>Email: </strong><?php echo $organizador['email'] ?></p>
                        <p><strong>Telefono: </strong><?php echo $organizador['tel'] ?></p>
                        <?php if ($organizador['web'] ) : ?>
                            <p><strong>Web: </strong><?php echo $organizador['web'] ?></p>
                        <?php endif ?>
                		<?php
                        /*
                        <p><span class="label label-info">888 photos</span> <span class="label label-warning">150 followers</span></p>
                        <p>
                            <a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-comment"></span> Message</a>
                            <a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-heart"></span> Favorite</a>
                            <a href="#" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-ban-circle"></span> Unfollow</a>
                        </p>
                        */
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>