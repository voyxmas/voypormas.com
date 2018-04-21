<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Organizadores_ajax extends My_Controller {

	function __construct()
	{
		parent::__construct();

		if (!$this->input->is_ajax_request()) 
		{
		   exit('No direct script access allowed');
        }
        
        $this->load->model('organizaciones_model');
        $this->load->model('organizadores_model');
	}

	public function identify()
	{

		// preparar las variables del input
		$check['cond']['password']  = md5($this->input->post('password'));
		$check['cond']['email']     = $this->input->post('email');

        // ve si se enviaron todos los datos
        if(!empty($this->input->post('email')) AND !empty($this->input->post('password')))
        {
            // actions
            $logincheck = $this->organizaciones_model->get($check); unset($check);

            if (!empty($logincheck)) 
            {
                // limpio metadata
                unset($logincheck[0]['total_items']);
                unset($logincheck[0]['total_results']);
                $tosession['organizador'] = $logincheck[0];
                $this->session->set_userdata($tosession);
                $loged_in = 1;
            }
            else
            {
                // verificar si el email existe
                $check['cond']['email'] = $this->input->post('email');
                $check['select'][] = 'email';
                $check['select'][] = 'nombre';
                $check['select'][] = 'organizacion_id';
                $emailcheck = $this->organizaciones_model->get($check); unset($check);
                if (empty($emailcheck)) 
                {
                    // si no existe creo la cuenta con el password enviado
                    $save['email'] = $this->input->post('email');
                    $save['password'] = md5($this->input->post('password'));
                    $save['token'] = md5(str_shuffle(time()));
                    $save['token_vto'] = cstm_tomorrow(SYS_DATETIME_FORMAT);
                    $newaccount = $this->organizaciones_model->save($save); 
                    
                    if(!empty($newaccount))
                    {
                        // tomo el id del registro generado
                        $newaccounttosession['organizador'] = $this->organizaciones_model->get($newaccount)[0];
                        // si se pudo generar el organizador lo logueo - signup
                        $this->session->set_userdata($newaccounttosession);
                        // Mando la notificacion
                        $this->load->model('notificaciones_model');
                        $this->notificaciones_model->nuevo_organizacion($newaccount);

                        $loged_in = 1;
                    }
                    else
                    {
                        $loged_in = 0;
                        $e[] = 'No se pudo generar el registro del organizador con los datos ingresados';
                    }

                }
                else
                {
                    $loged_in = 0;
                    $e[] = 'Ese email esta registrado pero la contaseña ingresada no es correcta.';
                }

            }
        }
        else
        {
            $loged_in = 0;
            $e[] = 'Debes ingresar un email y una contraseña';
        }

        // respuesta data
		$data = array();

        // dependiendo de la respuesta pedir que se haga algo en el origen del request
		switch ($loged_in) 
		{
			case 1:
                $do_after['reload'] = 1;
				break;
			default:
                $do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
        }
        
        $this->ajax_response($data,$do_after);

    }
    
    public function add_profile()
    {
        // comprobar que se enviaron todos los campos obligatorios
        $campos_obligatorios = array('nombre'=>'Nombre de la organización','tel'=>'Teléfono de la organización','email'=>'Email de la organización');
        foreach ($campos_obligatorios as $post => $campo) {
            if(empty($this->input->post($post)))
                $e[] = 'El campo <strong>'.$campo.'</strong> es obligatorio';
            else
                $save_org[$post] = $this->input->post($post);
        }
        // agregar al array los campos que definen si es publico o no los datos de contacto
        $save_org['tel_public'] =  (int)$this->input->post('tel_public');
        $save_org['email_public'] =  (int)$this->input->post('email_public');
        // agrego al array las redes sociales
        $save_org['redes_sociales'] = implode(',',$this->input->post('org_social_link'));
        $save_org['inicio_actividades'] = $this->input->post('inicio_actividades');
        $save_org['provincia'] = $this->input->post('provincia');
        $save_org['ciudad'] = $this->input->post('ciudad');

        // ver que al emnos hayan enviado un representante
        $rep_publicos = 0;
        if(!empty($this->input->post('rep_nombre')) AND !empty($this->input->post('rep_tel')) AND !empty($this->input->post('rep_email')) AND !empty($this->input->post('rep_publico')))
        {
            if(is_array($this->input->post('rep_nombre')) AND is_array($this->input->post('rep_tel')) AND is_array($this->input->post('rep_email')) AND is_array($this->input->post('rep_publico')))
            {
                foreach($this->input->post('rep_nombre') as $rep_key => $rep_data)
                {
                    $save_rep[$rep_key]['nombre'] = $this->input->post('rep_nombre')[$rep_key];
                    $save_rep[$rep_key]['tel'] = $this->input->post('rep_tel')[$rep_key];
                    $save_rep[$rep_key]['email'] = $this->input->post('rep_email')[$rep_key];
                    $save_rep[$rep_key]['publico'] = $this->input->post('rep_publico')[$rep_key];
                    $save_rep[$rep_key]['organizacion_id'] = $this->input->post('organizador_id');
                    
                    if($save_rep[$rep_key]['publico'] == 1)
                        $rep_publicos++;
                    
                    if(empty($save_rep[$rep_key]['nombre'])) $e[] = 'El campo <strong>nombre</strong> del representante no puede estar vacío';
                    if(empty($save_rep[$rep_key]['tel'])) $e[] = 'El campo <strong>Teléfono</strong> del representante '.$save_rep[$rep_key]['nombre'].' no puede estar vacío';
                    if(empty($save_rep[$rep_key]['email'])) $e[] = 'El campo <strong>Email</strong> del representante '.$save_rep[$rep_key]['nombre'].' no puede estar vacío';
                }
            }
        }

        if($rep_publicos==0)
            $e[] = 'Debe haber al menos un representante para mostrar al público';

        // intentar grabar los datos de la organizacion
        if(empty($e))
        {
            if(!$this->organizaciones_model->save($save_org, $this->session->organizador['organizacion_id']))
                $e[] = 'No se pudo actualizador el perfil de la organiación';
        }
        
        // intentar grabar los datos de los representantes de la organizacion";
        if(empty($e))
        {
            if(!$this->organizadores_model->save($save_rep))
                $e[] = 'No se pudieron agregar los representaes al perfil de la organiación';
        }

        // si todo fue bien y no hay errores agrego los datos nuevo a la sesión y recargo
        $data = array();
        if(empty($e))
        {
            // actualizo la session y preparo la respuesta
            $this->session->organizador = array_merge($this->session->organizador,$save_org);

            $do_after['toastr'] 		= 'Perfil actualizado';
			$do_after['toastr_type'] 	= 'success';
			$do_after['action_delay'] 	= 500;
            $do_after['reload']         = TRUE;
        }
        else
        {
            $do_after['toastr'] 		= implode('<br>',$e);
			$do_after['toastr_type'] 	= 'error';
        }

        $this->ajax_response($data,$do_after);
        
    }

    public function aprobar($organizacion_id = NULL)
	{
		if($organizacion_id === NULL) return FALSE;

		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'organizaciones_aprobar';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
		
		// trato de editar el evento
		$attr['estado'] = 1;
		$respuesta = $this->organizaciones_model->save($attr, $organizacion_id);unset($attr);
		if($respuesta == FALSE) $e[] = 'No se pudo aprobar' ;

		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'Aprobado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
    }
    
    public function editar($organizacion_id = NULL)
    {
        if($organizacion_id === NULL) $e[] = 'Datos insuficientes';

        // check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'organizaciones_editar';
        bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
        
        // tomo los datos del formulario
        $edit['nombre']             = $this->input->post('nombre');
        $edit['inicio_actividades'] = $this->input->post('inicio_actividades');
        $edit['provincia']          = $this->input->post('provincia');
        $edit['ciudad']             = $this->input->post('ciudad');
        $edit['email']              = $this->input->post('email');
        $edit['email_public']       = $this->input->post('email_public');
        $edit['tel']                = $this->input->post('tel');
        $edit['tel_public']         = $this->input->post('tel_public');
        $edit['web']                = $this->input->post('web');
        $edit['redes_sociales']     = $this->input->post('redes_sociales');

        // guardar cambios
        if($organizacion_id)
            $respuesta = $this->organizaciones_model->save($edit,$organizacion_id);
        else
            $respuesta = FALSE;

        // respuesta
        $data = array();
		switch ($respuesta) {
			case NULL:
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay']   = 500;
				$do_after['toastr'] 		= 'Editado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);

    }

	public function rechazar($organizacion_id = NULL)
	{
		if($organizacion_id === NULL) return FALSE;

		// check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'organizaciones_aprobar';
		bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
		
		// trato de editar el evento
		$attr['estado'] = 2;
		$respuesta = $this->organizaciones_model->save($attr, $organizacion_id);unset($attr);
		if($respuesta == FALSE) $e[] = 'No se pudo rechazar' ;

		$data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 			= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['reload'] 		= 1;
				$do_after['action_delay'] = 500;
				$do_after['toastr'] 			= 'Rechazado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
    }
    
    /* representantes */
    public function editar_representante()
    {
        // check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'organizaciones_representantes_editar';
        bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);
        
        /* tomar los datos */
        $id = $this->input->post('organizador_id');
        if(!$id) return FALSE; // si no paso organizador_id cancelo la operacion

        $edit['nombre'] = $this->input->post('nombre');
        $edit['email'] = $this->input->post('email');
        $edit['tel'] = $this->input->post('tel');
        $edit['publico'] = $this->input->post('publico');

        // guardar lo datos
        $respuesta = $this->organizadores_model->save($edit,$id);

        $data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['toastr'] 		= 'Editado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
    }

    public function eliminar_representante($organizador_id = NULL)
    {
        if($organizador_id === NULL) return FALSE;
        // check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'organizaciones_representantes_editar';
        bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

        $respuesta = $this->organizadores_model->delete($organizador_id);

        $data = array();
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= 'No se pudo borrar el registro';
				$do_after['toastr_type'] 	= 'error';
				break;
			
			default:
				$do_after['toastr'] 		= 'Eliminado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);
    }

    public function nuevo_representante($organizacion_id = NULL)
    {
        if($organizacion_id === NULL) return FALSE;
        // check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'organizaciones_representantes_nuevo';
        bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

        // tomo los datos
        $save['organizacion_id'] = $organizacion_id;
        $save['nombre'] = $this->input->post('nombre');
        $save['email'] = $this->input->post('email');
        $save['tel'] = $this->input->post('tel');
        $save['publico'] = $this->input->post('publico');
        
        if(empty($save['organizacion_id']) OR empty($save['nombre']) OR empty($save['email']) OR empty($save['tel']) ){
            $e[]='No se recibieron todos los campos';
        }else{
            // creo el registro
            $respuesta = $this->organizadores_model->save($save);
        }
        $data = array();
        $respuesta = isset($respuesta) ? $respuesta : FALSE;
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
            default:
                $do_after['reload'] 		= 1;
				$do_after['action_delay']   = 500;
				$do_after['toastr'] 		= 'Creado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);

    }

    public function nuevo_organizador()
    {
        // check permission
		$data['CURRENT_SECTION'] 	= 'admin';
		$data['CURRENT_PAGE'] 		= 'organizaciones_nuevo';
        bouncer($data['CURRENT_SECTION'],$data['CURRENT_PAGE']);

        // load models
        $this->load->model('notificaciones_model');

        // tomo los datos
        $save['nombre'] = $this->input->post('nombre');
        $save['email'] = $this->input->post('email');
        $save['email_public'] = $this->input->post('email_public');
        $save['tel'] = $this->input->post('tel');
        $save['tel_public'] = $this->input->post('tel_public');
        $save['provincia'] = $this->input->post('provincia');
        $save['ciudad'] = $this->input->post('ciudad');
        $save['web'] = $this->input->post('web');
        $save['redes_sociales'] = implode(',',$this->input->post('redes_sociales'));
        
        if(empty($save['nombre']) OR empty($save['email']) OR empty($save['tel']) ){
            $e[]='No se recibieron todos los campos';
        }else{
            // creo el registro
            $respuesta = $this->organizaciones_model->save($save);
            if(!$respuesta) 
                $e[] = "No se pudo crear el organizador".$this->db->last_query();
            else
                $this->notificaciones_model->nuevo_organizacion($respuesta); // envio la notificaion
        }

        $data = array();
        $respuesta = isset($respuesta) ? $respuesta : FALSE;
		switch ($respuesta) {
			case FALSE:
				$do_after['toastr'] 		= implode('<br>',$e);
				$do_after['toastr_type'] 	= 'error';
				break;
			
            default:
                $do_after['redirect'] 		= base_url().'admin/organizaciones/editar/'.$respuesta;
				$do_after['action_delay']   = 500;
				$do_after['toastr'] 		= 'Creado';
				$do_after['toastr_type'] 	= 'success';
				break;
		}
		$this->ajax_response($data,$do_after);

    }

	public function logout () 
	{
		$this->session->unset_userdata('organizador');
		$data = array();
		$do_after['reload'] = TRUE;
		$this->ajax_response($data,$do_after);
    }
    
    public function recuperar () {

    }

}
?>
