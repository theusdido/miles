<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_boleto_boleto","Boleto");

	// Atributos
	$contrato	= $entidade->addAttr(
		array("nome" => "contrato" , "descricao" => "Contrato" , "tipohtml" => "numero_inteiro")
	);

	$referencia	= $entidade->addAttr(
		array("nome" => "referencia" , "descricao" => "Referencia" , "tipohtml" => "numero_inteiro")
	);

    $data_emissao	= $entidade->addAttr(
		array("nome" => "data_emissao" , "descricao" => "Data de Emissão" , "tipohtml" => "data")
	);
    
	$data_processamento	= $entidade->addAttr(
		array("nome" => "data_processamento" , "descricao" => "Data de Processamento" , "tipohtml" => "data")
	);
    
    $data_vencimento	= $entidade->addAttr(
		array("nome" => "data_vencimento" , "descricao" => "Data de Vencimento" , "tipohtml" => "data")
	);

	$nosso_numero	= $entidade->addAttr(
		array("nome" => "nosso_numero" , "descricao" => "Nosso Número" , "tipo" => "varchar" , "tamanho" => 25)
	);

	$valortotal     = $entidade->addAttr(
		array("nome" => "valor" , "descricao" => "Valor" , "tipohtml" => "monetario")
	);

	$documento	    = $entidade->addAttr(
		array("nome" => "documento" , "descricao" => "Documento" , "tipo" => "varchar" , "tamanho" => 50)
	);

	$pagador	    = $entidade->addAttr(
		array("nome" => "pagador" , "descricao" => "Pagador" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_geral_pessoa'))
	);

	$instrucoes	    = $entidade->addAttr(
		array("nome" => "instrucoes" , "descricao" => "Instruções" , "tipohtml" => "ckeditor")
	);
	
    $especie	    = $entidade->addAttr(
		array("nome" => "especie" , "descricao" => "Espécie" , "tipo" => "varchar", "tamanho" => 5)
	);

	$especie_documento	    = $entidade->addAttr(
		array("nome" => "especie_documento" , "descricao" => "Espécie do Documento" , "tipo" => "varchar", "tamanho" => 5)
	);

	$aceite	    = $entidade->addAttr(
		array("nome" => "aceite" , "descricao" => "Aceite" , "tipo" => "varchar", "tamanho" => 5)
	);

	$moeda	    = $entidade->addAttr(
		array("nome" => "moeda" , "descricao" => "Moeda" , "tipo" => "varchar", "tamanho" => 5)
	);

	$conta_bancaria	  = $entidade->addAttr(
		array("nome" => "conta_bancaria" , "descricao" => "Conta Bancária" , "tipohtml" => "numero_inteiro", "chave_estrangeira" => getEntidadeId('erp_financeiro_contabancaria'))
	);

	$beneficiario	    = $entidade->addAttr(
		array("nome" => "beneficiario" , "descricao" => "Beneficiário" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_geral_pessoa'))
	);

	$local_pagamento	    = $entidade->addAttr(
		array("nome" => "local_pagamento" , "descricao" => "Local Pagamento" , "tipo" => "varchar", "tamanho" => 200)
	);

	$codigo_barras	    = $entidade->addAttr(
		array("nome" => "codigo_barras" , "descricao" => "Código de Barras" , "tipo" => "varchar", "tamanho" => 200)
	);

	$percentual_multa     = $entidade->addAttr(
		array("nome" => "percentual_multa" , "descricao" => "Percentual de Multa" , "tipohtml" => "percentual")
	);

    $percentual_multa_dia     = $entidade->addAttr(
		array("nome" => "percentual_multa_dia" , "descricao" => "Percentual de Multa por Dia" , "tipohtml" => "percentual")
	);

	$valor_multa     = $entidade->addAttr(
		array("nome" => "valor_multa" , "descricao" => "Valor da Multa" , "tipohtml" => "monetario")
	);

	$valor_multa_dia     = $entidade->addAttr(
		array("nome" => "valor_multa_dia" , "descricao" => "Valor da Multa por Dia" , "tipohtml" => "monetario")
	);

	$valor_juros_dia     = $entidade->addAttr(
		array("nome" => "valor_juros_dia" , "descricao" => "Valor de Juros por Dia" , "tipohtml" => "monetario")
	);

    $percentual_multa_fixa     = $entidade->addAttr(
		array("nome" => "percentual_multa_fixa" , "descricao" => "Percentual de Multa Fixa" , "tipohtml" => "percentual")
	);

	$valor_multa_fixa     = $entidade->addAttr(
		array("nome" => "valor_multa_fixa" , "descricao" => "Valor da Multa Fixa" , "tipohtml" => "monetario")
	);