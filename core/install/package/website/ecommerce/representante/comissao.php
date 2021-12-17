<?php
	// Setando variáveis
	$entidadeNome = "ecommerce_comissaogeral";
	$entidadeDescricao = "Comissão Geral";

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
	$valorfixo = criarAtributo($conn,$entidadeID,"valorfixo","Valor Fixo","float",0,1,13,0,0,0,"");
	
	// Cria Aba
	criarAba($conn,$entidadeID,"Geral",array($percentualdesconto,$valorfixo));
	
	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Comissão por Representante
	$tabelaprecorepresentanteID = criarEntidade(
		$conn,
		"ecommerce_comissaorepresentante",
		"Comissão Representante",
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
	$representante = criarAtributo($conn,$tabelaprecorepresentanteID,"representante","Representante","TEXT",0,0,22,1,getEntidadeId("ecommerce_representante",$conn),0,"");
	$percentualdesconto = criarAtributo($conn,$tabelaprecorepresentanteID,"percentualdesconto","% de Desconto","float",0,1,26,1,0,0,"");
	$valorfixo = criarAtributo($conn,$tabelaprecorepresentanteID,"valorfixo","Valor Fixo","float",0,1,13,1,0,0,"");	

	// Comissão por Produto
	$tabelaprecoprodutoID = criarEntidade(
		$conn,
		"ecommerce_comissaoproduto",
		"Comissão por Produto",
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
	$produto = criarAtributo($conn,$tabelaprecoprodutoID,"produto","Produto","TEXT",0,0,22,1,getEntidadeId("ecommerce_produto",$conn),0,"");
	$representante = criarAtributo($conn,$tabelaprecoprodutoID,"representante","Representante","TEXT",0,0,22,1,getEntidadeId("ecommerce_representante",$conn),0,"");
	$percentualdesconto = criarAtributo($conn,$tabelaprecoprodutoID,"percentualdesconto","% de Desconto","float",0,1,26,1,0,0,"");
	$valorfixo = criarAtributo($conn,$tabelaprecoprodutoID,"valorfixo","Valor Fixo","float",0,1,13,1,0,0,"");
	
	// Comissão x Pedido
	$comissaoPedidoID = criarEntidade(
		$conn,
		"ecommerce_comissaopedido",
		"Comissão x Pedido",
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
	//$valorfixo = criarAtributo($conn,$tabelaprecoprodutoID,"valor","Valor","float",0,1,13,1,0,0,"");
	$item = criarAtributo($conn,$comissaoPedidoID,"pedido","Pedido","tinyint",0,0,22,1,getEntidadeId("ecommerce_pedido",$conn),0,"",0,0);

	criarRelacionamento($conn,6,$entidadeID,$tabelaprecorepresentanteID,"Representante",0);
	criarRelacionamento($conn,6,$entidadeID,$tabelaprecoprodutoID,"Produto",0);