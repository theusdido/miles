<?php
	// Cria Entidade
	$entidade 	= new Entity("player_musica","Música");

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

    $usuario = $entidade->addAttr(
		array("nome" => "usuario" , "descricao" => "Usuário" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('usuario'))
	);

    $lingua = $entidade->addAttr(
		array("nome" => "lingua" , "descricao" => "Língua" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('website_idioma_lingua'))
	);

    $lingua = $entidade->addAttr(
		array("nome" => "publicacao" , "descricao" => "Publicação" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_publicacao','player/musica/publicacao'))
	);
	
    $direito_autoral = $entidade->addAttr(
		array("nome" => "direitoautoral" , "descricao" => "Direito Autoral" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_direitoautoral','player/musica/direitoautoral'))
	);	

    $genero_musical = $entidade->addAttr(
		array("nome" => "genero" , "descricao" => "Gênero Musical" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('player_musica_genero','player/musica/genero'))
	);	