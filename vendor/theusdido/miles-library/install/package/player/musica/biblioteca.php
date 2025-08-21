<?php
	// Cria Entidade
	$entidade 	= new Entity("player_musica_biblioteca","Biblioteca");

	// Atributos
    $playlist = $entidade->addAttr(
		array("nome" => "playlist" , "descricao" => "Playlist" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_playlist','player/musica/playlist'))
	);

    $album = $entidade->addAttr(
		array("nome" => "album" , "descricao" => "Álbum" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_album','player/musica/album'))
	);