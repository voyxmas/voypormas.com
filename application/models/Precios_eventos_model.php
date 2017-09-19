<?php
class Precios_eventos_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'evento_id';
			$this->primary_field= 'monto';
			$this->table_CRUD 	= 'precios_eventos';
			$this->table_read 	= 'precios_eventos';
	}

}
?>