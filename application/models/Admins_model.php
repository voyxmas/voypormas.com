<?php
class Admins_model extends CI_Model
{

	public function __construct()
	{
			parent::__construct();
	}

	public function login()
	{
		// preprar las variables
	    $cond['nombre'] 	= $this->input->post('user_name');
	    $cond['password'] 	= md5($this->input->post('user_name'));

		// query
		$this->db->select('admins.*');
		$this->db->where($cond);
		$result = $this->db->get('admins')->result_array();

		// if login ok
		if (!empty($result)){
			$result = 	$result[0];
			$ok = TRUE;
			// no guardar en la session
			foreach ($result as $key => $value) {
				if ($key == 'password') {
					unset($result[$key]);
				}
			}
		}else{
			$ok = FALSE;
		}

		// respuesta
		return ( $ok === TRUE ? $result : FALSE);
	}
}
