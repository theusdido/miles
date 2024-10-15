<?php
	$entidadeNome 		= "website_geral_perguntasfrequentes";
	$entidadeDescricao 	= "Perguntas Frequentes ?";

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
    criarAtributo($conn,$entidadeID,"ordem","Ordem"	,"INT",0,1,25	,1);
	criarAtributo($conn,$entidadeID,"pergunta","Pergunta"	,"text","",0,3	,1);
	criarAtributo($conn,$entidadeID,"resposta"	,"Resposta"	,"text","",0,21	,0);

	// 3 PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,7,'website-'.$entidadeNome,$entidadeID,'cadastro');