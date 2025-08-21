<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_carrinhocompras";
	$entidadeDescricao 	= "Carrinho de Compras";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=2,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$carrinho_cliente 				= criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,1,22,1,installDependencia("ecommerce_cliente","package/website/ecommerce/geral/cliente"),0,'',1,0);
	$carrinho_datahoracriacao		= criarAtributo($conn,$entidadeID,"datahoracriacao","Data/Hora Criação","datetime",0,0,23,0,0,0,"");
	$carrinho_datahoraultimoacesso 	= criarAtributo($conn,$entidadeID,"datahoraultimoacesso","Data/Hora Último Acesso","datetime",0,1,23,0,0,0,"");
	$carrinho_sessionid 			= criarAtributo($conn,$entidadeID,"sessionid","ID da Sessão","varchar",100,1,3,0,0,0,"");
	$carrinho_representante 		= criarAtributo($conn,$entidadeID,"representante","Representante","int",0,1,22,0,installDependencia("ecommerce_representante","package/website/ecommerce/representante/representante"),0,"",0,0);
	$carrinho_isrepresentante 		= criarAtributo($conn,$entidadeID,"isrepresentante","Representante ?","tinyint",0,1,7,0,0,0,'',1,0);
	$carrinho_inativo 				= criarAtributo($conn,$entidadeID,"inativo","Inativo","boolean",false,1,7,1);
	$carrinho_valortotal 			= criarAtributo($conn,$entidadeID,"valortotal","Valor Total","float",0,1,13,1,0,0,"");
	$carrinho_qtdetotaldeitens 		= criarAtributo($conn,$entidadeID,"qtdetotalitens","Qtde Total de Itens","int",0,1,25,0,0,0,"");
	$carrinho_valorfrete 			= criarAtributo($conn,$entidadeID,"valorfrete","Valor Frete","float",0,1,13,1,0,0,"");
	$carrinho_transportadora 		= criarAtributo($conn,$entidadeID,"transportadora","Transportadora","int",0,1,22,0,installDependencia("ecommerce_transportadora","package/website/ecommerce/envio/transportadora"));
	$carrinho_cep					= criarAtributo($conn,$entidadeID,"cep","CEP","varchar","10",0,9,1,0,0,"");
	$carrinho_is_retirar_loja 		= criarAtributo($conn,$entidadeID,"is_retirar_loja","Retirar na loja ?","tinyint",0,1,7,0,0,0,'',1,0);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Itens do Carrinho
	$itenscarrinhoID = criarEntidade(
		$conn,
		"ecommerce_carrinhoitem",
		"Itens do Carrinho",
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

	$itenscarrinho_carrinho 			= criarAtributo($conn,$itenscarrinhoID,"carrinho","Carrinho","int",0,1,16,0,$entidadeID,0,"");
	$itenscarrinho_produto 				= criarAtributo($conn,$itenscarrinhoID,"produto","Produto","int",0,1,22,0,installDependencia("ecommerce_produto","package/website/ecommerce/mercadoria/produto"),0,"");
	$itenscarrinho_qtde 				= criarAtributo($conn,$itenscarrinhoID,"qtde","Quantidade","tinyint",0,0,26,1,0,0,"");
	$itenscarrinho_descricao 			= criarAtributo($conn,$itenscarrinhoID,"descricao","Descrição","varchar",200,0,3,1,0,0,"");
	$itenscarrinho_imgsrc 				= criarAtributo($conn,$itenscarrinhoID,"imgsrc","Imagem ( Caminho )","varchar",300,1,3,1,0,0,"");
	$itenscarrinho_valor 				= criarAtributo($conn,$itenscarrinhoID,"valor","Valor","float",0,0,13,1,0,0,"");
	$itenscarrinho_valortotal			= criarAtributo($conn,$itenscarrinhoID,"valortotal","Valor Total","float",0,1,13,1,0,0,"");	
	$itenscarrinho_produtonome			= criarAtributo($conn,$itenscarrinhoID,"produtonome","Produto","varchar",200,1,3,0,0,0,"");
	$itenscarrinho_referencia			= criarAtributo($conn,$itenscarrinhoID,"referencia","Referência","varchar",200,1,3,0,0,0,"");
	$itenscarrinho_tamanho_desc 		= criarAtributo($conn,$itenscarrinhoID,"tamanho_desc","Tamanho","varchar",200,1,3,0,0,0,"");
	$itenscarrinho_cor_desc 			= criarAtributo($conn,$itenscarrinhoID,"cor_desc","Cor","varchar",200,1,3,0,0,0,"");
	$itenscarrinho_variacao 			= criarAtributo($conn,$itenscarrinhoID,"variacao","Variação","varchar",200,1,3,0,0,0,"");	
	$itenscarrinho_tamanho 				= criarAtributo($conn,$itenscarrinhoID,"tamanho","Tamanho","int",0,1,4,0,installDependencia("ecommerce_produtotamanho","package/website/ecommerce/mercadoria/produtotamanho"),0,"");
	$itenscarrinho_cor 					= criarAtributo($conn,$itenscarrinhoID,"cor","Cor","int",0,1,4,0,installDependencia("ecommerce_produtocor","package/website/ecommerce/mercadoria/produtocor"),0,"");

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,7,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

	// Cria Relacionamento
	criarRelacionamento($conn,2,$entidadeID,$itenscarrinhoID,"Itens",$itenscarrinho_carrinho);