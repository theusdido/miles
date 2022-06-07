<?php

	// Expedição
	$entidadeID = criarEntidade(
		$conn,
		"ecommerce_expedicao",
		"Expedição",
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	$pedido					= criarAtributo($conn,$entidadeID,"pedido","Pedido","int",0,0,22,1,installDependencia($conn,"ecommerce_pedido","package/website/"));
	$datahoraenvio 			= criarAtributo($conn,$entidadeID,"datahoraenvio","Data/Hora de Envio","datetime",0,0,23,0);
	$datahorarecebimento 	= criarAtributo($conn,$entidadeID,"datahorarecebimento","Data/Hora de Recebimento","datetime",0,0,23,0);
	$entregue 				= criarAtributo($conn,$entidadeID,"entregue","Entregue ?","boolean",0,0,7,0);
	$valorfrete				= criarAtributo($conn,$entidadeID,"valorfrete","Valor Frete","float",0,1,13,0);
	$pesototal				= criarAtributo($conn,$entidadeID,"pesototal","Peso Total","float",0,1,26,0);
	$transportadora			= criarAtributo($conn,$entidadeID,"transportadora","Transportadora","int",0,0,22,1,installDependencia($conn,"ecommerce_transportadora","package/website/"));
	$codigorastreamento		= criarAtributo($conn,$entidadeID,"codigorastreamento","Código Rastreamento","varchar",200,0,3,0);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu Expedição
	addMenu($conn,"Expedição","files/cadastro/".$entidadeID."/".PREFIXO."expedicao.html",'',$menu_webiste,7,'ecommerce-expedicao',$entidadeID,'cadastro');

	// Cria Relacionamento
	criarRelacionamento($conn,7,$entidadeID,getEntidadeId("ecommerce_endereco",$conn),utf8_decode("Endereço"),0);