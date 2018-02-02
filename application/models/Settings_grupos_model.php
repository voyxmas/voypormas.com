<?php
class Settings_grupos_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'setting_grupo_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'settings_grupos';
			$this->table_read 	= 'settings_grupos';
	}

}
?>