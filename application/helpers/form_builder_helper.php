<?php 
/**
 * CodeIgniter Helper extensions
 * @package	CodeIgniter
 * @author	Fracisco Javier Machado
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
function array2form($form = array())
{
	if (empty($form)) {
		echo "Form vacio, nada para escribir";
		return false;
	}

  // defaults
	$form['action'] = isset($form['action']) ? $form['action'] : null;
	$form['method'] = isset($form['method']) ? $form['method'] : 'post';
	$form['class'] = isset($form['class']) ? explode_espacios($form['class']) : array();
	$form['style'] = isset($form['style']) ? $form['style'] : null;
	$form['ajax_call'] = isset($form['ajax_call']) ? $form['ajax_call'] : 0;
  
  // agrego la clase para pedidos ajax
	if ($form['ajax_call'])
		$form['class'][] = 'ajax_call';
	else
		$form['class'][] = 'no_ajax_call';

	echo '<form action="' . $form['action'] . '" method="' . $form['method'] . '" class="' . implode(' ', $form['class']) . '" ' . (!empty($form['style']) ? 'style="' . $form['style'] . '"' : null) . '>';
	if (isset($form['inputs'])) {
		foreach ($form['inputs'] as $input) {
      // cal input function
			echo_input_general($input);
		}
	}
  
  // botones
    // defaults
	$form['submit_button_text'] = isset($form['submit_button_text']) ? $form['submit_button_text'] : 'Aceptar';
	$form['submit_button_class'] = isset($form['submit_button_class']) ? $form['submit_button_class'] : null;
	echo '<div class="row"><div class="col-sm-12">';
	echo '<button type="submit" class="' . $form['submit_button_class'] . ' btn btn-info">' . $form['submit_button_text'] . '</button>';
	if (isset($form['buttons']) and is_array($form['buttons'])) {
		foreach ($form['buttons'] as $button) {
			if ($button['type'] == 'a')
				echo '<a href="' . (isset($button['attr']['href']) ? $button['attr']['href'] : null) . '" class="btn ' . $button['class'] . '">' . $button['label'] . '</a>';
		}
	}
	echo '</div></div>';

	echo '</form>';
}

// input count
$input_count = 0;

function echo_input($input, $group = false, $sobre_escribir = array())
{
	global $input_count;
	$input_count++;
	$extra = array();
  // defaults
	$input["type"] = isset($input["type"]) ? $input["type"] : 'text';
	$input["name"] = isset($input["name"]) ? $input["name"] : 'input' . $input_count;
	$input["id"] = isset($input["id"]) ? $input["id"] : str_replace('[]', '', $input["name"]);
  // $group = $group ? 'input-group-addon' : NULL;
	$input["add_one_more"] = isset($input["add_one_more"]) ? $input["add_one_more"] : false;
	$input["value"] = isset($input["value"]) ? $input["value"] : null;
	$input["placeholder"] = isset($input["placeholder"]) ? $input["placeholder"] : null;
	$input["class"] = isset($input["class"]) ? $input["class"] : null;
	$input["class"] .= ' ' . str_replace('[]', '', $input["name"]);
	$input["prefixbox"] = isset($input["prefixbox"]) ? $input["prefixbox"] : null;
	$input["sufixbox"] = isset($input["sufixbox"]) ? $input["sufixbox"] : null;
	$input["required"] = isset($input["required"]) ? ($input["required"] ? 'required' : null) : null;
	$input["multiple"] = isset($input["multiple"]) ? ($input["multiple"] ? 'multiple' : null) : null;
	$input["draggable"] = isset($input["draggable"]) ? ($input["draggable"] ? 'draggable' : null) : null;
	$input["help"] = isset($input["help"]) ? $input["help"] : null;
	$input["title"] = isset($input["title"]) ? $input["title"] : null;
	$input["data"] = isset($input["data"]) ? $input["data"] : array();
	$input["style"] = isset($input["style"]) ? 'style="' . $input["style"] . '"' : null;
	$input["inputs"] = isset($input["inputs"]) ? $input["inputs"] : array();

  // check overrides
	if (!empty($sobre_escribir) and is_array($sobre_escribir)) {
		foreach ($sobre_escribir as $key => $value) {
			$input[$key] = $value;
		}
	}

  // defino el valor title para help si esta definido si no title
	$title = $input["help"] ? 'data-toggle="tooltip" title="' . $input["help"] . '"' : 'title="' . $input["title"] . '"';
    
  // labels
	if (isset($input["label"])) {
		echo '<label class="control-label ' . $input["name"] . '"><span>' . $input["label"] . '</span> <span>' . ($input["required"] ? '*' : '') . '</span>';
    // si se permite agregar otro elemento del mismo tipo, cargar el boton
		echo $input["add_one_more"] ? ' <a class="form_builder_helper_add_one_more btn default btn-sm ' . $input["name"] . '" data-name="' . $input["name"] . '"><i class="fa fa-plus-circle" aria-hidden="true"></i></a> <a class="form_builder_helper_add_one_less btn default btn-sm"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>' : '';

		echo '</label>';
	}

  // si tiene el rpefix box
	if ($input['prefixbox'] or $input['sufixbox'])
		echo '<div class="input-group">';

	if ($input['prefixbox'])
		echo '<div class="input-group-addon">' . $input['prefixbox'] . '</div>';

  // excepciones
	if ($input["type"] == "date") {
		$input['type'] = 'text';
		$extra[] = 'onfocus="(this.type=\'date\')"'; // lo hago para mostrar el placeholder y el formato de fecha cuando esta en focus
		$extra[] = 'onblur="(this.type=\'text\')"'; // lo hago para mostrar el placeholder cuando se saca el cursor
	}
  
  // tomar otras atributos si se definieron
	$custom_attr = (isset($input["attr"]) and is_array($input["attr"])) ? prepare_attr($input["attr"]) : '';
  // los atributos comunes a todos los imputs
	$common_attr_minimal = $title . ' id="' . $input["id"] . '" class="form-control input-group-z-element ' . $input['class'] . ' ' . ($input["help"] ? ' z_tooltip ' : null) . ' " ' . $input["style"] . ' ' . prepare_data($input["data"]) . ' ' . $custom_attr;
  // los atributos comues a todos los inputs
	$common_attr_TextArea = $common_attr_minimal . ' name="' . $input["name"] . '" ' . $input["required"] . ' type="' . $input["type"] . '" placeholder="' . $input["placeholder"] . '"';
  // los atributos propios del textarea
	$common_attr_notTextArea = $common_attr_TextArea . ' name="' . $input["name"] . '" value="' . $input["value"] . '" ' . implode(' ', $extra);

	switch ($input["type"]) {
		case 'datetime-local':
			$input['value'] = date("Y-m-d\TH:i", strtotime($input['value']));
			echo '<input ' . $common_attr_notTextArea . '>';
			break;
		case 'file':
			echo '<div id="' . $input["id"] . '_preview" class="preview"></div>';
		case 'password':
			echo '<input ' . $input["multiple"] . ' ' . $common_attr_notTextArea . '>';
			break;
		case 'date':
		case 'time':
		case 'text':
		case 'number':
		case 'email':
		case 'hidden':
			echo '<input ' . $input["multiple"] . ' ' . $common_attr_notTextArea . '>';
			break;
		case 'textarea':
			echo '<textarea  id="' . $input["id"] . '" ' . $common_attr_TextArea . '>' . $input["value"] . '</textarea>';
			break;
		case 'button':
			echo '<a ' . $common_attr_minimal . '>' . $input["placeholder"] . '</a>';
			break;
		case 'select':
		case 'radio':
		case 'checkbox':
      // defaults
			$input["options"] = isset($input["options"]) ? $input["options"] : array();
      
      // si es un select
			if ($input["type"] === "select") {
				echo '<select ' . $title . ' ' . $input["required"] . ' id="' . $input["id"] . '" name="' . $input["name"] . '" class="form-control input-group-z-element ' . $input['class'] . '">';
        // placehoder select
				echo '<option value="">' . $input["placeholder"] . '</option>';
				foreach ($input["options"] as $value => $text) {
          // check selected

					if (is_array($text)) {
            // si el key es un array, esoty agrupando
						if (count($text) > 1)
							echo '<optgroup label="' . $value . '">';

						foreach ($text as $group_key => $group_text) {
							$selected = $group_key == $input['value'] ? 'selected' : null;
							echo '<option ' . $selected . ' value="' . $group_key . '">' . $group_text . '</option>';
						}

						if (count($text) > 1)
							echo '</optgroup>';
					} else {
						$selected = $value == $input['value'] ? 'selected' : null;
						echo '<option ' . $selected . ' value="' . $value . '">' . $text . '</option>';
					}
				}
				echo '</select>';
			}
      
      // si es un radio o checkbox
			if ($input["type"] === "radio" or $input["type"] === "checkbox") {
				if (is_array($input["options"])) {
					foreach ($input["options"] as $value => $text) {
            // en el caso de que text sea un array en el que paso otros elementos
						if (is_array($text) and !empty($text)) {
              // espero ciertos keys como text, img, span
							$text_post = '';
							foreach ($text as $key1 => $value1) {
								switch ($key1) {
									case 'img':
										$text_post .= "<img src='" . base_url() . "$value1'>";
										break;
									default:
										$text_post .= "<span class='$key1'>$value1</span>";
										break;
								}
							}
							$text = $text_post;

						}
						$selected = $value == $input['value'] ? 'checked' : null;
						echo '<label class="' . $input["type"] . '-inline ' . $input["class"] . '">';
						echo '<input ' . $title . ' ' . $input["required"] . ' ' . $selected . ' type="' . $input['type'] . '" name="' . $input['name'] . '" value="' . $value . '"> ' . $text;
						echo '</label>';
					}
				}
			}

			break;
		default:
			echo '<' . $input["type"] . ' ' . $common_attr_minimal . '>' . $input["placeholder"] . '</a>';
	}

	if ($input['sufixbox']) {
		echo '<div class="input-group-addon">' . $input['sufixbox'] . '</div>';
	}


	if ($input['prefixbox'] or $input['sufixbox']) {
		echo '</div> <!-- fin prefixbox -->';
	}

}

function echo_input_general($input)
{
  // write form inputs
	$input["add_one_more"] = isset($input["add_one_more"]) ? $input["add_one_more"] : false;
	$input["container"] = isset($input["container"]) ? $input["container"] : false;
	$input["class"] = isset($input["class"]) ? $input["class"] : false;
	$input["id"] = isset($input["id"]) ? $input["id"] : false;
	$input["label"] = isset($input["label"]) ? $input["label"] : null;
  // $input["class"][] = "container";
	if ($input["container"]) // veo si debo agruparlos
	{
		echo '<!-- input container --><div id="' . $input["id"] . '" class="' . $input["class"] . '"><label style="line-height:32px">' . $input["label"] . '</label>';
		foreach ($input["container"] as $input_contained) {
			inputSwitch($input_contained);
		}
		if ($input["container"]) echo '</div><!-- end input container -->';
	} else {
		inputSwitch($input);
	}

}

// define si es un grupo, tabla de inputs o simplemente el input
function inputSwitch($input)
{
	echo '<div class="form-group ' . (isset($input['class']) ? $input['class'] : null) . '">'; // input wraper
	if (isset($input['group'])) {
		if (is_array($input['group']) and !empty($input['group'])) {
			if (isset($input["label"])) {
				echo '<label class="control-label"><span>' . $input["label"] . '</span>';
        // si se permite agregar otro elemento del mismo tipo, cargar el boton
				echo $input["add_one_more"] ? ' <a class="form_builder_helper_add_one_more btn default btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i></a> <a class="form_builder_helper_add_one_less btn default btn-sm"><i class="fa fa-minus-circle" aria-hidden="true"></i></a>' : '';
				echo '</label>';
			}

			echo '<div class="input_bag-z"><div class="input-group-z">';
			foreach ($input['group'] as $input_element) {
				echo_input($input_element, true);
			}
			echo '</div></div>';
		}
	} elseif (isset($input['inputtable'])) {
    // espero una variable en x, otra en y y los campos con los datos que se corresponden con esas coordenadas
    /* OPCIONES */
    // add_one_x: bool default true
		$input['inputtable']['add_one_x'] = (isset($input['inputtable']['add_one_x']) and is_bool($input['inputtable']['add_one_x'])) ? $input['inputtable']['add_one_x'] : true;
    // add_one_y: bool default true
		$input['inputtable']['add_one_y'] = (isset($input['inputtable']['add_one_y']) and is_bool($input['inputtable']['add_one_y'])) ? $input['inputtable']['add_one_y'] : true;
    // min_x_elements: casilleros para mostrar inicialmente,  default 1
		$input['inputtable']['min_x_elements'] = (isset($input['inputtable']['min_x_elements']) and is_integer($input['inputtable']['min_x_elements'])) ? $input['inputtable']['min_x_elements'] : 1;
    // min_y_elements: casilleros para mostrar inicialmente,  default 1
		$input['inputtable']['min_y_elements'] = (isset($input['inputtable']['min_y_elements']) and is_integer($input['inputtable']['min_y_elements'])) ? $input['inputtable']['min_y_elements'] : 1; // fix: duplica mal los campos, incluye los del header, no debería
    // max_x_elements: numero maximo de elementos para aregragar, default 0 = ilimitado
		$input['inputtable']['max_x_elements'] = (isset($input['inputtable']['max_x_elements']) and is_integer($input['inputtable']['max_x_elements'])) ? $input['inputtable']['max_x_elements'] : 0; // fix cuando el min es igual max, aun muestra el boton que haria exeder el rango
    // max_y_elements: numero maximo de elementos para aregragar, default 0 = ilimitado
		$input['inputtable']['max_y_elements'] = (isset($input['inputtable']['max_y_elements']) and is_integer($input['inputtable']['max_y_elements'])) ? $input['inputtable']['max_y_elements'] : 0; // fix cuando el min es igual max, aun muestra el boton que haria exeder el rango
    // xy_label: label en la esquina de la tabla, default NULL
		$input['inputtable']['xy_label'] = (isset($input['inputtable']['xy_label']) and is_string($input['inputtable']['xy_label'])) ? $input['inputtable']['xy_label'] : null;
    // labels de los botones para gregar o quitar campos
      // iconos
		$input['inputtable']['minus_x_icon'] = isset($input['inputtable']['minus_x_icon']) ? $input['inputtable']['minus_x_icon'] : '<i class="fa fa-minus-circle" aria-hidden="true"></i>';
		$input['inputtable']['plus_x_icon'] = isset($input['inputtable']['plus_x_icon']) ? $input['inputtable']['plus_x_icon'] : '<i class="fa fa-plus-circle" aria-hidden="true"></i>';
		$input['inputtable']['minus_y_icon'] = isset($input['inputtable']['minus_y_icon']) ? $input['inputtable']['minus_y_icon'] : '<i class="fa fa-minus-circle" aria-hidden="true"></i>';
		$input['inputtable']['plus_y_icon'] = isset($input['inputtable']['plus_y_icon']) ? $input['inputtable']['plus_y_icon'] : '<i class="fa fa-plus-circle" aria-hidden="true"></i>';
      // textos
		$input['inputtable']['minus_x_label'] = isset($input['inputtable']['minus_x_label']) ? $input['inputtable']['minus_x_label'] : null;
		$input['inputtable']['plus_x_label'] = isset($input['inputtable']['plus_x_label']) ? $input['inputtable']['plus_x_label'] : null;
		$input['inputtable']['minus_y_label'] = isset($input['inputtable']['minus_y_label']) ? $input['inputtable']['minus_y_label'] : null;
		$input['inputtable']['plus_y_label'] = isset($input['inputtable']['plus_y_label']) ? $input['inputtable']['plus_y_label'] : null;
		$input['inputtable']['plus_x_title'] = isset($input['inputtable']['plus_x_label']) ? $input['inputtable']['plus_x_label'] : 'Agregar';
		$input['inputtable']['plus_y_title'] = isset($input['inputtable']['plus_y_label']) ? $input['inputtable']['plus_y_label'] : 'Agregar';
		$input['inputtable']['minus_x_title'] = isset($input['inputtable']['minus_x_label']) ? $input['inputtable']['minus_x_label'] : 'Quitar';
		$input['inputtable']['minus_y_title'] = isset($input['inputtable']['minus_y_label']) ? $input['inputtable']['minus_y_label'] : 'Quitar';

		if (isset($input["label"]))
			echo '<label class="control-label">' . $input["label"] . '</label>';

		echo '<div class="table gridinput">
      <!-- los datos -->
      <div class="table-row">
        <div class="table-cell">
          <div 
            data-maxyelements="' . $input['inputtable']['max_y_elements'] . '" 
            data-maxxelements="' . $input['inputtable']['max_x_elements'] . '" 
            data-minyelements="' . $input['inputtable']['min_y_elements'] . '" 
            data-minxelements="' . $input['inputtable']['min_x_elements'] . '"
            class="table" ' . (isset($input["id"]) ? 'id="' . $input["id"] . '"' : '') . '>';
      // eje y, files de la tabla
		for ($inputtableycount = 0; $inputtableycount < $input['inputtable']['min_y_elements']; $inputtableycount++) { 
        // imprimo las filas del header
			echo '<div class="header table-row">';
        // imprimo el label para y
      
        // cargo columanas vacias tomar la columan de los datos adicionales
			foreach ($input['inputtable']['y'] as $y)
				echo '<div class="table-cell"></div>';

			for ($inputtablexcount = 0; $inputtablexcount < $input['inputtable']['min_x_elements']; $inputtablexcount++) { 
          // imprimo las columanas del header
				echo '<div class="table-cell">';
				foreach ($input['inputtable']['x'] as $x) {
					echo_input($x);
				}
				echo '</div>';
			}
			echo '</div><!-- fin .header.table-row -->';

        // imprimo las filas del body
			echo '<div class="body table-row">';
        // imprimo el label para y
			foreach ($input['inputtable']['y'] as $y) {
				echo '  <div class="table-cell">';
				echo_input($y);
				echo '  </div>';
			}

			for ($inputtablexcount = 0; $inputtablexcount < $input['inputtable']['min_x_elements']; $inputtablexcount++) { 
          // imprimo las columanas del body
				echo '<div class="table-cell">';
				echo_input($input['inputtable']['values']);
				echo '</div>';
			}
			echo '</div><!-- fin .body.table-row -->';

		}
		echo '</div> <!-- fin .table -->';

		if ($input['inputtable']['add_one_x'] == true) {
			echo '</div> <!-- fin .table-cell -->
        <div class="table-cell">
            <a data-addtoid="price-schedule" class="add-column btn btn-lg default" title="' . strip_tags($input['inputtable']['plus_x_title']) . '">
              ' . $input['inputtable']['plus_x_icon'] . ' ' . $input['inputtable']['plus_x_label'] . '
            </a> 
            <a data-remtoid="price-schedule" class="rem-column btn btn-lg default" title="' . strip_tags($input['inputtable']['minus_x_title']) . '">
              ' . $input['inputtable']['minus_x_icon'] . ' ' . $input['inputtable']['minus_x_label'] . '
            </a>
          </div> <!-- fin .table-cell boton -->
        </div> <!-- fin .table-cell -->';
		}

      // agrego el boton de agregar una fila si hace falta
		if ($input['inputtable']['add_one_y'] == true) {
			echo '<div clas="table-row">
          <div class="table-cell">
            <a data-addtoid="price-schedule" class="add-row btn btn-lg default" title="' . strip_tags($input['inputtable']['plus_y_title']) . '">
              ' . $input['inputtable']['plus_y_icon'] . ' ' . $input['inputtable']['plus_y_label'] . '
            </a> 
            <a data-remtoid="price-schedule" class="rem-row btn btn-lg default" title="' . strip_tags($input['inputtable']['minus_y_title']) . '">
              ' . $input['inputtable']['minus_y_icon'] . ' ' . $input['inputtable']['minus_y_label'] . '
            </a>
          </div>
        </div> <!-- fin .table-row -->';
		}
		echo '</div> <!-- fin .table -->';

	} else {
		echo_input($input, false);
	}
	echo '</div>'; // end input wraper
}

function prepare_data($data = null)
{
	return prepare_attr($data, $prefix = 'data');
}

function prepare_attr($data = null, $prefix = null)
{
	if (!is_array($data)) return false;

	if ($prefix !== null) $prefix .= '-';

	$return = "";

	foreach ($data as $data_key => $data_value)
		$return .= "$prefix$data_key=\"$data_value\" ";

	return $return;
}

function explode_espacios($str)
{
	if (!is_array($str))
		$array = explode(' ', $str);
	else
		$array = $str;

	$explodes = array();
  // veo que cada elemento sea una palabra
	foreach ($array as $key => $elemento) {
		if (strpos($elemento, ' ')) {
			$explodes = array_merge($explodes, explode(' ', $elemento));
      // unset this element si ya lo meti en el array explodes
			unset($array[$key]);
		}
	}
  // agregar las clases explodes al final y devolver
	return array_merge($array, $explodes);
}

function clean_special_chars($string)
{
	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

	return preg_replace('/[^A-Za-z0-9\-\.\_]/', '', $string); // Removes special chars.
}

?>