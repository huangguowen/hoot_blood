<?php 

	//获取clientIP
 function getip() {
$cip = (isset($_SERVER['HTTP_CLIENT_IP']) AND $_SERVER['HTTP_CLIENT_IP'] != "") ? $_SERVER['HTTP_CLIENT_IP'] : FALSE;
		$rip = (isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] != "") ? $_SERVER['REMOTE_ADDR'] : FALSE;
		$fip = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND $_SERVER['HTTP_X_FORWARDED_FOR'] != "") ? $_SERVER['HTTP_X_FORWARDED_FOR'] : FALSE;

		if ($cip && $rip)	$IP = $cip;
		elseif ($rip)		$IP = $rip;
		elseif ($cip)		$IP = $cip;
		elseif ($fip)		$IP = $fip;

		if (strpos($IP, ',') !== FALSE)
		{
			$x = explode(',', $IP);
			$IP = end($x);
		}

		if ( ! preg_match( "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/", $IP))
		{
			$IP = '0.0.0.0';
		}

		unset($cip);
		unset($rip);
		unset($fip);

		return $IP;
	}
 ?>