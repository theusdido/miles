<?php
	// Setando variáveis
	$entidadeNome = "ecommerce_taxaentrega";
	$entidadeDescricao = "Taxa de Entrega";
	
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
		$registrounico = 0
	);

	// Criando Atributos
	$remetente 			= criarAtributo($conn,$entidadeID,"remetente","Remetente","int",0,0,22,1,getEntidadeId("ecommerce_bairro",$conn),0,"");
	$destinatario 		= criarAtributo($conn,$entidadeID,"destinatario","Destinatario","int",0,0,22,1,getEntidadeId("ecommerce_bairro",$conn),0,"");
	$taxa 				= criarAtributo($conn,$entidadeID,"taxaentrega","Taxa de Entrega","float",0,0,13,1,0,0,"");
	$prazoentrega 		= criarAtributo($conn,$entidadeID,"prazoentrega","Prazo de Entrega","int",0,0,25,1,0,0,"");
	$transportadora		= criarAtributo($conn,$entidadeID,"transportadora","Transportadora","int",0,1,4,1,installDependencia($conn,"ecommerce_transportadora","package/website/"),0,"");

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','','','','ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');