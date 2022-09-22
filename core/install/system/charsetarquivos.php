<?php
	// Setando variáveis
	$entidadeNome       = "charsetfiles";
	$entidadeDescricao  = "Files Charset";
	
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
	$path       = criarAtributo($conn,$entidadeID,"path","Path","varchar",500,0,3);
	$file       = criarAtributo($conn,$entidadeID,"file","File","varchar",100,0,3);
    $charset    = criarAtributo($conn,$entidadeID,"charset","Charset","int",0,1,4,0,getEntidadeId("charset"));