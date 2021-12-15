<?php
	// Setando variáveis
	$entidadeNome = "aviso";
	$entidadeDescricao = "Aviso";
	
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
	$tipoaviso = criarAtributo($conn,$entidadeID,"tipoaviso","Tipo de Aviso","int",0,0,4,1,getEntidadeId("tipoaviso",$conn),0,"");
	$mensagem = criarAtributo($conn,$entidadeID,"mensagem","Mensagem","text",0,0,21,0,0,0,"");