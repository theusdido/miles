<?php

	$entidadeNome 		= "ecommerce_expedicao";
	$entidadeDescricao 	= "Expedição";


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
		$registrounico = 0
	);

	$pedido					= criarAtributo($conn,$entidadeID,"pedido","Pedido","int",0,0,22,1,installDependencia("ecommerce_pedido","package/website/ecommerce/comercial/pedido"));
	$datahoraenvio 			= criarAtributo($conn,$entidadeID,"datahoraenvio","Data/Hora de Envio","datetime",0,0,23,0);
	$datahorarecebimento 	= criarAtributo($conn,$entidadeID,"datahorarecebimento","Data/Hora de Recebimento","datetime",0,0,23,0);
	$entregue 				= criarAtributo($conn,$entidadeID,"entregue","Entregue ?","boolean",0,0,7,0);
	$valorfrete				= criarAtributo($conn,$entidadeID,"valorfrete","Valor Frete","float",0,1,13,0);
	$pesototal				= criarAtributo($conn,$entidadeID,"pesototal","Peso Total","float",0,1,26,0);
	$transportadora			= criarAtributo($conn,$entidadeID,"transportadora","Transportadora","int",0,0,22,1,installDependencia("ecommerce_transportadora","package/website/ecommerce/envio/transportadora"));
	$codigorastreamento		= criarAtributo($conn,$entidadeID,"codigorastreamento","Código Rastreamento","varchar",200,0,3,0);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu Expedição
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

	// Cria Relacionamento
	criarRelacionamento($conn,7,$entidadeID,installDependencia('ecommerce_endereco','package/website/ecommerce/endereco/endereco'),"Endereço",0);
