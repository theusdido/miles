<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_trocarprodutospontos";
	$entidadeDescricao 	= "Trocar Pontos por Produtos";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=2,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

    $_produto_entidade_id = installDependencia("ecommerce_produto","package/website/ecommerce/mercadoria/produto");
    $_cliente_entidade_id = installDependencia("ecommerce_cliente","package/website/ecommerce/geral/cliente");

	// Criando Atributos
    $tpp_produto        = criarAtributo($conn,$entidadeID,"produto","Produto","int",0,1,22,0,$_produto_entidade_id,0,"");
    $tpp_cliente        = criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,1,22,0,$_cliente_entidade_id,0,"");
    $tpp_pontos 	    = criarAtributo($conn,$entidadeID,"pontos","Pontos","float",0,1,25,0,0,0,"");
    $tpp_datahora 		= criarAtributo($conn,$entidadeID,"datahora","Data/Hora","datetime",0,0,23,1,0,0,"");