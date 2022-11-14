<?php
	// Setando variáveis
	$entidadeNome = "config";
	$entidadeDescricao = "Configurações";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$urlupload 					= criarAtributo($conn,$entidadeID,"urlupload","URL Upload","text",0,0,3,1,0,0,"");
	$urlrequisicoes 			= criarAtributo($conn,$entidadeID,"urlrequisicoes","URL Requisições","text",0,0,3,1,0,0,"");
	$urlsaveform 				= criarAtributo($conn,$entidadeID,"urlsaveform","URL Salvar Form","text",0,0,3,1,0,0,"");
	$urlloadform 				= criarAtributo($conn,$entidadeID,"urlloadform","URL Load Form","text",0,0,3,1,0,0,"");
	$urluploadform 				= criarAtributo($conn,$entidadeID,"urluploadform","URL Upload Form","text",0,0,3,1,0,0,"");
	$urlpesquisafiltro 			= criarAtributo($conn,$entidadeID,"urlpesquisafiltro","URL Pesquisa Filtro","text",0,0,3,1,0,0,"");
	$urlenderecofiltro 			= criarAtributo($conn,$entidadeID,"urlenderecofiltro","URL Endereço Filtro","text",0,0,3,1,0,0,"");
	$urlexcluirregistros 		= criarAtributo($conn,$entidadeID,"urlexcluirregistros","URL Excluir Registro","text",0,0,3,1,0,0,"");
	$urlinicializacao 			= criarAtributo($conn,$entidadeID,"urlinicializacao","URL Inicialização","text",0,0,3,1,0,0,"");
	$urlloading 				= criarAtributo($conn,$entidadeID,"urlloading","URL Loading","text",0,0,3,1,0,0,"");
	$urlloadgradededados 		= criarAtributo($conn,$entidadeID,"urlloadgradededados","URL Grade de Dados","text",0,0,3,1,0,0,"");
	$urlrelatorio 				= criarAtributo($conn,$entidadeID,"urlrelatorio","URL Relatório","text",0,0,3,1,0,0,"");
	$urlmenu 					= criarAtributo($conn,$entidadeID,"urlmenu","URL Menu","text",0,0,3,1,0,0,"");
	$bancodados 				= criarAtributo($conn,$entidadeID,"bancodados","Banco de Dados","varchar",35,0,3,1,0,0,"");
	$linguagemprogramacao 		= criarAtributo($conn,$entidadeID,"linguagemprogramacao","Linguagem de Programação","varchar",35,0,3,1,0,0,"");
	$pathfileupload 			= criarAtributo($conn,$entidadeID,"pathfileupload","Diretório de Arquivos ( Upload )","varchar",200,1,3);
	$pathfileuploadtemp 		= criarAtributo($conn,$entidadeID,"pathfileuploadtemp","Diretório Temporário de Arquivos ( Upload )","varchar",200,1,3);
	$urlupload 					= criarAtributo($conn,$entidadeID,"testecharset","Teste CharSet","text",0,0,3);
	$tipogradedados 			= criarAtributo($conn,$entidadeID,"tipogradedados","Tipo de Grade de Dados","varchar",10,1,3);
	$casasdecimais 				= criarAtributo($conn,$entidadeID,"casasdecimais","Quantidade de Casas Decimais","int",0,1,25);
	