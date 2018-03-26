<?php
class Organizaciones_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'organizacion_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'organizaciones';
			$this->table_read 	= 'organizaciones';
	}

}
?>