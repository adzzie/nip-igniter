<?php
function getLabel($string){
	$array = explode("_", $string);
	$arrayUcWord = array_map("ucwords", $array);
	$string = implode(" ", $arrayUcWord);
	return $string;
}

function getStrippedClass($camelCaseClass){
	preg_match_all('/((?:^|[A-Z])[a-z]+)/',$camelCaseClass ,$matches);
	$strippedClass = changeClassName($matches[0]);
	return $strippedClass;
}

function changeClassName($arrClassName = null){
	if($arrClassName){
		$newClass = "";
		foreach ($arrClassName as $i => $value) {
			if($i==0){
				$newClass .= strtolower($value);
			}else{
				if(strtolower($value) == "controller")
					break;
				$newClass .= "-".strtolower($value);
			}
		}
		return $newClass;
	}
}

function debug($var){
	echo "<pre>";
		print_r($var);
	echo "</pre>";
}

if(false === function_exists('lcfirst'))
{
    function lcfirst( $str ) {
        $str[0] = strtolower($str[0]);
        return (string)$str;
    }
}