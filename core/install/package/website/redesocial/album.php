<?php
	// Setando variáveis
	$entidadeNome = "website_redesocial_album";
	$entidadeDescricao = "Álbum de Fotos";

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
	$nome = criarAtributo($conn,$entidadeID,"titulo","Título","varchar","200",0,3,1,0,0,"");
	$datanascimento = criarAtributo($conn,$entidadeID,"data","Data","date",0,0,11,0,0,0,"");
	$perfil = criarAtributo($conn,$entidadeID,"perfil","Perfil","int",0,0,16,0,getEntidadeId("website_redesocial_perfil",$conn));

	// Criando Acesso
	$menu_redesocial = addMenu($conn,$entidadeDescricao,'#','',0,0,'redesocial');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_redesocial,1,'redesocial-' . $entidadeNome,$entidadeID, 'cadastro');
	
	// Instalando a entidade das fotos
	$fotos = installDependencia("website_redesocial_foto",'package/website/redesocial/foto');
