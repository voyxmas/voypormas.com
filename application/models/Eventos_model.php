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

	public function search ()
	{
		
	}

	public function get_full($evento_id = NULL)
	{
		if($evento_id === NULL) return FALSE;

		// get evento
		$evento = $this->get($evento_id);

		// get variantes
		
		// get caracteristicas
		$this->load->model('eventos_caracteristicas_model');
		$evento['caracteristicas'] = $this->eventos_caracteristicas_model->get_by_evento_id($evento_id);

		// get corredores que asisten

		// get tarifas

		return $evento;
	}

}
?>