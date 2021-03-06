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
function change_urls_to_links($text_input = null)
{
	if ($text_input === null) return null;
	$text_output = strip_tags($text_input);
	$text_output = emailtolink($text_output);
	$text_output = urltolink($text_output);
	$text_output = teltolink($text_output);
	return $text_output;
}

function urltolink($text_input = null, $settings = null)
{
	if ($text_input === null) return null;

	// chequeo settings
	$settings['Texto'] = (isset($settings['Texto']) and !empty($settings['Texto'])) ? $settings['Texto'] : null;
	$settings['Class'] = (isset($settings['Class']) and !empty($settings['Class'])) ? $settings['Class'] : null;
	$settings['Icono'] = isset($settings['Icono']) ? $settings['Icono'] : null;

	$expresion_regular  = "/";
	$expresion_regular .= "(?<!\\\"|'|\>)"; // evito que se reemplacen los textos ya reemplazados, atributos o entre tags
	$expresion_regular .= "(http\:\/\/|https\:\/\/)?"; // pude o no tener http/s
	$expresion_regular .= "([a-zA-Z0-9\-\_]+\.)?"; // posible subdominio
	$expresion_regular .= "(?<!@)"; // filtrar que no sea un email
	$expresion_regular .= "([a-zA-Z0-9\-\_]+)"; // dominio
	$expresion_regular .= "(\.[a-zA-Z]{2,10})"; // tld
	$expresion_regular .= "(\.[a-zA-Z]{2})?"; // local
	$expresion_regular .= "(\/)?"; // posible trailing slash
	$expresion_regular .= "(\/[a-zA-Z0-9\-\_\/\.\%]+)?"; // subdirectorios
	$expresion_regular .= "(\?[a-zA-Z0-9\-\_\=\%&]+)?"; // get atributes
	$expresion_regular .= "(?!=\\\"|'|\<|\w|\/)"; // evito que se reemplacen los textos ya reemplazados, atributos o entre tags
	$expresion_regular .= "/";

	// encontrar los links
	preg_match_all($expresion_regular, $text_input, $links_encontrados);
	// para cada caso reemplazar el texto de la url por el link html
	foreach ($links_encontrados[0] as $key => $link_encontrado) {
			$crum_to_find[$key] = "[:link$key:]";
			if (!empty($link_encontrado)) {
					// ver si tiene el http
					$link_http = strpos($link_encontrado, 'http') !== false ? $link_encontrado : 'http://' . $link_encontrado;

					// ver si tengo que cambiar el texto del link
					if (!empty($settings['Texto'])) {
						$link_anchor_text = $settings['Texto'];
					} else {
						$link_anchor_text = substr($link_encontrado, 0, 42) . (strlen($link_encontrado) > 42 ? '...' : null);
					}

					// creo el link html
					$link_market_html = '<a title="' . $link_encontrado . '" href="' . $link_http . '" target="_blank" class="btn btn-xs btn-info ' . $settings['Class'] . '">';
					// defino si pongo el icono por defecto, uno especifico o si no pongo nada
					$link_market_html .= ($settings['Icono'] === false) ? null : '<i class="' . ($settings['Icono'] === null ? 'fa fa-external-link-square' : $settings['Icono']) . '"></i> ';
					$link_market_html .= $link_anchor_text . '</a>';

					// reemplazo el texto por el link
					$text_input = str_replace($link_encontrado, $crum_to_find[$key], $text_input);
				}
			$crum_replacer[$key] = $link_market_html;
		}
	$text_output = isset($crum_to_find) ? str_replace($crum_to_find, $crum_replacer, $text_input) : $text_input; // si hay algo para reemplazar lo hago

	return $text_output;
}

function emailtolink($text_input = null)
{
	if ($text_input === null) return null;

	$expresion_regular  = "/\b";
	$expresion_regular .= "(?<!mailto:|>)"; // evitar string que empiezan con mailto: o que vienen de un tag >
	$expresion_regular .= "([a-zA-Z0-9\-\_\+\.]+)"; // dominio
	$expresion_regular .= "@"; // dominio
	$expresion_regular .= "([a-zA-Z0-9]+)"; // dominio
	$expresion_regular .= "(\.[a-zA-Z]{2,10})"; // tld
	$expresion_regular .= "(\.[a-zA-Z]{2})?"; // local
	$expresion_regular .= "\b/";

	// echo htmlentities($expresion_regular).'<br>';
	// encontrar los links

	preg_match_all($expresion_regular, $text_input, $links_encontrados);
	foreach (array_unique($links_encontrados[0]) as $key => $link_encontrado) {
			$crum_to_find[$key] = "[:email$key:]";
			if (!empty($link_encontrado)) {
					// creo el link html
					$link_market_html = '<a href="mailto:' . $link_encontrado . '" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-envelope"></i> ' . $link_encontrado . '</a>';
					// reemplazo el texto por el link
					$text_input = str_replace($link_encontrado, $crum_to_find[$key], $text_input);
				}
			$crum_replacer[$key] = $link_market_html;
		}
	$text_input = isset($crum_to_find) ? str_replace($crum_to_find, $crum_replacer, $text_input) : $text_input; // si hay algo para reemplazar lo hago
	return $text_input;
}

function teltolink($text_input = null)
{
	if ($text_input === null) return null;

	$expresion_regular  = "/";
	$expresion_regular .= "(?<!\=|\w)"; // que no tenga antes con un = ni con un caracter de palabra a numero (fuerzo que reconozaca toda la cadena de numeros)
	$expresion_regular .= "[0-9\+\-\(\)]{6,}"; // el numero con caracteres especiales
	$expresion_regular .= "(?!=\&)"; //  que no termine con un & para evitar que lo reconozca en get queries
	$expresion_regular .= "/";

	preg_match_all($expresion_regular, $text_input, $links_encontrados);

	foreach (array_unique($links_encontrados[0]) as $key => $link_encontrado) {
			$crum_to_find[$key] = "[:tel$key:]";
			if (!empty($link_encontrado)) {
					// creo el link html
					$link_market_html = '<a href="tel:' . str_replace(array('(', ')', '-'), '', $link_encontrado) . '" target="_blank" class="btn btn-xs btn-info"><i class="fa fa-phone-square"></i>  ' . $link_encontrado . '</a>';
					// reemplazo el texto por el link
					$text_input = str_replace($link_encontrado, $crum_to_find[$key], $text_input);
				}
			$crum_replacer[$key] = $link_market_html;
		}
	$text_input = isset($crum_to_find) ? str_replace($crum_to_find, $crum_replacer, $text_input) : $text_input; // si hay algo para reemplazar lo hago
	return $text_input;
}

function print_redes_socailes($redes_sociales = null)
{
	if ($redes_sociales === null) return false;

	if (!is_array($redes_sociales))
		$redes_sociales = explode(',', $redes_sociales);


	// check for domain
	$re = '/(https\:\/\/|http\:\/\/)((\w+)\.)?(((\w+)\.(com|net|org)))/';

	foreach ($redes_sociales as $key => $link) {
			$redes_sociales_return 	= null;
			if (empty($link)) continue;

			$matches;
			preg_match($re, $link, $matches);

			$redes_sociales_return[$key]['link'] 	= $link;
			$redes_sociales_return[$key]['red']		= isset($matches[6]) ? $matches[6] : null;

			switch ($redes_sociales_return[$key]['red']) {
				case 'facebook':
					$redes_sociales_return[$key]['icono-class'] = "fa-facebook";
					break;
				case 'google':
					$redes_sociales_return[$key]['icono-class'] = "fa-google-plus";
					break;
				case 'pinterest':
					$redes_sociales_return[$key]['icono-class'] = "fa-pinterest";
					break;
				case 'twitter':
					$redes_sociales_return[$key]['icono-class'] = "fa-twitter";
					break;
				case 'linkedin':
					$redes_sociales_return[$key]['icono-class'] = "fa-linkedin";
					break;
				case 'youtube':
					$redes_sociales_return[$key]['icono-class'] = "fa-youtube";
					break;
				case 'instagram':
					$redes_sociales_return[$key]['icono-class'] = "fa-instagram";
					break;
				case 'tumblr':
					$redes_sociales_return[$key]['icono-class'] = "fa-tumblr";
					break;
				case 'flickr':
					$redes_sociales_return[$key]['icono-class'] = "fa-flickr";
					break;
				default:
					$redes_sociales_return[$key]['icono-class'] = null;
					break;
			}
		}

	return $redes_sociales_return;
}
 