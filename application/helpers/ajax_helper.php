<?php

  // ajax json builder
  function ajax_response($data, $do_after)
  {
    $return['data']=$data;
    $return['do_after']=$do_after;
    echo json_encode($return);
  }


  
?>
