<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');


// main layout
  $config['layouts']['main']['pre_content'][]='layout/main/header';
  $config['layouts']['main']['pre_content'][]='layout/main/sidebar';
  $config['layouts']['main']['pre_content'][]='layout/main/breadcrumbs';
  // contents goes HRTime\PerformanceCounter
  $config['layouts']['main']['post_content'][]= 'layout/main/footer';

  // login layout
    

?>
