<?php
	// Cria Entidade
	$entidade 	= new Entity("website_blog_colunista","Colunista");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $foto = $entidade->addAttr(
		array("nome" => "foto" , "descricao" => "Foto", "tipohtml" => "arquivo_caminho", "is_obrigatorio" => 0)
	);

    $coluna = $entidade->addAttr(
		array("nome" => "coluna" , "descricao" => "Coluna" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('website_blog_coluna','website/blog/editorial/coluna'), 'is_exibirgradedados' => 1)
	);