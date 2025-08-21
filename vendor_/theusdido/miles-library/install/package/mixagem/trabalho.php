<?php

    $entidade 	= new Entity("trabalho","Ttrabalho de Mixagem");

	$plano = $entidade->addAttr(
		array("nome" => "plano" , "descricao" => "Plano" , "tipohtml" => "lista_unica" , "chave_estrangeira" => installDependencia('negocio_produtoramusical_mixagem_plano','package/negocio/produtoramusical/mixagem/plano'))
	);

    $usuario = $entidade->addAttr(
		array("nome" => "usuario" , "descricao" => "Usuário" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('usuario'))
	);    

    $descricao	  = $entidade->addAttr(
        array("nome" => "descricao" , "descricao" => "Descrição", "is_display" => true, "is_exibirgradedados" => true)
    );

	$imagem = $entidade->addAttr(
		array("nome" => "imagem" , "descricao" => "Imagem", "tipohtml" => "arquivo_caminho")
	);

	$track_original = $entidade->addAttr(
		array("nome" => "trackoriginal" , "descricao" => "Track Original", "tipohtml" => "arquivo_caminho")
	); 

	$track_mixada = $entidade->addAttr(
		array("nome" => "trackmixada" , "descricao" => "Track Mixada", "tipohtml" => "arquivo_caminho")
	);

	$track_masterizada = $entidade->addAttr(
		array("nome" => "trackmasterizada" , "descricao" => "Track Masterizada", "tipohtml" => "arquivo_caminho")
	);

	$detalhamento = $entidade->addAttr(
		array("nome" => "detalhamento" , "descricao" => "Detalhamento", "tipohtml" => "ckeditor")
	);