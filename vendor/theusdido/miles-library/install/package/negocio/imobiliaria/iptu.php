<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_iptu";
	$entidadeDescricao = "IPTU";

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
	$numerocarneiptu 		= criarAtributo($conn,$entidadeID,"numerocarneiptu","Número Carnê IPTU","varchar",200,1,3,1);
	$valor 					= criarAtributo($conn,$entidadeID,"valor","Valor","float",0,1,13);
	$informacoesiptu 		= criarAtributo($conn,$entidadeID,"informacaoiptu","Informação IPTU","varchar",200,1,3);
	$percentualcomissaoiptu = criarAtributo($conn,$entidadeID,"percentualcomissaoaluguel","% Comissáo IPTU","int",0,1,26);
	$imovel 				= criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,1,16,0,installDependencia('imobiliaria_imovel','package/negocio/imobiliaria/imovel'));

	// Criar Relacionamento
	criarRelacionamento($conn,6,$imovel,$entidadeID,"IPTU",$imovel);