<?php
class Admins_model extends CI_Model
{

	public function __construct()
	{
			parent::__construct();
	}

	public function login($email=NULL,$password=NULL)
	{
		if($email===NULL OR $password===NULL) 
			return FALSE;
		
			// preprar las variables
	    $cond['email'] 	= $email;
	    $cond['password'] 	= md5($password);

		// query
		$this->db->select('admins.*');
		$this->db->where($cond);
		$result = $this->db->get('admins');
		if($result)
			$result = $result->result_array();
		else
			$result = array();

		// if login ok
		if (!empty($result)){
			$result = 	$result[0];
			// no guardar en la session
			foreach ($result as $key => $value) {
				if ($key == 'password') {
					unset($result[$key]);
				}
			}
		}

		// respuesta
		return $result;
	}
}
