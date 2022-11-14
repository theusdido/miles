<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_locador";
	$entidadeDescricao = "Locador";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$pessoa = criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,0,16,0,getEntidadeId('imobiliaria_pessoa',$conn),0,"",1,0);
	$taxacontrato = criarAtributo($conn,$entidadeID,"taxacontrato","Taxa de Contrato","float",0,0,13);
	$comissaoaluguel = criarAtributo($conn,$entidadeID,"comissaoaluguel","% de Comissão do Aluguél","int",0,0,26);
	$comissaoiptu = criarAtributo($conn,$entidadeID,"comissaoiptu","Comissão IPTU","int",0,1,26);
	$diapagamento = criarAtributo($conn,$entidadeID,"diapagamento","Dia do Pagamento","int",0,1,25);
	$tipopagametno = $formapagamento = criarAtributo($conn,$entidadeID,"tipopagamento","Tipo de Pagamento","int",0,0,4,1,getEntidadeId("imobiliaria_formapagamento",$conn),0,"",0,0);	
	$emitecheque = criarAtributo($conn,$entidadeID,"emitecheque","Emite Cheque","tinyint",0,1,7);

	criarRelacionamento(
		$conn,
		9,
		getEntidadeId("imobiliaria_pessoa",$conn),
		$entidadeID,
		"Locador",
		$pessoa
	);