<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_tamanhoproduto";
	$entidadeDescricao 	= "Tamanho do Produto";
	
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
	
	$_produto_entidade_id = installDependencia("ecommerce_produto","package/website/ecommerce/mercadoria/produto");

	// Criando Atributos
	$produto 	= criarAtributo($conn,$entidadeID,"produto","Produto","int",0,1,16,0,$_produto_entidade_id,0,"");
	$descricao 	= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","200",0,3,1,0,0,"");
	$valor 		= criarAtributo($conn,$entidadeID,"valor","Valor","float",0,1,13,1);
	$peso 		= criarAtributo($conn,$entidadeID,"peso","Peso","float",0,1,26,1);
	Entity::setDescriptionField($conn,$entidadeID,$descricao,false);

	// Cria Relacionamento
	criarRelacionamento($conn,2,$_produto_entidade_id,$entidadeID,"Tamanhos",$produto);