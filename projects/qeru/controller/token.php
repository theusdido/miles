<?php
	$op 		= $dados["op"];
	$ip			= $_SERVER["HTTP_HOST"];
	$datahora	= date("Ymdhis");
	$sessaophp	= session_id();
	$token 		= md5($sessaophp . $ip . $datahora);

	Session::append("TOKEN",$token);
	$_SESSION["TOKEN"] = $token;

	echo json_encode(array("token" => $token));