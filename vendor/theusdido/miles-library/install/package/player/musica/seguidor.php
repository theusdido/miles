<?php
	// Cria Entidade
	$entidade 	= new Entity("player_musica_seguidor","Seguidor");

	// Atributos
    $artista = $entidade->addAttr(
		array("nome" => "artista" , "descricao" => "Artísta" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_artista','player/musica/artista'))
	);

    $usuario = $entidade->addAttr(
		array("nome" => "usuario" , "descricao" => "Usuário" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('usuario'))
	);