<?php
class Variantes_eventos_premios_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'variante_evento_premio_id';
			$this->primary_field= 'descripcion';
			$this->table_CRUD 	= 'variantes_eventos_premios';
			$this->table_read 	= 'variantes_eventos_premios';
	}

}
?>