<?php
class Variantes_eventos_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'variante_evento_id';
			$this->primary_field= 'distancia';
			$this->table_CRUD 	= 'variantes_eventos';
			$this->table_read 	= 'variantes_eventos';
	}

}
?>