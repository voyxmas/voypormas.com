<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login_ajax extends My_Controller
{

	function __construct()
	{
		parent::__construct();

		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
	}

	public function index()
	{

		// load models
		$this->load->model('admins_model');

		// preparar las variables del input
		$email = $this->input->post('email');
		$password = $this->input->post('password');

		// actions
		$respuesta['user'] = $this->admins_model->login($email, $password);
		$token['token'] = build_token($respuesta['user']);
		$token['expire'] = time() + APP_SESSION_EXPIRE;

		if (!empty($respuesta['user'])) {
			$this->session->set_userdata($respuesta);
			$this->session->set_userdata($token);
			$loged_in = 1;
		} else {
			$loged_in = 0;
		}

    // respuesta data
		$data = array();

    // dependiendo de la respuesta pedir que se haga algo en el origen del request
		switch ($loged_in) {
			case 1:
				if (isset($_SESSION['go_back_to'])) {
					$do_after['redirect'] = base_url() . 'admin/main';
					unset($_SESSION['go_back_to']);
				} else
					$do_after['reload'] = 1;
				break;
			default:
				$do_after['alert'] = 'Error en el intento de login';
				break;
		}
		$this->ajax_response($data, $do_after);
	}

	public function logout()
	{
		$this->session->unset_userdata('user');
		$data = array();
		$do_after['redirect'] = base_url();
		$this->ajax_response($data, $do_after);
	}

}
?>
