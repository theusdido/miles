<?php
	// Dependencias
	$tipotelefone = installDependencia($conn,'erp_geral_tipotelefone','package/sistema/');
	$operadoratelefone = installDependencia($conn,'erp_geral_operadoratelefone','package/sistema/');

	// Setando variáveis
	$entidadeNome = "telefone";
	$entidadeDescricao = "Telefone";

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