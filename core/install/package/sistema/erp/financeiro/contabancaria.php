<?php
	// Setando variáveis
	$entidadeNome = "erp_financeiro_contabancaria";
	$entidadeDescricao = "Conta Bancária";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = 0,
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$banco 		= criarAtributo($conn,$entidadeID,"banco","Banco","int",0,0,22,1,installDependencia("erp_financeiro_banco",'package/sistema/erp/financeiro/banco'),0,"",0,0);
	$agencia 	= criarAtributo($conn,$entidadeID,"agencia","Agência","varchar","30",1,3);
	$divagencia = criarAtributo($conn,$entidadeID,"divagencia","DV Agência","varchar",1,0,3);
	$operacao 	= criarAtributo($conn,$entidadeID,"operacao","Operação","int",0,0,4,1,installDependencia("erp_financeiro_operacaobancaria",'package/sistema/erp/financeiro/operacaobancaria'),0,"",0,0);
	$numero 	= criarAtributo($conn,$entidadeID,"numero","Número","varchar","60",1,25);
	$divconta 	= criarAtributo($conn,$entidadeID,"divagencia","DV Agência","varchar",1,0,3);
	$tipo 		= criarAtributo($conn,$entidadeID,"tipo","Tipo","int",0,0,4,1,installDependencia("erp_financeiro_tipocontabancaria",'package/sistema/erp/financeiro/tipocontabancaria'),0,"",0,0);
	$cpf 		= criarAtributo($conn,$entidadeID,"cpf","CPF","varchar","30",0,10);
	$favorecido = criarAtributo($conn,$entidadeID,"favorecido","Nome do Favorecido","varchar","200",0,3);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Financeiro','#','',0,0,'financeiro');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'financeiro-' . $entidadeNome,$entidadeID, 'cadastro');