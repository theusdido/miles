<?php
	// Mês 
	$entidadeNome 	= PREFIXO . "mes";
	$campos			= array("nome");

	// Registros
	inserirRegistro($conn,PREFIXO . "charset",1,  $campos, array("'Janeiro'"));
	inserirRegistro($conn,PREFIXO . "charset",2,  $campos, array("'Fevereiro'"));
	inserirRegistro($conn,PREFIXO . "charset",3,  $campos, array("'Março'"));
	inserirRegistro($conn,PREFIXO . "charset",4,  $campos, array("'Abril'"));
	inserirRegistro($conn,PREFIXO . "charset",5,  $campos, array("'Maio'"));
	inserirRegistro($conn,PREFIXO . "charset",6,  $campos, array("'Junho'"));
	inserirRegistro($conn,PREFIXO . "charset",7,  $campos, array("'Julho'"));
	inserirRegistro($conn,PREFIXO . "charset",8,  $campos, array("'Agosto'"));
	inserirRegistro($conn,PREFIXO . "charset",9,  $campos, array("'Setembro'"));
	inserirRegistro($conn,PREFIXO . "charset",10, $campos, array("'Outubro'"));
	inserirRegistro($conn,PREFIXO . "charset",11, $campos, array("'Novembro'"));
	inserirRegistro($conn,PREFIXO . "charset",12, $campos, array("'Dezembro'"));