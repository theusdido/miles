<?php
	// Cria Entidade
	$entidade 	= new Entity("website_idioma_traducao","Tradução");

	// Atributos
	$lingua	= $entidade->addAttr(
		array("nome" => "lingua" , "descricao" => "Língua" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('website_idioma_lingua'))
	);
    
	$texto	= $entidade->addAttr(
		array("nome" => "texto" , "descricao" => "Texto" , "tipo" => "text" , "tamanho" => 0)
	);

	$atributo	= $entidade->addAttr(
		array("nome" => "atributo" , "descricao" => "Atributo" , "tipohtml" => "numero_inteiro")
	);

	$registro	= $entidade->addAttr(
		array("nome" => "registro" , "descricao" => "Registro" , "tipohtml" => "numero_inteiro")
	);    