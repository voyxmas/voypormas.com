<?php
class Eventos_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'evento_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'eventos';
			$this->table_read 	= 'eventos_view';
	}

}
?>