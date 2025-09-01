<?php
	// Setando variáveis
	$entidadeNome = "erp_geral_servico";
	$entidadeDescricao = "Serviço";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	
	// Criando Atributos
	$nome 			= criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",1,3,1,0,0,"");
	$descricao 		= criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,1,21,0,0,0,"",1,0);
	$tiposervico 	= criarAtributo($conn,$entidadeID,"tipo","Tipo","int",0,1,4,0,installDependencia($conn,"erp_geral_tiposervico"));
	$valor 			= criarAtributo($conn,$entidadeID,"valor","Valor","float",0,0,13,1,0);

	// Criando Acesso
	$menu_webiste 	= addMenu($conn,'Geral','#','',0,0,'geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID,'cadastro');