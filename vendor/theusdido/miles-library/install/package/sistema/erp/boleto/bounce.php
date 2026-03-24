<?php
	// Cria Entidade
	$entidade 	= new Entity("boleto_bounce","Bouce de Boletos");

	// Atributos
	$descricao	= $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição" , "tipo" => "varchar" , "tamanho" => 200)
	);

	$codigo	= $entidade->addAttr(
		array("nome" => "codigo" , "descricao" => "Código" , "tipo" => "varchar" , "tamanho" => 5)
	);
	
	$remetente	= $entidade->addAttr(
		array("nome" => "remetente" , "descricao" => "Remetente (De)" , "tipo" => "varchar" , "tamanho" => 200)
	);
	
	$destinatario	= $entidade->addAttr(
		array("nome" => "destinatario" , "descricao" => "Destinatário (Para)" , "tipo" => "varchar" , "tamanho" => 200)
	);	

	$mensagem	= $entidade->addAttr(
		array("nome" => "mensagem" , "descricao" => "Mensagem" , "tipo" => "text")
	);
	
	$xsmtplw	= $entidade->addAttr(
		array("nome" => "xsmtplw" , "descricao" => "Descrição do Retorno" , "tipo" => "varchar" , "tamanho" => 200)
	);

	$data_hora	= $entidade->addAttr(
		array("nome" => "data_hora" , "descricao" => "Data/Hora" , "tipo" => "datetime")
	);	