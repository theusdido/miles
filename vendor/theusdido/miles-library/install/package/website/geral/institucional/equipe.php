<?php
	$entidadeNome 		= "website_geral_equipe";
	$entidadeDescricao 	= "Equipe";
	
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
	$nome 		= criarAtributo($conn,$entidadeID,"nome","Nome"	,"varchar",200,0,3	,1,0,0,"");
	$foto		= criarAtributo($conn,$entidadeID,"foto","Foto"	,"text","",1,19	,0,0,0,"");
	$biografia	= criarAtributo($conn,$entidadeID,"biografia"	,"Biografia"	,"text","",1,21	,0,0,0,"");
	$cargo		= criarAtributo($conn,$entidadeID,"cargo"	,"Cargo","int",0,1,4,1,installDependencia("website_geral_cargo"));

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$nome,true);

	// 3 PASSO
	$menu = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,0,'website-'.$entidadeNome,$entidadeID,'cadastro');