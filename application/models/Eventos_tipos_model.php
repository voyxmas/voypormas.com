<?php
class Eventos_tipos_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'evento_tipo_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'eventos_tipos';
			$this->table_read 	= 'eventos_tipos';
	}

}
?>