<?php
class Admins_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'admin_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'admins';
			$this->table_read 	= 'admins';
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
