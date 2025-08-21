<?php
	// Setando variáveis
	$entidadeNome = "usuario";
	$entidadeDescricao = "Usuário";
	
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
	$nome 					= criarAtributo($conn,$entidadeID,"nome","Nome","varchar",150,0,3,1,0,0,"");
	$login 					= criarAtributo($conn,$entidadeID,"login","Login","varchar",50,0,3,1,0,0,"");
	$email 					= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",250,1,3,1,0,0,"");
	$senha 					= criarAtributo($conn,$entidadeID,"senha","Senha","varchar",50,0,6,0,0,0,"");	
	$permitirexclusao 		= criarAtributo($conn,$entidadeID,"permitirexclusao","Permitir Exclusão ?","tinyint",0,1,7,0,0,0,"");
	$permitirtrocarempresa 	= criarAtributo($conn,$entidadeID,"permitirtrocarempresa","Permitir Trocar Empresa ?","tinyint",0,1,7,0,0,0,"");
	$grupousuario 			= criarAtributo($conn,$entidadeID,"grupousuario","Grupo de Usuário","int",0,1,4,0,getEntidadeId("grupousuario",$conn),0,"");
	$perfilusuario 			= criarAtributo($conn,$entidadeID,"perfilusuario","Perfil de Usuário ?","tinyint",0,1,7,0,0,0,"");
	$perfil 				= criarAtributo($conn,$entidadeID,"perfil","Perfil","int",0,1,4,0,$entidadeID,0,"");
	$fotoperfil 			= criarAtributo($conn,$entidadeID,"fotoperfil","Foto (Perfil)","mediumblob",0,1,19);