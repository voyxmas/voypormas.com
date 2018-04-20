<?php
class Notificaciones_model extends CI_Model 
{
	
	public function __construct() 
	{
        
  }

  public function nuevo_organizacion($id_organizador = NULL)
  {
    if($id_organizador === NULL OR !is_numeric($id_organizador)) return FALSE;
    // cargo modelo de organizacion
    $this->load->model('organizaciones_model');
    // tomo los datos
    $organizacion = $this->organizaciones_model->get($id_organizador)[0];
    $organizacion['activation_link'] = base_url().'app/organizaciones/activar?id='.$organizacion['organizacion_id'].'&token='.md5($organizacion['token']); 
    if(!$organizacion) return FALSE;
    // los paso al template
    $contenido_del_mail = $this->load->view('emails/general/bienvenida_activar_organizacion',$organizacion,TRUE);
    // intento enviar el mail
    return $this->send($organizacion['email'],'VoyPorMas.com - Activar perfil de organizador',$contenido_del_mail);
  }
  

  // Funcion general que canaliza el despacho de email.
  private function send($email, $subject, $content, $template = NULL, $attach = FALSE)
  {
      // config
      $config['mailtype'] = 'html';

      if (ENABLE_SMTP === TRUE) 
      {
          $config['protocol']  = 'smtp';
          $config['smtp_host'] = EMAILS_SMTP_HOST;
          $config['smtp_user'] = EMAILS_SMTP_USER;
          $config['smtp_pass'] = EMAILS_SMTP_PASS;
          $config['smtp_port'] = EMAILS_SMTP_PORT;
          $config['crlf']         = "\r\n";
          $config['newline']      = "\r\n";
          $config['validation']   = TRUE; 
          $config['charset']      = 'utf-8';
          
          if (EMAILS_SMTP_TIMEOUT !== NULL)
          $config['smtp_timeout'] = EMAILS_SMTP_TIMEOUT;
          
          if (EMAILS_SMTP_KEEPALIVE !== NULL)
          $config['smtp_keepalive'] = EMAILS_SMTP_KEEPALIVE;

          if (EMAILS_SMTP_CRYPTO !== NULL)
          $config['smtp_crypto'] = EMAILS_SMTP_CRYPTO;
      }

      if (ENABLE_SENDMAIL === TRUE) 
      {
          $config['protocol']  = 'sendmail';
          $config['mailpath'] = EMAILS_MAILPATH;
          $config['charset']  = EMAILS_CHARSET;
          $config['wordwrap'] = EMAILS_WORDWRAP;
      }

      // carget el $template y enviar el $content para guardarlo en $this->email->message, si no enviarlo $message como viene
      if($template)
        $content = $this->load->view($template,$content,TRUE); // los views del template estan en views/emails/general/[templete-name]
      
      // En entorno de produccion despachamos los emails realmente
      if (ENVIRONMENT == 'production') 
      {
          $this->load->library('email');
          $this->email->initialize($config);
          $this->email->from(EMAIL_SYSTEM, EMAILS_ADDRESS);
          $this->email->bcc($email);
          $this->email->subject(mb_convert_encoding($subject, "UTF-8"));        
          $this->email->message(mb_convert_encoding($content, "UTF-8"));
          
          // procesamos los adjuntos
          if ($attach!=FALSE)
          {
            if (is_array($attach)) {
              foreach ($attach as $attachment) {
                $this->email->attach($attachment);
              }
            }else{
              $this->email->attach($attach);
            }
          }

          return @$this->email->send(); 
      }
      // En entorno de desarrollo guaradmos el email que saldria como htm en la carpeta de debuging.
      else
      {
        $filename = date('YmdHis').'-'.$email.'.htm';

        // Creamos los directorios si no existiesen.
        if (!is_dir('./assets/emails')) mkdir('./assets/emails');
        if (!is_dir('./assets/emails/debug')) mkdir('./assets/emails/debug');
        if (!is_dir('./assets/emails/debug/emails')) mkdir('./assets/emails/debug/emails');

        file_put_contents('./assets/emails/debug/emails/'.$filename, $content);
      }
  }
}