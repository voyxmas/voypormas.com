<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Caracteristicas_ajax extends My_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->input->is_ajax_request()) 
		{
		   exit('No direct script access allowed');
		}
		$this->load->model('caracteristicas_model');
	}

	public function nuevo ()
	{
    // check permission
    $data['CURRENT_SECTION'] 	= 'admin';
    $data['CURRENT_PAGE'] 		= 'caracteristicas_nuevo';
    bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// get post data					
			// tomar os datos del caracteristica
		$save['nombre'] = $this->input->post('nombre');
			// crear el registro
		$caracteristica_id = $this->caracteristicas_model->save($save); unset($save);
		$debug=$this->db->last_query();
		if(!$caracteristica_id) $e[] = 'No se pudo crear la caracteristica';

		$data = array();
		switch ($caracteristica_id) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		  = 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'Caracteristica creada';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}


}
?>
