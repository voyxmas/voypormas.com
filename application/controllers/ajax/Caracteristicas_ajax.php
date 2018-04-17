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
		$this->load->model('eventos_caracteristicas_model');
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

		foreach ($_FILES as $name => $file) {
			
			$filename = $this->security->sanitize_filename($file['name']);

			$config['file_name'] = $filename;
			
			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload($name))
			{
				$e[] = array('error' => $this->upload->display_errors());
			}
			else
			{
				// tomo los datos cargados
				$upload_data = $this->upload->data();
				// agrego el full path a savepara guardar la referencia a la imagen
				$save['icono']=$config['upload_path'].$upload_data['file_name'];
			}
		}             
			// crear el registro
		$caracteristica_id = $this->caracteristicas_model->save($save); unset($save);

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

	public function asignar($evento_id = NULL, $caracteristica_id = NULL)
	{
		$e = array();
		if( $caracteristica_id === NULL OR $evento_id === NULL) 
			$e[] = 'No se recivió la información necesaria para generar este registr';
		
		if(empty($e))
		{
			// asigno la caracteristica al evento
			$query['evento_id'] 			= $evento_id;
			$query['caracteristica_id'] 	= $caracteristica_id;

			if(!$this->eventos_caracteristicas_model->save($query))
				$e[]='No se pudo asignar la caracteritica porque falló el comando save';
		}

		$data = array();
		if(!empty($e)) {
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 		= 'error';
		}else{

				$do_after['reload'] 		  	= 1;
				$do_after['action_delay'] 		= 500;
				$do_after['toastr'] 			= 'Caracteristica asignada';
				$do_after['toastr_type'] 		= 'success';
		}
		$this->ajax_response($data,$do_after);
	}

	public function eliminar($evento_id = NULL, $caracteristica_id = NULL)
	{
		$e = array();
		if( $caracteristica_id === NULL OR $evento_id === NULL) 
			$e[] = 'No se recivió la información necesaria para eliminar este registro';
		
		if(empty($e))
		{
			// asigno la caracteristica al evento
			$query['evento_id'] 			= $evento_id;
			$query['caracteristica_id'] 	= $caracteristica_id;

			if(!$this->eventos_caracteristicas_model->delete($query))
				$e[]='No se pudo asignar la caracteritica porque falló el comando save';
		}

		$data = array();
		if(!empty($e)) {
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 		= 'error';
		}else{

				$do_after['reload'] 		  	= 1;
				$do_after['action_delay'] 		= 500;
				$do_after['toastr'] 			= 'Caracteristica asignada';
				$do_after['toastr_type'] 		= 'success';
		}
		$this->ajax_response($data,$do_after);
	}

}
?>
