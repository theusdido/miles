<?php

	// Setando variáveis
	$entidadeNome 		= "ecommerce_configuracoes";
	$entidadeDescricao 	= "Configurações do E-Commercer";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 1,
		$carregarlibjavascript = 1,
		$criarinativo = false
	);

	// Criando Atributos
	$valorminimopedido 			= criarAtributo($conn,$entidadeID,"valorminimopedido","Valor Mínimo Pedido","float",0,1,13);
	$permitecomprasemestoque 	= criarAtributo($conn,$entidadeID,"permitecomprasemestoque","Permite Comprar Sem Estoque","boolean",0,1,7);
	$usarvariacaoproduto		= criarAtributo($conn,$entidadeID,"usarvariacaoproduto","Usar Variação de Produtos ?","boolean",0,1,7);
	$ispaginacaoprodutohome		= criarAtributo($conn,$entidadeID,"ispaginacaoprodutohome","Páginação de Produtos na Home ?","boolean",0,1,7);
	$qtdadeprodutohome			= criarAtributo($conn,$entidadeID,"qtdadeprodutohome","Quantidade Produto na Home","int",0,1,25);
	$valorminimoentrega 		= criarAtributo($conn,$entidadeID,"valorminimoentrega","Valor Mínimo de Entrega","float",0,1,13);
	$prazominimoentrega			= criarAtributo($conn,$entidadeID,"prazominimoentrega","Prazo Mínimo de Entrega","int",0,1,25);
	$emailenviopedido			= criarAtributo($conn,$entidadeID,"emailenviopedido","E-Mail Envio Pedido","varchar",200,1,12);
	$is_show_price_only_logged	= criarAtributo($conn,$entidadeID,"is_show_price_only_logged","Exibir Preço Apenas Logado ?","boolean",0,1,7);
	$qtdademaximaitenspedido	= criarAtributo($conn,$entidadeID,"qtdademaximaitenspedido","Quantidade de Itens no Pedido","int",0,1,25);
	$cep_origem_pedido			= criarAtributo($conn,$entidadeID,"cep_origem_pedido","CEP Origem do Pedido","varchar",9,1,9);

	$is_send_order_email		= criarAtributo($conn,$entidadeID,"is_send_order_email","Enviar pedido por e-mail ?","boolean",0,1,7);
	$is_send_app_mobile			= criarAtributo($conn,$entidadeID,"is_send_app_mobile","Enviar pedido para o aplicativo ?","boolean",0,1,7);
	$is_control_inventory		= criarAtributo($conn,$entidadeID,"is_control_inventory","Usar controle de estoque ?","boolean",0,1,7);
	$is_control_commission		= criarAtributo($conn,$entidadeID,"is_control_commission","Usar controle de comissão ?","boolean",0,1,7);
	$is_show_button_checkout	= criarAtributo($conn,$entidadeID,"is_show_button_checkout","Exibir botão para finalizar compra ?","boolean",0,1,7);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');