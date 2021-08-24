<?php
	// Setando variáveis
	$entidadeNome = "ecommerce_recuperacaosenha";
	$entidadeDescricao = "Recuperacao de Senha";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0,
		$carregarlibjavascript = 1,
		$criarinativo = false
	);

	// Criando Atributos
	$token 				= criarAtributo($conn,$entidadeID,"token","Token","varchar","200",0,3);
	$cliente			= criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,22,1,getEntidadeId("ecommerce_cliente"));
	$datahoraenvio		= criarAtributo($conn,$entidadeID,"datahoraenvio","Data/Hora de Envio","datetime",0,1,23,1);
	$datahoravalidade	= criarAtributo($conn,$entidadeID,"datahoravalidade","Data/Hora de Validade","datetime",0,0,23,1);
	$email				= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar","200",1,12);
	$ip					= criarAtributo($conn,$entidadeID,"ip","IP","varchar","64",1,3);
	$dadosnavegacao		= criarAtributo($conn,$entidadeID,"dadosnavegacao","Dados de Navegação","text",0,1,21);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','','','','ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".PREFIXO.$entidadeNome.".html",'',$menu_webiste,8,'ecommerce-' . $entidadeNome);