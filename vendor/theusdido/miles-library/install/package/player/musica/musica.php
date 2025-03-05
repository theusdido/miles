<?php
	// Cria Entidade
	$entidade 	= new Entity("player_musica_musica","Música");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $duracao = $entidade->addAttr(
		array("nome" => "duracao" , "descricao" => "Duração", "tipohtml" => "tempo", "is_obrigatorio" => 0)
	);

    $arquivo = $entidade->addAttr(
		array("nome" => "arquivo" , "descricao" => "Arquivo", "tipohtml" => "arquivo_caminho", "is_obrigatorio" => 0)
	);

    