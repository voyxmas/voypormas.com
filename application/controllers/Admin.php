<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends My_Controller {

	function __construct()
	{
			parent::__construct();
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

		// render data
		$this->layouts->view($data['CURRENT_SECTION'].'/init',$data,'admin/general');
	}

}
?>
