<?php
class Organizadores_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'organizador_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'organizadores';
			$this->table_read 	= 'organizadores';
	}

}
?>