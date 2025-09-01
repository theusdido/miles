<?php
	$entidadeNome 		= "website_geral_slider";
	$entidadeDescricao 	= "Slider";
	
	// 1º PASSO
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$descricao = $entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	
	// 2º PASSO	
	$titulo = criarAtributo($conn,$entidadeID,"titulo","Título"	,"varchar",200,1,3,1);
	$imagem = criarAtributo($conn,$entidadeID,"imagem","Imagem"	,"text","",1,19	,1,0,0,"");
	$html 	= criarAtributo($conn,$entidadeID,"html"	,"HTML"	,"text","",1,21	,0,0,0,"");
	$exibir = criarAtributo($conn,$entidadeID,"exibir","Exibir"	,"tinyint","",1,7,1);

	Entity::setDescriptionField($conn,$entidadeID,$titulo,true);
	
	// 3º PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');
	
	// 4º PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,6,'website-'.$entidadeNome,$entidadeID,'cadastro');