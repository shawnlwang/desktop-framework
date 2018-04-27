<?php
/**
 * 框架公用函数
 * @author laiwenhui
 */

/**
 * 使用反斜线转义单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）。 
 *
 * @param mixed 可以为数组或者字符串
 * @return mixed
 */
function daddslashes($string) {
	if(is_array($string)) {
		$keys = array_keys($string);
		foreach($keys as $key) {
			$val = $string[$key];
			unset($string[$key]);
			$string[addslashes($key)] = daddslashes($val);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

/**
 * 去除转义单引号（'）、双引号（"）、反斜线（\）与 NUL（NULL 字符）的反斜线。 
 *
 * @param mixed 可以为数组或者字符串
 * @return mixed
 */
function dstripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

/**
 * 获得ip
 *
 * @return unknown
 */
function get_client_IP()
{
    $ip = '';
    $requestHeaders = getallheaders();
    if(!empty($requestHeaders) && isset($requestHeaders['Qvia'])) {
        $ip = $requestHeaders['Qvia'];
    }elseif (getenv("HTTP_CLIENT_IP")){
        $ip = getenv("HTTP_CLIENT_IP");
	}elseif(getenv("HTTP_X_FORWARDED_FOR")){
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	}elseif(getenv("REMOTE_ADDR")){
		$ip = getenv("REMOTE_ADDR");
	}else{
		$ip = '127.0.0.1';
	}
	$pos = strpos($ip, ',');
	if( $pos > 0 )
	{
		$ip = substr($ip, 0, $pos);
	}
	return trim($ip);
}
