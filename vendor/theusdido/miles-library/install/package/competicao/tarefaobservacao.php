<?php
	// Cria Entidade
	$entidade 	= new Entity("competicao_gincana_tarefaobservacao","Observação da Tarefa");

	// Atributos
	$tarefa	= $entidade->addAttr(
		array("nome" => "tarefa" , "descricao" => "Tarefa" , "tipo" => "inteiro" , "chave_estrangeira" => getEntidadeId('competicao_gincana_tarefa'))
	);    
	$numero	= $entidade->addAttr(
		array("nome" => "numero" , "descricao" => "Número" , "tipo" => "inteiro")
	);
	$descricao	= $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição" , "tipo" => "ckeditor")
	);