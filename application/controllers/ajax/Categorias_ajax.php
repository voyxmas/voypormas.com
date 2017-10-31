<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categorias_ajax extends My_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->input->is_ajax_request()) 
		{
		   exit('No direct script access allowed');
		}
		$this->load->model('categorias_model');
		$this->load->model('categorias_grupos_model');
	}

	public function nuevo ()
	{
		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'categorias_nuevo';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

			// ver si se intenta crear un grupo nuevo de categorias
		$save_evento_tipo_grupo['nombre'] = $this->input->post('evento_tipo_grupo_nombre');
		$save_evento_tipo['evento_tipo_grupo_id'] = $this->input->post('evento_tipo_grupo_id');
		
		if(!empty($save_evento_tipo_grupo['nombre']))
		{
			// creo el grupo y sobreescrivo la referencia del post con el id recien creado
			$save_evento_tipo['evento_tipo_grupo_id'] = $this->categorias_grupos_model->save($save_evento_tipo_grupo);
		}

		if (!$save_evento_tipo['evento_tipo_grupo_id']) $e[] = 'No se pudo crear el grupo para la catergoria y no se creó la categoria';

		$save_evento_tipo['nombre'] = $this->input->post('nombre');
			// crear el registro si se creao o no hace falta creat la categoria
		if(empty($e))
		{
			$categoria_id = $this->categorias_model->save($save_evento_tipo); unset($save_evento_tipo);
			if(!$categoria_id) $e[] = 'No se pudo crear la categoria';
		}
		else
		{
			$categoria_id = FALSE;
		}


		$data = array();
		switch ($categoria_id) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		  = 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'categoria creada';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}


}
?>
