<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_imovelfoto";
	$entidadeDescricao = "Imóvel Foto";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$imovel = criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,1,16,0,installDependencia($conn,'erp_imobiliaria_imovel'),0,"",1,0);
	$foto = criarAtributo($conn,$entidadeID,"foto","Foto","int",0,1,16,0,installDependencia($conn,'erp_geral_foto'),0,"",1,0);

	// Criando Relacionamento
	criarRelacionamento(
		$conn,
		6,
		installDependencia($conn,'erp_imobiliaria_imovel'),
		installDependencia($conn,'erp_geral_foto'),
		"Fotos",
		$imovel
	);