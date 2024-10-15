<?php
	// Cria Entidade
	$entidade 	= new Entity("website_idioma_lingua","Língua");

	// Atributos
	$nome	= $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipo" => "varchar" , "tamanho" => 200, 'is_display' => true , 'is_exibirgradedados' => 1),
	);

	$codigo_iso = $entidade->addAttr(
		array("nome" => "codigo_iso" , "descricao" => "Código ISO" , "tipo" => "char" , "tamanho" => 5)
	);

	$bandeira = $entidade->addAttr(
		array("nome" => "bandeira" , "descricao" => "Bandeira" , "tipohtml" => 'arquivo_caminho')
	);