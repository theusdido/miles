<?php
	// Setando variáveis
	$entidadeNome		= "website_blog_perfil";
	$entidadeDescricao 	= "Perfil";
	
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
	$usuario = criarAtributo($conn,$entidadeID,"usuario","Usuário","int","0",0,4,0,getEntidadeId("usuario",$conn),0,"");
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",0,3,1,0,0,"");
	$profissao = criarAtributo($conn,$entidadeID,"profissao","Profissão","varchar","200",0,3,1,0,0,"");
	$quemsou = criarAtributo($conn,$entidadeID,"quemsou","Quem Sou","varchar","500",0,"21",0,0,0,"");
	$datanascimento = criarAtributo($conn,$entidadeID,"datanascimento","Data de Nascimento","date",0,1,11,1,0,0,"");
	$nacionalidade = criarAtributo($conn,$entidadeID,"nacionalidade","Nacionalidade","varchar","50",0,3,1,0,0,"");
	$naturalidade = criarAtributo($conn,$entidadeID,"naturalidade","Naturalidade","varchar","50",0,3,1,0,0,"");
	$conjuge = criarAtributo($conn,$entidadeID,"conjuge","Conjugê","varchar","200",0,3,0,0,0,"");
	$filhos = criarAtributo($conn,$entidadeID,"filhos","Filhos","text",0,0,3,0,0,0,"");
	$foto = criarAtributo($conn,$entidadeID,"foto","Foto","varchar","200",0,19,0,0,0,"");
	
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Blog','#','',0,0,'blog');
	
	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,1,'blog-' . $entidadeNome,$entidadeID, 'cadastro');