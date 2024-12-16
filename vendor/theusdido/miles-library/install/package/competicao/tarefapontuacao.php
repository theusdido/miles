<?php
	// Cria Entidade
	$entidade 	= new Entity("competicao_gincana_tarefapontuacao","Pontuação da Tarefa");

	// Atributos
	$tarefa	= $entidade->addAttr(
		array("nome" => "tarefa" , "descricao" => "Tarefa" , "tipo" => "inteiro" , "chave_estrangeira" => getEntidadeId('competicao_gincana_tarefa'))
	);
	$equipe	= $entidade->addAttr(
		array("nome" => "equipe" , "descricao" => "Equipe" , "tipo" => "inteiro" , "chave_estrangeira" => installDependencia('competicao_gincana_equipe'))
	);	 
	$pontuacao	= $entidade->addAttr(
		array("nome" => "pontuacao" , "descricao" => "Pontuação" , "tipo" => "inteiro")
	);
	$classificacao	= $entidade->addAttr(
		array("nome" => "classificacao" , "descricao" => "Classificação" , "tipo" => "inteiro" , "is_obrigatorio" => 0)
	);