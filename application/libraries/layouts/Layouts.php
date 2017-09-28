<?php

  class Layouts
  {
    // hold CI instance and variables
    private $CI;

    public function __construct()
    {

      $this->CI =& get_instance();

      $this->metas['charset'] = config_item('charset');
      $this->metas['title'] = LIVE_DOMAIN_PATH;
      $this->metas['site'] = LIVE_DOMAIN_PATH;
      $this->metas['description'] = '';
      $this->metas['keywords'] = '';
      $this->metas['favicon'] = base_url().'assets/global/imgs/iconos/favicon.png';
      // mobile
      $this->metas['format-detection'] = 'telephone=no';
      $this->metas['apple-mobile-web-app-capable'] = 'yes';
      $this->metas['viewport'] = 'width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no, minimal-ui';
      // social
      $this->metas['og:type'] = 'website';
      if(define_tagd('FB_APP_ID')) $this->metas['fb:app_id'] = $CI->lang->line(FB_APP_ID);

        // verifications
      if(define_tagd('GOOGLE_SITE_VERIFICATION')) $this->metas['google-site-verification'] = GOOGLE_SITE_VERIFICATION;
      if(define_tagd('BING_SITE_VERIFICATION')) $this->metas['msvalidate.01'] = BING_SITE_VERIFICATION;
    }

    public function set_title($title){$this->metas['title'] = $title;}
    public function set_description($description){$this->metas['description'] = $description;}
    public function set_favicon($description){$this->metas['favicon'] = $description;}

    // public function set_language(){} lang & og:locale
    // public function set_icon(){}
    // public function set_author(){}
    // public function set_no_index(){}
    /*
    https://moz.com/blog/category/on-page-seo
    https://moz.com/blog/meta-data-templates-123

    http://ogp.me/
    https://dev.twitter.com/cards/overview
    https://developers.pinterest.com/docs/rich-pins/overview/
    https://developers.google.com/+/web/snippet/
    http://schema.org/docs/gs.html
    */
    // public function set_datos_horarios(){}
    // public function set_datos_image(){}
    // public function set_datos_type(){}
    // public function set_datos_institucion(){}
    // public function set_datos_profecional(){}

    public function add_include($path, $where = 'head')
    {
      if (!preg_match('/^http/',$path)){
        $this->file_includes[$where][] = base_url() . $path;
      }else{
        $this->file_includes[$where][] = $path;
      }

      return $this; // This allows chain-methods
    }

    public function print_includes($where='head')
    {
      // Initialize a string that will hold all includes
      $final_includes = '';

      if (!empty($this->file_includes[$where]))
      {
        foreach ($this->file_includes[$where] as $include)
        {
          if (preg_match('/js$/', $include))
          {
            $final_includes .= '<script type="text/javascript" src="' . $include . '"></script>';
          }
          elseif (preg_match('/css$/', $include))
          {
            $final_includes .= '<link href="' . $include . '" rel="stylesheet" type="text/css" />';
          }
        }
      }

      return $final_includes;

    }

    public function define_tag($parameter = NULL, $value=NULL)
    {
      if($parameter === NULL OR $value === NULL) return FALSE;
      $value = strip_tags(preg_replace( "/\r|\n/", "",$value));
      // defino tags especiales
      switch ($parameter){
        case 'title':
          $this->metas['og:title'] = $value;
          $this->metas['twitter:title'] = $value;
          $this->metas['itemprop:name'] = $value;
          $this->metas[$parameter] = $value;
          break;
        case 'description':
          $this->metas['og:description'] = substr($value,0,200);
          $this->metas['twitter:description'] = substr($value,0,200);
          $this->metas[$parameter] = $value;
          break;
        case 'thumb':
          $dimensiones = @getimagesize($value);
          $this->metas['og:image'] = $value;
          $this->metas['og:image:width'] = $dimensiones[0];
          $this->metas['og:image:height'] = $dimensiones[1];
          $this->metas['twitter:image:width'] = $dimensiones[0];
          $this->metas['twitter:image:height'] = $dimensiones[1];
          $this->metas['twitter:image'] = $value;
          $this->metas['twitter:card'] = 'summary_large_image';
          break;
        case 'site':
          $this->metas['twitter:site'] = $value;
          $this->metas['twitter:creator'] = $value;
          break;
        case 'canonical':
        $this->metas['canonical'] = $value;
        $this->metas['og:url'] = $value;
          break;
      }
    }

    public function print_tags() 
    {
      foreach($this->metas as $meta => $value)
      {
        if      ($meta === 'title') echo '<title>'.$value.'</title>';
        elseif  ($meta === 'charset')  echo '<meta charset="'.$value.'">';
        elseif  ($meta === 'canonical')  echo '<link rel="canonical" href="'.$value.'">';
        elseif  ($meta === 'favicon')  echo '<link rel="shortcut icon" href="'.$value.'" type="image/x-icon"><link rel="icon" href="'.$value.'" type="image/x-icon">';
        elseif  (preg_match('/(^og:)|(^fb:)/',$meta)) echo '<meta property="'.$meta.'" content="'.$value.'" />';
        elseif  (preg_match('/(^itemprop:)/',$meta)) echo '<meta itemprop="'.$meta.'" content="'.preg_replace('/(^itemprop:)/','',$mvalueeta).'" />';
        else echo '<meta name="'.$meta.'" content="'.$value.'" />';
        echo "\r\n";
      }
      // si no se define_tag en metas el favicon y si el archvio existe en el root mostrar el favicon
      echo file_exists(__DIR__.'favicon.ico') AND !array_key_exists(base_url().'favicon',$metas) ? '<link rel="shortcut icon" href="'.base_url().'favicon.ico" type="image/x-icon"><link rel="icon" href="'.base_url().'favicon.ico" type="image/x-icon">' : NULL;
    }

    
    public function view($view_name, $data = array(), $layout_name = FALSE)
    {
      $layout_name =  $layout_name === FALSE ? $view_name :  $layout_name;
      // get templates Arrays
      $this->CI->load->config('layouts');
      $layouts_array =  config_item('layouts');

      $data['body_class'] = str_replace('/','_', $view_name);

      // intentar cargar archivos css y js especificos de cada view si existen
      $path_to_current_page = $view_name;
      $path = APP_ASSETS_FOLDER.'/pages/scripts/'.$path_to_current_page.'.js';
      if (is_file(FCPATH.$path) === TRUE) $this->add_include(base_url().$path, 'foot');
      $path = APP_ASSETS_FOLDER.'/pages/css/'.$path_to_current_page.'.css';
      if (is_file(FCPATH.$path) === TRUE) $this->add_include(base_url().$path, 'head');

      // render content
      $main = preg_match('/\/$/',$view_name)? 'main' : ''; // add or remove main dependiendo si es un archivo o un directorio
      $data['content'] = $this->CI->load->view('pages/'.$view_name.$main,$data, TRUE);

      $this->CI->load->view('layout/pages/'.$layout_name.'/main',$data);

    }
  }
?>