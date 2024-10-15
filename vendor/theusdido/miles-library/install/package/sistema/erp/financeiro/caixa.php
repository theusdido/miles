<?php
	// Setando variáveis
	$entidadeNome       = "erp_financeiro_caixa";
	$entidadeDescricao  = "Caixa";

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
	$descricao 			= criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3,1);
	$lancamento			= criarAtributo($conn,$entidadeID,"lancamento","Lançamento","int",0,1,16,0);
	$tipolancamento		= criarAtributo($conn,$entidadeID,"tipolancamento","Tipo de Lançamento","int",0,0,4,0,installDependencia("erp_financeiro_caixa_lancamentotipo","package/sistema/erp/financeiro/caixalancamentotipo"));
	$dataemissao 		= criarAtributo($conn,$entidadeID,"dataemissao","Data de Emissão","date",0,1,11,0,0,0,"",0,0);
	$datalancamento 	= criarAtributo($conn,$entidadeID,"datalancamento","Data de Lançamento","date",0,1,11,0,0,0,"",0,0);
	$operacao 		    = criarAtributo($conn,$entidadeID,"operacao","Operação","tinyint",0,1,7,0);
	$valor 				= criarAtributo($conn,$entidadeID,"valor","Valor","float",0,1,13,0,0,0,"",0,0);

	// Campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$descricao,true);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Financeiro','#','',0,0,'financeiro');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'financeiro-' . $entidadeNome,$entidadeID, 'cadastro');