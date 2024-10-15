<?php
	$entidadeNome 		= "website_geral_sobrenos";
	$entidadeDescricao 	= "Sobre Nós";
	
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
		$registrounico = 1
	);

	// 2 PASSO
	$foto		= criarAtributo($conn,$entidadeID,"foto","Foto"	,"text","",1,19	,0,0,0,"");
	$texto	    = criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text","",1,21	,0,0,0,"");
    $video      = criarAtributo($conn,$entidadeID,"youtube"	,"Youtube ( LINK )"	,"varchar",500,1,3	,0,0,0,"");

	// 3 PASSO
	$menu = addMenu($conn,'WebSite','#','',0,0,'website');

	// 4 PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,0,'website-'.$entidadeNome,$entidadeID,'cadastro');