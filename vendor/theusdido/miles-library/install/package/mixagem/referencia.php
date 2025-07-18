<?php
    
    $entidade 	= new Entity("referencia","Referencia de Mixagem");

	$trabalho = $entidade->addAttr(
		array("nome" => "trabalho" , "descricao" => "Trabalho" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('negocio_produtoramusical_mixagem_trabalho','package/negocio/produtoramusical/mixagem/trabalho'))
	);    

    $url	  = $entidade->addAttr(
        array("nome" => "url" , "descricao" => "URL", "tipohtml" => "varchar" , "tamanho" => 1000)
    );

	$track_referencia = $entidade->addAttr(
		array("nome" => "trackreferencia" , "descricao" => "Track Referencia", "tipohtml" => "arquivo_caminho")
	);

	$material = $entidade->addAttr(
		array("nome" => "material" , "descricao" => "Material", "tipohtml" => "arquivo_caminho")
    );