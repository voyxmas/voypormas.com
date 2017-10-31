<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends My_Controller {

	function __construct()
	{
			parent::__construct();
			$this->load->model('eventos_model');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/scripts/toastr.min.js','foot');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/toastr.min.css','head');
	}

	public function index()
	{
		// verificar permisos y la sesion para poder continuar
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'login';
		
		// verificar si ya se logeuo y enviarlo a main, si no contiuar
		if(check_session()) redirect(base_url().'admin/main');
		
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// set title
		$this->layouts->set_title('ADMIN');

		// definir includes en el head del documento
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/css/login.css','head');

		// render data
		$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/login');

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
		$this->load->model('eventos_precios_model');

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
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_listar';

			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

			$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');

			// buscar los eventos y ordenarlos por fecha de publicacion
			$attr['order_by'] = 'creado DESC';
			$attr['page'] = $this->input->get('p');

			$data['eventos_nuevos'] = $this->eventos_model->get($attr); unset($attr);

			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');
		}

		private function events_moderar ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_moderar';
	
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

			// buscar los eventos y ordenarlos por fecha de publicacion
			$attr['order_by'] = 'creado DESC';
			$attr['cond']['estado'] = 0;

			$data['eventos_nuevos'] = $this->eventos_model->get($attr); unset($attr);

			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');
		}

		private function events_nuevo ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_nuevo';
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
			
			// definir titulos y crumbs
			$this->layouts->set_title('Cargar un evento nuevo');
			
			$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/cstm_forms_helpers.js','foot');
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');
			
			// definir el formulario
				$data['form']['action'] = base_url().'ajax/eventos_ajax/nuevo';
				$data['form']['ajax_call'] = 1;
				// inputs
				$data['form']['inputs'][] = array(
					'name' 			=> 'nombre',
					'placeholder' 	=> 'Titulo',
					'label' 		=> 'Titulo',
					
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'descripcion',
					'placeholder' 	=> 'Descripcion',
					'label' 		=> 'Descripcion',
					'type' 			=> 'textarea',
					
				);

				$data['form']['inputs'][] = array(
					'label' 		=> 'Lugar',
					'name' 			=> 'lugar',
					'type' 			=> 'text',
					
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'numero_casa',
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'calle',
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'ciudad',
					'type' 			=> 'hidden'
				);
				
				$data['form']['inputs'][] = array(
					'name' 			=> 'departamento',
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'provincia',
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'pais',
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'fecha',
					'placeholder' 	=> 'Fecha del evento',
					'label' 		=> 'Fecha del evento',
					'type' 			=> 'date',
					
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'hora',
					'placeholder' 	=> 'Hora del evento',
					'label' 		=> 'Hora del evento',
					'type' 			=> 'time',
					
				);
				
				$data['form']['inputs'][] = array(
					'name' 			=> 'publicar_desde',
					'label' 		=> 'Publicar desde',
					'type' 			=> 'date'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'publicar_hasta',
					'label' 		=> 'Publicar hasta',
					'type' 			=> 'date'
				);

				$data['form']['inputs'][] = array(
					'label' 		=> 'Tabla Precios',
					'id' 			=> 'price-schedule',
					'inputtable'	=> array(
						'xy_label'		 => 'Distancia/Fecha' ,
						// header fields for x
						'x' => array(
							array(
								'name' 			=> 'fecha[]',
								'placeholder'	=> 'fecha',
								'type' 			=> 'date',
								'required' 		=> TRUE
							)
						),
						// header fields for y
						'y' => array(
							array(
								'name' 			=> 'distancia[]',
								'placeholder'	=> 'Distancia (Km)',
								'type' 			=> 'number',
								'sufixbox' 		=> 'kms',
								'required' 		=> TRUE
							),
							array(
								'name' 			=> 'info[]',
								'placeholder'	=> 'Requisitos',
								'type'			=> 'textarea',
								'required' 		=> TRUE
							)
						),
						'values' => array(
							'name' 			=> 'monto[]',
							'placeholder' 	=> '$ Monto',
							'type' 			=> 'number',
							'prefixbox' 	=> '$',
							'required' 		=> TRUE
						)
					)
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'evento_tipo_id',
					'label' 		=> 'Tipo de evento',
					'placeholder' 	=> 'Tipo de evento',
					'type' 			=> 'select',
					'options'		=> $this->categorias_model->get_for_input(array('inputgroup'=>'grupo')),
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'caracteristica_id[]',
					'label' 		=> 'Caracteristicas',
					'placeholder' 	=> 'Caracteristicas',
					'type' 			=> 'checkbox',
					'options'		=> $this->caracteristicas_model->get_for_input(),
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'corredor[]',
					'label' 		=> 'Corredores',
					'placeholder' 	=> 'Nombre del corredor destacado',
					'add_one_more'	=> TRUE
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'estado',
					'label' 		=> 'Estado',
					'placeholder' 	=> 'Estado',
					'type' 			=> 'radio',
					'options'		=> array( 0 => 'Nuevo', 1 => 'Aprobado', 2 => 'Denegado' ),
				);

			// definir titulos y crumbs
			$this->layouts->set_title($data['CURRENT_SECTION'].' '.$data['CURRENT_PAGE']);

			// cargar la pagina y pasar los datos al view
			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');
	
		}

		private function events_ver($evento_id)
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_ver';
	
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

			// cargar includes
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/components.min.css','head');

			// cargar el evento
			$data['evento'] = $this->eventos_model->get($evento_id)[0];

			if(empty($data['evento'])) redirect(base_url().'admin/');

			// definir el formulario de edicion
				$data['form']['action'] = base_url().'ajax/eventos_ajax/editar/'.$evento_id;
				$data['form']['ajax_call'] = 1;
				// inputs
				// inputs
				$data['form']['inputs'][] = array(
					'name' 			=> 'descripcion',
					'value' 		=> $data['evento']['descripcion'],
					'placeholder' 	=> 'Descripcion',
					'label' 		=> 'Descripcion',
					'type' 			=> 'textarea',
					'required' 		=> TRUE,
				);

				$data['form']['inputs'][] = array(
					'class' 		=> 'col-sm-12',
					'name' 			=> 'lugar',
					'value' 		=> $data['evento']['lugar'],
					'type' 			=> 'text',
					'required' 		=> TRUE,
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'numero_casa',
					'value' 		=> $data['evento']['numero_casa'],
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'calle',
					'value' 		=> $data['evento']['calle'],
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'ciudad',
					'value' 		=> $data['evento']['ciudad'],
					'type' 			=> 'hidden'
				);
				
				$data['form']['inputs'][] = array(
					'name' 			=> 'departamento',
					'value' 		=> $data['evento']['departamento'],
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'provincia',
					'value' 		=> $data['evento']['provincia'],
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'pais',
					'value' 		=> $data['evento']['pais'],
					'type' 			=> 'hidden'
				);

				$data['form']['inputs'][] = array(
					'class' 		=> 'col-sm-12',
					'name' 			=> 'distancia',
					'value' 		=> $data['evento']['distancia'],
					'placeholder' 	=> 'Km',
					'label' 		=> 'Distancia',
					'type' 			=> 'number',
					'required' 		=> TRUE,
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'fecha',
					'value' 		=> $data['evento']['fecha'],
					'placeholder' 	=> 'Fecha del evento',
					'label' 		=> 'Fecha del evento',
					'type' 			=> 'date',
					'required' 		=> TRUE,
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'hora',
					'value' 		=> $data['evento']['hora'],
					'placeholder' 	=> 'Hora del evento',
					'label' 		=> 'Hora del evento',
					'type' 			=> 'time',
					'required' 		=> TRUE,
				);
				
				$data['form']['inputs'][] = array(
					'name' 			=> 'publicar_desde',
					'value' 		=> date(SYS_DATE_FORMAT,strtotime($data['evento']['publicar_desde'])),
					'label' 		=> 'Publicar desde',
					'type' 			=> 'date',
					'required' 		=> TRUE,
				);

				$data['form']['inputs'][] = array(
					'name' 			=> 'publicar_hasta',
					'value' 		=> date(SYS_DATE_FORMAT,strtotime($data['evento']['publicar_hasta'])),
					'label' 		=> 'Publicar hasta',
					'type' 			=> 'date',
					'required' 		=> TRUE,
				);

				$data['form']['inputs'][] = array(
					'class' 		=> 'col-sm-12',
					'name' 			=> 'estado',
					'label' 		=> 'Estado',
					'placeholder' 	=> 'Estado',
					'type' 			=> 'radio',
					'options'		=> array( 0 => 'Nuevo', 1 => 'Aprobado', 2 => 'Denegado' ),
					'required' 		=> TRUE,
				);

			// cargar caracteristicas
				$attr['cond']['evento_id'] = $evento_id;
				$data['caracteristicas'] = $this->eventos_caracteristicas_model->get($attr); unset($attr);
				$data['caracteristicas_options'] = $this->caracteristicas_model->get();

			// cargar precios
			$attr['cond']['evento_id'] = $evento_id;
			$data['precios'] = $this->eventos_precios_model->get($attr); unset($attr);

			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');
		}

		private function events_eliminar ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_eliminar';
	
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
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
		}
	}
		// metodos de caracteristicas
		private function caracteristicas_listar ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'caracteristicas_listar';

			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

			// buscar los eventos y ordenarlos por fecha de publicacion
			$attr['order_by'] = 'creado DESC';

			$data['caracteristicas'] = $this->caracteristicas_model->get($attr); unset($attr);

			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');
		}

		private function caracteristicas_nuevo ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'caracteristicas_nuevo';
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
			
			// definir titulos y crumbs
			$this->layouts->set_title('Cargar un evento nuevo');
			
			// definir el formulario
				$data['form']['action'] = base_url().'ajax/caracteristicas_ajax/nuevo';
				$data['form']['ajax_call'] = 1;
				// inputs
				$data['form']['inputs'][] = array(
					'name' => 'nombre',
					'placeholder' => 'Caracteristica',
					'required' => TRUE,
				);

				$data['form']['inputs'][] = array(
					'name' => 'icono',
					'placeholder' => 'Icono',
					'type' => 'file',
					'required' => TRUE,
					'multiple' => TRUE,
				);

			// cargar la pagina y pasar los datos al view
			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');

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
		}
	}
		// metodos de categorias
		private function categorias_listar ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'categorias_listar';

			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

			// buscar los eventos y ordenarlos por fecha de publicacion
			$attr['order_by'] = 'creado DESC';

			$data['categorias'] = $this->categorias_model->get($attr); unset($attr);

			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');
		}

		private function categorias_nuevo ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'categorias_nuevo';
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
			
			// definir titulos y crumbs
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/scripts/fileUpload.js','foot');			
			$this->layouts->add_include(APP_ASSETS_FOLDER.'/plugins/css/fileUpload.css','head');			
			$this->layouts->set_title('Cargar un evento nuevo');
			
			// definir el formulario
				$data['form']['action'] = base_url().'ajax/categorias_ajax/nuevo';
				$data['form']['ajax_call'] = 1;
				// inputs
				$data['form']['inputs'][0]['label'] = 'Nombre';
				$data['form']['inputs'][0]['name'] = 'nombre';
				$data['form']['inputs'][0]['placeholder'] = 'Nombre de la nueva categoria';
				$data['form']['inputs'][0]['required'] = TRUE;

				$data['form']['inputs'][1]['label'] 		= 'Grupo';
				$data['form']['inputs'][1]['name'] 			= 'evento_tipo_grupo_id';
				$data['form']['inputs'][1]['placeholder'] 	= 'Grupo';
				$data['form']['inputs'][1]['required'] 		= TRUE;
				$data['form']['inputs'][1]['type'] 	   		= 'select';
				$data['form']['inputs'][1]['options']		= $this->categorias_grupos_model->get_for_input();

				$data['form']['inputs'][2]['name'] 			= 'evento_tipo_grupo_nombre';
				$data['form']['inputs'][2]['placeholder'] 	= 'Nombre del nuevo grupo';
				$data['form']['inputs'][2]['class'] 		= 'hidden';

			print_r($data['form']['inputs'][1]['options']);

			// cargar la pagina y pasar los datos al view
			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');

		}

}
?>
