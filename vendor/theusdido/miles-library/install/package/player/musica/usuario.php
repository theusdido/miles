<?php
	// Cria Entidade
	$entidade 	= new Entity("player_musica_usuario","Usuário");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

	$data_nascimento	  = $entidade->addAttr(
		array("nome" => "datanascimento" , "descricao" => "Data de Nascimento", "tipohtml" => "data")
	);

	$email	  = $entidade->addAttr(
		array("nome" => "email" , "descricao" => "E-Mail", "tipohtml" => "email")
	);	

	$senha	  = $entidade->addAttr(
		array("nome" => "senha" , "descricao" => "Senha", "tipohtml" => "senha")
	);

    $foto = $entidade->addAttr(
		array("nome" => "foto" , "descricao" => "Foto", "tipohtml" => "arquivo_caminho", "is_obrigatorio" => 0)
	);