<?php
	// Bairro
	$entidadeNomeBairro 		= "ecommerce_bairro";
	$entidadeDescricaoBairro 	= "Bairro";
	$bairroID = criarEntidade(
		$conn,
		$entidadeNomeBairro,
		$entidadeDescricaoBairro,
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
	$bairro_nome = criarAtributo($conn,$bairroID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$bairroID,"cidade","Cidade","int",0,0,22,1,installDependencia('ecommerce_cidade','package/website/ecommerce/endereco/cidade'),0,"");
	Entity::setDescriptionField($conn,$bairroID,$bairro_nome,true);
