<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends My_Controller {

	function __construct()
	{
			parent::__construct();
	}

	public function index()
	{
		// verificar permisos y la sesion para poder continuar
		$data['CURRENT_SECTION'] = 'login';
		$data['CURRENT_PAGE'] = 'main';
		
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

		// set title
		$this->layouts->set_title('Welcome');
		$this->layouts->set_description('Welcome');

		// definir includes en el head del documento
		$this->layouts->add_include(APP_ASSETS_FOLDER.'/pages/css/login.css','head');


		// render data
		$this->layouts->view($data['CURRENT_SECTION'].'/'.$data['CURRENT_PAGE'],$data,'login');

	}

}
?>
