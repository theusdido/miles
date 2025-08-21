<?php
	// Setando variáveis
	$entidadeNome = "website_redesocial_foto";
	$entidadeDescricao = "Foto";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
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
	// Criando Atributos
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$datahoraenvio = criarAtributo($conn,$entidadeID,"datahoraenvio","Data e Hora de Envio","datetime",0,0,23,0,0,0,"");
	$tipo = criarAtributo($conn,$entidadeID,"tipo","Tipo","varchar",50,1,3);
	$nomearquivo = criarAtributo($conn,$entidadeID,"nomearquivo","Nome do Arquivo","varchar","200",0,3,1,0,0,"");
	$tamanho = criarAtributo($conn,$entidadeID,"tamanho","Tamanho","int",0,0,25);
	$album = criarAtributo($conn,$entidadeID,"album","Álbum","int",0,0,16,0,getEntidadeId("website_redesocial_album",$conn));
	$legenda = criarAtributo($conn,$entidadeID,"legenda","Legenda","varchar",500,0,3,1,0,0,"");
	
	// Criando Acesso
	$menu_redesocial = addMenu($conn,$entidadeDescricao,'#','',0,0,'redesocial');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_redesocial,1,'redesocial-' . $entidadeNome,$entidadeID, 'cadastro');