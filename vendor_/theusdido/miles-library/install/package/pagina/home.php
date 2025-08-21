<?php
	$entidadeNome 		= "home";
	$entidadeDescricao 	= "Home";

	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$descricao = $entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 1
	);

	// 2 PASSO
	$saudacao 	= criarAtributo($conn,$entidadeID,"saudacao","Saudação","varchar",50,1,3);
	$texto 		= criarAtributo($conn,$entidadeID,"texto","Texto","text","",1,21,0,0,0,"");
	$titulo 	= criarAtributo($conn,$entidadeID,"titulo","Título","varchar",50,1,3);
	$slogan 	= criarAtributo($conn,$entidadeID,"slogan","Slogan","varchar",120,1,3);