<?php
	$entidadeNome 		= "trabalheconosco";
	$entidadeDescricao 	= "Trabalhe Conosco";
	
	// 1 PASSO
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
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 1
	);

	// 2 PASSO
	$titulo = criarAtributo($conn,$entidadeID,"titulo","Titulo"	,"text","",0,3	,1,0,0,"");
	$imagem = criarAtributo($conn,$entidadeID,"imagem","Imagem"	,"text","",1,19	,0,0,0,"");
	$texto  = criarAtributo($conn,$entidadeID,"texto"	,"Texto"	,"text","",1,21	,0,0,0,"");
    
    Entity::setDescriptionField($conn,$entidadeID,$titulo,true);