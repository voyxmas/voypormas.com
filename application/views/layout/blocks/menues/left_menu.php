<!-- BEGIN SIDEBAR MENU -->
<!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
<!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
<!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
<!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
<!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
<!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->

  <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->

  <?php
  $menu['resumen']['txt']   = 'Resumen';
  // $menu['resumen']['icon']  = 'fa fa-cubes';
    //$menu['accounts']['sub']['manage']['txt']    = 'Manage';

  $menu['turnos']['txt']   = 'Turnos';
    $menu['turnos']['sub']['nuevo']['txt']    = 'Nuevo';
    $menu['turnos']['sub']['administrar']['txt']    = 'Administrar';

  $menu['pacientes']['txt']   = 'Pacientes';
    $menu['pacientes']['sub']['nuevo']['txt']    = 'Nuevo';
    $menu['pacientes']['sub']['administrar']['txt']    = 'Administrar';
    $menu['pacientes']['sub']['data_entry']['txt'] = 'Data Enrty';
 
  $menu['cirugias']['txt']   = 'Cirugias';
    $menu['cirugias']['sub']['nuevo']['txt']    = 'Nuevo';
    $menu['cirugias']['sub']['administrar']['txt']    = 'Administrar';

  $menu['cirigias']['txt']   = 'Cirigias';
    $menu['cirigias']['sub']['nuevo']['txt']    = 'Nuevo';
    $menu['cirigias']['sub']['administrar']['txt']    = 'Administrar';

  $menu['estadisticas']['txt']   = 'estadisticas';

  $menu['mensajeria']['txt']   = 'Mensajeria';

  $menu['usuarios']['txt']   = 'Usuarios';
    $menu['usuarios']['sub']['nuevo']['txt']    = 'Nuevo';

  print_menu ($menu, $CURRENT_SECTION, $CURRENT_PAGE);

  ?>
