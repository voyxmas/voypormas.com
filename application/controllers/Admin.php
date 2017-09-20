<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends My_Controller {

	function __construct()
	{
			parent::__construct();
			$this->load->model('eventos_model');
			$this->output->enable_profiler(TRUE);
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
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'main';

		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// set title
		$this->layouts->set_title('Administrador del sitio');

		// tomar eventos nuevos

		// tomar eventos aprovados recientemente

		// render data
		$this->layouts->view($data['CURRENT_SECTION'].'/init',$data,'admin/general');
	}

	public function eventos ($method = NULL, $evento_id = NULL)
	{
		// cargar modelos para eventos
		$this->load->model('eventos_model');
		$this->load->model('eventos_tipos_model');
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
		}
	}
	// metodos de eventos
		private function events_listar ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_listar';
	
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
			
		}

		private function events_nuevo ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_nuevo';
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
			
			// definir titulos y crumbs
			$this->layouts->set_title('Cargar un evento nuevo');
			
			// definir el formulario
				$data['form']['action'] = base_url().'ajax/eventos_ajax/nuevo';
				$data['form']['ajax_call'] = 1;
				// inputs
				$data['form']['inputs'][0]['name'] = 'nombre';
				$data['form']['inputs'][0]['placeholder'] = 'Titulo';
				$data['form']['inputs'][0]['label'] = 'Titulo';
				$data['form']['inputs'][0]['required'] = TRUE;

				$data['form']['inputs'][1]['name'] = 'descripcion';
				$data['form']['inputs'][1]['placeholder'] = 'Descripcion';
				$data['form']['inputs'][1]['label'] = 'Descripcion';
				$data['form']['inputs'][1]['type'] = 'textarea';
				$data['form']['inputs'][1]['required'] = TRUE;

				$data['form']['inputs'][8]['name'] = 'distancia';
				$data['form']['inputs'][8]['placeholder'] = 'Km';
				$data['form']['inputs'][8]['label'] = 'Distancia';
				$data['form']['inputs'][8]['type'] = 'number';
				$data['form']['inputs'][8]['required'] = TRUE;

				$data['form']['inputs'][2]['name']	= 'publicar_desde';
				$data['form']['inputs'][2]['label'] = 'Publicar desde';
				$data['form']['inputs'][2]['type'] 	= 'date';
				$data['form']['inputs'][2]['required'] = TRUE;

				$data['form']['inputs'][3]['name'] 	= 'publicar_hasta';
				$data['form']['inputs'][3]['label'] = 'Publicar hasta';
				$data['form']['inputs'][3]['type'] 	= 'date';
				$data['form']['inputs'][3]['required'] = TRUE;

				$data['form']['inputs'][4]['add_one_more'] = TRUE;
				$data['form']['inputs'][4]['label'] = 'Precio';
				$data['form']['inputs'][4]['group'][0]['name'] = 'precio[]';
				$data['form']['inputs'][4]['group'][0]['label'] = 'Monto';
				$data['form']['inputs'][4]['group'][0]['placeholder'] = 'Monto';
				$data['form']['inputs'][4]['group'][0]['type'] = 'number';
				
				$data['form']['inputs'][4]['group'][1]['name'] = 'precio_desde[]';
				$data['form']['inputs'][4]['group'][1]['label'] = 'Desde';
				$data['form']['inputs'][4]['group'][1]['type'] = 'datetime-local';

				$data['form']['inputs'][4]['group'][2]['name'] = 'precio_hasta[]';
				$data['form']['inputs'][4]['group'][2]['label'] = 'Hasta';
				$data['form']['inputs'][4]['group'][2]['type'] = 'datetime-local';

				$data['form']['inputs'][6]['name'] = 'evento_tipo_id';
				$data['form']['inputs'][6]['type'] = 'select';
				$data['form']['inputs'][6]['placeholder'] = 'Tipo de evento';
				$data['form']['inputs'][6]['label'] = 'Tipo de evento';
				$data['form']['inputs'][6]['options'] = $this->eventos_tipos_model->get_for_input();
				$data['form']['inputs'][6]['required'] = TRUE;
				
				$data['form']['inputs'][5]['name'] = 'caracteristica_id[]';
				$data['form']['inputs'][5]['type'] = 'checkbox';
				$data['form']['inputs'][5]['label'] = 'Caracteristicas';
				$data['form']['inputs'][5]['placeholder'] = 'Caracteristicas';
				$data['form']['inputs'][5]['options'] = $this->caracteristicas_model->get_for_input();
				
				$data['form']['inputs'][7]['name'] = 'estado';
				$data['form']['inputs'][7]['label'] = 'Estado';
				$data['form']['inputs'][7]['type'] = 'radio';
				$data['form']['inputs'][7]['required'] = TRUE;
				$data['form']['inputs'][7]['options'] = array( 0 => 'Nuevo', 1 => 'Aprobado', 2 => 'Denegado' );

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
			$data['evento'] = $this->eventos_model->get($evento_id);

			// cargar caracteristicas
			$attr['cond']['evento_id'] = $evento_id;
			$data['caracteristicas'] = $this->eventos_caracteristicas_model->get($attr); unset($attr);

			// cargar precios
			$attr['cond']['evento_id'] = $evento_id;
			$data['precios'] = $this->eventos_precios_model->get($attr); unset($attr);

			$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'admin/general');
		}

		private function events_editar ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_editar';
	
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
		}

		private function events_eliminar ()
		{
			$data['CURRENT_SECTION'] 	= 'admin';
			$data['CURRENT_PAGE'] 		= 'events_eliminar';
	
			bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
		}
	
	// metodos de organizadores

	// metodos de caracteristicas

	// metodos de tipo de eventos

}
?>
