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
		$this->load->model('variantes_eventos_premios_model');
	}

	public function nuevo ()
	{
		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_nuevo';
		// bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// defaults
		$e = array();
		// get post data
			// tomar os datos del evento
		$save['organizador_id'] 			= $this->input->post('organizador_id');
		$save['evento_tipo_id'] 			= $this->input->post('evento_tipo_id');
		$save['nombre'] 					= $this->input->post('nombre');
		$save['fecha'] 						= $this->input->post('fecha');
		$save['descripcion'] 				= $this->input->post('descripcion');
		$save['inscripciones'] 				= $this->input->post('inscripciones');
		$save['publicar_desde'] 			= $this->input->post('publicar_desde');
		$save['lugar'] 						= $this->input->post('lugar');
		$save['pais'] 						= $this->input->post('pais');
		$save['provincia'] 					= $this->input->post('provincia');
		$save['departamento'] 				= $this->input->post('departamento');
		$save['ciudad'] 					= $this->input->post('ciudad');
		$save['calle'] 						= $this->input->post('calle');
		$save['numero_casa'] 				= $this->input->post('numero_casa');
		$save['latitud'] 					= $this->input->post('latitud');
		$save['longitud'] 					= $this->input->post('longitud');
		$save['participantes_destacados'] 	= implode(',',$this->input->post('participantes_destacados'));
		$save['estado'] 					= $this->input->post('estado');

		if(isset($_FILES) AND is_array($_FILES) AND !empty($_FILES))
		{
			$config['upload_path']          = './assets/uploads/';
			$config['allowed_types']        = 'gif|jpg|jpeg|png';
			$config['max_size']             = 800;
			$config['max_width']            = 3024;
			$config['max_height']           = 3024;
			

			foreach ($_FILES as $name => $file) 
			{
				if($file['name']){

					$filename = $this->security->sanitize_filename($file['name']);
					$config['file_name'] = $filename;	
					$this->load->library('upload', $config);
					if ( ! $this->upload->do_upload($name))
					{
						$e[] = $this->upload->display_errors();
					}
					else
					{
						// tomo los datos cargados
						$upload_data = $this->upload->data();

						//resize:

						$config_resize['image_library'] = 'gd2';
						$config_resize['source_image'] = $upload_data['full_path'];
						$config_resize['maintain_ratio'] = TRUE;
						$config_resize['width']     = 200;
						$config_resize['height']   = 200;
				
						$this->load->library('image_lib', $config_resize); 
				
						$this->image_lib->resize();
						// agrego el full path a savepara guardar la referencia a la imagen
						$save['imagen']=$config['upload_path'].$upload_data['file_name'];
					}
				}
			} 
		} 
		// crear el registro
		if(empty($e))
			$evento_id = $this->eventos_model->save($save); unset($save);


		if(!isset($evento_id) OR !$evento_id) $e[] = 'No se pudo crear el evento: ' ;
			
			// asignar caracteristicas
		$caracteristicas = $this->input->post('caracteristica_id');
		if(!empty($caracteristicas) AND $evento_id)
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
		$vhora	 		= $this->input->post('vhora');
		$vfecha 		= $this->input->post('vfecha');
		$vmonto 		= $this->input->post('vmonto');
		$vlugar 		= $this->input->post('vlugar');
		
		if(!empty($vdistancia) AND !empty($vfecha) AND !empty($vmonto) AND $evento_id)
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
				$save_variantes['fechahora'] 	= $vhora[$key_variante]; 
				$save_variantes['lugar_largada']= $vlugar[$key_variante]; 
				
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
					$save_variantes_eventos_precios['info'] = $monto;
					$save_variantes_eventos_precios['fecha'] = $vfecha[$key_fecha];
					$variante_evento_monto_id = $this->variantes_eventos_precios_model->save($save_variantes_eventos_precios); // aca guardo los montos apra cada variante
					
					// comprobar si se cargo bien la info
					if(!$variante_evento_monto_id) 
					{
						$e[] = 'No se pudo asociar una tarifa: '.$this->db->last_query() ;
						continue;
					}
				}

				// asignar los premios a cada variante
				// veo si hay premios asociados a esta variante
				$premios_cnt 		= $this->input->post('premios_cnt');
				$premio_descripcion = $this->input->post('premio_descripcion');
				$premio_monto 		= $this->input->post('premio_monto');

				if(isset($premios_cnt[$key_variante]) AND !empty($premios_cnt[$key_variante]))
				{
					// cuento los premios anteriores para definir el key de la primer premio de esta variante
					$offset = 0;
					for($pre_offset = 0 ; $pre_offset < $key_variante ; $pre_offset++)
					{
						$offset += (int)$premios_cnt[$pre_offset];
					}
					// numero de premios de esta variante
					$lenght = $premios_cnt[$key_variante];

					// extraigo el array de premios para esta variante
					$premios_desc 		= array_slice($premio_descripcion,$offset,$lenght);
					$premios_premios 	= array_slice($premio_monto,$offset,$lenght);
					
					// armo el array para guardar los datos
					foreach ($premios_desc as $key_premios_desc => $premio_desc) 
					{
						// si se pasan datos del premio guardarlos, si no, no
						if(!empty($premios_desc[$key_premios_desc]) AND !empty($premios_premios[$key_premios_desc]) )
						{

							$save_premios_variantes = array(
								'variante_evento_id' => $variante_evento_id,
								'descripcion' => $premios_desc[$key_premios_desc],
								'premio' => $premios_premios[$key_premios_desc]
							);
							// guardo cada premio de esta variante
							$save_premios_variantes_return = $this->variantes_eventos_premios_model->save($save_premios_variantes);
							if(!$save_premios_variantes_return) 
							{
								$e[] = 'No se pudo asociar un premio' ;
								continue;
							}
						}
					}
				}
			}
		}
		else
		{
			$e[] = "Todo evento debe tener al menos una variante y no se enviaron los datos necesarios apra definir una variante para este evento".$this->db->last_query();
		}
 
		$data = array();
		switch (count($e)) {
			case 0:
				if(!check_session())
					$do_after['redirect'] 		= base_url().'app/evento/'.$evento_id;
				else
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
		$attr['evento_tipo_id']		= $this->input->post('evento_tipo_id');
		$attr['fecha']				= $this->input->post('fecha');
		$attr['lugar']				= $this->input->post('lugar');
		$attr['inscripciones']		= $this->input->post('inscripciones');
		$attr['numero_casa']		= $this->input->post('numero_casa');
		$attr['calle']				= $this->input->post('calle');
		$attr['ciudad']				= $this->input->post('ciudad');
		$attr['departamento']		= $this->input->post('departamento');
		$attr['provincia']			= $this->input->post('provincia');
		$attr['pais']				= $this->input->post('pais');
		$attr['latitud']			= $this->input->post('latitud');
		$attr['longitud']			= $this->input->post('longitud');
		$attr['publicar_desde'] 	= $this->input->post('publicar_desde');
		$attr['participantes_destacados'] = $this->input->post('participantes_destacados');
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
				$do_after['toastr'] 		= 'Evento editado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function add_caracteristica($evento_id)
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

	public function eliminar_caracteristica(){
		
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
		if($evento_variante_id === NULL ) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_eliminar_variante';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$respuesta = $this->variantes_eventos_model->delete($evento_variante_id);

		if(!$respuesta) $e[] = 'No se pudo eliminar la variante';
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] 	= 500;
				$do_after['toastr'] 		= 'Variante eliminada correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function editar_variante($evento_variante_id)
	{
		if($evento_variante_id === NULL ) return FALSE;

		
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_editar_variante';

		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$respuesta = $this->variantes_eventos_model->save($this->input->post(), $evento_variante_id);

		if(!$respuesta) $e[] = 'No se pudo editar la variante';
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['toastr'] 		= 'Variante editada correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function editar_premio($premio_id = NULL)
	{
		if($premio_id === NULL) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_editar_premio';

		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$respuesta = $this->variantes_eventos_premios_model->save($this->input->post(), $premio_id);

		if(!$respuesta) $e[] = 'No se pudo editar el premio';
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['toastr'] 		= 'Premio editado correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);

	}
	
	public function nuevo_premio($variante_evento_id = NULL)
	{
		if(!$this->input->post() OR $variante_evento_id === NULL) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_editar_premio';

		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$save['premio'] = $this->input->post('premio');
		$save['descripcion'] = $this->input->post('descripcion');
		$save['variante_evento_id'] = $variante_evento_id;

		$respuesta = $this->variantes_eventos_premios_model->save($save);

		if(!$respuesta) $e[] = 'No se pudo crear el premio';
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e).$this->db->last_query();
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['toastr'] 		= 'Premio editado correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);

	}

	public function eliminar_premio($premio_id = NULL)
	{
		if($premio_id === NULL ) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_eliminar_premio';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$respuesta = $this->variantes_eventos_premios_model->delete($premio_id);

		if(!$respuesta) $e[] = 'No se pudo eliminar la premio';
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] 	= 500;
				$do_after['toastr'] 		= 'Premio eliminada correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	public function editar_precio($precio_id = NULL)
	{
		if($precio_id === NULL) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_editar_precio';

		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$respuesta = $this->variantes_eventos_precios_model->save($this->input->post(), $precio_id);

		if(!$respuesta) $e[] = 'No se pudo editar la inscripcion';
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['toastr'] 		= 'Inripcion editada correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);

	}

	public function nuevo_precio($variante_evento_id = NULL)
	{
		if(!$this->input->post() OR $variante_evento_id === NULL) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_editar_precio';

		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$save['monto'] = $this->input->post('monto');
		$save['fecha'] = $this->input->post('fecha');
		$save['variante_evento_id'] = $variante_evento_id;

		$respuesta = $this->variantes_eventos_precios_model->save($save);

		if(!$respuesta) $e[] = 'No se pudo agregar la fecha de inscripcion';
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e).$this->db->last_query();
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['toastr'] 		= 'Fecha de inscripcion editada correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);

	}

	public function eliminar_precio($precio_id = NULL)
	{
		if($precio_id === NULL ) return FALSE;

		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'events_eliminar_precio';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		$respuesta = $this->variantes_eventos_precios_model->delete($precio_id);

		if(!$respuesta) $e[] = 'No se pudo eliminar la precio';
		
		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] 	= 500;
				$do_after['toastr'] 		= 'Precio eliminada correctamente';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
	}

	

}
?>
