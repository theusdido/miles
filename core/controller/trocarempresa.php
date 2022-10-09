<?php
if (isset($_GET["empresa"])){
	$session 				= Session::get();
	$session->autenticado 	= true;
	$session->userid 		= $session->userid;
	$session->username 		= $session->username;
	$session->empresa		= $_GET["empresa"];
	$session->projeto		= 1;	
	Session::set($session);	
}	
echo("<script>parent.location.href = 'index.php';</script>");