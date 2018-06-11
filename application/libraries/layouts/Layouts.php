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
      if(defined('FB_APP_ID')) $this->metas['fb:app_id'] = $CI->lang->line(FB_APP_ID);

        // verifications
      if(defined('GOOGLE_SITE_VERIFICATION')) $this->metas['google-site-verification'] = GOOGLE_SITE_VERIFICATION;
      if(defined('BING_SITE_VERIFICATION')) $this->metas['msvalidate.01'] = BING_SITE_VERIFICATION;
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

    public function add_include($path, $where = 'head', $type = NULL)
    {
      //defino si es js o css
      if($type === NULL)
      {
        if (preg_match('/js$/', $path))
          $type = 'js';
        else
          $type = 'css';
      }

      if (!preg_match('/^http/',$path)){
        $this->file_includes[$where][$type][] = base_url() . $path;
      }else{
        $this->file_includes[$where][$type][]= $path;
      }

      return $this; // This allows chain-methods
    }

    public function print_includes($where='head')
    {
      // Initialize a string that will hold all includes
      $final_includes = '';

      if (!empty($this->file_includes[$where]))
      {
        if(isset($this->file_includes[$where]['js']))
          foreach ($this->file_includes[$where]['js'] as $js)
            $final_includes .= '<script type="text/javascript" src="' . $js . '"></script>';
        
        elseif(isset($this->file_includes[$where]['css']))
          foreach ($this->file_includes[$where]['css'] as $css)
            $final_includes .= '<link href="' . $css . '" rel="stylesheet" type="text/css" />';
      }

      return $final_includes;

    }

    public function define_meta($parameter = NULL, $value=NULL)
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
      // si no se define_meta en metas el favicon y si el archvio existe en el root mostrar el favicon
      echo file_exists(__DIR__.'favicon.ico') AND !array_key_exists(base_url().'favicon',$metas) ? '<link rel="shortcut icon" href="'.base_url().'favicon.ico" type="image/x-icon"><link rel="icon" href="'.base_url().'favicon.ico" type="image/x-icon">' : NULL;
    }

    public function view($view_name, $data = array(), $layout_name = FALSE)
    {

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

      // print metas

      // print includes head

      // print view

      // prtin inucludes footer
      if($layout_name)
        $this->CI->load->view('layout/pages/'.$layout_name.'/main',$data);
      else  
        $this->CI->load->view('pages/'.$view_name,$data);

    }

    public function paginacion($resultados = NULL, $current_page = NULL, $settings = array())
    {
      if($resultados === NULL || !is_array($resultados)) return FALSE;

      // attr default settigns
        // agrego botones next y prev BOOL
        $per_page = isset($settings['per_page']) ? $settings['per_page'] : count($resultados) ? count($resultados) : 1 ;
        // agrego botones next y prev BOOL
        $prevnext = isset($settings['prevnext']) ? $settings['prevnext'] : TRUE;
        // agrego botones con el numero de pagina si defino una cantidad para mostrar INT
        $pagebuttons = isset($settings['pagebuttons']) ? $settings['pagebuttons'] : 1;
        // UL class
        $ulclass = isset($settings['ulclass']) ? $settings['ulclass'] : 'pagination';
        // li class
        $liclass = isset($settings['liclass']) ? $settings['liclass'] : NULL;
        // a class
        $aclass = isset($settings['aclass']) ? $settings['aclass'] : NULL;
        
        // FALTA
          // FALTA agrego dropdown con las paginas posibles BOOL (jump to page menu)
        $pagedropdown = isset($settings['pagedropdown']) AND !empty($settings['pagedropdown']) ? TRUE : FALSE;
          // FALTA muetro o no la pagina 1 la ultima en el rango de paginas totales BOOL
        $showpagerange = isset($settings['showpagerange']) AND !empty($settings['showpagerange']) ? TRUE : FALSE;

      // tamar la url de base
      $url_base = current_url();
      
      // tomar el query_string actual
      $query_string = $_SERVER['QUERY_STRING'];

      // ver si es associativo
        if(isset($resultados[0]['total_results']))
          $resultados_total = count($resultados);
        elseif(isset($resultados['total_results'])) 
          $resultados_total = 1;
        else
          $resultados_total = 0;

      // definir el numero de paginas
      $page_total_number = ceil($resultados_total / $per_page);

      // tomar page
        if($current_page === NULL)
          if(isset($_GET['p']) AND !empty($_GET['p']))
            $current_page = $_GET['p'];
          else
            $current_page = 1;
        else
          $current_page = $current_page;
        
        $html = "<ul class='$ulclass'>";
      // mostrar pagina anterior
        if($current_page > 1 AND $prevnext)
          $html ."<li>Prev</li>";
        
        if($pagebuttons)
        {
          // mostrar los botnones de las paginas
          for($p = 1 ; $p <= $page_total_number ; $p++ )
          {
            // mostrar el boton de la pagina actual como activo
            if($current_page === $p) $active_class = 'active'; else $active_class = ''; 
            // mostrar el resto de los bontones
              // cambiar el numero de pagina en el query_string
            $set_get['p'] = $p ;
            $url = $this->set_query_string($query_string,$set_get);
            $html .= "<li class='$active_class $liclass'><a href='".base_url()."app/home?$url'>$p</a></li>";
          }

        }
        
      // mostrar pagina siguiente
      if($current_page < $resultados_total AND $prevnext)
        $html ."<li>Next</li>";

      $html .= "</ul>";
      
      /*
      <ul class="pagination" style="visibility: visible;">
          <li class="prev disabled"><a href="#" title="First"><i class="fa fa-angle-double-left"></i></a></li>
          <li class="prev disabled"><a href="#" title="Prev"><i class="fa fa-angle-left"></i></a></li>
          <li class="active"><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li class="next"><a href="#" title="Next"><i class="fa fa-angle-right"></i></a></li>
          <li class="next"><a href="#" title="Last"><i class="fa fa-angle-double-right"></i></a></li>
      </ul>
      */
      
      return $html;

    }

    private function set_query_string($string = NULL, $variables = array())
    {
      if($string === NULL) return FALSE;

      // seetings
      $regex_base = "/variable=([a-zA-Z0-9\-_]+)(&)?/";

      if (!empty($variables))
      {
        foreach($variables as $variable => $valor)
        {
          $regex  = str_replace('variable',$variable,$regex_base);

          if(!preg_match($regex,$string))
          {
            // no esta la variable la tengo que agregar
            if(strlen($string)>=3)
            {
              // si ya existe la agrego &
              $string .= "&$variable=$valor";
            }
            else
            {
              // si no existe la agrego ?
              $string = "$variable=$valor";
            }
          }
          else
          {
            // la reemplazo
            $string = preg_replace($regex,"$variable=$valor",$string);
          }

        }
      }
      else
      {
        $string = "4";
      }
      return $string;
    }

  }
?>