<?php 

	//获取clientIP
    function getIp() { 
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) $ip = getenv("HTTP_CLIENT_IP"); 
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) $ip = getenv("HTTP_X_FORWARDED_FOR"); 
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) $ip = getenv("REMOTE_ADDR"); 
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) $ip = $_SERVER['REMOTE_ADDR']; 
    else $ip = "unknown"; 
    return ($ip); 
} 

//制作option name=name value=value
	function setoption($name,$array){
		$option = '';
			foreach($array as $k=>$v){
					$option .= "<option value=$v>$k</option>";
			}
			return $option;
	}
//图片载入
	function get_photo($name){
		return base_url() . 'static/images/pre.gif';
	}
	//根据键值返回建民
	function get_arrvalue_bykey($arr,$key){
			foreach($arr as $k=>$v){
				if($key == $k){
					return $v;
				}
			}
	}
 ?>