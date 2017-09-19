<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eventos_ajax extends My_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->input->is_ajax_request()) 
		{
		   exit('No direct script access allowed');
		}
		$this->load->model('eventos_model');
		$this->load->model('caracteristicas_model');
		$this->load->model('eventos_caracteristicas_model');
		$this->load->model('precios_eventos_model');
	}

	public function nuevo ()
	{
    // check permission
    $data['CURRENT_SECTION'] 	= 'admin';
    $data['CURRENT_PAGE'] 		= 'events_nuevo';
    bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// get post data					
			// tomar os datos del evento
		$save['organizador_id'] = $this->input->post('organizador_id');
		$save['evento_tipo_id'] = $this->input->post('evento_tipo_id');
		$save['nombre'] = $this->input->post('nombre');
		$save['descripcion'] = $this->input->post('descripcion');
		$save['distancia'] = $this->input->post('distancia');
		$save['publicar_desde'] = $this->input->post('publicar_desde');
		$save['publicar_hasta'] = $this->input->post('publicar_hasta');
		$save['estado'] = $this->input->post('estado');
			// crear el registro
		$evento_id = $this->eventos_model->save($save); unset($save);
		$debug=$this->db->last_query();
		if(!$evento_id) $e[] = 'No se pudo crear el evento';
			
			// asignar caracteristicas
		$caracteristicas = $this->input->post('caracteristica_id');
		if(!empty($caracteristicas))
		{
			foreach ($caracteristicas as $caracteristica) 
			{
				$save[] = array('evento_id'=>$evento_id, 'caracteristica_id'=>$caracteristica);
			}
			$caracteristicas_id = $this->eventos_caracteristicas_model->save($save); unset($save);
			if(!$caracteristicas_id) $e[] = 'No se pudieron asignar las caracteristicas';
		}
			
			// asignar precios
		$precios = $this->input->post('precio');
		$precio_desde = $this->input->post('precio_desde');
		$precio_hasta = $this->input->post('precio_hasta');
		if(!empty($precios))
		{
			foreach ($precios as $key => $precio) 
			{
				$save[] = array('monto'=>$precio, 'evento_id'=>$evento_id, 'desde'=>$precio_desde[$key], 'hasta'=>$precio_hasta[$key]);
			}
			$precios_id = $this->precios_eventos_model->save($save); unset($save);
			if(!$caracteristicas_id) $e[] = 'No se pudieron asignar las tarifas';
		}

		$data = array();
		switch ($evento_id) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['redirect'] 		= base_url().'admin/eventos/ver/'.$evento_id;
				$do_after['action_delay'] = 1000;
				$do_after['toastr'] 			= 'Evento creado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

}
?>
