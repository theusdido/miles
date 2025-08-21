<?php
	// Cria Entidade
	$entidade 	= new Entity("artista","Artista");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $fotocapa = $entidade->addAttr(
		array("nome" => "fotocapa" , "descricao" => "Foto Capa", "tipohtml" => "arquivo_caminho")
	);

    $generomusical = $entidade->addAttr(
		array("nome" => "generomusical" , "descricao" => "Gênero Musical" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('negocio_produtoramusical_generomusical','package/negocio/produtoramusical/geral/generomusical'))
	);

    $biografia = $entidade->addAttr(
		array("nome" => "biografia" , "descricao" => "Biografia", "tipohtml" => "ckeditor")
	);	