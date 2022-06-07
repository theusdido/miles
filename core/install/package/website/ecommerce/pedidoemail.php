<?php
	// Setando variáveis
	$entidadeNome = "ecommerce_pedidoemail";
	$entidadeDescricao = "E-Mail de Pedido";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 1
	);

	// Criando Atributos
    $email      = criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",200,0,12);
    $descricao  = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",50,1,3);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');

    // Criar Capa
    criarAba($conn,$entidadeID,'Destinatário',array($email));

	// Cria Relacionamento
    criarRelacionamento($conn,7,$entidadeID,getEntidadeId("email"),"Remetente",$email);