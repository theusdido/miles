<?php
	// Cria Entidade
	$entidade 	= new Entity("faixa","Faixa");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $duracao = $entidade->addAttr(
		array("nome" => "duracao" , "descricao" => "Duração", "tipohtml" => "hora", "is_obrigatorio" => 0)
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
		array("nome" => "publicacao" , "descricao" => "Publicação" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('player_musica_geral_publicacao','package/player/musica/geral/publicacao'))
	);
	
    $direito_autoral = $entidade->addAttr(
		array("nome" => "direitoautoral" , "descricao" => "Direito Autoral" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('player_musica_geral_direitoautoral','package/player/musica/geral/direitoautoral'))
	);

    $genero_musical = $entidade->addAttr(
		array("nome" => "genero" , "descricao" => "Gênero Musical" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('player_musica_geral_genero','package/player/musica/geral/genero'))
	);	