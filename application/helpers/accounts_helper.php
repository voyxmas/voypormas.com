<?php
    function hashit($password, $salt = NULL)
    {
        if ($salt===NULL) $salt=saltit();
        $length = 30;
        return  $salt.substr(sha1($salt.$password), 0, $length);
    }

    function saltit ($length = 10) 
    {
        return substr(str_shuffle(md5(microtime())), 0, $length);
    }

    function bouncer ($perm_group, $perm_item) 
    {
        // definir si la pagina actual es el login para evitar un loop infinito con los redirects y mostrar el 403
        $is_login   = ($perm_group == 'admin' && $perm_item == 'login') ? TRUE : FALSE;
        $logged_in  = check_session();
        $allowed_in = check_permissions($perm_group, $perm_item);

        // permission ok
        if (!$logged_in) 
        {
            if (!$is_login) 
            {
                $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $_SESSION['go_back_to'] = $actual_link;
                redirect (base_url());
            }
            else
            {
                if (!$allowed_in) 
                {
                    header('HTTP/1.0 403 Forbidden');
                    exit;
                }
            }
        } 
        else 
        {
            if(!$allowed_in)
            {
                redirect (base_url());
            }
            else
            {
                if ($is_login) 
                {
                    redirect(base_url().$perm_group);
                }
            }
        }
    }

    function check_permissions($perm_group, $perm_item) // $user data es la que se guarda en la session de login: ids de area, role, usuraio son los que inmportan
    {
        //if (!check_session () && !($perm_group != 'login' && $perm_item != 'main')) {return FALSE;}
        $CI =& get_instance();

        // get user data
        $user_data = $CI->session->userdata();
        $CI->load->config('permissions');
        $perm = $CI->config->item('perm');

        // check db for permition setting
        $db_check = 0;

        // if nothing is found use defaults
        if ($db_check == 0) {
        // si no esta definido en config, prohibir el acceso
        if (!isset($perm[$perm_group][$perm_item])) {
            $check = 0;
            // no esta en el array de configuracion;
        }else{
            $check = (int)$perm[$perm_group][$perm_item];
        }
        }else{
        // else use config in database
            // 'lo busco en la base de datos';
            $check = (int)$db_check;
        }


        if ($check==1) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    // check if the user has a valid session
    function check_session () {

        $CI =& get_instance();

        $token    = $CI->session->userdata('token');
        $user_data  = $CI->session->userdata('user');

        if (!empty($token))
        {
            $user_data  = $CI->session->userdata('user');
            $token      = $CI->session->userdata('token');
            $expire     = $CI->session->userdata('expire');


            $valid_token = build_token($user_data);
            $integrity = ($token == $valid_token && $expire > time()) ? TRUE : FALSE;

            if ($integrity === TRUE)
            {
                $CI->session->set_userdata('expire', time() + (APP_SESSION_EXPIRE === FALSE ? 1000 : APP_SESSION_EXPIRE)); // reset expire
                return TRUE;
            }
            else
            {
                $CI->session->unset_userdata('user');
                $CI->session->unset_userdata('token');
                $CI->session->unset_userdata('expire');
                return FALSE;
            }
        }

    }

    function build_token ($data) {
        if (is_array($data)) {
        ksort($data);
        $token = '';
        foreach($data as $key => $val){
            $token .= $val;
        }
        return md5($token.APP_SECRET_TOKEN);
        }
        else {
        return FALSE;
        }

    }

?>
