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
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$email = criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",120,0,3,1,0,0,"");
	$senha = criarAtributo($conn,$entidadeID,"senha","Senha","varchar",50,0,6,0,0,0,"");
	$foto = criarAtributo($conn,$entidadeID,"foto","Foto","text","",1,19,0,0,0,"");