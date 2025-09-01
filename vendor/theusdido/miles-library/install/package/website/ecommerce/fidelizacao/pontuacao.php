<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_pontuacao";
	$entidadeDescricao 	= "Pontuação";

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
	$taxaconversao = criarAtributo($conn,$entidadeID,"taxaconversao","Taxa Conversão","float",0,1,13,1,0,0,"");

	// Cria Aba
	criarAba($conn,$entidadeID,"Geral",array($taxaconversao));

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Comissão por Representante
	$itenspontuacao = criarEntidade(
		$conn,
		"ecommerce_itenspontuacao",
		"Itens de Pontuação",
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
	$descricao = criarAtributo($conn,$itenspontuacao,"descricao","Descrição","varchar",200,0,3,1,0,0,"",0,0);
	$ponto = criarAtributo($conn,$itenspontuacao,"ponto","Ponto","int",0,1,25,0,0,0,"");

	// Comissão por Produto
	$pontuacaoclienteID = criarEntidade(
		$conn,
		"ecommerce_pontuacaocliente",
		"Pontuação do Cliente",
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
	$cliente = criarAtributo($conn,$pontuacaoclienteID,"cliente","Cliente","int",0,0,22,1,getEntidadeId("ecommerce_cliente",$conn),0,"",0,0);
	$item = criarAtributo($conn,$pontuacaoclienteID,"item","Item","tinyint",0,0,4,1,getEntidadeId("ecommerce_itenspontuacao",$conn),0,"",0,0);
	
	criarRelacionamento($conn,6,$entidadeID,$itenspontuacao,"Item",0);
	criarRelacionamento($conn,6,$entidadeID,$pontuacaoclienteID,"Cliente",0);