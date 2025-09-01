<?php
	// Cria Entidade
	$entidade 	= new Entity("website_curso","Curso");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $instrutor = $entidade->addAttr(
		array("nome" => "instrutor" , "descricao" => "Instrutor" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('website_curso_instrutor','website/curso/instrutor/musica/artista'))
	);
    
    $assunto = $entidade->addAttr(
		array("nome" => "assunto" , "descricao" => "Assunto" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('website_curso_assunto','website/curso/assunto'))
	);
    
    $nivel = $entidade->addAttr(
		array("nome" => "nivel" , "descricao" => "Nível" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('website_curso_nivel','website/curso/nivel'))
	);
    
    $conteudo = $entidade->addAttr(
		array("nome" => "conteudo" , "descricao" => "Conteúdo" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('website_curso_conteudo','website/curso/conteudo'))
	);