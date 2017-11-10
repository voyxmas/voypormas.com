<?php 

function array2form($form = array()) 
{
  if(empty($form)) 
  {
    echo "Form vacio, nada para escribir"; 
    return FALSE;
  }

  // defaults
  $form['action'] = isset($form['action']) ? $form['action'] : NULL;
  $form['method'] = isset($form['method']) ? $form['method'] : 'post';
  $form['class'] = isset($form['class']) ? $form['class'] : '';
  $form['ajax_call'] = isset($form['ajax_call']) ? $form['ajax_call'] : 0;
  
  if(isset($form['ajax_call']))
  {
    if($form['ajax_call'])
    {
      $form['class'].=' ajax_call';
    }
    else
    {
      $form['class'].=' no_ajax_call';
    }
  }
  else
  {
    $form['class'].=' no_ajax_call_set';
  }

  echo '<form action="'.$form['action'].'" method="'.$form['method'].'" class="'.$form['class'].'">';
  
  foreach ($form['inputs'] as $input) 
  {
    
    // write form inputs
    $input["add_one_more"] = isset($input["add_one_more"]) ? $input["add_one_more"] : FALSE;
    
    echo '<div class="form-group '. (isset($input['class'])? $input['class'] : NULL) .'">'; // input wraper
    if(isset($input['group']))
    {
      if(is_array($input['group']) AND !empty($input['group']))
      {
        if(isset($input["label"]))
        {
          echo '<label class="control-label"><span>'.$input["label"].'</span>';
          // si se permite agregar otro elemento del mismo tipo, cargar el boton
          echo $input["add_one_more"] ? ' <a class="form_builder_helper_add_one_more btn default btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i></a> <a class="form_builder_helper_add_one_less btn default btn-sm"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>' : '';
          echo '</label>';
        }
        
        echo '<div class="input_bag-z"><div class="input-group-z">';
        foreach($input['group'] as $input)
        {
          echo_input($input,TRUE);
        }
        echo '</div></div>';
      }
    }
    elseif(isset($input['inputtable']))
    {
      // espero una variable en x, otra en y y los campos con los datos que se corresponden con esas coordenadas
      /* OPCIONES */
      // add_one_x: bool default true
      $input['inputtable']['add_one_x'] = ( isset($input['inputtable']['add_one_x']) AND is_bool($input['inputtable']['add_one_x']) ) ? $input['inputtable']['add_one_x'] : TRUE;
      // add_one_y: bool default true
      $input['inputtable']['add_one_y'] = ( isset($input['inputtable']['add_one_y']) AND is_bool($input['inputtable']['add_one_y']) ) ? $input['inputtable']['add_one_y'] : TRUE;
		  // min_x_elements: casilleros para mostrar inicialmente,  default 1
      $input['inputtable']['min_x_elements'] = ( isset($input['inputtable']['min_x_elements']) AND is_integer($input['inputtable']['min_x_elements']) ) ? $input['inputtable']['min_x_elements'] : 1;
			// min_y_elements: casilleros para mostrar inicialmente,  default 1
      $input['inputtable']['min_y_elements'] = ( isset($input['inputtable']['min_y_elements']) AND is_integer($input['inputtable']['min_y_elements']) ) ? $input['inputtable']['min_y_elements'] : 1;
			// max_x_elements: numero maximo de elementos para aregragar, default 0 = ilimitado
      $input['inputtable']['max_x_elements'] = ( isset($input['inputtable']['max_x_elements']) AND is_integer($input['inputtable']['max_x_elements']) ) ? $input['inputtable']['max_x_elements'] : 0;
			// max_y_elements: numero maximo de elementos para aregragar, default 0 = ilimitado
      $input['inputtable']['max_y_elements'] = ( isset($input['inputtable']['max_y_elements']) AND is_integer($input['inputtable']['max_y_elements']) ) ? $input['inputtable']['max_y_elements'] : 0;
      // xy_label: label en la esquina de la tabla, default NULL
      $input['inputtable']['xy_label'] = ( isset($input['inputtable']['xy_label']) AND is_string($input['inputtable']['xy_label']) ) ? $input['inputtable']['xy_label'] : NULL;
      
      if(isset($input["label"]))
      {
        echo '<label class="control-label">'.$input["label"].'</label>';
      }

      echo '<div class="table gridinput">
        <!-- los datos -->
        <div class="table-row">
          <div class="table-cell">
            <div 
              data-maxyelements="'.$input['inputtable']['max_y_elements'].'" 
              data-maxxelements="'.$input['inputtable']['max_x_elements'].'" 
              data-minyelements="'.$input['inputtable']['min_y_elements'].'" 
              data-minxelements="'.$input['inputtable']['min_x_elements'].'"
              class="table" '.(isset($input["id"]) ? 'id="'.$input["id"].'"' : '' ).'>';
        // eje y, files de la tabla
        for ($inputtableycount=0; $inputtableycount < $input['inputtable']['min_y_elements']; $inputtableycount++) 
        { 
          // imprimo las filas del header
          echo '<div class="header table-row">';
          // imprimo el label para y
         
          // cargo columanas vacias tomar la columan de los datos adicionales
          foreach($input['inputtable']['y'] as $y)
            echo '<div class="table-cell"></div>';

          for ($inputtablexcount=0; $inputtablexcount < $input['inputtable']['min_x_elements']; $inputtablexcount++) 
          { 
            // imprimo las columanas del header
            echo '<div class="table-cell">';
            foreach($input['inputtable']['x'] as $x)
            {
                echo_input($x);
            }
            echo '</div>';
          }
          echo '</div><!-- fin .header.table-row -->';

          // imprimo las filas del body
          echo '<div class="body table-row">';
          // imprimo el label para y
          foreach($input['inputtable']['y'] as $y)
          {
            echo '  <div class="table-cell">';
              echo_input($y);
            echo '  </div>';
          }

          for ($inputtablexcount=0; $inputtablexcount < $input['inputtable']['min_x_elements']; $inputtablexcount++) 
          { 
            // imprimo las columanas del body
            echo '<div class="table-cell">';
            echo_input($input['inputtable']['values']);
            echo '</div>';
          }
          echo '</div><!-- fin .body.table-row -->';

        }
        echo '</div> <!-- fin .table.gridinput -->';

        if($input['inputtable']['add_one_x'] == TRUE)
        {
          echo '</div> <!-- fin .table-cell -->';
            // agrego el boton de agregar columan si hace falta
            echo '<div class="table-cell"><a data-addtoid="price-schedule" class="add-column btn btn-lg default"><i class="fa fa-plus-circle" aria-hidden="true"></i></a> <a data-remtoid="price-schedule" class="rem-column btn btn-lg default"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div> </div> <!-- fin .table-cell boton -->';
          echo '</div> <!-- fin .table-cell -->';
        }

        // agrego el boton de agregar una fila si hace falta
        if($input['inputtable']['add_one_y'] == TRUE)
        {
          echo '<div clas="table-row">';
            echo '<div class="table-cell"><a data-addtoid="price-schedule" class="add-row btn btn-lg default"><i class="fa fa-plus-circle" aria-hidden="true"></i></a> <a data-remtoid="price-schedule" class="rem-row btn btn-lg default"><i class="fa fa-minus-circle" aria-hidden="true"></i></a></div>';
          echo '</div> <!-- fin .table-row -->';
        }
      echo '</div> <!-- fin .table -->';

    }
    else
    {
      echo_input($input,FALSE);
    }
    echo '</div>'; // end input wraper

  }
  
  // botones
    // defaults
  $form['submit_button_text'] = isset($form['submit_button_text']) ? $form['submit_button_text'] : 'Aceptar';
  $form['submit_button_class'] = isset($form['submit_button_class']) ? $form['submit_button_class'] : NULL;
  echo '<div class="row"><div class="col-sm-12">';
  echo '<button type="submit" class="'.$form['submit_button_class'].' btn btn-info">'.$form['submit_button_text'].'</button>';
  echo '</div></div>';
  
  echo '</form>';
}

// input count
$input_count = 0;

function echo_input($input,$group = FALSE)
{
  global $input_count;
  $input_count++;
  // defaults
  $input["name"] = isset($input["name"]) ? $input["name"] : $input["type"].$input_count;
  $input["id"] = isset($input["id"]) ? $input["id"] : str_replace('[]','',$input["name"]);
  // $group = $group ? 'input-group-addon' : NULL;
  $input["add_one_more"] = isset($input["add_one_more"]) ? $input["add_one_more"] : FALSE;
  $input["type"] = isset($input["type"]) ? $input["type"] : 'text';
  $input["value"] = isset($input["value"]) ? $input["value"] : NULL;
  $input["placeholder"] = isset($input["placeholder"]) ? $input["placeholder"] : NULL;
  $input["class"] = isset($input["class"]) ? $input["class"] : NULL;
  $input["class"] .= ' '.str_replace('[]','',$input["name"]);
  $input["prefixbox"] = isset($input["prefixbox"]) ? $input["prefixbox"] : NULL;
  $input["sufixbox"] = isset($input["sufixbox"]) ? $input["sufixbox"] : NULL;
  $input["required"] = isset($input["required"]) ? ($input["required"] ? 'required' : NULL ) : NULL;
  $input["multiple"] = isset($input["multiple"]) ? ($input["multiple"] ? 'multiple' : NULL ) : NULL;
  $input["help"] = isset($input["help"]) ? $input["help"] : NULL;
  $input["data"] = isset($input["data"]) ? $input["data"] : array();
  $input["style"] = isset($input["style"]) ? 'style="'.$input["style"].'"' : NULL;

  // defino el valor title para help
  $title = $input["help"] ? 'data-toggle="tooltip" title="'.$input["help"].'"' : '';

    
  // labels
  if(isset($input["label"]))
  {
    echo '<label class="control-label"><span>'.$input["label"].'</span> <span>'.($input["required"] ? '*' : '').'</span>';
    // si se permite agregar otro elemento del mismo tipo, cargar el boton
    echo $input["add_one_more"] ? ' <a class="form_builder_helper_add_one_more btn default btn-sm" data-name="'.$input["name"].'"><i class="fa fa-plus-circle" aria-hidden="true"></i></a> <a class="form_builder_helper_add_one_less btn default btn-sm"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>' : '';

    echo '</label>';
  }

  // si tiene el rpefix box
  if($input['prefixbox'] OR $input['sufixbox'])
    echo '<div class="input-group">';
  
  if($input['prefixbox'])
    echo '<div class="input-group-addon">'.$input['prefixbox'].'</div>';
  
  // los atributos comunes a todos los imputs
  $common_attr_minimal     = $title . ' id="'.$input["id"].'" class="form-control input-group-z-element '.$input['class'].'" '.$input["style"].' '.prepare_data($input["data"]);
  // los atributos comues a todos los inputs
  $common_attr_TextArea    = $common_attr_minimal . ' ' . $input["required"] . ' type="'.$input["type"].'" placeholder="'.$input["placeholder"].'"';
  // los atributos propios del textarea
  $common_attr_notTextArea = $common_attr_TextArea . ' name="'.$input["name"].'" value="'.$input["value"].'"';
  
  switch ($input["type"]) 
  {
    case 'datetime-local':
      $input['value'] = date("Y-m-d\TH:i",strtotime($input['value']));
      echo '<input '.$common_attr_notTextArea.'>';
      break;
    case 'file':
      echo '<div id="'.$input["id"].'_preview" class="preview"></div>';
    case 'time':
    case 'date':
    case 'text':
    case 'number':
    case 'email':
    case 'hidden':
      echo '<input '.$input["multiple"].' '.$common_attr_notTextArea.'>';
      break;
    case 'textarea':
      echo '<textarea  id="'.$input["id"].'" '.$common_attr_TextArea.'>'.$input["value"].'</textarea>';
      break;
    case 'button':
      echo '<a '.$common_attr_minimal.'>'.$input["placeholder"].'</a>';
      break;
    case 'select':
    case 'radio':
    case 'checkbox':
      // defaults
      $input["options"] = isset($input["options"]) ? $input["options"] : array();
      
      // si es un select
      if($input["type"] === "select")
      {
        echo '<select '.$title.' '.$input["required"].' id="'.$input["id"].'" name="'.$input["name"].'" class="form-control input-group-z-element '.$input['class'].'">';
        // placehoder select
        echo '<option value="">'.$input["placeholder"].'</option>';
        foreach ($input["options"] as $value => $text) 
        {
          // check selected
          
          if(is_array($text))
          {
            // si el key es un array, esoty agrupando
            if(count($text) > 1)
              echo '<optgroup label="'.$value.'">';

            foreach ($text as $group_key => $group_text)
            {
              $selected = $group_key == $input['value'] ? 'selected' : NULL;
              echo '<option '.$selected.' value="'.$group_key.'">'.$group_text.'</option>';
            }
            
            if(count($text) > 1)
              echo '</optgroup>';
          }
          else
          {
            $selected = $value == $input['value'] ? 'selected' : NULL;
            echo '<option '.$selected.' value="'.$value.'">'.$text.'</option>';
          }
        }
        echo '</select>';
      }
      
      // si es un radio o checkbox
      if($input["type"] === "radio" OR $input["type"] === "checkbox")
      {
        if(is_array($input["options"]))
        {
          foreach ($input["options"] as $value => $text) 
          {
            $selected = $value == $input['value'] ? 'checked' : NULL;
            echo '<label class="'.$input["type"].'-inline">';
            echo '<input '.$title.' '.$input["required"].' '.$selected.' type="'.$input['type'].'" name="'.$input['name'].'" value="'.$value.'"> '.$text;
            echo '</label>';
          }
        }
      }

      break;
  }

  if($input['sufixbox'])
    echo '<div class="input-group-addon">'.$input['sufixbox'].'</div>';

  
  if($input['prefixbox'] OR $input['sufixbox'])
    echo '</div> <!-- fin prefixbox -->';
}

function prepare_data($data)
{
  if(!is_array($data)) return FALSE;
  $return = "";
  foreach ($data as $data_key => $data_value) 
  {
    $return .= "data-$data_key=\"$data_value\" ";
  }
  return $return;
}

?>