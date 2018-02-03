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
	}

	public function identify()
	{

		// load models
		$this->load->model('organizadores_model');

		// preparar las variables del input
		$check['cond']['password']  = md5($this->input->post('password'));
		$check['cond']['email']     = $this->input->post('email');
        $check['select'][] = 'organizador_id';
        $check['select'][] = 'email';
        $check['select'][] = 'nombre';

        // ve si se enviaron todos los datos
        if(!empty($this->input->post('email')) AND !empty($this->input->post('password')))
        {
            // actions
            $logincheck = $this->organizadores_model->get($check); unset($check);

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
                $check['select'][] = 'organizador_id';
                $emailcheck = $this->organizadores_model->get($check); unset($check);
                if (empty($emailcheck)) 
                {
                    // si no existe creo la cuenta con el password enviado
                    $save['email'] = $this->input->post('email');
                    $save['password'] = md5($this->input->post('password'));
                    $newaccount = $this->organizadores_model->save($save); 
                    
                    if(!empty($newaccount))
                    {
                        // tomo el id del registro generado
                        $newaccounttosession['organizador'] = $save;
                        $newaccounttosession['organizador']['organizador_id'] = $newaccount;

                        // si se pudo generar el organizador lo logueo - signup
                        $this->session->set_userdata($newaccounttosession);
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
