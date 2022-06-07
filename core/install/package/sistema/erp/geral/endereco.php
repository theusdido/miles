<?php
	// Criando Acesso
	$menu_webiste 	= addMenu($conn,'Geral','#','',0,0,'geral');

	// Estado
	$paisID = criarEntidade(
		$conn,
		"erp_geral_pais",
		"País",
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	criarAtributo($conn,$paisID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$paisID,"sigla","Sigla","char","2",0,3,1,0,0,"");

	// Estado
	$entidadeNomeUF 		= "erp_geral_uf";
	$entidadeDescricaoUF 	= "Estado ( UF )";
	$ufID = criarEntidade(
		$conn,
		$entidadeNomeUF,
		$entidadeDescricaoUF,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	$uf_nome 	= criarAtributo($conn,$ufID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$uf_sigla 	= criarAtributo($conn,$ufID,"sigla","Sigla","char","2",0,3,1,0,0,"");
	$uf_pais 	= criarAtributo($conn,$ufID,"pais","País","int",0,0,4,1,$paisID,0,"");
	Entity::setDescriptionField($conn,$ufID ,$uf_nome,true);

	// Adicionando Menu
	addMenu($conn,$entidadeDescricaoUF,"files/cadastro/".$ufID."/".getSystemPREFIXO().$entidadeNomeUF.".html",'',$menu_geral,0,'geral-uf',$ufID,'cadastro');

	// Cidade
	$entidadeNomeCidade 		= "erp_geral_cidade";
	$entidadeDescricaoCidade 	= "Cidade ( Localidade )";
	$cidadeID = criarEntidade(
		$conn,
		$entidadeNomeCidade,
		$entidadeDescricaoCidade,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	criarAtributo($conn,$cidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$cidadeID,"bairromapeado","Bairro Mapeado","tinyint",0,0,7,0,0,0,"");
	criarAtributo($conn,$cidadeID,"uf","UF","int",0,0,4,1,$ufID,0,"");

	// Adicionando Menu
	addMenu($conn,$entidadeDescricaoCidade,"files/cadastro/".$cidadeID."/".getSystemPREFIXO().$entidadeNomeCidade.".html",'',$menu_geral,0,'geral-cidade',$cidadeID,'cadastro');

	// Bairro
	$entidadeNomeBairro 		= "erp_geral_bairro";
	$entidadeDescricaoBairro 	= "Bairro";
	$bairroID = criarEntidade(
		$conn,
		$entidadeNomeBairro,
		$entidadeDescricaoBairro,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	criarAtributo($conn,$bairroID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$bairroID,"cidade","Cidade","int",0,0,22,1,$cidadeID,0,"");

	// Adicionando Menu
	addMenu($conn,$entidadeDescricaoBairro,"files/cadastro/".$bairroID."/".getSystemPREFIXO().$entidadeNomeBairro.".html",'',$menu_geral,0,'geral-bairro',$cidadeID,'cadastro');
	
	// Endereço
	$entidadeNomeEndereco 		= "erp_geral_endereco";
	$entidadeDescricaoEndereco 	= "Endereço";
	$enderecoID = criarEntidade(
		$conn,
		$entidadeNomeEndereco,
		$entidadeDescricaoEndereco,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);
	criarAtributo($conn,$enderecoID,"bairro","Bairro","varchar","35",0,22,1,$bairroID,0,"");
	criarAtributo($conn,$enderecoID,"logradouro","Logradouro","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"numero","Número","varchar","5",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"complemento","Complemento","varchar","200",0,3,1,0,0,"");
	criarAtributo($conn,$enderecoID,"cep","CEP","varchar","10",0,9,1,0,0,"");
	
	// Adicionando Menu
	addMenu($conn,$entidadeDescricaoEndereco,"files/cadastro/".$enderecoID."/".getSystemPREFIXO().$entidadeNomeEndereco.".html",'',$menu_geral,0,'geral-endereco',$cidadeID,'cadastro');
