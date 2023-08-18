<?php
	// Cria Entidade
	$entidade 	= new Entity("qa_requisito_funcional","Requisito Funcional");

	// Atributos
	$nome	= $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipohtml" => 3 , "is_display" => true)
	);

	$descricao	  = $entidade->addAttr(
		array("nome" => "descricao" , "descricao" => "Descrição")
	);
    installDependencia('qa_requisito_nao_funcional','package/qa/analise/requisito/nao_funcional');