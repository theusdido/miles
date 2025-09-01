<?php
	// Cria Entidade
	$entidade 	= new Entity("competicao_gincana_tarefa","Tarefa");

	// Atributos
	$numero	= $entidade->addAttr(
		array("nome" => "numero" , "descricao" => "Número" , "tipo" => "inteiro")
	);    
	$nome	= $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipohtml" => "varchar" , "tamanho" => 200)
	);
	$descricao	= $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição" , "tipo" => "ckeditor")
	);
	$instrucao	= $entidade->addAttr(
		array("nome" => "instrucao" , "descricao" => "Instrução" , "tipo" => "ckeditor")
	);
	$datahora	= $entidade->addAttr(
		array("nome" => "datahora" , "descricao" => "Data/Hora" , "tipo" => "datahora")
	);
	$local	= $entidade->addAttr(
		array("nome" => "local" , "descricao" => "Local" , "tipo" => "inteiro" , "chave_estrangeira" => installDependencia('competicao_gincana_local'))
	);
	$desenvolvimento	= $entidade->addAttr(
		array("nome" => "desenvolvimento" , "descricao" => "Desenvolvimento" , "tipo" => "ckeditor")
	);
	$criterioavaliativo	= $entidade->addAttr(
		array("nome" => "criterioavaliativo" , "descricao" => "Critério Avaliativo" , "tipohtml" => "varchar" , "tamanho" => 500)
	);