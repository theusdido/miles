<?php
	// Setando variáveis
	$entidadeNome = "website_redesocial_perfil";
	$entidadeDescricao = "Perfil";

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
	$genero = criarAtributo($conn,$entidadeID,"genero","Gênero","int",0,0,4,1,getEntidadeId("website_redesocial_genero",$conn),0,"");
	$datanascimento = criarAtributo($conn,$entidadeID,"datanascimento","Data de Nascimento","date",0,0,11,0,0,0,"");
	$especie = criarAtributo($conn,$entidadeID,"especie","Espécie","int",0,0,4,1,installDependencia("website_redesocial_especie",'package/website/redesocial/especie'));
	$foto = criarAtributo($conn,$entidadeID,"foto","Foto","varchar",500,1,19);
	$usuario = criarAtributo($conn,$entidadeID,"usuario","Usuário","int",0,0,16,0,getEntidadeId("website_redesocial_usuario",$conn));

	// Criando Acesso
	$menu_redesocial = addMenu($conn,$entidadeDescricao,'#','',0,0,'redesocial');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_redesocial,1,'redesocial-' . $entidadeNome,$entidadeID, 'cadastro');

	// Relacionamento
	criarRelacionamento($conn,2,getEntidadeId("website_redesocial_usuario",$conn),$entidadeID,"Perfil",$usuario);