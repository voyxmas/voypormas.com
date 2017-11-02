<?php
class Variantes_eventos_precios_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'variante_evento_precio_id';
			$this->primary_field= 'distancia';
			$this->table_CRUD 	= 'variantes_eventos_precios';
			$this->table_read 	= 'variantes_eventos_precios';
	}

}
?>