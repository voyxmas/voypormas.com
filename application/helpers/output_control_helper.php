<?php

function xml_enconde($array, SimpleXMLElement $xml, $child_name)
{
    foreach ($array as $k => $v) {
        if(is_array($v)) {
            (is_int($k)) ? $this->arrayToXML($v, $xml->addChild($child_name), $v) : $this->arrayToXML($v, $xml->addChild(strtolower($k)), $child_name);
        } else {
            (is_int($k)) ? $xml->addChild($child_name, $v) : $xml->addChild(strtolower($k), $v);
        }
    }

    return $xml->asXML();
}

?>
