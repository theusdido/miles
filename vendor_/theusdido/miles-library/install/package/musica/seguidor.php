<?php
	// Cria Entidade
	$entidade 	= new Entity("seguidor","Seguidor");

	// Atributos
    $artista = $entidade->addAttr(
		array("nome" => "artista" , "descricao" => "Artísta" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_geral_artista','package/player/geral/artista'))
	);

    $usuario = $entidade->addAttr(
		array("nome" => "usuario" , "descricao" => "Usuário" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('usuario'))
	);