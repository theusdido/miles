<?php	
	$entidadeNome = "rodape";
	$entidadeDescricao = "Rodapé";
	
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
	criarAtributo($conn,$entidadeID,"exibirfrasedireitosreservados","Frase ( Direitos Reservados )","tinyint","",0,7,1,0,0,"");
	criarAtributo($conn,$entidadeID,"exibirfrasedesenvolvidopor","Frase ( Desenvolvido por )","tinyint","",0,7,1,0,0,"");
	
	// 3º PASSO
	$menu_webiste = addMenu($conn,'WebSite','#','',0,0,'website');
	
	// 4º PASSO
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',".$menu_webiste.",6,'website-' . $entidadeNome);