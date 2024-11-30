<?php
	// Setando variáveis
	$entidadeNome       = "usuario_recuperar_senha";
	$entidadeDescricao  = "Recuperação de Senha";
	
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
	$hash 					= criarAtributo($conn,$entidadeID,"hash","Hash","varchar",300,0,3,0);
	$usuario 				= criarAtributo($conn,$entidadeID,"usuario","Usuário","int",0,0,4,1,getEntidadeId('usuario'));
	$email 					= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",250,1,3,1,0,0,"");
	$datahoraenvio 			= criarAtributo($conn,$entidadeID,"datahoraenvio","Data/Hora de Envio","date",0,0,23);
	$datahoraalteracao		= criarAtributo($conn,$entidadeID,"datahoraalteracao","Data/Hora da Recuperação","date",0,1,23);
	