<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_fichatecnica";
	$entidadeDescricao 	= "Ficha Técnica";

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
        $registrounico = 0
	);

    // Criando Atributos
    $produto        = criarAtributo($conn,$entidadeID,"produto","Produto","int",0,0,16,1,getEntidadeId("ecommerce_produto",$conn),0,"");
    $especificacao  = criarAtributo($conn,$entidadeID,"especificacao","Especificação","int",0,0,4,0,getEntidadeId("ecommerce_especificacaotecnica",$conn),1,"");
    $descricao      = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar","1000",0,3,1,0,0,"");

   	// Criando Acesso
	$menu_webiste = addMenu($conn,'E-Commerce','#','',0,0,'ecommerce');

	// Adicionando Menu
    addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'ecommerce-' . $entidadeNome,$entidadeID,'cadastro');
    
	// Cria Relacionamento
	criarRelacionamento($conn,6,getEntidadeId("ecommerce_produto",$conn),$entidadeID,"Ficha Técnica",$produto);