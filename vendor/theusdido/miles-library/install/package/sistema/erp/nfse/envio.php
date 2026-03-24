<?php
	$entidadeNome = "erp_nfse_envio_log";
	$entidadeDescricao = "Envio da NFSe (Log)";

	$entidadeID = criarEntidade(
		$conn, 
		$entidadeNome, 
		$entidadeDescricao, 
		$ncolunas=1, 
		$exibirmenuadministracao=0, 
		$exibircabecalho=1, 
		$campodescchave=0, 
		$atributogeneralizacao=0, 
		$exibirlegenda=1, 
		$criarprojeto=0, 
		$criarempresa=0, 
		$criarauth=0, 
		$registrounico=0,
		$carregarlibjavascript=1
	);

	// datahora: armazenada a data e hora de envio
	$datahora = criarAtributo(
		$conn, 
		$entidadeID, 
		'datahora', 
		'Data/Hora Envio', 
		'datetime', 
		0, 
		0, 
		23, 
		0, 
		0, 
		0, 
		"", 
		1, 
		0, 
		"", 
		0
	);

	// nfse: Id da nota fisca td_erp_nfse_nota
	$nfse = criarAtributo(
		$conn, 
		$entidadeID, 
		'nfse', 
		'Nota Fiscal', 
		'int', 
		0, 
		0, 
		4, 
		0, 
		getEntidadeId('td_erp_nfse_nota', $conn), 
		0, 
		"", 
		1, 
		0, 
		"", 
		0
	);

	// enviada?: Boolean ( Sim / Não )
	$enviada = criarAtributo(
		$conn, 
		$entidadeID, 
		'enviada', 
		'Enviada?', 
		'boolean', 
		0, 
		0, 
		7, 
		0, 
		0, 
		0, 
		"", 
		1, 
		0, 
		"", 
		0
	);

	// error_code: Número interiro com o código de erro do envio | Não obrigatória
	$error_code = criarAtributo(
		$conn, 
		$entidadeID, 
		'error_code', 
		'Cód. Erro', 
		'varchar', 
		5, 
		1, 
		3, 
		0, 
		0, 
		0, 
		"", 
		1, 
		0, 
		"", 
		0
	);

	// error_message: Mensagem de erro do envio | Não obrigatória
	$error_message = criarAtributo(
		$conn, 
		$entidadeID, 
		'error_message', 
		'Mensagem de Erro', 
		'text', 
		0, 
		1, 
		14, 
		0, 
		0, 
		0, 
		"", 
		1, 
		0, 
		"", 
		0
	);