<?php
class Eventos_caracteristicas_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'caracteristica_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'caracteristica_evento_assigns';
			$this->table_read 	= 'caracteristica_evento_assigns_view';
	}

	public function get_by_evento_id($evento_id = NULL)
	{
		if($evento_id === NULL) return FALSE;

		$query['cond']['evento_id'] = $evento_id;
		$query['results'] = 1000;

		return $this->get($query);

	}

}
?>