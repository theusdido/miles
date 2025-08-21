<?php
	// Setando variáveis
	$entidadeNome = "entidadepermissoes";
	$entidadeDescricao = "Entidade Permissões";

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
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$entidade 		= criarAtributo($conn,$entidadeID,"entidade","Entidade","int",0,0,4,0,0,0,"");
	$usuario 		= criarAtributo($conn,$entidadeID,"usuario","Usuário","int",0,0,4,0,getEntidadeId("usuario",$conn),0,"");
	$inserir 		= criarAtributo($conn,$entidadeID,"inserir","Inserir","tinyint",0,0,7,0,0,0,"");
	$excluir 		= criarAtributo($conn,$entidadeID,"excluir","Excluir","tinyint",0,0,7,0,0,0,"");
	$editar 		= criarAtributo($conn,$entidadeID,"editar","Editar","tinyint",0,0,7,0,0,0,"");
	$visualizar 	= criarAtributo($conn,$entidadeID,"visualizar","Visualizar","tinyint",0,0,7,0,0,0,"");