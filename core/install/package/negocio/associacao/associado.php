<?php

	// Setando variáveis
	$entidadeNome = "erp_associacao_associado";
	$entidadeDescricao = "Associado";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	$idPessoa 			= getEntidadeId('erp_geral_pessoa');

	// Criando Atributos
	$pessoa				= criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,1,16,$idPessoa);
	$planomensalidade 	= criarAtributo($conn,$entidadeID,"planomensalidade","Plano de Mensalidade","int",0,0,4,0,installDependencia('erp_associacao_planomensalidade','package/sistema/erp/associacao/planomensalidade'));

	// Criando Acesso
	$menu = addMenu($conn,'Associação','#','',0,0,'associacao');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'associacao-' . $entidadeNome,$entidadeID,'cadastro');
	
	// Criar Relacionado com Pessoa
	criarRelacionamento($conn,9,$idPessoa,$entidadeID,'Associado',$pessoa);