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
          echo '<label class="control-label">'.$input["label"];
          // si se permite agregar otro elemento del mismo tipo, cargar el boton
          echo $input["add_one_more"] ? ' <a class="form_builder_helper_add_one_more btn green btn-xs">+</a>' : '';
          echo '</label>';
        }
        
        echo '<div class="input-group-z">';
        foreach($input['group'] as $input)
        {
          echo_input($input,TRUE);
        }
        echo '</div>';
      }
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
  echo '<div><div class="col-sm-12">';
  echo '<button type="submit" class="'.$form['submit_button_class'].' btn btn-info">'.$form['submit_button_text'].'</button>';
  echo '</div></div>';
  
  echo '</form>';
}

function echo_input($input,$group = FALSE)
{

  // defaults
  $input["id"] = isset($input["id"]) ? $input["id"] : $input["name"];
  // $group = $group ? 'input-group-addon' : NULL;
  $input["add_one_more"] = isset($input["add_one_more"]) ? $input["add_one_more"] : FALSE;
  $input["type"] = isset($input["type"]) ? $input["type"] : 'text';
  $input["value"] = isset($input["value"]) ? $input["value"] : NULL;
  $input["placeholder"] = isset($input["placeholder"]) ? $input["placeholder"] : NULL;
  $input["class"] = isset($input["class"]) ? $input["class"] : NULL;
  $input["required"] = isset($input["required"]) ? ($input["required"] ? 'required' : NULL ) : NULL;
    
  // labels
  if(isset($input["label"]))
  {
    echo '<label class="control-label">'.$input["label"].' '.($input["required"] ? '*' : '');
    // si se permite agregar otro elemento del mismo tipo, cargar el boton
    echo $input["add_one_more"] ? ' <a class="form_builder_helper_add_one_more btn green btn-xs" data-name="'.$input["name"].'">+</a>' : '';

    echo '</label>';
  }

  switch ($input["type"]) 
  {
    case 'datetime-local':
      $input['value'] = date("Y-m-d\TH:i",strtotime($input['value']));
      echo '<input '.$input["required"].' type="'.$input["type"].'" name="'.$input["name"].'" class="form-control input-group-z-element" placeholder="'.$input["placeholder"].'" value="'.$input["value"].'">';
      break;
    case 'time':
    case 'date':
    case 'text':
    case 'number':
    case 'email':
    case 'hidden':
      echo '<input '.$input["required"].' type="'.$input["type"].'" name="'.$input["name"].'" class="form-control input-group-z-element" placeholder="'.$input["placeholder"].'" value="'.$input["value"].'">';
      break;
    case 'textarea':
      echo '<textarea '.$input["required"].' name="'.$input["name"].'" class="form-control input-group-z-element" placeholder="'.$input["placeholder"].'">'.$input["value"].'</textarea>';
      break;
    case 'select':
    case 'radio':
    case 'checkbox':
      // defaults
      $input["options"] = isset($input["options"]) ? $input["options"] : array();
      
      // si es un select
      if($input["type"] === "select")
      {
        echo '<select '.$input["required"].' name="'.$input["name"].'" class="form-control input-group-z-element">';
        // placehoder select
        echo '<option value="">'.$input["placeholder"].'</option>';
        foreach ($input["options"] as $value => $text) 
        {
          // check selected
          $selected = $value == $input['value'] ? 'selected' : NULL;
          echo '<option '.$selected.' value="'.$value.'">'.$text.'</option>';
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
            echo '<input '.$input["required"].' '.$selected.' type="'.$input['type'].'" name="'.$input['name'].'" value="'.$value.'"> '.$text;
            echo '</label>';
          }
        }
      }

      break;
  }
  
  if(isset($input["label"]))
  {
    echo '</label>';
  }
}

?>