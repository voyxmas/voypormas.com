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
		$this->load->model('variantes_eventos_model');
		$this->load->model('variantes_eventos_precios_model');
	}

	public function nuevo ()
	{
		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_nuevo';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// defaults
		$e = array();
		// get post data					
			// tomar os datos del evento
		$save['organizador_id'] = $this->input->post('organizador_id');
		$save['evento_tipo_id'] = $this->input->post('evento_tipo_id');
		$save['nombre'] = $this->input->post('nombre');
		$save['fecha'] = $this->input->post('fecha');
		$save['hora'] = $this->input->post('hora');
		$save['descripcion'] = $this->input->post('descripcion');
		$save['publicar_desde'] = $this->input->post('publicar_desde');
		$save['lugar'] = $this->input->post('lugar');
		$save['pais'] = $this->input->post('pais');
		$save['provincia'] = $this->input->post('provincia');
		$save['departamento'] = $this->input->post('departamento');
		$save['ciudad'] = $this->input->post('ciudad');
		$save['calle'] = $this->input->post('calle');
		$save['numero_casa'] = $this->input->post('numero_casa');
		$save['estado'] = $this->input->post('estado');
			// crear el registro
		$evento_id = $this->eventos_model->save($save); unset($save);

		if(!$evento_id) $e[] = 'No se pudo crear el evento: ' ;
			
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
			
		// asignar detalles de las variantes
		$vdistancia 	= $this->input->post('vdistancia');
		$vinfo 			= $this->input->post('vinfo');
		$vpremio 		= $this->input->post('vpremio');
		$vfecha 		= $this->input->post('vfecha');
		$vmonto 		= $this->input->post('vmonto');

		if(!empty($vdistancia) AND !empty($vfecha) AND !empty($vmonto))
		{
			// si el precio no esta vacio guardarlo 
				// tengo que hacer un array_chunk del array de los montos, los grupos tendran el tamaño de count(montos)/count(variantes) el key resultante se corresponde con el key de vinfo, vpremio y vfecha
			$vmonto_split = array_chunk($vmonto,count($vfecha));

			// loppeo y creo las variantes (filas)
			foreach ($vdistancia as $key_variante => $variante_distancia) 
			{
				$save_variantes['evento_id'] 	= $evento_id; 
				$save_variantes['distancia'] 	= $vdistancia[$key_variante]; 
				$save_variantes['info'] 		= $vinfo[$key_variante]; 
				
				$variante_evento_id = $this->variantes_eventos_model->save($save_variantes); // aca guardo la variante

				// comprobar si se cargo bien la info
				if(!$variante_evento_id) 
				{
					$e[] = 'No se pudo asociar una variante al evento '.$this->db->last_query() ;
					continue;
				}
				
				
				// asocio a cada variante los montos definidos por las columnas de las fechas
				// el key de los fields se corresponde con el key1 del array de vmonto_split (fila)
				// cada elemento en cada array de vmonto_split tiene un key2 que se corresponde con el key de la fecha (columna), que es el monto para esa variante para esa fecha
				
				$save_variantes_eventos_precios['variante_evento_id'] = $variante_evento_id;
				
				// verifico que pueda seguir
				if(!isset($vmonto_split[$key_variante]) OR !is_array($vmonto_split[$key_variante]))
				{
					$e[] = 'El split de los montos no resulto en un array como era esperado o no esta seteado';
					continue;
				}
				// loppeo por cada precio para esta variante
				foreach ($vmonto_split[$key_variante] as $key_fecha => $monto) 
				{
					$save_variantes_eventos_precios['variante_evento_id'] = $variante_evento_id;
					$save_variantes_eventos_precios['monto'] = $monto;
					$save_variantes_eventos_precios['fecha'] = $vfecha[$key_fecha];
					$variante_evento_monto_id = $this->variantes_eventos_precios_model->save($save_variantes_eventos_precios); // aca guardo los montos apra cada variante
					
					// comprobar si se cargo bien la info
					if(!$variante_evento_monto_id) 
					{
						$e[] = 'No se pudo asociar una tarifa: '.$this->db->last_query() ;
						continue;
					}
				}
			}
		}
		else
		{
			$e[] = "Todo evento debe tener al menos una variante y no se enviaron los datos necesarios apra definir una variante para este evento";
		}
 
		$data = array();
		switch (count($e)) {
			case 0:
				$do_after['redirect'] 		= base_url().'admin/eventos/ver/'.$evento_id;
				$do_after['action_delay'] 	= 1000;
				$do_after['toastr'] 		= 'Evento creado';
				$do_after['toastr_type'] 	= 'success';
				break;
			
			default:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
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

	public function agregar_variante($evento_id = NULL, $variante = NULL)
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

	public function eliminar_variante($evento_variante_id)
	{
		if($precio_evento_id === NULL ) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_eliminar_tarifa';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

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
