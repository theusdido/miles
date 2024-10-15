<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_unidadeimovel";
	$entidadeDescricao = "Unidade Imóvel";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos	
	$unidadesimovel = criarAtributo($conn,$entidadeID,"unidadesimovel","Unidades Imóvel","int",0,0,4,1,installDependencia($conn,'erp_imobiliaria_unidadesimovel'),0,"",1,0);
	$quantidade = criarAtributo($conn,$entidadeID,"quantidade","Quantidade","int",0,0,25,1);
	$foto = criarAtributo($conn,$entidadeID,"foto","Foto","text",0,1,19,0,0,0,'',1,0);	
	
	// Criando Relacionamento
	criarRelacionamento(
		$conn,
		10,
		installDependencia($conn,"erp_imobiliaria_imovel"),
		$entidadeID,
		"Unidade",
		0
	);