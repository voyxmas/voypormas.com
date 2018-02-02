<?php
class Settings_model extends MY_Model
{

	public function __construct()
	{
			parent::__construct();
			$this->primary_id 	= 'setting_id';
			$this->primary_field= 'nombre';
			$this->table_CRUD 	= 'settings';
			$this->table_read 	= 'settings_view';
	}

}
?>