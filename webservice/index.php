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

	
	ini_set('display_errors',1);
	ini_set('display_startup_erros',1);
	error_reporting(E_ALL);	

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

	// Limpa cachê do SOAP
	ini_set("soap.wsdl_cache_enabled", 0);

	// Carrega os arquivos de configuração do sistema
	require '../vendor/theusdido/miles-library/autoload.php';

	// Variavél de retorno
	$retorno 			= [];
	$retorno["status"] 	= "";

	$tokenparms = tdc::r('_token') != '' ? tdc::r('_token') : tdc::r('token');
	if ($tokenparms == ''){
		echo 'Token não informado';
		exit;
	}

	$permissao = false;
	switch($tokenparms){
		case md5("miles.ws"): // e03c3599f75d548acc0232f2f3dcaa11
			$permissao = true;
		break;
	}

	if (!$permissao){
		echo 'Token não confere com o cadastrado';
		exit;
	}

	//Filtrosgit 
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

	// Opção dentro do servições ( Será removido )
	$op = isset($_GET['op']) ? $_GET['op'] : ( isset($_POST['op']) ? $_POST['op'] : '' );

	$service = '';
	// Aceitar parametro service
	if ($op == ''){
		$op 		= isset($_GET['service']) ? $_GET['service'] : ( isset($_POST['service']) ? $_POST['service'] : '' );
		$service 	= $op;
	}

	// Adiciona o pacote para requisitar o serviço
	$_package		= tdc::r('_package') != '' ? tdc::r('_package') . '.' : '';

	// Opção dentro do servições ( Será removido )
	$_op 		= tdc::r('_op');

	// Serviço solicitado + pacote
	$_service	= $_package . tdc::r('_service');

	if ($service == '' && $_service == ''){
		echo 'Serviço nao informado ou nao encontrado.';
		exit;
	}

	// Variável para valor padrão para verificação
	$_value = tdc::r('_value');

	// Variável padrão para o recebimento de dados
	$_data = tdc::r('_data') != '' ? json_decode(tdc::r('_data')) : new stdClass;

	// Sessão de seleção de língua para idioma
	Session::append('selected_language', tdc::r('_language') != '' ? tdc::r('_language') : 0);

	// Encaminha para o serviço solicitado
	require 'rota.php';

	try {

		// Fecha a transação com o banco de dados
		Transacao::Commit();

		header("Content-Type: application/json");

		// Retorna a requisição em formatado de Array
		#echo json_encode( [$retorno] );
		echo json_encode( $retorno );

	}catch(Exeception $e){
		echo json_encode($e->getMessage());
	}