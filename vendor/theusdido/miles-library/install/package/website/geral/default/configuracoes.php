<?php
	$entidadeNome 		= "website_geral_configuracoes";
	$entidadeDescricao 	= "Website ( Configurações )";

	// 1º PASSO
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
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

	// 2º PASSO	
	$logotipohome 		= criarAtributo($conn,$entidadeID,"logohome","Logotipo ( Página Inicial )","text","",1,19,0,0,0,"");
	$metatagdescription = criarAtributo($conn,$entidadeID,"metatagdescription","Meta Tag Descrição","varchar",50,1,3,0,0,0,"",0,0);
	$metatagauthor 		= criarAtributo($conn,$entidadeID,"metatagauthor","Meta Tag Autor","varchar",50,1,3,0,0,0,"",0,0);

	// 3º PASSO
	$menu_webiste 		= addMenu($conn,'WebSite','#','',0,0,'website');

	// 4º PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,1,'website-'.$entidadeNome,$entidadeID, 'cadastro');

	// 5º PASSO
	criarAba($conn,$entidadeID,'Logotipo',$logotipohome);
	criarAba($conn,$entidadeID,'SEO',array($metatagdescription,$metatagauthor));