<?php
	// Mês 
	$entidadeNome 	= PREFIXO . "diasemana";
	$campos			= array("dia","mes","nome");

	// Registros
	inserirRegistro($conn,PREFIXO . "charset",1,  $campos, array(1,1,'Confraternização Universal'));
	inserirRegistro($conn,PREFIXO . "charset",2,  $campos, array(21,4,'Tiradentes'));
	inserirRegistro($conn,PREFIXO . "charset",3,  $campos, array(1,5,'Dia do Trabalho'));
	inserirRegistro($conn,PREFIXO . "charset",4,  $campos, array(11,6,'Corpus Chirsti'));
	inserirRegistro($conn,PREFIXO . "charset",5,  $campos, array(7,9,'Independencia do Brasil'));
	inserirRegistro($conn,PREFIXO . "charset",6,  $campos, array(12,10,'Nossa Sr.a Aparecida - Padroeira do Brasil'));
	inserirRegistro($conn,PREFIXO . "charset",7,  $campos, array(2,11,'Finados'));
	inserirRegistro($conn,PREFIXO . "charset",8,  $campos, array(15,11,'Proclamação da República'));
	inserirRegistro($conn,PREFIXO . "charset",9,  $campos, array(25,12,'Natal'));