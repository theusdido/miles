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

	// Retorna os dados da requisição em JSON
	//header("Content-Type: application/json", true);

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

	// Arquivo de configuração miles.json
	$_miles_json_root_file = '../miles.json';

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
		case md5("ecommerce.sidercomp"): // 9c372ce9eeaa680bc3c6a0252c711643
			$permissao = true;
		break;
		case md5("ecommerce.primodas"): // 61d205a14cb39f75b93676bf97cf967f
			$permissao = true;
		break;
		case md5("ecommerce.opticaadolfo"): // 302dfe099db99f9079447f1e3bbbe5ab
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