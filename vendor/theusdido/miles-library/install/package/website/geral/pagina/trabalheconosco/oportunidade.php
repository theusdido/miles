<?php
	$entidadeNome 		= "website_geral_trabalheconosco_oportunidade";
	$entidadeDescricao 	= "Oportunidade";
	
	// 1 PASSO
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

	// 2 PASSO
	$vaga   		= criarAtributo($conn,$entidadeID,"vaga","Vaga"	,"varchar",250,0,3,1);
	$texto  		= criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text","",1,21	,0,0,0,"");
	$requisitos  	= criarAtributo($conn,$entidadeID,"requisitos"	,"Requisitos"	,"text","",1,21	,0,0,0,"");
	$beneficios  	= criarAtributo($conn,$entidadeID,"beneficios"	,"Beneficios"	,"text","",1,21	,0,0,0,"");

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$vaga,true);

	// 3 PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,7,'website-'.$entidadeNome,$entidadeID,'cadastro');