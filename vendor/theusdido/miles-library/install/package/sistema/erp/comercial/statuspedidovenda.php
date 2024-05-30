<?php	
	
	// Setando variáveis
	$entidadeNome = "erp_comercial_statuspedidovenda";
	$entidadeDescricao = "Status do Pedido de Venda";
	
	// Status do Pedido
	$statuspedidoID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
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
	$descricao = criarAtributo($conn,$statuspedidoID,"descricao","Descrição","varchar",200,0,3,0,0,0,"");
	
	// Criando Acesso
	$menu_comercial = addMenu($conn,'Comercial','#','',0,0,'comercial');
	
	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$statuspedidoID."/".PREFIXO."statuspedido.html",'',$menu_comercial,7,'comercial-statuspedido');	