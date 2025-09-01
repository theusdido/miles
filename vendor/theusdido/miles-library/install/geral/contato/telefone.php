<?php
	// Dependencias
	$tipotelefone 		= installDependencia('erp_geral_tipotelefone','geral/contato/tipotelefone');
	$operadoratelefone 	= installDependencia('erp_geral_operadoratelefone','geral/contato/operadoratelefone');

	// Setando variáveis
	$entidadeNome 		= "erp_geral_telefone";
	$entidadeDescricao 	= "Telefone";

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
	$descricao 		= criarAtributo($conn,$entidadeID,"numero","Número","varchar","25",1,8,0,0,0,"");
	$tipo 			= criarAtributo($conn,$entidadeID,"tipo","Tipo de Telefone","int",0,0,4,0,$tipotelefone,0,"",1,0);
	$operadora 		= criarAtributo($conn,$entidadeID,"operadora","Operadora","int",0,0,4,0,$operadoratelefone,0,"",1,0);
	$contato 		= criarAtributo($conn,$entidadeID,"contato","Contato","varchar","60",1,3,1,0,0,"");

	// Criando Acesso
	$menu_webiste 	= addMenu($conn,'Geral','#','',0,0,'geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID,'cadastro');
