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
		$this->load->model('eventos_precios_model');
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
		$save['fecha'] = $this->input->post('fecha');
		$save['hora'] = $this->input->post('hora');
		$save['descripcion'] = $this->input->post('descripcion');
		$save['distancia'] = $this->input->post('distancia');
		$save['publicar_desde'] = $this->input->post('publicar_desde');
		$save['publicar_hasta'] = $this->input->post('publicar_hasta');
		$save['estado'] = $this->input->post('estado');
		$save['lugar'] = $this->input->post('lugar');
		$save['pais'] = $this->input->post('pais');
		$save['provincia'] = $this->input->post('provincia');
		$save['departamento'] = $this->input->post('departamento');
		$save['ciudad'] = $this->input->post('ciudad');
		$save['calle'] = $this->input->post('calle');
		$save['numero_casa'] = $this->input->post('numero_casa');
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
				// si el precio no esta vacio guardarlo  
				if($precio != "")
				$save[] = array('monto'=>$precio, 'evento_id'=>$evento_id, 'desde'=>$precio_desde[$key], 'hasta'=>$precio_hasta[$key]);
			}
			if(isset($save))
				$precios_id = $this->agregar_precios($evento_id, $save); unset($save);
			if(!isset($precios_id) OR !$precios_id) $e[] = 'No se pudieron asignar las tarifas';
		}
 
		$data = array();
		switch ($evento_id) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				// $do_after['redirect'] 		= base_url().'admin/eventos/ver/'.$evento_id;
				// $do_after['action_delay'] = 1000;
				$do_after['toastr'] 			= 'Evento creado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function aprobar($evento_id = NULL)
	{
		if($evento_id === NULL) return FALSE;

		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_aprobar';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
		
		// trato de editar el evento
		$attr['estado'] = 1;
		$respuesta = $this->eventos_model->save($attr, $evento_id);unset($attr);
		if($respuesta == FALSE) $e[] = 'No se pudo aprobar el evento' ;

		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'Evento aprobado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function rechazar($evento_id = NULL)
	{
		if($evento_id === NULL) return FALSE;

		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_aprobar';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
		
		// trato de editar el evento
		$attr['estado'] = 2;
		$respuesta = $this->eventos_model->save($attr, $evento_id);unset($attr);
		if($respuesta == FALSE) $e[] = 'No se pudo rechazar el evento' ;

		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'Evento rechazado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function desactivar($evento_id = NULL)
	{
		if($evento_id === NULL) return FALSE;

		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_aprobar';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
		
		// trato de editar el evento
		$attr['estado'] = 0;
		$respuesta = $this->eventos_model->save($attr, $evento_id);unset($attr);
		if($respuesta == FALSE) $e[] = 'No se pudo desactivar el evento' ;

		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'Evento desactivado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function editar($evento_id = NULL)
	{
		if($evento_id === NULL) return FALSE;

		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_editar';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// get $_POST
		$attr['nombre']				= $this->input->post('nombre');
		$attr['descripcion']		= $this->input->post('descripcion');
		$attr['distancia']			= $this->input->post('distancia');
		$attr['evento_tipo_id']		= $this->input->post('evento_tipo_id');
		$attr['fecha']				= $this->input->post('fecha');
		$attr['publicar_desde'] 	= $this->input->post('publicar_desde');
		$attr['publicar_hasta'] 	= $this->input->post('publicar_hasta');
		$attr['estado']				= $this->input->post('estado');
		
		$respuesta = $this->eventos_model->save($attr, $evento_id);unset($attr);
		if($respuesta == FALSE) $e[] = 'No se pudo editar el evento' ;

		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] 	= 500;
				$do_after['toastr'] 		= 'Evento editado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function add_caracteristica_a_evento($evento_id)
	{
		if($evento_id === NULL) return FALSE;
		
		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_add_categoria_a_evento';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// get $_POST
		$caracteristica_id = $this->input->post('caracteristica_id');
		$attr['evento_id']=$evento_id;
		$attr['caracteristica_id']=$caracteristica_id;
		$check['cond'] = $attr;

		if(!empty($caracteristica_id))
		{
			// ver si la catacteristica ya fue asignada
			$ya_existe = $this->eventos_caracteristicas_model->get($check);
			if(!empty($ya_existe)) 
			{
				$respuesta = FALSE;
				$e[] = 'Esta caracteritica ya esta definida en este evento';
			}
			else
			{
				$respuesta = $this->eventos_caracteristicas_model->save($attr); unset($attr);	
				if(!$respuesta) $e[] = 'No se pudieron asignar las caracteristicas';
			}
		}
		else
		{
			$e[] = 'No se recibieron los datos necesarios';
		}

		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 		= 'Caracteristica agregada';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function agregar_precios($evento_id = NULL, $precios = NULL)
	{
		if($evento_id === NULL) return FALSE;
		// definir si envio los datos desde post o si los paso como un arguemnto del emtodo
		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_agregar_tarifa';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$ajax_response = 0;
		if($precios===NULL)
		{
			$ajax_response = 1;
			$precios[] = array(
				'monto'=>$this->input->post('monto'), 
				'evento_id'=>$evento_id, 
				'desde'=>$this->input->post('desde'), 
				'hasta'=>$this->input->post('hasta')
			);
		}
		elseif(empty($precios))
		{
			return FALSE;
		}

		foreach ($precios as $precio) 
		{
			// si el precio no esta vacio guardarlo  
			if($precio != "")
				$save[] = array('monto'=>$precio['monto'], 'evento_id'=>$evento_id, 'desde'=>$precio['desde'], 'hasta'=>$precio['hasta']);
		}
		$precios_id = $this->eventos_precios_model->save($save); unset($save);
		if(!$precios_id) $e[] = 'No se pudo agregar la tarifa al evento'; 
		// ver si devuelvo boleano o preparao la respuesta ajax
		if ($ajax_response == 0)
		{
			if(!empty($e)) return FALSE; 
			else return TRUE;
		}
		else
		{
			$data = array();
			switch ($evento_id) {
				case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
				
				default:
				$do_after['redirect'] 		= base_url().'admin/eventos/ver/'.$evento_id;
				$do_after['action_delay'] = 1000;
				$do_after['toastr'] 			= 'Tarifa agregada correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
			}

			$this->ajax_response($data,$do_after);
		}
		
	}

	public function eliminar_precio($precio_evento_id)
	{
		if($precio_evento_id === NULL ) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_eliminar_tarifa';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$respuesta = $this->eventos_precios_model->delete($precio_evento_id);

		if(!$respuesta) $e[] = 'No se pudo eliminar la tarifa'.$this->db->last_query();;
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 		= 'Tarifa eliminada correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

}
?>
