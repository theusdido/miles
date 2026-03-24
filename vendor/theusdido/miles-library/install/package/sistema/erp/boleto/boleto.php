<?php
	// Cria Entidade
	$entidade 	= new Entity("boleto","Boleto");

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
		array("nome" => "nosso_numero" , "descricao" => "Nosso Número" , "tipo" => "varchar" , "tamanho" => 35)
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

	// Endereço do Pagador
	$endereco	= $entidade->addAttr(
		array("nome" => "endereco" , "descricao" => "Endereço" , "tipo" => "varchar" , "tamanho" => 200)
	);
	
	$bairro	= $entidade->addAttr(
		array("nome" => "bairro" , "descricao" => "Bairro" , "tipo" => "varchar" , "tamanho" => 35)
	);
	
	$cidade	= $entidade->addAttr(
		array("nome" => "cidade" , "descricao" => "Cidade" , "tipo" => "varchar" , "tamanho" => 50)
	);

	$uf	= $entidade->addAttr(
		array("nome" => "uf" , "descricao" => "UF" , "tipo" => "char" , "tamanho" => 2)
	);		

	$cep_pagador	= $entidade->addAttr(
		array("nome" => "cep_pagador" , "descricao" => "CEP do Pagador" , "tipo" => "varchar" , "tamanho" => 10)
	);
	
	$email	  = $entidade->addAttr(
		array("nome" => "email" , "descricao" => "E-Mail", "tipohtml" => "email")
	);	

	$eventos	    = $entidade->addAttr(
		array("nome" => "eventos" , "descricao" => "Eventos" , "tipo" => "text")
	);	

	$municipio	= $entidade->addAttr(
		array("nome" => "municipio" , "descricao" => "Município" , "tipo" => "varchar" , "tamanho" => 35)
	);

	// Dados Bancários
	$banco	= $entidade->addAttr(
		array("nome" => "banco" , "descricao" => "Banco" , "tipo" => "varchar" , "tamanho" => 15)
	);

	$digito_banco	= $entidade->addAttr(
		array("nome" => "digito_banco" , "descricao" => "Dígito do Banco" , "tipo" => "varchar" , "tamanho" => 5)
	);	

	$agencia	= $entidade->addAttr(
		array("nome" => "agencia" , "descricao" => "Agência" , "tipo" => "varchar" , "tamanho" => 5)
	);		

	$endereco_beneficiario	= $entidade->addAttr(
		array("nome" => "endereco_beneficiario" , "descricao" => "Endereço do Beneficiário" , "tipo" => "varchar" , "tamanho" => 200)
	);	

	$cep_beneficiario	= $entidade->addAttr(
		array("nome" => "cep_beneficiario" , "descricao" => "CEP do Beneficiário" , "tipo" => "varchar" , "tamanho" => 10)
	);
	
	$fone	= $entidade->addAttr(
		array("nome" => "fone" , "descricao" => "Telefone" , "tipo" => "varchar" , "tamanho" => 20)
	);	

	$status	= $entidade->addAttr(
		array("nome" => "status" , "descricao" => "Status" , "tipo" => "char" , "tamanho" => 1)
	);		

	$htmlfile	= $entidade->addAttr(
		array("nome" => "htmlfile" , "descricao" => "Arquivo HTML" , "tipo" => "mediumblob")
	);		

	$qtde_impressao	= $entidade->addAttr(
		array("nome" => "qtde_impressao" , "descricao" => "Quantidade de Impressões" , "tipohtml" => "numero_inteiro")
	);	

	$qtde_envio	= $entidade->addAttr(
		array("nome" => "qtde_envio" , "descricao" => "Quantidade de Envios" , "tipohtml" => "numero_inteiro")
	);		

	$qtde_pdf	= $entidade->addAttr(
		array("nome" => "qtde_pdf" , "descricao" => "Quantidade de PDFs" , "tipohtml" => "numero_inteiro")
	);

	$data_criacao	= $entidade->addAttr(
		array("nome" => "data_criacao" , "descricao" => "Data de Criação" , "tipohtml" => "data")
	);	

	$data_atualizacao	= $entidade->addAttr(
		array("nome" => "data_atualizacao" , "descricao" => "Data de Atualização" , "tipohtml" => "data")
	);
	
	$data_reajuste	= $entidade->addAttr(
		array("nome" => "data_reajuste" , "descricao" => "Data de Reajuste" , "tipohtml" => "data")
	);	

	$endereco_imovel	= $entidade->addAttr(
		array("nome" => "endereco_imovel" , "descricao" => "Endereço do Imóvel" , "tipo" => "varchar" , "tamanho" => 200)
	);	
	
	$linha_digitavel	= $entidade->addAttr(
		array("nome" => "linha_digitavel" , "descricao" => "Linha Digitável" , "tipo" => "varchar" , "tamanho" => 48)
	);		

	$data_hora_impressao	= $entidade->addAttr(
		array("nome" => "data_hora_impressao" , "descricao" => "Data/Hora de Impressão" , "tipo" => "datetime")
	);
	
	$senha	= $entidade->addAttr(
		array("nome" => "senha" , "descricao" => "Senha" , "tipohtml" => "senha_sem_criptografia")
	);	

	$is_baixado	= $entidade->addAttr(
		array("nome" => "is_baixado" , "descricao" => "Está Baixado" , "tipohtml" => "checkbox")
	);	

	$data_baixa	= $entidade->addAttr(
		array("nome" => "data_baixa" , "descricao" => "Data da Baixa" , "tipohtml" => "data")
	);	