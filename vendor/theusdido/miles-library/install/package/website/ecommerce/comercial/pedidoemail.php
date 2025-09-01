<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_pedidoemail";
	$entidadeDescricao 	= "E-Mail de Envio do Pedido";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 1
	);

	// Criando Atributos
    $email      		= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",200,0,12);
	$username 			= criarAtributo($conn,$entidadeID,"username","Nome de Usuário","varchar",200,0,3);
	$password 		    = criarAtributo($conn,$entidadeID,"password","Senha","varchar",120,0,3);
    $assunto  			= criarAtributo($conn,$entidadeID,"assunto","Assunto","varchar",50,1,3);
	$destinatario    	= criarAtributo($conn,$entidadeID,"destinatario","Destinatário ( FromName )","varchar",50,1,3);	

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');