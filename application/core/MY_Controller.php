<?php 
/**
 * CodeIgniter Core Controller Etension extensions
 * @package	CodeIgniter
 * @author	Fracisco Javier Machado
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {

	function __construct()
	{
		parent::__construct();

		// ayuda, mostrar el profiler solo en development y solo cuando no son ajax requests
		if(!$this->input->is_ajax_request())
		{
			if(ENVIRONMENT != 'production' AND isset($_GET['profiler']))
			{
				$this->output->enable_profiler(TRUE);
			} 
		}
		
		// ayuda, borrar sessiones mediante comando get
		if(isset($_GET['clear_session']))
		{
			if(empty($_GET['clear_session']))
			{
				$this->session->sess_destroy();
			}
			if(isset($_SESSION[$_GET['clear_session']]))	
			{
				$this->session->unset_userdata($_GET['clear_session']);
			}
		}
				
	}

	// preparo el json
	protected function ajax_response($data = NULL, $do_after = NULL)
	{
		$return['data']=$data;
		$return['do_after']=$do_after;
		echo json_encode($return);
	}

	// preparo los callback apra interactuar con js y reaccionar a la respuesta del llamado ajax
	protected function ajax_clean_do_after($do_after)
	{
		foreach($do_after as $key => $value) 
		{
			switch ($key) 
			{
				// aciones que se tienen que corresponder con las definidas en assets/custom/scripts/ajaxforms.js en el metodo do_response
				case 'callback': // ejecutar una funcion por el nombre
				case 'redirect': // redirecciona a otra pagina
				case 'reload': // recarga la pagina actual
				case 'popup': // carga un popup con un mensaje
				// miscelaneos
				case 'action_delay': // define el tiempo que se espera para ejecutar la accion
					$do_after[$key] = $value;
					break;
			}
		}

		return $do_after;
	}

	protected function uploadFile($files = NULL, $folder = NULL, $name = array()) // pasar un folder y name o un array de folders y names
	{
		if($files === NULL) return FALSE;

		// check folder
		if(is_string($folder))
		{
			$folder_levels = explode('/',$folder);
			$loop_current_folder_level = "";
			foreach ($folder_levels as $folder) {
				if (!is_dir('./assets/emails')) mkdir('./assets/emails');
				$loop_current_folder_level = ($loop_current_folder_level) ? "/".$folder : $folder;
			}
		}
		
		if(isset($_FILES) AND is_array($_FILES))
		{
			$filename = $this->security->sanitize_filename($file['name']);
			foreach ($_FILES as $name => $file) 
			{
				$config['file_name'] = $filename;	
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload($name))
				{
					$e[] = array('error' => $this->upload->display_errors());
				}
				else
				{
					// tomo los datos cargados
					$upload_data = $this->upload->data();
					// agrego el full path a savepara guardar la referencia a la imagen
					$return['icono']=$config['upload_path'].$upload_data['file_name'];
				}
			}
		}
	}


}
?>