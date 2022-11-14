<?php

	include 'conexao.php';
	include '../system/funcoes.php';
	
	$entidaderegistro = $_GET["entidaderegistro"];
	
	if (file_exists($entidaderegistro)){
		include_once $entidaderegistro . ".php";
	}