<?php
	// Cria Entidade
	$entidade 	= new Entity("player_musica_playlist","Playlist");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $usuario = $entidade->addAttr(
		array("nome" => "usuario" , "descricao" => "Usuário" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('usuario'))
	);

	$datahoracriacao	= $entidade->addAttr(
		array("nome" => "datahoracriacao" , "descricao" => "Data/Hora de Criação" , "tipohtml" => "datahora")
	);	