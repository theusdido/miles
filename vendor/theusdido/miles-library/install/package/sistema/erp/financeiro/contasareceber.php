<?php
	// Setando variáveis
	$entidadeNome 		= "erp_financeiro_contasareceber";
	$entidadeDescricao 	= "Contas à Receber";
	
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
	$cliente 			= criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,22,1,installDependencia("erp_pessoa",'package/sistema/erp/pessoa/pessoa'));
	$documento 			= criarAtributo($conn,$entidadeID,"documento","Documento","varchar","50",1,3,1,0,0,"",0,0);
	$valor 				= criarAtributo($conn,$entidadeID,"valor","Valor","float",0,1,13,1,0,0,"",0,0);
	$dataemissao 		= criarAtributo($conn,$entidadeID,"dataemissao","Data de Emissão","date",0,1,11,0,0,0,"",0,0);
	$datavemcimento 	= criarAtributo($conn,$entidadeID,"datavencimento","Data de Vencimento","date",0,1,11,0,0,0,"",0,0);
	$datarecebimento 	= criarAtributo($conn,$entidadeID,"datarecebimento","Data de Recebimento","date",0,1,11,0,0,0,"",0,0);
	$formarecebimento 	= criarAtributo($conn,$entidadeID,"formarecebimento","Forma de Recebimento","int",0,1,4,0,installDependencia("erp_financeiro_formarecebimento",'package/sistema/erp/financeiro/formapagamento'));
	$pago 				= criarAtributo($conn,$entidadeID,"pago","Pago","tinyint",0,1,7,1,0,0,"",0,0);
	$comprovante 		= criarAtributo($conn,$entidadeID,"comprovante","Comprovante","mediumblob",0,1,19,0,0,0,"",0,0);
	$receita 			= criarAtributo($conn,$entidadeID,"receita","Receita","int",0,1,4,0,installDependencia("erp_financeiro_receita",'package/sistema/erp/financeiro/receita'));
	$referencia 		= criarAtributo($conn,$entidadeID,"referencia","Referência","char",7,1,29,0);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Financeiro','#','',0,0,'financeiro');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'financeiro-' . $entidadeNome,$entidadeID, 'cadastro');
