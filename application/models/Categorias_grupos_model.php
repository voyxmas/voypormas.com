<?php
class Categorias_grupos_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'evento_tipo_grupo_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'eventos_tipos_grupos';
			$this->table_read 	= 'eventos_tipos_grupos';
	}

}
?>