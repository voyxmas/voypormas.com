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
		$this->layouts->add_include('https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key=AIzaSyDaDtH2arGzUFc_wrBN1VgvlZ_xOmRJiCY','foot','js');
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/global/scripts/autocompletarlugar.js','foot');

		// get categorias
		$data['categorias'] = $this->categorias_model->get_for_input();

		// get eventos
		$attr['cond']['estado'] = 1;
		$attr['cond']['publicar_desde <='] = date(SYS_DATETIMEFULL_FORMAT);
		$attr['cond']['publicar_hasta >'] = date(SYS_DATETIMEFULL_FORMAT);
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
