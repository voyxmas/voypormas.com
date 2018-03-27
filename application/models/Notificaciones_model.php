<?php
class Notifications extends CI_Model 
{
	
	public function __construct() 
	{
        
	}

    // Funcion general que canaliza el despacho de email.
    public function send($email, $subject, $content, $on_debug = 'DFLT', $attach = FALSE)
    {
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
        
        // En entorno de produccion despachamos los emails realmente
        if (ENVIRONMENT == 'production') 
        {
            $this->load->library('email');
            $this->email->initialize($config);
            $this->email->from(EMAIL_SYSTEM, $this->lang->line('email_from'));
            $this->email->bcc($email);
            $this->email->subject(mb_convert_encoding($subject, "UTF-8"));        
            $this->email->message(mb_convert_encoding($content, "UTF-8"));
            
            // procesamos los adjuntos
            if ($attach!=FALSE){
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
          $filename = date('YmdHis').'-'.$on_debug.'-'.$email.'.htm';

          // Creamos los directorios si no existiesen.
          if (!is_dir('./assets/emails')) mkdir('./assets/emails');
          if (!is_dir('./assets/emails/debug')) mkdir('./assets/emails/debug');
          if (!is_dir('./assets/emails/debug/emails')) mkdir('./assets/emails/debug/emails');

          file_put_contents('./assets/emails/debug/emails/'.$filename, $content);
        }
    }
}