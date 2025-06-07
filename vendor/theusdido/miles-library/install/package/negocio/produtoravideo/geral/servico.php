<?php
	// Cria Entidade
	$entidade 	= new Entity("negocio_produtorvideo_servico","Serviço");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

	$detalhamento = $entidade->addAttr(
		array("nome" => "detalhamento" , "descricao" => "Detalhamento", "tipohtml" => "ckeditor")
	);

	$valor     = $entidade->addAttr(
		array("nome" => "valor" , "descricao" => "Valor" , "tipohtml" => "monetario")
	);

	$profissao = $entidade->addAttr(
		array("nome" => "profissao" , "descricao" => "Profissao" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('negocio_produtoravideo_profissao','package/negocio/produtoravideo/geral/profissao'))
	);