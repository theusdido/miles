<?php
	// Setando variáveis
	$entidadeNome = "comunicado";
	$entidadeDescricao = "Comunicado";
	
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
	$datainicio = criarAtributo($conn,$entidadeID,"datainicio","Data de Inicio","datetime",0,0,23,1,0,0,"");
	$datafinal = criarAtributo($conn,$entidadeID,"datafinal","Data de Final","datetime",0,0,23,1,0,0,"");
	$titulo = criarAtributo($conn,$entidadeID,"titulo","Título","varchar",200,0,3,1,0,0,'',1,0);
	$mensagem = criarAtributo($conn,$entidadeID,"mensagem","Mensagem","text",0,0,21,0,0,0,"");