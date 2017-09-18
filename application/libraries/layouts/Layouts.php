<?php

  class Layouts
  {
    // hold CI instance and variables
    private $CI;
    private $layout_title = NUll;
    private $layout_description = NUll;

    public function __construct()
    {
      $this->CI =& get_instance();
    }

    public function set_title($title){$this->layout_title = $title;}
    public function set_description($description){$this->layout_description = $description;}

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

    public function view($view_name, $data = array(), $layout_name = FALSE)
    {
      $layout_name =  $layout_name === FALSE ? $view_name :  $layout_name;
      // get templates Arrays
      $this->CI->load->config('layouts');
      $layouts_array =  config_item('layouts');

      $data['body_class'] = str_replace('/','_', $view_name);
      $data['layout_title'] = $this->layout_title;
      $data['layout_description'] = $this->layout_description;

      // intentar cargar archivos css y js especificos de cada view si existen
      $path_to_current_page = $view_name;
      $path = APP_ASSETS_FOLDER.'/custom/pages/js/'.$path_to_current_page.'.js';
      if (is_file(FCPATH.$path) === TRUE) $this->add_include(base_url().$path, 'foot');
      $path = APP_ASSETS_FOLDER.'/custom/pages/css/'.$path_to_current_page.'.css';
      if (is_file(FCPATH.$path) === TRUE) $this->add_include(base_url().$path, 'head');

      // render content
      $main = preg_match('/\/$/',$view_name)? 'main' : ''; // add or remove main dependiendo si es un archivo o un directorio
      $data['content'] = $this->CI->load->view('pages/'.$view_name.$main,$data, TRUE);

      $this->CI->load->view('layout/pages/'.$layout_name.'/main',$data);
    }
  }
?>
