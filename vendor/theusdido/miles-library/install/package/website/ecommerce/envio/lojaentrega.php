<?php

	$entidadeNome 		= "ecommerce_lojaentrega";
	$entidadeDescricao 	= "Entrega ( Configurações da Loja )";

	// Expedição
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 1
	);

	$loja					= criarAtributo($conn,$entidadeID,"loja","Loja","int",0,0,22,1,installDependencia("ecommerce_loja","package/website/ecommerce/geral/loja"));
	$is_entrega				= criarAtributo($conn,$entidadeID,"is_entrega","Entrega ?","boolean",0,0,7,0);
	$is_entrega_feriado		= criarAtributo($conn,$entidadeID,"is_entrega_feriado","Entrega Feriado ?","boolean",0,0,7,0);
	$valor_minimo			= criarAtributo($conn,$entidadeID,"valor_minimo","Valor Mínimo","float",0,1,13,0);
	$quantidade_minima		= criarAtributo($conn,$entidadeID,"quantidade_minima","Quantidade Mínima","int",0,1,25,0);