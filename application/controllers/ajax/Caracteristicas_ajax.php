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
			// tratar el icono
		$config['upload_path']          = './assets/uploads/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;

		$this->load->library('upload', $config);

		foreach ($_FILES as $name => $file) {
			# code...
			
			if ( ! $this->upload->do_upload($name))
			{
				$e[] = array('error' => $this->upload->display_errors());
			}
			else
			{
				// tomo los datos cargados
				$upload_data = $this->upload->data();
				print_r($upload_data);
				// agrego el full path a savepara guardar la referencia a la imagen
				$save['icono']=$upload_data['full_path'];
			}
		}             
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
				//$do_after['reload'] 		  = 1;
				//$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'Caracteristica creada';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}


}
?>
