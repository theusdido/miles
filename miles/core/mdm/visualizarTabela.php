<?php
	// Visualizar uma tabela do banco de dados
	require '../lib/config.php';
	
	$index 		= tdClass::Open("pagina");			
	$index->setTitulo('Visualizar tabela - MILES DATABASE MANAGER');	

	$index->addCorpo("");
	$index->exibir();

	$grade = tdClass::Open("gradededados");	
	$grade->setTabela("usuario");
	$grade->exibir();