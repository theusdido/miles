<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_tabelaprecogeral";
	$entidadeDescricao 	= "Tabela de Preço Geral";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 1
	);
	
	// Criando Atributos
	$percentualdesconto = criarAtributo($conn,$entidadeID,"percentualdesconto","% de Desconto","float",0,1,26,0,0,0,"");
	$valorfixo 			= criarAtributo($conn,$entidadeID,"valorfixo","Valor Fixo","float",0,1,13,0,0,0,"");
	
	// Cria Aba
	criarAba($conn,$entidadeID,"Geral",array($percentualdesconto,$valorfixo));
	
	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');
	
	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
	
	$_produto_entidade_id 			= installDependencia("ecommerce_produto","package/website/ecommerce/mercadoria/produto");
	$_representante_entidade_id 	= installDependencia("ecommerce_representante","package/website/ecommerce/representante/representante");
	
	// Tabela de Preço por Representante
	$tabelaprecorepresentanteID = criarEntidade(
		$conn,
		"ecommerce_tabelaprecorepresentante",
		"Tabela de Preço por Representante",
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	
	// Criando Atributos
	$representante = criarAtributo($conn,$tabelaprecorepresentanteID,"representante","Representante","TEXT",0,0,22,1,$_representante_entidade_id,0,"");
	$percentualdesconto = criarAtributo($conn,$tabelaprecorepresentanteID,"percentualdesconto","% de Desconto","float",0,1,26,1,0,0,"");
	$valorfixo = criarAtributo($conn,$tabelaprecorepresentanteID,"valorfixo","Valor Fixo","float",0,1,13,1,0,0,"");	

	// Tabela de Preço por Produto
	$tabelaprecoprodutoID = criarEntidade(
		$conn,
		"ecommerce_tabelaprecoproduto",
		"Tabela de Preço por Produto",
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	
	// Criando Atributos
	$produto 				= criarAtributo($conn,$tabelaprecoprodutoID,"produto","Produto","int",0,0,22,1,$_produto_entidade_id,0,"");
	$representante 			= criarAtributo($conn,$tabelaprecoprodutoID,"representante","Representante","int",0,0,22,1,$_representante_entidade_id,0,"");
	$percentualdesconto 	= criarAtributo($conn,$tabelaprecoprodutoID,"percentualdesconto","% de Desconto","float",0,1,26,1,0,0,"");
	$valorfixo 				= criarAtributo($conn,$tabelaprecoprodutoID,"valorfixo","Valor Fixo","float",0,1,13,1,0,0,"");
	
	// Promoção
	$promocaoID = criarEntidade(
		$conn,
		"ecommerce_promocao",
		"Promoção",
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$nome 		= criarAtributo($conn,$promocaoID,"nome","Nome","varchar",200,0,3,1,0,0,"");
	$produto 	= criarAtributo($conn,$promocaoID,"produto","Produto","int",0,0,4,0,$_produto_entidade_id,0,"");
	$percentualdesconto = criarAtributo($conn,$promocaoID,"percentualdesconto","% de Desconto","float",0,1,26,1,0,0,"");
	$valorfixo = criarAtributo($conn,$promocaoID,"valorfixo","Valor Fixo","float",0,1,13,1,0,0,"");	
	$datahorainicio = criarAtributo($conn,$promocaoID,"datahorainicio","Data/Hora Inicio","datetime",0,0,23,1,0,0,"");
	$datahorafim = criarAtributo($conn,$promocaoID,"datahorafim","Data/Hora Fim","datetime",0,0,23,1,0,0,"");
	
	criarRelacionamento($conn,6,$entidadeID,$tabelaprecorepresentanteID,"Representante",0);
	criarRelacionamento($conn,6,$entidadeID,$tabelaprecoprodutoID,"Produto",0);
	criarRelacionamento($conn,6,$entidadeID,$promocaoID,"Promoção",0);