<?php
	// Mês 
	$entidadeNome 	= PREFIXO . "diasemana";
	$campos			= array("nome");

	// Registros
	inserirRegistro($conn,PREFIXO . "charset",1,  $campos, array("'Domingo'"));
	inserirRegistro($conn,PREFIXO . "charset",2,  $campos, array("'Segunda'"));
	inserirRegistro($conn,PREFIXO . "charset",3,  $campos, array("'Terça'"));
	inserirRegistro($conn,PREFIXO . "charset",4,  $campos, array("'Quarta'"));
	inserirRegistro($conn,PREFIXO . "charset",5,  $campos, array("'Quinta'"));
	inserirRegistro($conn,PREFIXO . "charset",6,  $campos, array("'Sexta'"));
	inserirRegistro($conn,PREFIXO . "charset",7,  $campos, array("'Sábado'"));