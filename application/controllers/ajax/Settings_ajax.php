<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_ajax extends My_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->input->is_ajax_request()) 
		{
		   exit('No direct script access allowed');
		}
		$this->load->model('settings_model');
	}

	public function save ()
	{
		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'settings_editar';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// get post data					
            // tomar os datos del caracteristica
        $setting_id = $this->input->post('setting_id');
        $save['value'] = $this->input->post('valor');
			// crear el registro
		$return = $this->settings_model->save($save,$setting_id);;

		if(!$return) $e[] = 'No se pudo guardar el cambio';

		$data = array();
		switch ($return) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		  = 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'Setting gaurdado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}
}
?>
