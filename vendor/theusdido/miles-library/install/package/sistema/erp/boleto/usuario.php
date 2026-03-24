<?php
	// Cria Entidade
	$entidade 	= new Entity("boleto_usuario","Usuário dos Boletos");

	// Atributos
	$nome	= $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipo" => "varchar" , "tamanho" => 200)
	);

	$email	  = $entidade->addAttr(
		array("nome" => "email" , "descricao" => "E-Mail", "tipohtml" => "email")
	);	
	
	$login	= $entidade->addAttr(
		array("nome" => "login" , "descricao" => "Login" , "tipo" => "varchar" , "tamanho" => 25)
	);
	
	$senha	= $entidade->addAttr(
		array("nome" => "senha" , "descricao" => "Senha" , "tipohtml" => "senha_sem_criptografia", "tamanho" => 15)
	);	