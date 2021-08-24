<?php
	$entidadeNome = "tickettipo";
	$entidadeDescricao = "Tipo";
	
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);
	
	criarAtributo(
		$conn,
		$entidade = $entidadeID,
		$nome = "descricao",
		$descricao = "Descrição",
		$tipo = "varchar",
		$tamanho = "200",
		$nulo = 0,
		$tipohtml = 3,
		$exibirgradededados = 1,
		$chaveestrangeira = 0,
		$dataretroativa = 0,
		$inicializacao = ""
	);
	
	$conn->exec("INSERT INTO " .PREFIXO . $entidadeNome. " (id,descricao) VALUES (1,'Alterar');");
	$conn->exec("INSERT INTO " .PREFIXO . $entidadeNome. " (id,descricao) VALUES (2,'Corrigir');");
	$conn->exec("INSERT INTO " .PREFIXO . $entidadeNome. " (id,descricao) VALUES (3,'Desenvolver');");
	$conn->exec("INSERT INTO " .PREFIXO . $entidadeNome. " (id,descricao) VALUES (4,'Estudar');");
	$conn->exec("INSERT INTO " .PREFIXO . $entidadeNome. " (id,descricao) VALUES (5,'".utf8_decode('Propôr')."');");
	$conn->exec("INSERT INTO " .PREFIXO . $entidadeNome. " (id,descricao) VALUES (6,'Solicitar');");
	$conn->exec("INSERT INTO " .PREFIXO . $entidadeNome. " (id,descricao) VALUES (7,'Verificar');");
	$conn->exec("INSERT INTO " .PREFIXO . $entidadeNome. " (id,descricao) VALUES (8,'Contactar');");