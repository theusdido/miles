<?php
	// Cria Entidade
	$entidade 	= new Entity("website_idioma_configuracao","Configurações");
    $entidade->setRegistroUnico();

	// Atributos
	$lingua	= $entidade->addAttr(
		array("nome" => "lingua_padrao" , "descricao" => "Língua Padrão" , "tipohtml" => "filtro_pesquisa" , "chave_estrangeira" => installDependencia('website_idioma_lingua'))
	);