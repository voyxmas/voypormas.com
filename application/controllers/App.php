<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends My_Controller {

	function __construct()
	{
		parent::__construct();
		
		// load models
		$this->load->model('categorias_model');
		$this->load->model('eventos_model');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/scripts/toastr.min.js','foot');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/toastr.min.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/animate.css','head');

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

		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/app_main.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/todo.min.css','head');
		$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');

		// get categorias
		$this->data['categorias'] = $this->categorias_model->get_for_input();

		// get eventos
		$attr['cond']['estado'] = 1;
		$attr['cond']['publicar_desde <='] = date(SYS_DATETIMEFULL_FORMAT);
		// filtrar por las condiciones
		if($this->input->get('fecha'))
			$attr['cond']['fecha'] = $this->input->get('fecha');
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

		// paginacion
		if($this->input->get('p'))
			$attr['page'] = $this->input->get('p');
		

		$this->data['eventos'] = $this->eventos_model->get($attr); unset($attr);
		
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

		$this->layouts->set_title('Welcome');
		$this->layouts->set_description('Welcome');

		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/app_main.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/todo.min.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/todo.min.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/css/admin/events_nuevo.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/animate.css','head');

		$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/cstm_forms_helpers.js','foot');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');

		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/scripts/admin/events_nuevo.js','foot');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/scripts/app/home.js','foot');

		// get categorias
		$this->data['categorias'] = $this->categorias_model->get_for_input();

		// definir si el organizador esta logueado
		if(isset($this->session->organizador))
		{
			$this->data['organizador_is_logged_in'] = (bool)$this->session->organizador;
			$this->data['organizador'] = $this->session->organizador;
		}
		else
		{
			$this->data['organizador_is_logged_in'] = FALSE;
		}
		
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
			'help'			=> 'Direccion de correo electrónico a la que tengas acceso para verificar la publicación.'					
		);

		$this->data['form_organizador']['inputs'][] = array(
			'name' 			=> 'password',
			'placeholder' 	=> 'Contraseña',
			'label' 		=> 'Contraseña',
			'class' 		=> 'col-md-6 col-lg-4 no-gutters',
			'help'			=> 'Eleje una contraseña nueva si no tiens una cuenta o ingresa una que ya hayas usado en otra publicación'					
		);

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
					'value'			=> $this->data['organizador']['organizador_id']					
				);
			}
				//defino el estado del evento como borraor
			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'estado',
				'type' 			=> 'hidden',
				'value'			=> 0					
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'nombre',
				'placeholder' 	=> 'Nombre de la carrera',
				'label' 		=> 'Nombre de la carrera',
				'help'			=> 'Nombre con el que aparece listado el evento'					
			);

			$this->data['form_evento']['inputs'][] = array(
				'label' 		=> 'Lugar',
				'name' 			=> 'lugar',
				'type' 			=> 'text',
				'class' 		=> 'col-md-6 col-lg-4 no-gutters first'				
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
				'name' 			=> 'fecha',
				'placeholder' 	=> 'Fecha del evento',
				'label' 		=> 'Fecha del evento',
				'type' 			=> 'date',
				'class' 		=> 'col-md-6 col-lg-4 no-gutters'
				
			);
			
			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'publicar_desde',
				'label' 		=> 'Publicar desde',
				'type' 			=> 'date',
				'value' 		=> date(SYS_DATE_FORMAT),
				'class' 		=> 'col-md-6 col-lg-4 no-gutters last'
			);


			$this->data['form_evento']['inputs'][] = array(
				'label' 		=> 'Información de la carrera',
				'id' 			=> 'price-schedule',
				'help'			=> 'Variantes del evento y respectivos costos de inscripcion',
				'inputtable'	=> array(
					'xy_label'		 => 'Distancia/Fecha' ,
					// header fields for x
					'x' => array(
						array(
							'name' 			=> 'vfecha[]',
							'placeholder'	=> 'fecha',
							'type' 			=> 'date',
							'value' 		=> date(SYS_DATE_FORMAT),
							'help'			=> 'Fecha apartir de la cual los montos de esta columna tienen vigencia'
						)
					),
					// header fields for y
					'y' => array(
						array(
							'name' 			=> 'vdistancia[]',
							'placeholder'	=> 'Distancia (Km)',
							'type' 			=> 'number',
							'sufixbox' 		=> 'kms',
							'help'			=> 'Distancia en Km de esta variacion del evento'
						),
						array(
							'name' 			=> 'vhora[]',
							'placeholder'	=> 'Hora de largada',
							'type' 			=> 'time',
							'prefixbox' 		=> 'Hora',
							'help'			=> 'Hora de largada para esta variante del evento'
						),
						array(
							'name' 			=> 'vinfo[]',
							'placeholder'	=> 'Elementos',
							'type'			=> 'textarea',
							'help'			=> 'Requisitos que se deben cumplir para poder participar'
						),
						array(
							'type'			=> 'button',
							'placeholder'	=> 'Premios',
							'class'			=> 'btn default btn-sm add-premio',
							'style'			=> 'margin:0 !important',
							'help'			=> 'Agregar premios para este evento',
							'data'			=> array('popup'=>'premios')
						)		
					),
					'values' => array(
						'name' 			=> 'vmonto[]',
						'placeholder' 	=> 'Monto inscripcion',
						'type' 			=> 'number',
						'prefixbox' 	=> '$',
						'help'			=> 'Costo de la incripción para esta variante del evento en esta fecha',
					)
				)
			);

			$this->data['form_evento']['inputs'][] = array(
				'label' 		=> 'Premios',
				'class' 		=> 'premios hide popup portlet light animated',
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
					),
				)
			);

			$this->data['form_evento']['inputs'][] = array(
				'name' 			=> 'evento_tipo_id',
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
		
		// get maxs and mins
			// price
		$this->data['pricelimits'] = $this->eventos_model->minmax('precio');
				
			// distancia
		$this->data['distancialimits'] = $this->eventos_model->minmax('distancia');

		$this->layouts->view($this->data['CURRENT_SECTION'].'/'.$this->data['CURRENT_PAGE'],$this->data,'app/general');
	}

	public function evento()
	{
		$this->data['CURRENT_SECTION'] = 'app';
		$this->data['CURRENT_PAGE'] = 'evento';

		// load models
		$this->load->model('eventos_model');

		$this->layouts->set_title('Welcome');
		$this->layouts->set_description('Welcome');

		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/app_main.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/todo.min.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/todo.min.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/css/admin/events_nuevo.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/animate.css','head');

		$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/cstm_forms_helpers.js','foot');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');

		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/scripts/admin/events_nuevo.js','foot');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/scripts/app/home.js','foot');

		// get categorias
		$this->data['categorias'] = $this->categorias_model->get_for_input();

		// get event
		$evento_id = $this->input->get('id');
		$evento = $this->eventos_model->get($evento_id)[0];

		// definir si el organizador esta logueado
		if(isset($this->session->organizador))
		{
			$this->data['organizador_is_logged_in'] = (bool)$this->session->organizador;
			$this->data['organizador'] = $this->session->organizador;
			$this->data['is_organizador_author_of_this'] = $this->data['organizador'] === $evento['organizador_id'] ? TRUE : FALSE;
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

}
?>
