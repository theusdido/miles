<?php
	// Setando variáveis
	$entidadeNome = "album";
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
	
	// Instalando a entidade das fotos
	$fotos = installDependencia("website_redesocial_foto",'package/website/redesocial/foto');
