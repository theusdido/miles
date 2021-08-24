<?php

	// Setando variáveis
	$entidadeNome = "ecommerce_configuracoes";
	$entidadeDescricao = "Configurações do E-Commercer";

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

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','','','','ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');