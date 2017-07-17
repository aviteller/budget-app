<?php 


function sortArrayByDates( $a, $b ) {
 	return strtotime($a["date"]) - strtotime($b["date"]);
}

function in_array_r($item , $array){
  return preg_match('/"'.$item.'"/i' , json_encode($array));
}

function encrypt($string, $key){
	$string = rtrim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_ECB)));
	return $string;
}

function decrypt($string, $key){
	$string = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($string), MCRYPT_MODE_ECB));
	return $string;
}

