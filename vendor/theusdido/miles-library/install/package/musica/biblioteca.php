<?php
	// Cria Entidade
	$entidade 	= new Entity("biblioteca","Biblioteca");

	// Atributos
    $playlist = $entidade->addAttr(
		array("nome" => "playlist" , "descricao" => "Playlist" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_geral_playlist','package/player/geral/musica/playlist'))
	);

    $album = $entidade->addAttr(
		array("nome" => "album" , "descricao" => "Álbum" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_geral_album','package/player/geral/album'))
	);