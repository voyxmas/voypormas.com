<?php
class Caracteristicas_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'caracteristica_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'caracteristicas';
			$this->table_read 	= 'caracteristicas';
	}

}
?>