<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends My_Controller {

	function __construct()
	{
		parent::__construct();
		
		// load models
		$this->load->model('categorias_model');
		$this->load->model('eventos_model');
	}

	public function main()
	{
		// verificar permisos y la sesion para poder continuar
		$data['CURRENT_SECTION'] = 'app';
		$data['CURRENT_PAGE'] = 'soon';
		
		// bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

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
		$data['CURRENT_SECTION'] = 'app';
		$data['CURRENT_PAGE'] = 'home';

		$this->layouts->set_title('Welcome');
		$this->layouts->set_description('Welcome');

		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/app_main.css','head');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/css/todo.min.css','head');

		// get categorias
		$data['categorias'] = $this->categorias_model->get_for_input();

		// get eventos
		$attr['cond']['estado'] = 1;
		$attr['cond']['publicar_desde <='] = date(SYS_DATETIMEFULL_FORMAT);
		$attr['cond']['publicar_hasta >'] = date(SYS_DATETIMEFULL_FORMAT);
		// filtrar por las condiciones
		if($this->input->get('fecha'))
			$attr['cond']['fecha >'] = $this->input->get('fecha');
		else
			$attr['cond']['fecha >'] = date(SYS_DATETIMEFULL_FORMAT);
		
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
		
		if($this->input->get('p'))
			$attr['page'] = $this->input->get('p');
		

		$data['eventos'] = $this->eventos_model->get($attr); unset($attr);
		
		// get maxs and mins
			// price
		$data['pricelimits'] = $this->eventos_model->minmax('precio');
				
			// distancia
		$data['distancialimits'] = $this->eventos_model->minmax('distancia');
		
		// get number of results to show
		$data['count'] = isset($data['eventos'][0]['total_results']) ? $data['eventos'][0]['total_results'] : 0;

		$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'app/general');
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
