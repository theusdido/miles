<?php
	// Setando variáveis
	$entidadeNome           = "monitor";
	$entidadeDescricao      = "Monitor";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);
 
	// Criando Atributos
    // I - Insert, U - Update, D - Delete e S - Select
    $operacao           = criarAtributo($conn,$entidadeID,'operacao',"Operação","enum",array('I','U','D','S'),0,32,0);
	$entidade 	        = criarAtributo($conn,$entidadeID,'entidade',"Entidade","int",0,0,4,0);
    $atributo 	        = criarAtributo($conn,$entidadeID,'atributo',"Atributo","int",0,0,19,0);
    $datahoracriacao    = criarAtributo($conn,$entidadeID,'datahoracriacao',"Data/Hora de Criação","datetime",0,0,23,0);
    $datahoraconsumo    = criarAtributo($conn,$entidadeID,'datahoraconsumo',"Data/Hora de Consumo","datetime",0,0,23,0);
    $valorid            = criarAtributo($conn,$entidadeID,'valorid',"Valor ID","int",0,0,25,0);
    $consumidor         = criarAtributo($conn,$entidadeID,'consumidor',"Consumidor","int",0,0,25,0);