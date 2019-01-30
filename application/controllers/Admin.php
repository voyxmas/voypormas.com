<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends My_Controller {

	function __construct()
	{
			parent::__construct();
			$this->load->model('eventos_model');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/scripts/toastr.min.js','foot');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/toastr.min.css','head');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/animate.css','head');

			// get alerts
			$attr['cond']['estado'] = 0; 
			$attr['select'] = 'nombre,creado,evento_id'; 
			$attr['order_by'] = 'creado ASC'; 
			$this->data['notifications'] = $this->eventos_model->get($attr); unset($attr);
			$this->data['notifications_count'] = count($this->data['notifications']);
	}

	public function index()
	{
		// verificar permisos y la sesion para poder continuar
		$this->data['CURRENT_SECTION'] 	= 'admin';
		$this->data['CURRENT_PAGE'] 	= 'login';
		
		// verificar si ya se logeuo y enviarlo a main, si no contiuar
		if(check_session()) redirect(base_url().'admin/main');
		
		bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

		// set title
		$this->layouts->set_title('ADMIN');

		// definir includes en el head del documento
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/css/login.css','head');

		// render data
		$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/login');

	}

	public function main()
	{
		// verificar permisos y la sesion para poder continuar
		$this->eventos('moderar');
	}

	public function eventos ($method = NULL, $evento_id = NULL)
	{
		// cargar modelos para eventos
		$this->load->model('eventos_model');
		$this->load->model('categorias_model');
		$this->load->model('caracteristicas_model');
		$this->load->model('eventos_caracteristicas_model');
		$this->load->model('variantes_eventos_model');
		$this->load->model('variantes_eventos_precios_model');

		// cargar controladores para eventos
		switch ($method) {
			case NULL:
				$this->events_listar();
				break;
			case 'nuevo':
				$this->events_nuevo();
				break;
			case 'ver':
				$this->events_ver($evento_id);
				break;
			case 'editar':
				$this->events_editar($evento_id);
				break;
			case 'eliminar':
				$this->events_eliminar($evento_id);
				break;
			case 'moderar':
				$this->events_moderar();
				break;
		}
	}
	// metodos de eventos
		private function events_listar ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'events_listar';

			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');

			// buscar los eventos y ordenarlos por fecha de publicacion
			$attr['order_by'] = 'creado DESC';
			$attr['group_by'] = 'evento_id'; 
			$attr['page'] = $this->input->get('p');

			$this->data['eventos_nuevos'] = $this->eventos_model->get($attr); unset($attr);
			$this->data['pagination_data'] = array(
				'total_results'=>$this->data['eventos_nuevos'][0]['total_results'], 
				'page'=>$this->input->get('p'),
				'ref_url'=>'admin/eventos'
			);

			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}

		private function events_moderar ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'events_moderar';
	
			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			// buscar los eventos y ordenarlos por fecha de publicacion
			$attr['order_by'] = 'creado DESC';
			$attr['cond']['estado'] = 0;
			$attr['group_by'] = 'evento_id'; 

			$this->data['eventos_nuevos'] = $this->eventos_model->get($attr); unset($attr);

			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}

		private function events_nuevo ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'events_nuevo';
			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);
			
			// definir titulos y crumbs
			$this->layouts->set_title('CARGA TU CARRERA GRATIS!');
			
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/cstm_forms_helpers.js','foot');
			$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/scripts/admin/events_nuevo.js','foot');
			
			// definir el formulario
				$this->data['form']['action'] = base_url().'ajax/eventos_ajax/nuevo';
				$this->data['form']['ajax_call'] = 1;
				// inputs
				$this->data['form']['inputs'][] = array(
					'name' 			=> 'nombre',
					'placeholder' 	=> 'Nombre de la carrera',
					'label' 		=> 'Nombre de la carrera',
					'help'			=> 'Nombre con el que aparece listado el evento',
					'required'		=> TRUE				
				);

				$this->data['form']['inputs'][] = array(
					'label' 		=> 'Imagen',
					'name' 			=> 'image',
					'type' 			=> 'file'		
				);

				$this->data['form']['inputs'][] = array(
					'label' 		=> 'Lugar',
					'name' 			=> 'lugar',
					'id' 			=> 'lugar',
					'type' 			=> 'text',
					'required'		=> TRUE
					
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'numero_casa',
					'type' 			=> 'hidden'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'calle',
					'type' 			=> 'hidden'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'ciudad',
					'type' 			=> 'hidden'
				);
				
				$this->data['form']['inputs'][] = array(
					'name' 			=> 'departamento',
					'type' 			=> 'hidden'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'provincia',
					'type' 			=> 'hidden'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'pais',
					'type' 			=> 'hidden'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'latitud',
					'type' 			=> 'hidden'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'longitud',
					'type' 			=> 'hidden'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'fecha',
					'placeholder' 	=> 'Fecha del evento',
					'label' 		=> 'Fecha del evento',
					'type' 			=> 'date',
					'required'		=> TRUE
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'publicar_desde',
					'label' 		=> 'Publicar desde',
					'type' 			=> 'date',
					'value' 		=> date(SYS_DATE_FORMAT),
					'required'		=> TRUE
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'inscripciones_link',
					'label' 		=> 'Link de inscripción',
					'type' 			=> 'text'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'inscripciones',
					'label' 		=> 'Inscripción',
					'type' 			=> 'textarea'
				);
	
				$this->data['form']['inputs'][] = array(
					'name' 			=> 'inscripciones_fecha_limite',
					'label' 		=> 'Fecha Límite',
					'type' 			=> 'date',
					'value' 		=> date(SYS_DATE_FORMAT)
				);
	
				$this->data['form']['inputs'][] = array(
					'name' 			=> 'inscripciones_cupo',
					'label' 		=> 'Cupo',
					'type' 			=> 'checkbox',
					'value'			=> 0,
					'options'		=> array( 1 => 'Hasta agotar cupo')
				);

				$this->data['form']['inputs'][] = array(
					'label' 		=> 'Información de la carrera',
					'id' 			=> 'price-schedule',
					'title'			=> 'Variantes del evento y respectivos costos de inscripcion',
					'inputtable'	=> array(
						'xy_label'		 => 'Distancia/Fecha' ,
						// header fields for x
						'x' => array(
							array(
								'name' 			=> 'vfecha[]',
								'required'		=> TRUE,
								'placeholder'	=> 'fecha',
								'type' 			=> 'date',
								'value' 		=> date(SYS_DATE_FORMAT),
								'title'			=> 'Fecha apartir de la cual los montos de esta columna tienen vigencia'
							)
						),
						// header fields for y
						'y' => array(
							array(
								'name' 			=> 'vdistancia[]',
								'required'		=> TRUE,
								'placeholder'	=> 'Distancia (Km)',
								'type' 			=> 'number',
								'sufixbox' 		=> 'kms',
								'title'			=> 'Distancia en Km de esta variacion del evento'
							),
							array(
								'name' 			=> 'vhora[]',
								'placeholder'	=> 'Hora de largada',
								'type' 			=> 'time',
								'prefixbox' 	=> 'Hora',
								'title'			=> 'Hora de largada para esta variante del evento'
							),
							array(
								'type'			=> 'button',
								'placeholder'	=> '+ INFO',
								'class'			=> 'btn default btn-sm add-premio',
								'style'			=> 'margin:0 !important',
								'title'			=> 'Agregar premios para este evento',
								'data'			=> array('popup'=>'premios')
							)		
						),
						'values' => array(
							'name' 			=> 'vmonto[]',
							'required'		=> TRUE,
							'placeholder' 	=> 'Monto inscripcion',
							'type' 			=> 'number',
							'prefixbox' 	=> '$',
							'title'			=> 'Costo de inscripción en este periodo',
						)
					)
				);
	
				$this->data['form']['inputs'][] = array(
					'class' 		=> 'premios hide popup portlet light animated',
					'label' 		=> '+ INFO',
					'container'		=> array(
						array(
							'label' 		=> 'Premios',
							'add_one_more' 	=> TRUE,
							'group' 		=> array(
								array(
									'name' 			=> 'premio_descripcion[]',
									'label' 		=> 'Puesto',
									'placeholder' 	=> 'ej: 1ero Caballero General',
									'help'			=> 'Critero por el cual se otorga el premio'
								),
								array(
									'name' 			=> 'premio_monto[]',
									'label' 		=> 'Premio',
									'placeholder' 	=> 'ej: $1000',
									'type' 			=> 'text',
									'help'			=> 'Premio que se entraga'
								)
							)
						),
						array(
							'type'			=> 'hidden',
							'name'			=> 'premios_cnt[]'
						),
						array(
							'label' 		=> 'Lugar de entrega de kit',
							'type'			=> 'text',
							'name'			=> 'kit_lugar[]',
							'class'			=> 'col-sm-6'
						),
						array(
							'label' 		=> 'Hora de entrega de kit',
							'type'			=> 'time',
							'name'			=> 'kit_hora[]',
							'class'			=> 'col-sm-6'
						),
						array(
							'name' 			=> 'vlugar[]',
							'placeholder'	=> 'Lugar de largada',
							'label'			=> 'Lugar de largada',
							'type' 			=> 'text',
							'attr' 			=> array ('maxlength'=>140)
						),
						array(
							'name' 			=> 'vinfo[]',
							'placeholder'	=> 'Elementos',
							'label'	=> 'Elementos',
							'type'			=> 'text',
							'title'			=> 'Requisitos que se deben cumplir para poder participar'
						),
					)
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'evento_tipo_id',
					'label' 		=> 'Tipo de evento',
					'placeholder' 	=> 'Tipo de evento',
					'type' 			=> 'select',
					'options'		=> $this->categorias_model->get_for_input(array('inputgroup'=>'grupo')),
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'caracteristica_id[]',
					'label' 		=> 'Caracteristicas',
					'placeholder' 	=> 'Caracteristicas',
					'type' 			=> 'checkbox',
					'options'		=> $this->caracteristicas_model->get_for_input(),
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'participantes_destacados[]',
					'label' 		=> 'Corredores destacados',
					'add_one_more' 	=> TRUE,
					'placeholder' 	=> 'Participantes destacados'
				);

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'estado',
					'label' 		=> 'Estado',
					'placeholder' 	=> 'Estado',
					'type' 			=> 'radio',
					'options'		=> array( 0 => 'Nuevo', 1 => 'Aprobado', 2 => 'Denegado' ),
				);

			// definir titulos y crumbs
			$this->layouts->set_title($this->data['CURRENT_SECTION'].' '.$this->data['CURRENT_PAGE']);

			// cargar la pagina y pasar los datos al view
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
	
		}

		private function events_ver($evento_id)
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 	= 'events_ver';
	
			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			// load models
			$this->load->model('caracteristicas_model');
			$this->load->model('variantes_eventos_premios_model');
			// cargar includes
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/components.min.css','head');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/cstm_forms_helpers.js','foot');
			$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');

			// cargar el evento
			$this->data['evento'] = $this->eventos_model->get($evento_id)[0];

			// si no se encuentra el evento salir
			if(empty($this->data['evento'])) redirect(base_url().'admin/');

			// hacer formulario general
				$this->data['form_general']['action'] = base_url().'ajax/eventos_ajax/editar/'.$evento_id;
				$this->data['form_general']['ajax_call'] = 1;
				// inputs
				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'nombre',
					'value'			=> $this->data['evento']['nombre'],
					'placeholder' 	=> 'Nombre de la carrera',
					'label' 		=> 'Nombre de la carrera',
					'help'			=> 'Nombre con el que aparece listado el evento'					
				);

				$this->data['form_general']['inputs'][] = array(
					'value'			=> $this->data['evento']['lugar'],
					'name' 			=> 'lugar',
					'id' 			=> 'lugar',
					'type' 			=> 'text',
					'required'		=> TRUE
					
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'numero_casa',
					'value'			=> $this->data['evento']['numero_casa'],
					'type' 			=> 'hidden'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'calle',
					'value'			=> $this->data['evento']['calle'],
					'type' 			=> 'hidden'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'ciudad',
					'value'			=> $this->data['evento']['ciudad'],
					'type' 			=> 'hidden'
				);
				
				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'departamento',
					'value'			=> $this->data['evento']['departamento'],
					'type' 			=> 'hidden'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'provincia',
					'value'			=> $this->data['evento']['provincia'],
					'type' 			=> 'hidden'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'pais',
					'value'			=> $this->data['evento']['pais'],
					'type' 			=> 'hidden'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'latitud',
					'value'			=> $this->data['evento']['latitud'],
					'type' 			=> 'hidden'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'longitud',
					'value'			=> $this->data['evento']['longitud'],
					'type' 			=> 'hidden'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'inscripciones_link',
					'label' 		=> 'Link de inscripción',
					'type' 			=> 'text',
					'value'			=> $this->data['evento']['inscripciones_link'],
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'inscripciones',
					'label' 		=> 'Inscripción',
					'type' 			=> 'textarea',
					'value'			=> $this->data['evento']['inscripciones'],
					'required'		=> TRUE
				);
	
				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'inscripciones_fecha_limite',
					'label' 		=> 'Fecha Límite',
					'type' 			=> 'date',
					'value'			=> cstm_get_date($this->data['evento']['inscripciones_fecha_limite'],SYS_DATE_FORMAT),
					'required'		=> TRUE
				);
	
				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'inscripciones_cupo',
					'label' 		=> 'Cupo',
					'type' 			=> 'checkbox',
					'value'			=> $this->data['evento']['inscripciones_cupo'],
					'options'		=> array( 1 => 'Hasta agotar cupo')
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'fecha',
					'value'			=> $this->data['evento']['fecha'],
					'placeholder' 	=> 'Fecha del evento',
					'label' 		=> 'Fecha del evento',
					'type' 			=> 'date',
					
				);
				
				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'publicar_desde',
					'value'			=> cstm_get_date($this->data['evento']['publicar_desde'],SYS_DATE_FORMAT),
					'label' 		=> 'Publicar desde',
					'type' 			=> 'date'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'evento_tipo_id',
					'value'			=> $this->data['evento']['evento_tipo_id'],
					'label' 		=> 'Tipo de evento',
					'placeholder' 	=> 'Tipo de evento',
					'type' 			=> 'select',
					'options'		=> $this->categorias_model->get_for_input(array('inputgroup'=>'grupo')),
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'participantes_destacados',
					'value'			=> $this->data['evento']['participantes_destacados'],
					'label' 		=> 'Corredores destacados',
					'placeholder' 	=> 'Participantes destacados'
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'estado',
					'value'			=> $this->data['evento']['estado'],
					'label' 		=> 'Estado',
					'placeholder' 	=> 'Estado',
					'type' 			=> 'radio',
					'options'		=> array( 0 => 'Nuevo', 1 => 'Aprobado', 2 => 'Denegado' ),
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'suspendido',
					'value'			=> $this->data['evento']['suspendido'],
					'label' 		=> 'Suspendido',
					'type' 			=> 'radio',
					'options'		=> array( 0 => 'Activo', 1 => 'Suspendido' ),
				);

				$this->data['form_general']['inputs'][] = array(
					'name' 			=> 'reprogramado',
					'value'			=> $this->data['evento']['reprogramado'],
					'label' 		=> 'Reprogramado',
					'type' 			=> 'radio',
					'options'		=> array( 0 => 'Sin cambios', 1 => 'Reprogramado' ),
				);

			// tomar caracteristicas del evento
			$attr['cond']['evento_id'] = $evento_id;
			$attr['results'] = 1000;
			$this->data['evento']['evento_caracteristicas'] = $this->eventos_caracteristicas_model->get($attr); unset($attr);

			// tomar caracteristicas del sistema
			$attr['results'] = 1000;
			$this->data['caracteristicas'] = $this->caracteristicas_model->get($attr);unset($attr);
			
				// lopear para ver que caracteristicas ya estan asignadas
			foreach ($this->data['caracteristicas'] as $key => $caracteristica){
				$this->data['caracteristicas'][$key]['estado'] = $this->searchMultiArray($this->data['evento']['evento_caracteristicas'],'caracteristica_id',$caracteristica['caracteristica_id']);
			}
			
			// tomar variantes
			$attr['cond']['evento_id'] = $evento_id;
			$attr['results'] = 1000;
			$this->data['evento']['evento_variantes'] = $this->variantes_eventos_model->get($attr); unset($attr);

			// tomar precios y premios de las variantes
			foreach ($this->data['evento']['evento_variantes'] as $variante_key => $variante) 
			{
				$attr['cond']['variante_evento_id'] = $variante['variante_evento_id'];
				$attr['results'] = 1000;
				$this->data['evento']['evento_variantes'][$variante_key]['montos'] = $this->variantes_eventos_precios_model->get($attr); unset($attr);

				// tomo los premios
				$query['cond']['variante_evento_id'] = $variante['variante_evento_id'];
				$this->data['evento']['evento_variantes'][$variante_key]['premios'] = $this->variantes_eventos_premios_model->get($query); unset($query);
			}
			
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
			
		}

		private function events_eliminar ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'events_eliminar';
	
			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);
		}
	
	// metodos de organizadores

	public function caracteristicas ($method = NULL, $caracteristica_id = NULL)
	{
		// cargar modelos para eventos
		$this->load->model('caracteristicas_model');

		// cargar controladores para eventos
		switch ($method) {
			case NULL:
				$this->caracteristicas_listar();
				break;
			case 'nuevo':
				$this->caracteristicas_nuevo();
				break;
			case 'editar':
				$this->caracteristicas_editar($caracteristica_id);
				break;
			default:
				show_404();
		}
	}
		// metodos de caracteristicas
		private function caracteristicas_listar ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'caracteristicas_listar';

			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			// buscar los eventos y ordenarlos por fecha de publicacion
			$attr['order_by'] = 'nombre ASC';
			$attr['page'] = $this->input->get('p');

			$this->data['caracteristicas'] = $this->caracteristicas_model->get($attr); unset($attr);
			$this->data['pagination_data'] = array(
				'total_results'=>$this->data['caracteristicas'][0]['total_results'], 
				'page'=>$this->input->get('p'),
				'ref_url'=>'admin/caracteristicas'
			);

			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}

		private function caracteristicas_nuevo ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'caracteristicas_nuevo';
			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);
			
			// definir titulos y crumbs
			$this->layouts->set_title('Cargar un evento nuevo');
			
			// definir el formulario
				$this->data['form']['action'] = base_url().'ajax/caracteristicas_ajax/nuevo';
				$this->data['form']['ajax_call'] = 1;
				// inputs
				$this->data['form']['inputs'][] = array(
					'name' => 'nombre',
					'placeholder' => 'Caracteristica',
					'required' => TRUE,
				);

				$this->data['form']['inputs'][] = array(
					'name' => 'icono',
					'placeholder' => 'Icono',
					'type' => 'file',
					'required' => TRUE
				);

			// cargar la pagina y pasar los datos al view
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');

		}
		
		private function caracteristicas_editar($caracteristica_id = NULL)
		{
			if($caracteristica_id===NULL) return FALSE;

			$this->data['CURRENT_SECTION'] 		= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'caracteristicas_editar';

			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			// tomar registros y agruparlos por segun su grupo para poder hacer los tabs
			$query['cond']['caracteristica_id'] = $caracteristica_id;
			$this->data['caracteristicas'] = $this->caracteristicas_model->get($query)[0]; unset($query);

			// cargar formulario
				$this->data['form']['action'] = base_url().'ajax/caracteristicas_ajax/editar/'.$caracteristica_id;
				$this->data['form']['ajax_call'] = 1;
				// inputs

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'nombre',
					'label' 		=> 'Nombre',
					'type' 			=> 'text',
					'value'			=> $this->data['caracteristicas']['nombre']					
				);			
			// cargo el view
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}
	
	// metodos de tipo de eventos
	public function categorias ($method = NULL, $categoria_id = NULL)
	{
		// cargar modelos para eventos
		$this->load->model('categorias_model');
		$this->load->model('categorias_grupos_model');

		// cargar controladores para eventos
		switch ($method) {
			case NULL:
				$this->categorias_listar();
				break;
			case 'nuevo':
				$this->categorias_nuevo();
				break;
			case 'editar':
				$this->categorias_editar($categoria_id);
				break;
			default:
				show_404();
		}
	}
		// metodos de categorias
		private function categorias_listar ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'categorias_listar';

			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			// buscar los eventos y ordenarlos por fecha de publicacion
			$attr['order_by'] = 'grupo ASC, nombre ASC';
			$attr['page'] = $this->input->get('p');

			$this->data['categorias'] = $this->categorias_model->get($attr); unset($attr);
			$this->data['pagination_data'] = array(
				'total_results'=>$this->data['categorias'][0]['total_results'], 
				'page'=>$this->input->get('p'),
				'ref_url'=>'admin/categorias'
			);

			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}

		private function categorias_nuevo ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'categorias_nuevo';
			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);
			
			// definir titulos y crumbs
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/scripts/fileUpload.js','foot');			
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/fileUpload.css','head');			
			$this->layouts->set_title('Cargar un evento nuevo');
			
			// definir el formulario
				$this->data['form']['action'] = base_url().'ajax/categorias_ajax/nuevo';
				$this->data['form']['ajax_call'] = 1;
				// inputs
				$this->data['form']['inputs'][0]['label'] = 'Nombre';
				$this->data['form']['inputs'][0]['name'] = 'nombre';
				$this->data['form']['inputs'][0]['placeholder'] = 'Nombre de la nueva categoria';
				$this->data['form']['inputs'][0]['required'] = TRUE;

				$this->data['form']['inputs'][1]['label'] 		= 'Grupo';
				$this->data['form']['inputs'][1]['name'] 			= 'evento_tipo_grupo_id';
				$this->data['form']['inputs'][1]['id'] 			= 'evento_tipo_grupo_id';
				$this->data['form']['inputs'][1]['placeholder'] 	= 'Grupo';
				$this->data['form']['inputs'][1]['required'] 		= TRUE;
				$this->data['form']['inputs'][1]['type'] 	   		= 'select';
				$this->data['form']['inputs'][1]['options']		= $this->categorias_grupos_model->get_for_input();

				$this->data['form']['inputs'][2]['name'] 		= 'evento_tipo_grupo_nombre';
				$this->data['form']['inputs'][2]['id'] 			= 'evento_tipo_grupo_nombre';
				$this->data['form']['inputs'][2]['placeholder'] = 'Nombre del nuevo grupo';
				$this->data['form']['inputs'][2]['class'] 		= 'hidden';

			print_r($this->data['form']['inputs'][1]['options']);

			// cargar la pagina y pasar los datos al view
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');

		}

		private function categorias_editar ($evento_tipo_id = NULL)
		{
			if($evento_tipo_id===NULL) return FALSE;

			$this->data['CURRENT_SECTION'] 		= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'categorias_editar';

			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			// tomar registros y agruparlos por segun su grupo para poder hacer los tabs
			$query['cond']['evento_tipo_id'] = $evento_tipo_id;
			$this->data['categorias'] = $this->categorias_model->get($query)[0]; unset($query);

			// cargar formulario
				$this->data['form']['action'] = base_url().'ajax/categorias_ajax/editar/'.$evento_tipo_id;
				$this->data['form']['ajax_call'] = 1;
				// inputs

				$this->data['form']['inputs'][] = array(
					'name' 			=> 'nombre',
					'label' 		=> 'Nombre',
					'type' 			=> 'text',
					'value'			=> $this->data['categorias']['nombre']					
				);

				$this->data['form']['inputs'][] = array(
					'id' 			=> 'evento_tipo_grupo_id',
					'label' 		=> 'Grupo',
					'name' 			=> 'evento_tipo_grupo_id',
					'required' 		=> TRUE,
					'type' 	   		=> 'select',
					'value'   		=> $this->data['categorias']['grupo_id'],
					'options'		=> $this->categorias_grupos_model->get_for_input(array('cond'=>array('evento_tipo_grupo_id !='=>1)))
				);			
			// cargo el view
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}
	
	public function settings ($method = NULL, $categoria_id = NULL)
	{
		// cargar modelos para eventos
		$this->load->model('settings_model');
		$this->load->model('settings_grupos_model');

		// cargar controladores para eventos
		switch ($method) {
			case NULL:
				$this->settings_listar();
				break;
			case 'nuevo':
				$this->settings_nuevo();
				break;
		}
	}
		// metodos de settings
		private function settings_listar ()
		{
			$this->data['CURRENT_SECTION'] 		= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'settings_listar';

			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/cstm_forms_helpers.js','foot');

			// buscar los eventos y ordenarlos por fecha de publicacion
			
			// tomar registros y agruparlos por segun su grupo para poder hacer los tabs
			$query['order_by'] = 'nombre ASC';
			$data['settings'] = $this->settings_model->get($query); unset($query);

			$this->data['settings'] = array();

			if(is_array($data['settings'] ))
			{
				foreach ($data['settings'] as $setting) 
				{
					// definir el key con el nombre del grupo
					$this->data['settings'][$setting['grupo']][] = $setting;
				}
			}

			unset($data['settings']);

			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}

		private function settings_nuevo ()
		{
			$this->data['CURRENT_SECTION'] 	= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'settings_nuevo';
			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);
			
			// definir titulos y crumbs
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/scripts/fileUpload.js','foot');			
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/fileUpload.css','head');			
			$this->layouts->set_title('Cargar un evento nuevo');
			
			// definir el formulario
				$this->data['form']['action'] 					= base_url().'ajax/settings_ajax/nuevo';
				$this->data['form']['ajax_call'] 				= 1;
				// inputs
				$this->data['form']['inputs'][0]['label'] 		= 'Nombre';
				$this->data['form']['inputs'][0]['name'] 		= 'nombre';
				$this->data['form']['inputs'][0]['placeholder'] = 'Nombre de la nueva categoria';
				$this->data['form']['inputs'][0]['required'] 	= TRUE;

				$this->data['form']['inputs'][1]['label'] 		= 'Grupo';
				$this->data['form']['inputs'][1]['name'] 		= 'evento_tipo_grupo_id';
				$this->data['form']['inputs'][1]['placeholder'] = 'Grupo';
				$this->data['form']['inputs'][1]['required'] 	= TRUE;
				$this->data['form']['inputs'][1]['type'] 	   	= 'select';
				$this->data['form']['inputs'][1]['options']		= $this->settings_grupos_model->get_for_input();

				$this->data['form']['inputs'][2]['name'] 		= 'evento_tipo_grupo_nombre';
				$this->data['form']['inputs'][2]['placeholder'] = 'Nombre del nuevo grupo';
				$this->data['form']['inputs'][2]['class'] 		= 'hidden';

			print_r($this->data['form']['inputs'][1]['options']);

			// cargar la pagina y pasar los datos al view
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');

		}
	
	public function organizaciones ($method = NULL, $organizacion_id = NULL)
	{
		// cargar modelos para eventos
		$this->load->model('organizaciones_model');
		$this->load->model('organizadores_model');

		// cargar controladores para eventos
		switch ($method) {
			case NULL:
				$this->organizaciones_listar();
				break;
			case 'nuevo':
				$this->organizaciones_nuevo();
				break;
			case 'editar':
				$this->organizaciones_editar($organizacion_id);
				break;
		}
	}
		// metodos de organizaciones
		private function organizaciones_listar ()
		{
			$this->data['CURRENT_SECTION'] 		= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'organizaciones_listar';

			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			// tomar registros y agruparlos por segun su grupo para poder hacer los tabs
			$query['order_by'] = 'nombre ASC';
			$this->data['organizaciones'] = $this->organizaciones_model->get($query); unset($query);
			$this->data['pagination_data'] = array(
				'total_results'=>$this->data['organizaciones'][0]['total_results'], 
				'page'=>$this->input->get('p'),
				'ref_url'=>'admin/organizaciones'
			);

			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}

		private function organizaciones_editar ($organizacion_id = NULL)
		{
			if($organizacion_id===NULL) return FALSE;

			$this->data['CURRENT_SECTION'] 		= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'organizaciones_editar';

			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

			// tomar registros y agruparlos por segun su grupo para poder hacer los tabs
			$query['cond']['organizacion_id'] = $organizacion_id;
			$this->data['organizacion'] = $this->organizaciones_model->get($query)[0]; unset($query);

			// cargar formulario
				$this->data['form_organizacion']['action'] = base_url().'ajax/organizadores_ajax/editar/'.$organizacion_id;
				$this->data['form_organizacion']['ajax_call'] = 1;
				// inputs
				$this->data['form_organizacion']['inputs'][] = array(
					'name' 			=> 'organizador_id',
					'type' 			=> 'hidden',
					'value'			=> $this->data['organizacion']['organizacion_id']					
				);
				$this->data['form_organizacion']['inputs'][] = array(
					'name' 			=> 'nombre',
					'label' 		=> 'Nombre',
					'type' 			=> 'text',
					'value'			=> $this->data['organizacion']['nombre']					
				);
				$this->data['form_organizacion']['inputs'][] = array(
					'name' 			=> 'inicio_actividades',
					'label' 		=> 'Inicio de actividades',
					'type' 			=> 'date',
					'value'			=> cstm_get_date($this->data['organizacion']['inicio_actividades'],SYS_DATE_FORMAT)				
				);
				$this->data['form_organizacion']['inputs'][] = array(
					'name' 			=> 'provincia',
					'label' 		=> 'Provincia',
					'type' 			=> 'text',
					'value'			=> $this->data['organizacion']['provincia']			
				);
				$this->data['form_organizacion']['inputs'][] = array(
					'name' 			=> 'ciudad',
					'label' 		=> 'Ciudad',
					'type' 			=> 'text',
					'value'			=> $this->data['organizacion']['ciudad']			
				);
				$this->data['form_organizacion']['inputs'][] = array(
					'label' 		=> 'Email',
					'group'			=> array(
						array(
							
							'name' 			=> 'email',
							'value'			=> $this->data['organizacion']['email'],
							'placeholder' 	=> 'Email',
							'type'			=> 'email',
							'required'		=> TRUE),
						array(
							'name' 			=> 'email_public',
							'class'			=> 'label-text-black-regular',
							'type'			=> 'radio',
							'value'			=> $this->data['organizacion']['email_public'],
							'options'		=> array(
								1 => 'Publico',
								0 => 'Privado'
		
							)	
						)			
					)	
				);

				$this->data['form_organizacion']['inputs'][] = array(
					'name' 			=> 'password',
					'label' 		=> 'Contraseña',
					'type' 			=> 'password',
					'value'			=> NULL			
				);
				
				$this->data['form_organizacion']['inputs'][] = array(
					'label' 		=> 'Telefono',
					'group'			=> array(
						array(
							'name' 			=> 'tel',
							'value'			=> $this->data['organizacion']['tel'],
							'placeholder' 	=> 'tel',
							'type'			=> 'text',
							'required'		=> TRUE),
						array(
							'name' 			=> 'tel_public',
							'class'			=> 'label-text-black-regular',
							'type'			=> 'radio',
							'value'			=> $this->data['organizacion']['tel_public'],
							'options'		=> array(
								1 => 'Publico',
								0 => 'Privado'
		
							)	
						)			
					)	
				);
				$this->data['form_organizacion']['inputs'][] = array(
					'name' 			=> 'web',
					'label' 		=> 'Web',
					'type' 			=> 'text',
					'value'			=> $this->data['organizacion']['web']			
				);
				$this->data['form_organizacion']['inputs'][] = array(
					'name' 			=> 'redes_sociales',
					'label' 		=> 'Redes Sociales',
					'type' 			=> 'text',
					'value'			=> $this->data['organizacion']['redes_sociales']			
				);

			// si se encuentra un organizador buscar los representantes
			if (!empty($this->data['organizacion'])) 
			{
				$query['cond']['organizacion_id'] = $this->data['organizacion']['organizacion_id'];
				$this->data['organizacion']['representantes'] = $this->organizadores_model->get($query); unset($query);
				
				if($this->data['organizacion']['representantes']){
					foreach ($this->data['organizacion']['representantes'] as $key => $value) {
						$this->data['representantes_forms'][$key]['action'] = base_url().'ajax/organizadores_ajax/editar_representante/';
						$this->data['representantes_forms'][$key]['ajax_call'] = 1;
						$this->data['representantes_forms'][$key]['class'] = 'card col-sm-12 col-md-6 col-lg-4';
						$this->data['representantes_forms'][$key]['style'] = 'border: 1px solid #EEE;padding: 15px;';
						$this->data['representantes_forms'][$key]['buttons'][] = array(
							'type'			=> 'a',
							'label'			=> 'Eliminar',
							'class'			=> 'btn btn-danger ajax_call',
							'attr'			=> array (
								'href'		=> base_url().'ajax/organizadores_ajax/eliminar_representante/'.$value['organizador_id']
							)
						);
						// inputs
						$this->data['representantes_forms'][$key]['inputs'][] = array(
							'name' 			=> 'organizador_id',
							'type' 			=> 'hidden',
							'value'			=> $value['organizador_id']					
						);
						$this->data['representantes_forms'][$key]['inputs'][] = array(
							'name' 			=> 'nombre',
							'label' 		=> 'Nombre',
							'type' 			=> 'text',
							'value'			=> $value['nombre']					
						);
						$this->data['representantes_forms'][$key]['inputs'][] = array(
							'name' 			=> 'email',
							'label' 		=> 'Email',
							'type' 			=> 'email',
							'value'			=> $value['email']					
						);
						$this->data['representantes_forms'][$key]['inputs'][] = array(
							'name' 			=> 'tel',
							'label' 		=> 'Telefono',
							'type' 			=> 'text',
							'value'			=> $value['tel']					
						);
						$this->data['representantes_forms'][$key]['inputs'][] = array(
							'name' 			=> 'publico',
							'label' 		=> 'Publicar?',
							'type' 			=> 'radio',
							'value' 		=> $value['publico'],
							'options'		=> array( 0 => 'privado', 1 => "Publico")					
						);
					}
				}else{
					$this->data['representantes_forms'] = null;
				}
			}
			// creo el formulario para cargar otro representante
				$this->data['representante_nuevo_forms']['action'] = base_url().'ajax/organizadores_ajax/nuevo_representante/'.$organizacion_id;
				$this->data['representante_nuevo_forms']['ajax_call'] = 1;
				$this->data['representante_nuevo_forms']['class'] = 'col-sm-12';

				// inputs
				$this->data['representante_nuevo_forms']['inputs'][] = array(
					'name' 			=> 'organizacion_id',
					'type' 			=> 'hidden',
					'value'			=> $organizacion_id					
				);
				$this->data['representante_nuevo_forms']['inputs'][] = array(
					'name' 			=> 'nombre',
					'label' 		=> 'Nombre',
					'type' 			=> 'text'				
				);
				$this->data['representante_nuevo_forms']['inputs'][] = array(
					'name' 			=> 'email',
					'label' 		=> 'Email',
					'type' 			=> 'email'					
				);
				$this->data['representante_nuevo_forms']['inputs'][] = array(
					'name' 			=> 'tel',
					'label' 		=> 'Telefono',
					'type' 			=> 'text'					
				);
				$this->data['representante_nuevo_forms']['inputs'][] = array(
					'name' 			=> 'publico',
					'label' 		=> 'Publicar?',
					'type' 			=> 'radio',
					'options'		=> array( 0 => 'privado', 1 => "Publico")					
				);
			
			// cargo el view
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');
		}

		private function organizaciones_nuevo ()
		{
			$this->data['CURRENT_SECTION'] 		= 'admin';
			$this->data['CURRENT_PAGE'] 		= 'organizaciones_nuevo';
			bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);
			
			// definir titulos y crumbs	
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/cstm_forms_helpers.js','foot');	
			$this->layouts->set_title('Cargar un evento nuevo');
			
			// definir el formulario
				$this->data['form']['action'] 					= base_url().'ajax/organizadores_ajax/nuevo_organizador';
				$this->data['form']['ajax_call'] 				= 1;
				// inputs
				$this->data['form']['inputs'][0]['label'] 		= 'Nombre';
				$this->data['form']['inputs'][0]['name'] 		= 'nombre';
				$this->data['form']['inputs'][0]['placeholder'] = 'Nombre del organizador';
				$this->data['form']['inputs'][0]['required'] 	= TRUE;

				$this->data['form']['inputs'][1]['label'] 		= 'Inicio de actividades';
				$this->data['form']['inputs'][1]['name'] 		= 'inicio_actividades';
				$this->data['form']['inputs'][1]['placeholder'] = 'Inicio de actividades';
				$this->data['form']['inputs'][1]['required'] 	= TRUE;
				$this->data['form']['inputs'][1]['type'] 	   	= 'date';

				$this->data['form']['inputs'][2]['label'] 		= 'Provincia';
				$this->data['form']['inputs'][2]['name'] 		= 'provincia';
				$this->data['form']['inputs'][2]['placeholder'] = 'Provincia';
				$this->data['form']['inputs'][2]['required'] 	= TRUE;

				$this->data['form']['inputs'][3]['label'] 		= 'Ciudad';
				$this->data['form']['inputs'][3]['name'] 		= 'ciudad';
				$this->data['form']['inputs'][3]['placeholder'] = 'Ciudad';
				$this->data['form']['inputs'][3]['required'] 	= TRUE;

				$this->data['form']['inputs'][] = array(
					'label'			=> 'Email',
					'group'			=> array(
						array(
							'name' 			=> 'email',
							'placeholder' 	=> 'Email',
							'type'			=> 'email',
							'required'		=> TRUE),
						array(
							'name' 			=> 'email_public',
							'class'			=> 'label-text-black-regular',
							'type'			=> 'radio',
							'options'		=> array(
								1 => 'Publico',
								0 => 'Privado'
		
							)	
						)			
					)	
				);

				$this->data['form']['inputs'][] = array(
					'label'			=> 'Telefono',
					'group'			=> array(
						array(
							'name' 			=> 'tel',
							'placeholder' 	=> 'Teléfono',
							'type'			=> 'text',
							'required'		=> TRUE),
						array(
							'name' 			=> 'tel_public',
							'class'			=> 'label-text-black-regular',
							'type'			=> 'radio',
							'options'		=> array(
								1 => 'Publico',
								0 => 'Privado'
		
							)	
						)			
					)	
				);

				$this->data['form']['inputs'][] = array(
					'label'			=> 'Web',
					'name' 			=> 'web',
					'placeholder' 	=> 'Sitio web',
					'type'			=> 'text',
					'required'		=> TRUE
				);

				$this->data['form']['inputs'][] = array(
					'label'			=> 'Redes_sociales',
					'name' 			=> 'redes_sociales[]',
					'placeholder' 	=> 'Perfiles en rede social',
					'type'			=> 'text',
					'required'		=> TRUE,
					'add_one_more'		=> TRUE
				);

			// cargar la pagina y pasar los datos al view
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'admin/general');

		}
	
			
	private function searchMultiArray($array, $field, $needle)
	{
		foreach($array as $key => $value)
		{
			if ( $value[$field] === $needle )
				return $key;
		}
		return FALSE;
	}
}
?>
