<?php
	// Setando variáveis
	$entidadeNome = "erp_livraria_livro";
	$entidadeDescricao = "Livro";

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
	$ano	 			= criarAtributo($conn,$entidadeID,"ano","Ano","char",4,1,25,0);
	$autor       		= criarAtributo($conn,$entidadeID,"autor","Autor","int",0,1,22,0,installDependencia("erp_livraria_autor",'package/sistema/erp/livraria/autor'));
	$titulo 			= criarAtributo($conn,$entidadeID,"titulo","Título","varchar",200,0,3,1);
	$editora       		= criarAtributo($conn,$entidadeID,"editora","Editora","int",0,1,22,0,installDependencia("erp_livraria_editora",'package/sistema/erp/livraria/livro'));
	$edicao 			= criarAtributo($conn,$entidadeID,"edicao","Edição","varchar",15,1,3,0);
	$volume 			= criarAtributo($conn,$entidadeID,"volume","Volume","varchar",15,1,3,0);
	$isbn 				= criarAtributo($conn,$entidadeID,"isbn","ISBN","varchar",50,1,3,0);
	$datapublicacao		= criarAtributo($conn,$entidadeID,"datapublicacao","Data de Publicação","date",0,1,11,0);
	$paginas			= criarAtributo($conn,$entidadeID,"paginas","Páginas","tinyint",0,1,25,0);
	$genero       		= criarAtributo($conn,$entidadeID,"genero","Gênero","int",0,1,4,0,installDependencia("erp_livraria_genero",'package/sistema/erp/livraria/genero'));
	$imagem				= criarAtributo($conn,$entidadeID,"imagem","Imagem","varchar",1000,1,19,1);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$titulo,true);
			
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Livraria','#','',0,0,'livraria');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,1,'livraria-' . $entidadeNome,$entidadeID, 'cadastro');