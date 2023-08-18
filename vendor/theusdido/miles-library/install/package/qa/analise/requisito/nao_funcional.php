<?php
	// Cria Entidade
	$entidade 	= new Entity("qa_requisito_nao_funcional","Requisito Não Funcional");

	// Atributos
	$requisito_funcional	= $entidade->addAttr(
		array("nome" => "requisito_funcional" , "descricao" => "Requisito Funcional" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId("qa_requisito_funcional"))
	);

	$nome	= $entidade->addAttr(
		array("nome" => "nome" , "descricao" => "Nome" , "tipohtml" => 3)
	);

	$restricao     = $entidade->addAttr(
		array("nome" => "restricao" , "descricao" => "Restrição" , "tipohtml" => 3, "tamanho" => 200)
	);

	$categoria	= $entidade->addAttr(
		array("nome" => "categoria" , "descricao" => "Categoria" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia("qa_requisito_categoria",'package/qa/analise/requisito/categoria'))
	);    