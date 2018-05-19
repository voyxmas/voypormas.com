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
  $config['perm']['admin']['events_moderar']            = TRUE;
  $config['perm']['admin']['events_nuevo']              = TRUE;
  $config['perm']['admin']['events_ver']                = TRUE;
  $config['perm']['admin']['events_editar']             = TRUE;
  $config['perm']['admin']['events_aprobar']            = TRUE;
  $config['perm']['admin']['events_rechazar']           = TRUE;
  $config['perm']['admin']['events_desactivar']         = TRUE;
  $config['perm']['admin']['events_borrar']             = TRUE;
  $config['perm']['admin']['events_agregar_tarifa']     = TRUE;
  $config['perm']['admin']['events_eliminar_tarifa']    = TRUE;
  $config['perm']['admin']['events_add_categoria_a_evento']     = TRUE;
  $config['perm']['admin']['events_editar_variante']    = TRUE;
  $config['perm']['admin']['events_nueva_variante']     = TRUE;
  $config['perm']['admin']['events_eliminar_variante']  = TRUE;
  $config['perm']['admin']['events_editar_premio']      = TRUE;
  $config['perm']['admin']['events_eliminar_premio']    = TRUE;
  $config['perm']['admin']['events_editar_precio']      = TRUE;
  $config['perm']['admin']['events_eliminar_precio']    = TRUE;
  // caracteristicas
  $config['perm']['admin']['caracteristicas_listar']    = TRUE;
  $config['perm']['admin']['caracteristicas_nuevo']     = TRUE;
  $config['perm']['admin']['caracteristicas_editar']    = TRUE;
  $config['perm']['admin']['caracteristicas_borrar']    = TRUE;
  // categorias
  $config['perm']['admin']['categorias_listar']         = TRUE;
  $config['perm']['admin']['categorias_nuevo']          = TRUE;
  $config['perm']['admin']['categorias_editar']         = TRUE;
  $config['perm']['admin']['categorias_eliminar']       = TRUE;
  // categorias
  $config['perm']['admin']['organizaciones_listar']     = TRUE;
  $config['perm']['admin']['organizaciones_nuevo']      = TRUE;
  $config['perm']['admin']['organizaciones_editar']     = TRUE;
  $config['perm']['admin']['organizaciones_borrar']     = TRUE;
  $config['perm']['admin']['organizaciones_aprobar']    = TRUE;
  $config['perm']['admin']['organizaciones_rechazar']   = TRUE;
  $config['perm']['admin']['organizaciones_representantes_editar']  = TRUE;
  $config['perm']['admin']['organizaciones_representantes_nuevo']   = TRUE;
  $config['perm']['admin']['organizaciones_representantes_borrar']  = TRUE;

  // Settings
  $config['perm']['admin']['settings_nuevo']            = TRUE;
  $config['perm']['admin']['settings_listar']           = TRUE;
  $config['perm']['admin']['settings_editar']           = TRUE;
  $config['perm']['admin']['settings_borrar']           = TRUE;
  $config['perm']['admin']['settings_groups_nuevo']     = TRUE;
  $config['perm']['admin']['settings_groups_listar']    = TRUE;
  $config['perm']['admin']['settings_groups_editar']    = TRUE;
  $config['perm']['admin']['settings_groups_borrar']    = TRUE;
  
// app
  // main
$config['perm']['app']['main']                          = TRUE;
  
?>
