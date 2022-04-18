<?php
	/*
		* Framework MILES
		* @license : Teia Online.
		* @link http://www.teia.online

		* Index do Webservice
		* Data de Criacao: 24/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	*/

	// Permite que qualquer site acesse (destinada a apis públicas)
	header("Access-Control-Allow-Origin: *");

	// Projeto Consumidor
	$projetoconsumidor = isset($_GET["project"]) ? $_GET["project"] : ( isset($_POST["project"]) ? $_POST["project"] : '' );
	if ($projetoconsumidor == ''){
		$projetoconsumidor = isset($_GET["currentproject"]) ? $_GET["currentproject"] : ( isset($_POST["currentproject"]) ? $_POST["currentproject"] : '' );
	}
	if ($projetoconsumidor == ''){
		$projetoconsumidor = 1;
	}

	// Aumenta o tempo de execução da página
	set_time_limit(3600);

	// Seta o ambiente da requisição
	define("AMBIENTE","WEBSERVICE");

	// Seta o ambiente da requisição
	define("PROJETO_CONSUMIDOR", $projetoconsumidor);

	// Carrega os arquivos de configuração do sistema
	require '../core/autoload.php';

	// Variavél de retorno
	$retorno["status"] = "success";

	$tokenparms = tdc::r('token');
	if ($tokenparms == ""){
		echo 'Token não informado';
		exit;
	}

	$permissao = false;
	switch($tokenparms){
		case md5("appfarmaciaonline"): // 1a09b8446aa30b7ae9247fa644a4e23b
			$permissao = true;
		break;
		case md5("ecommerce.jp"): // 43fa61c213c85fb565748a5dd50c8186
			$permissao = true;
		break;
		case md5("ecommerce.villafrancioni"): // 6d4e3a2c112f673fde3e92361614feb7
			$permissao = true;
		break;
		case md5("website.cedup"): // 4beac02ce154dc140b030b95fd4bee5a
			$permissao = true;
		break;
		case md5("app.idaevolta"): // d8fab2b972d2abc677a050ece3102b3e
			$permissao = true;
		break;
		case md5("ecommerce.granuemporio"): // 5bbc1f5977be278e8521ee0fb2b3658a
			$permissao = true;
		break;

	}
	if (!$permissao){
		echo 'Token não confere com o cadastrado';
		exit;
	}

	//Filtros
	$criterio = null;
	$filtro = retornar("filtros");
	if ($filtro!=""){
		include 'filtro.php';
	}

	//Propriedades
	$propriedade = retornar("propriedades");
	$propriedadeORDER = $propriedadeLIMIT = $propriedadeOFFSET = $propriedadeGROUP = "";
	if ($propriedade != ""){
		include 'propriedade.php';
	}else{
		$propriedades = '';
	}

	// Recebendos dados
	$dados = json_decode(utf8_decode(isset($_GET["dados"])?$_GET["dados"]:(isset($_POST["dados"])?$_POST["dados"]:'')),true);

	// Opção dentro do servições
	$op = isset($_GET['op']) ? $_GET['op'] : ( isset($_POST['op']) ? $_POST['op'] : '' );

	// Encaminha para o serviço solicitado
	require 'rota.php';

	try {

		// Fecha a transação com o banco de dados
		Transacao::Commit();

		// Retorna os dados da requisição em JSON
		header("Content-Type: application/json", true);

		// Retorna a requisição em formatado de Array
		echo json_encode( [$retorno] );

	}catch(Exeception $e){
		echo json_encode($e->getMessage());
	}