<?php
	$entidadeNome = "ticketstatus";
	$entidadeDescricao = "Status";
	
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
	
	$conn->exec("INSERT INTO " . PREFIXO . $entidadeNome . " (id,descricao) VALUES (1,'Abrir');");	
	$conn->exec("INSERT INTO " . PREFIXO . $entidadeNome . " (id,descricao) VALUES (2,'Aguardar');");
	$conn->exec("INSERT INTO " . PREFIXO . $entidadeNome . " (id,descricao) VALUES (3,'Interagir');");
	$conn->exec("INSERT INTO " . PREFIXO . $entidadeNome . " (id,descricao) VALUES (4,'Finalizar');");
	$conn->exec("INSERT INTO " . PREFIXO . $entidadeNome . " (id,descricao) VALUES (5,'Reabrir');");