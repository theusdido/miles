<?php
	// Setando variáveis
	$entidadeNome = "contratoevento";
	$entidadeDescricao = "Evento do Contrato";

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
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3);
	$operacao = criarAtributo($conn,$entidadeID,"operacao","Operação","int",0,0,4,0,getEntidadeId("imobiliaria_operacaoevento",$conn));