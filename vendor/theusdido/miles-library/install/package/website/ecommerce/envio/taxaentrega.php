<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_taxaentrega";
	$entidadeDescricao 	= "Taxa de Entrega";
	
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

	$_bairro_entidade_id = installDependencia("ecommerce_bairro","package/website/ecommerce/endereco/bairro");
	// Criando Atributos
	$remetente 			= criarAtributo($conn,$entidadeID,"remetente","Remetente","int",0,0,22,1,$_bairro_entidade_id,0,"");
	$destinatario 		= criarAtributo($conn,$entidadeID,"destinatario","Destinatario","int",0,0,22,1,$_bairro_entidade_id,0,"");
	$taxa 				= criarAtributo($conn,$entidadeID,"taxaentrega","Taxa de Entrega","float",0,0,13,1,0,0,"");
	$prazoentrega 		= criarAtributo($conn,$entidadeID,"prazoentrega","Prazo de Entrega","int",0,0,25,1,0,0,"");
	$transportadora		= criarAtributo($conn,$entidadeID,"transportadora","Transportadora","int",0,1,4,1,installDependencia("ecommerce_transportadora","package/website/ecommerce/envio/transportadora"),0,"");

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');