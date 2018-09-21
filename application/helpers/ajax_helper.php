<?php
/**
 * CodeIgniter Helper extensions
 * @package	CodeIgniter
 * @author	Fracisco Javier Machado
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
  // ajax json builder
  function ajax_response($data, $do_after)
  {
    $return['data']=$data;
    $return['do_after']=$do_after;
    echo json_encode($return);
  }


  
?>
