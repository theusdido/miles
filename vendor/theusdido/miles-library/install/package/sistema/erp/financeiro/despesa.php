<?php
	// Setando variáveis
	$entidadeNome = "erp_financeiro_despesa";
	$entidadeDescricao = "Despesa";
	
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
	$fornecedor 		= criarAtributo($conn,$entidadeID,"fornecedor","Fornecedor","int",0,0,22,0,installDependencia("erp_pessoa",'package/sistema/erp/pessoa/pessoa'),0,"",0,0);
	$elementocusto 		= criarAtributo($conn,$entidadeID,"elementocusto","Elemento de Custo","int",0,1,4,0,installDependencia("erp_contabil_elementocusto",'package/sistema/erp/contabil/elementocusto'),0,"",0,0);
	$valor 				= criarAtributo($conn,$entidadeID,"valor","Valor","float",0,1,13,0,0,0,"",0,0);
	$formapagamento 	= criarAtributo($conn,$entidadeID,"formapagamento","Forma de Pagamento","int",0,1,4,0,installDependencia("erp_financeiro_formapagamento",'package/sistema/erp/financeiro/formapagamento'),0,"",0,0);
	$despesafixa 		= criarAtributo($conn,$entidadeID,"despesafixa","Despesa Fixa","tinyint",0,1,7,0,0,0,"",0,0);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Financeiro','#','',0,0,'financeiro');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'financeiro-' . $entidadeNome,$entidadeID, 'cadastro');
