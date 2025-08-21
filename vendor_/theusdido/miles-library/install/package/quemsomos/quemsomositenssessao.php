<?php
	$entidadeNome 		= "quemsomositenssessao";
	$entidadeDescricao 	= "Sessão de Itens ( Quem Somos )";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	$descricao          = criarAtributo($conn,$entidadeID,"descricao"	,"Descrição"	,"varchar",200,0,3,1);
	$image_svg			= criarAtributo($conn,$entidadeID,"imagemsvg","Imagem (SVG)","text",0,1,14,0);
    Entity::setDescriptionField($conn,$entidadeID,$descricao);