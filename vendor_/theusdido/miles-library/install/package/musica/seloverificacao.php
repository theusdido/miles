<?php
	// Cria Entidade
	$entidade 	= new Entity("seloverificacao","Selo de Verificação");

	// Atributos
	$nome	  = $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome", "is_display" => true, "is_exibirgradedados" => true)
	);

    $icone = $entidade->addAttr(
		array("nome" => "icone" , "descricao" => "Ícone", "tipohtml" => "arquivo_caminho", "is_obrigatorio" => 0)
	);