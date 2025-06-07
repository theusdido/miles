<?php
	// Cria Entidade
	$entidade 	= new Entity("negocio_produtoramusical_produtor","Produtor");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $especialidade = $entidade->addAttr(
		array("nome" => "especialidade" , "descricao" => "Especialidade" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('negocio_produtoramusical_especialidade','package/negocio/produtoramusical/geral/especialidade'))
	);

    $fotocapa = $entidade->addAttr(
		array("nome" => "fotocapa" , "descricao" => "Foto Capa", "tipohtml" => "arquivo_caminho")
	);

    $biografia = $entidade->addAttr(
		array("nome" => "biografia" , "descricao" => "Biografia", "tipohtml" => "ckeditor")
	);	