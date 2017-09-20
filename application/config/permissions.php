<?php

// menu lateral
$config['perm']['menu']['events']                       = TRUE;
$config['perm']['menu']['events-manager']               = TRUE;
$config['perm']['menu']['events-new']                   = TRUE;
$config['perm']['menu']['events-edit']                  = TRUE;
$config['perm']['menu']['events-delete']                = TRUE;

// controladores
// admin
$config['perm']['admin']['login']                       = TRUE;
$config['perm']['admin']['main']                        = TRUE;
  // eventos
  $config['perm']['admin']['events_listar']             = TRUE;
  $config['perm']['admin']['events_nuevo']              = TRUE;
  $config['perm']['admin']['events_ver']                = TRUE;
  $config['perm']['admin']['events_editar']             = TRUE;
  $config['perm']['admin']['events_aprobar']            = TRUE;
  $config['perm']['admin']['events_rechazar']           = TRUE;
  $config['perm']['admin']['events_desactivar']         = TRUE;
  $config['perm']['admin']['events_borrar']             = TRUE;
// app
  // main
$config['perm']['app']['main']                          = TRUE;
  
?>
