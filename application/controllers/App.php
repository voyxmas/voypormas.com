<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends My_Controller {

	function __construct()
	{
		parent::__construct();

		// load helpers
		$this->load->helper('z_elements');
		
		// load models
		$this->load->model('categorias_model');
		$this->load->model('categorias_grupos_model');
		$this->load->model('eventos_model');
		$this->load->model('eventos_caracteristicas_model');

		// includes
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/animate.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/app_main.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/todo.min.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/css/admin/events_nuevo.css','head');		
		
		// cargar plugins
		$this->layouts->add_include('https://cdn.jsdelivr.net/momentjs/latest/moment.min.js','foot');
		$this->layouts->add_include('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js','foot');
		$this->layouts->add_include('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/toastr.min.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/scripts/toastr.min.js','foot');
		
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/cstm_forms_helpers.js','foot');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/scripts/app/home.js','foot');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/scripts/admin/events_nuevo.js','foot');
		$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
		$this->layouts->add_include('https://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5a8c6fc1f58deed6','foot', 'js');
		
		

		// get datos del searchbar
		$this->data['categorias'] =$this->categorias_model->get_for_input(array('inputgroup'=>'grupo'));

		// setear usuario para el cliente web, cuando no hay naie logueado
		$session['user']['admin_id'] = 0;
		$this->session->set_userdata($session);
	}

	public function main()
	{
		// verificar permisos y la sesion para poder continuar
		$this->data['CURRENT_SECTION'] = 'app';
		$this->data['CURRENT_PAGE'] = 'soon';
		
		// bouncer($this->data['CURRENT_SECTION'],$this->data['CURRENT_PAGE']);

		// set title
		$this->layouts->set_title('Welcome');
		$this->layouts->set_description('Welcome');

		// definir includes en el head del documento
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/css/login.css','head');

		// render data
		$this->load->view('pages/app/soon');

	}

	public function home()
	{
		$this->data['CURRENT_SECTION'] = 'app';
		$this->data['CURRENT_PAGE'] = 'home';

		$this->layouts->set_title('Welcome');
		$this->layouts->set_description('Welcome');

		// get eventos
		$attr['cond']['estado'] = 1;
		$attr['cond']['publicar_desde <='] = date(SYS_DATETIMEFULL_FORMAT);
		// filtrar por las condiciones
		if($this->input->get('fecha'))
		{
			$date_range = $this->date_range_input_to_db($this->input->get('fecha'));
			
			$attr['cond']['fecha >='] = $date_range[0];
			$attr['cond']['fecha <='] = $date_range[1];
		}
		else
			$attr['cond']['fecha >='] = date(SYS_DATE_FORMAT);
		if($this->input->get('nombre'))
			$attr['cond']['nombre ~'] = $this->input->get('nombre');
		if($this->input->get('evento_tipo_id') != -1 AND !empty($this->input->get('evento_tipo_id')))
			$attr['cond']['evento_tipo_id'] = $this->input->get('evento_tipo_id');
		if($this->input->get('distancia1'))
			$attr['cond']['distancia >='] = $this->input->get('distancia1');
		if($this->input->get('distancia2'))
			$attr['cond']['distancia <='] = $this->input->get('distancia2');
		if($this->input->get('precio1'))
			$attr['cond']['precio >='] = $this->input->get('precio1');
		if($this->input->get('precio2'))
			$attr['cond']['precio <='] = $this->input->get('precio2');
		/* trabajo con las variables de ubicacion */
		if($this->input->get('numero_casa'))
			$attr['cond']['numero_casa'] = $this->input->get('numero_casa');
		if($this->input->get('calle'))
			$attr['cond']['calle'] = $this->input->get('calle');
		if($this->input->get('ciudad'))
			$attr['cond']['ciudad'] = $this->input->get('ciudad');
		if($this->input->get('departamento'))
			$attr['cond']['departamento'] = $this->input->get('departamento');
		if($this->input->get('provincia'))
			$attr['cond']['provincia'] = $this->input->get('provincia');
		if($this->input->get('pais'))
			$attr['cond']['pais'] = $this->input->get('pais');
		// hacer los find in set para las caracteristicas
		if($this->input->get('c'))
			foreach ($this->input->get('c') as $caracteristica_id )
				$attr['cond']['FIND_IN_SET('.$caracteristica_id.',caracteristicas) != 0'] = NULL;
		// procesar order by
		if($this->input->get('order'))
			$attr['order_by'] = $this->input->get('order');
		// paginacion
		if($this->input->get('p'))
			$attr['page'] = $this->input->get('p');
		
		$query = $this->eventos_model->get($attr); unset($attr);
		$this->data['eventos'] = $query ? $query : array() ;

		// set filters holder
		$this->data['filters']['caracteristicas'] = array();

		// tomar las caracterisicas
		if($this->data['eventos'])
		foreach ($this->data['eventos'] as $key => $evento) 
		{
			// get caracteristicas del evento
			$query['cond']['evento_id'] = $evento['evento_id'];
			$query['results'] = 1000;
			$this->data['eventos'][$key]['caracteristicas'] = $this->eventos_caracteristicas_model->get($query); unset($query);
			foreach($this->data['eventos'][$key]['caracteristicas'] as $caracteristica)
			{
				$this->data['filters']['caracteristicas'][$caracteristica['caracteristica_id']]['caracteristica_nombre'] = $caracteristica['caracteristica_nombre'];
				$this->data['filters']['caracteristicas'][$caracteristica['caracteristica_id']]['caracteristica_icono'] = $caracteristica['caracteristica_icono'];
				$this->data['filters']['caracteristicas'][$caracteristica['caracteristica_id']]['caracteristica_id'] = $caracteristica['caracteristica_id'];
				if(isset($this->data['filters']['caracteristicas'][$caracteristica['caracteristica_id']]['count']))
				{
					$this->data['filters']['caracteristicas'][$caracteristica['caracteristica_id']]['count']++;
				}
				else
				{
					$this->data['filters']['caracteristicas'][$caracteristica['caracteristica_id']]['count'] = 1;
				}
			}
		}
		// get maxs and mins
			// price
		$this->data['pricelimits'] = $this->eventos_model->minmax('precio');
				
			// distancia
		$this->data['distancialimits'] = $this->eventos_model->minmax('distancia');
		
		// get number of results to show
		$this->data['count'] = isset($this->data['eventos'][0]['total_results']) ? $this->data['eventos'][0]['total_results'] : 0;

		$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'app/general');
	}

	public function nuevo()
	{
		$this->data['CURRENT_SECTION'] = 'app';
		$this->data['CURRENT_PAGE'] = 'nuevo';

		// load models
		$this->load->model('caracteristicas_model');
		$this->load->model('settings_model');

		$this->layouts->set_title('Welcome');
		$this->layouts->set_description('Welcome');

		// get categorias
		$this->data['categorias'] = $this->categorias_model->get_for_input();

		// definir si el organizador esta logueado
		if(isset($this->session->organizador))
		{
			$this->data['organizador_is_logged_in'] = TRUE;
			$this->data['organizador'] = $this->session->organizador;
			
			// veo si el perfil esta completo
			if (
				!empty($this->data['organizador']['nombre'])
				AND !empty($this->data['organizador']['tel'])
				AND !empty($this->data['organizador']['email'])
			) // compruebo que tenga la informacion necesaria
			{
				$this->data['profile_ok'] = TRUE;
			}
			else
			{
				$this->data['profile_ok'] = FALSE;
				$this->data['form_organizador_details']['action'] = base_url().'ajax/organizadores_ajax/add_profile';
				$this->data['form_organizador_details']['ajax_call'] = 1;
				// inputs
				$this->data['form_organizador_details']['inputs'][] = array(
					'name' 			=> 'organizador_id',
					'type' 			=> 'hidden',
					'value'			=> $this->data['organizador']['organizacion_id']					
				);
				$this->data['form_organizador_details']['inputs'][] = array(
					'name' 			=> 'nombre',
					'placeholder' 	=> 'Nombre de la organización',
					'required'		=> TRUE				
				);
				$this->data['form_organizador_details']['inputs'][] = array(

					'group'		=> 	array(
						array(
							'name' 			=> 'tel',
							'placeholder' 	=> 'Telefono',
							'required'		=> TRUE,
						),
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
				$this->data['form_organizador_details']['inputs'][] = array(
					'group'			=> array(
						array(
							'name' 			=> 'email',
							'value'			=> $this->session->organizador['email'],
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

				$this->data['form_organizador_details']['inputs'][] = array(
					'name' 			=> 'provincia',
					'placeholder' 	=> 'Provincia'
				);
				$this->data['form_organizador_details']['inputs'][] = array(
					'name' 			=> 'ciudad',
					'placeholder' 	=> 'Ciudad'
				);
				$this->data['form_organizador_details']['inputs'][] = array(
					'name' 			=> 'org_web',
					'placeholder' 	=> 'webpage',
					'add_one_more' 	=> TRUE,			
				);
				$this->data['form_organizador_details']['inputs'][] = array(
					'name' 			=> 'inicio_actividades',
					'placeholder' 	=> 'inicio de actividades',
					'type'			=> 'date'	
				);
				$this->data['form_organizador_details']['inputs'][] = array(
					'label' 		=> 'Representantes',
					'add_one_more' 	=> TRUE,
					'group' 		=> array(
						array(
							'name' 			=> 'rep_nombre[]',
							'label'			=> '',
							'placeholder'	=> 'Nombre'
						),
						array(
							'name' 			=> 'rep_tel[]',
							'label'			=> '',
							'placeholder'	=> 'Telefono'
						),
						array(
							'name' 			=> 'rep_email[]',
							'label'			=> '',
							'placeholder'	=> 'Email',
							'type' 			=> 'email'
						),
						array(
							'name' 			=> 'publico_ignore[]',
							'type'			=> 'checkbox',
							'value'			=> 1,
							'class'			=> 'toggle-publico',
							'options'		=> array(1=>'Publico')
						),
						array(
							'name' 			=> 'rep_publico[]',
							'value'			=> 1,
							'type'			=> 'hidden'
						),
					)
				);
				$this->data['form_organizador_details']['inputs'][] = array(
					'name' 			=> 'org_social_link[]',
					'placeholder' 	=> 'Link a red social',
					'label' 		=> 'Perfil de redes sociales (Facebook, Google+, Twitter, etc)',
					'add_one_more' 	=> TRUE,			
				);

			}

		}
		else
		{
			$this->data['organizador_is_logged_in'] = FALSE;
		}

		// get terminos y condiciones
		$this->data['terminos'] = $this->settings_model->get(1)[0]['value'];
		
		// organizador
			// tomar los datos para el login o para crear un organizador nuevo
			$this->data['form_organizador']['action'] = base_url().'ajax/organizadores_ajax/identify';
			$this->data['form_organizador']['ajax_call'] = 1;
			// inputs
			$this->data['form_organizador']['inputs'][] = array(
				'name' 			=> 'email',
				'placeholder' 	=> 'Email',
				'label' 		=> 'Email',
				'class' 		=> 'col-md-6 col-lg-4 no-gutters first',
				'required'		=> TRUE,			
				'help'			=> 'Direccion de correo electrónico a la que tengas acceso para verificar la publicación.'					
			);

			$this->data['form_organizador']['inputs'][] = array(
				'name' 			=> 'password',
				'placeholder' 	=> 'Contraseña',
				'label' 		=> 'Contraseña',
				'type' 			=> 'password',
				'required'		=> TRUE,
				'class' 		=> 'col-md-6 col-lg-4 no-gutters',
				'help'			=> 'Eleje una contraseña nueva si no tiens una cuenta o ingresa una que ya hayas usado en otra publicación'					
			);

			/*
			$this->data['form_organizador']['inputs'][] = array(
				'type'			=> 'a',
				'placeholder'	=> 'Recuperar contraseña',
				'style'			=> 'margin:0 !important',
				'class' 		=> 'col-md-6 col-lg-4 no-gutters last',
				'label' 		=> '¿Olvidaste tu contraseña?',
				'help'			=> 'Si ya te registraste y no recuerdas tu contraseña, puedes recuperarla haciendo click aqui.',
				'attr'			=> array('href'=>base_url().'ajax/organizadores/recuperar'),
				'ajax_call' 	=> 1
			);
			*/

		// definir el formulario del evento
			$this->data['form_evento']['action'] = base_url().'ajax/eventos_ajax/nuevo';
			$this->data['form_evento']['ajax_call'] = 1;
			// inputs
				// si el organizador esta logeado cargo los datos en el form como un hidden field
			if($this->data['organizador_is_logged_in'])
			{
				$this->data['form_evento']['inputs'][] = array(
					'name' 			=> 'organizador_id',
					'type' 			=> 'hidden',
					'value'			=> $this->session->organizador['organizacion_id']					
				);
			}

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'nombre',
				'placeholder' 	=> 'Nombre con el que aparecerá en el listado de resultados',
				'label' 		=> 'Nombre con el que aparecerá en el listado de resultados',
				'required'		=> TRUE,
				'help'			=> 'Nombre con el que aparece listado el evento'					
			);

			$this->data['form_evento']['inputs'][] = array(
				'label' 		=> 'Imagen',
				'name' 			=> 'image',
				'type' 			=> 'file'		
			);

			$this->data['form_evento']['inputs'][] = array(
				'label' 		=> 'Lugar',
				'placeholder'	=> 'lugar',
				'name' 			=> 'lugar',
				'id' 			=> 'lugar',
				'type' 			=> 'text',
				'required'		=> TRUE,
				'class' 		=> 'col-md-4 no-gutters first'				
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'numero_casa',
				'type' 			=> 'hidden'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'calle',
				'type' 			=> 'hidden'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'ciudad',
				'type' 			=> 'hidden'
			);
			
			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'departamento',
				'type' 			=> 'hidden'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'provincia',
				'type' 			=> 'hidden'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'pais',
				'type' 			=> 'hidden'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'latitud',
				'type' 			=> 'hidden'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'longitud',
				'type' 			=> 'hidden'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'fecha',
				'placeholder' 	=> 'Fecha del evento',
				'label' 		=> 'Fecha del evento',
				'type' 			=> 'date',
				'required'		=> TRUE,
				'class' 		=> 'col-md-4 no-gutters'
				
			);
			
			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'publicar_desde',
				'label' 		=> 'Publicar desde',
				'type' 			=> 'date',
				'required'		=> TRUE,
				'value' 		=> date(SYS_DATE_FORMAT),
				'class' 		=> 'col-md-4 no-gutters last'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'inscripciones_link',
				'label' 		=> 'Link de inscripción',
				'type' 			=> 'text'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'inscripciones',
				'label' 		=> 'Instrucciones para la inscripción',
				'type' 			=> 'textarea',
				'class'			=> 'col-sm-4',
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'inscripciones_fecha_limite',
				'label' 		=> 'Fecha Límite',
				'type' 			=> 'date',
				'class'			=> 'col-sm-4'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'inscripciones_cupo',
				'label' 		=> 'Cupo',
				'type' 			=> 'checkbox',
				'class'			=> 'col-sm-4 no-line-break',
				'value'			=> 0,
				'options'		=> array( 1 => 'Hasta agotar cupo')
			);

			$this->data['form_evento']['inputs'][] = array(
				'label' 		=> 'Información de la carrera',
				'class'			=> 'clear-both',
				'id' 			=> 'price-schedule',
				'title'			=> 'Variantes del evento y respectivos costos de inscripcion',
				'inputtable'	=> array(
					'xy_label'		=> 'Distancia/Fecha' ,
					'plus_y_label'	=> "<small>Agregar distancias</small>",
					'plus_x_label'	=> "<small>Agregar periodos de pago</small>",
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
						'title'			=> 'Costo de la incripción para esta variante del evento en esta fecha',
					)
				)
			);

			$this->data['form_evento']['inputs'][] = array(
				'class' 		=> 'premios hide popup portlet light animated',
				'label' 		=> '+ INFO',
				'container'		=> array(
					array(
						'label' 		=> 'Premios',
						'draggable' 	=> TRUE,
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

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'evento_tipo_id',
				'required'		=> TRUE,
				'label' 		=> 'Tipo de evento',
				'placeholder' 	=> 'Tipo de evento',
				'type' 			=> 'select',
				'options'		=> $this->categorias_model->get_for_input(array('inputgroup'=>'grupo')),
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'caracteristica_id[]',
				'label' 		=> 'Caracteristicas',
				'placeholder' 	=> 'Caracteristicas',
				'type' 			=> 'checkbox',
				'options'		=> $this->caracteristicas_model->get_for_input(),
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'participantes_destacados[]',
				'label' 		=> 'Corredores destacados',
				'add_one_more' 	=> TRUE,
				'placeholder' 	=> 'Participantes destacados'
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'terminos',
				'required'		=> TRUE,
				'label' 		=> 'Terminos y condiciones',
				'type'			=> 'checkbox',
				'options'		=> array(1=>'Acepto los terminos')
			);
			
			$this->data['form_evento']['inputs'][] = array(
				'type'			=> 'button',
				'class'			=> 'btn-default',
				'placeholder'	=> 'Leer términos y condiciones',
				'data'			=> array('toggle'=>'modal', 'target'=>'#modal_terminos')
			);
		
		// get maxs and mins
			// price
		$this->data['pricelimits'] = $this->eventos_model->minmax('precio');
				
			// distancia
		$this->data['distancialimits'] = $this->eventos_model->minmax('distancia');

		$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'app/general');
	}

	public function evento($evento_id, $modal = 1)
	{
		$this->data['CURRENT_SECTION'] = 'app';
		$this->data['CURRENT_PAGE'] = 'evento';

		// includes
		$this->layouts->set_title('Welcome');
		$this->layouts->set_description('Welcome');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/scripts/app/evento.js','foot');

		// load models
		$this->load->model('eventos_model');
		$this->load->model('variantes_eventos_model');
		$this->load->model('variantes_eventos_precios_model');
		$this->load->model('variantes_eventos_premios_model');
		$this->load->model('eventos_caracteristicas_model');
		$this->load->model('organizaciones_model');
		$this->load->model('organizadores_model');


		// get categorias
		$this->data['categorias'] = $this->categorias_model->get_for_input();

		// get event
		$this->data['evento'] = $this->eventos_model->get($evento_id)[0];
		$this->data['evento']['inscripciones_con_links'] = change_urls_to_links($this->data['evento']['inscripciones']);

		// get variantes
		$query['cond']['evento_id'] = $this->data['evento']['evento_id'];
		$this->data['evento']['variantes'] = $this->variantes_eventos_model->get($query); unset($query);

		// get precios para cada variante
		foreach ($this->data['evento']['variantes'] as $key => $value) {
			$query['cond']['variante_evento_id'] = $value['variante_evento_id'];
			$this->data['evento']['variantes'][$key]['inscripcion'] = $this->variantes_eventos_precios_model->get($query); unset($query);
		}

		// get premios para cada variante
		foreach ($this->data['evento']['variantes'] as $key => $value) {
			$query['cond']['variante_evento_id'] = $value['variante_evento_id'];
			$this->data['evento']['variantes'][$key]['premios'] = $this->variantes_eventos_premios_model->get($query); unset($query);
		}

		// get caracteristicas
		$query['cond']['evento_id'] = $evento_id;
		$query['results'] = 1000;
		$this->data['evento']['caracteristicas'] = $this->eventos_caracteristicas_model->get($query); unset($query);

		// get organizacion
		$this->data['evento']['organizacion'] = $this->organizaciones_model->get($this->data['evento']['organizador_id']);
		$this->data['evento']['organizacion'][0]['redes_sociales'] = $this->print_redes_socailes($this->data['evento']['organizacion'][0]['redes_sociales']); 

		// get representantes
		$attr['cond']['organizacion_id'] = $this->data['evento']['organizador_id'];
		$this->data['evento']['representantes'] = $this->organizadores_model->get($attr); unset($attr);	
		// definir si el organizador esta logueado
		if(isset($this->session->organizador))
		{
			$this->data['organizador_is_logged_in'] = (bool)$this->session->organizador;
			$this->data['organizador'] = $this->session->organizador;
			$this->data['is_organizador_author_of_this'] = $this->data['organizador'] === $this->data['evento']['organizador_id'] ? TRUE : FALSE;
		}
		else
		{
			$this->data['organizador_is_logged_in'] = FALSE;
		}
		
		// get maxs and mins
			// price
		$this->data['pricelimits'] = $this->eventos_model->minmax('precio');		
			// distancia
		$this->data['distancialimits'] = $this->eventos_model->minmax('distancia');

		// veo se cargo el evento en su pagina o como modal
		if ($modal == 'modal')
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'].'_modal', $this->data, FALSE);
		else
			$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'], $this->data, 'app/general');

	}

	public function organizaciones($seccion = NULL)
	{
		if($seccion === NULL ) return FALSE;

		switch ($seccion) {
			case 'activar':
				/// busco el get de token y lo paso
				$token_del_mail = $this->input->get('token');
				$organizacion_id = $this->input->get('id');
				$this->organizaciones_activar($organizacion_id, $token_del_mail);
				break;
		}
	}

	private function organizaciones_activar($organizacion_id = NULL, $token_del_mail = NULL)
	{
		if($organizacion_id === NULL OR $token_del_mail === NULL) $e[] = "No se enviaron los datos para la activacion";;

		$this->data['CURRENT_SECTION'] 	= 'app';
		$this->data['CURRENT_PAGE'] 	= 'organizacion_confirmar';

		// tomo el token
		$this->load->model('organizaciones_model');
		$organizador = $this->organizaciones_model->get($organizacion_id);
		$token_del_organizador = $organizador ? $organizador[0]['token'] : FALSE;
		if(!isset($e))
		{
			if (md5($token_del_organizador) === $token_del_mail) {
				if(date(SYS_DATETIME_FORMAT) < $organizador[0]['token_vto'] ){
					// save activation status
					if(!$organizador[0]['token_activado']){
						$save['token_activado'] = date(SYS_DATETIME_FORMAT);
						$respuesta = $this->organizaciones_model->save($save,$organizacion_id);
					}else{
						$respuesta = FALSE;
						$e[] = "El token ya fue activado";
					}
				}else{
					$respuesta = FALSE;
					$e[] = "El token expiró";
				}
			} else {
				$respuesta = FALSE;
				$e[] = "El token de activación es incorrecto";
			}
		}

		// get maxs and mins
			// price
			$this->data['pricelimits'] = $this->eventos_model->minmax('precio');
				
			// distancia
		$this->data['distancialimits'] = $this->eventos_model->minmax('distancia');

		$this->data['respuesta'] = $respuesta ? TRUE : FALSE ;
		$this->data['e'] = isset($e) ? $e : NULL;
		$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'app/general');
		
	}

	private function getmaxmin($array, $key = NULL)
	{
		// si defino key creoun array con los elementos de ese campo
		if($key)
		{
			$target = array();
			for($i=0 ; $i < count($array) ; $i++)
				if(isset($array[$i][$key]))
					$target[] = $array[$i][$key];
	
			$return['max'] = count($target) > 0 ? max($target)  : 1;
			$return['min'] = count($target) > 0 ? min($target)  : 0;
		}
		// si no defino key, tomo el array como una lista de valores numericos
		else
		{
			$target = $array;
		}

		$return['max'] = count($target) > 0 ? max($target)  : 1;
		$return['min'] = count($target) > 0 ? min($target)  : 0;

		return $return;
	}

	private function print_redes_socailes($redes_sociales=NULL)
	{
		if($redes_sociales === NULL) return false;
		
		if(!is_array($redes_sociales))
			$redes_sociales = explode(',',$redes_sociales);
		
		
		// check for domain
		$re = '/(https\:\/\/|http\:\/\/)((\w+)\.)?(((\w+)\.(com|net|org)))/';

		foreach ($redes_sociales as $key => $link) 
		{
			$matches;
			preg_match($re, $link, $matches);

			$redes_sociales_return[$key]['link'] 	= $link;
			$redes_sociales_return[$key]['red']		= isset($matches[6]) ? $matches[6] : NULL;

			switch ($redes_sociales_return[$key]['red']) 
			{
				case 'facebook':
					$redes_sociales_return[$key]['icono-class'] = "fa-facebook-square";
					break;
				case 'google':
					$redes_sociales_return[$key]['icono-class'] = "fa-google-plus-square";
					break;
				case 'pinterest':
					$redes_sociales_return[$key]['icono-class'] = "fa-pinterest-square";
					break;
				case 'twitter':
					$redes_sociales_return[$key]['icono-class'] = "fa-twitter-square";
					break;
				case 'linkedin':
					$redes_sociales_return[$key]['icono-class'] = "fa-linkedin-square";
					break;
				case 'youtube':
					$redes_sociales_return[$key]['icono-class'] = "fa-youtube-square";
					break;
				case 'instagram':
					$redes_sociales_return[$key]['icono-class'] = "fa-instagram-square";
					break;
				case 'tumblr':
					$redes_sociales_return[$key]['icono-class'] = "fa-tumblr-square";
					break;
				case 'flickr':
					$redes_sociales_return[$key]['icono-class'] = "fa-flickr-square";
					break;
				default:
					$redes_sociales_return[$key]['icono-class'] = NULL;
					break;
			}
		}

		return $redes_sociales_return;
	}

	private function scape_regex_special_chars($string) 
	{
		$find 		= array('.' ,'/' ,':' ,'-' ,'=' ,'?' ,'_' ,'+', '@', '(', ')');
		$replace 	= array('\.','\/','\:','\-','\=','\?','\_','\+','\@','\(','\)');
		return str_replace($find, $replace, $string);
	}

	private function date_range_input_to_db ($date_range) 
	{
		$date_range = explode(' - ',$date_range);
		
		foreach ($date_range as $key => $date) {
			$date_pieces = explode('/',$date);
			$date_range[$key] = cstm_get_date('20'.$date_pieces[2].'-'.$date_pieces[1].'-'.$date_pieces[0],SYS_DATE_FORMAT);
		}

		return $date_range;
	}

}
?>
