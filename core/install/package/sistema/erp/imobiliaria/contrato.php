<?php

	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_contrato";
	$entidadeDescricao = "Contrato";

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
	$origem = criarAtributo($conn,$entidadeID,"origemfilial","Origem do Cadastro ( Filial )","int",0,0,4,0,installDependencia($conn,"erp_imobiliaria_empresas"));
	$agenciamento = criarAtributo($conn,$entidadeID,"agenciamento","Agenciamento","int",0,1,16);
	$atendente = criarAtributo($conn,$entidadeID,"atendente","Atendente","int",0,0,4,0,installDependencia($conn,"erp_imobiliaria_atendente"),0,'',1,1);
	$periocidade = criarAtributo($conn,$entidadeID,"periocidade","Periocidade","int",0,0,4,0,installDependencia($conn,"erp_imobiliaria_periodicidade"),0,'',1,0);
	$prazolocacao = criarAtributo($conn,$entidadeID,"prazolocacao","Prazo de Locação","int",0,1,25);
	$prazolocacao = criarAtributo($conn,$entidadeID,"prazolocacao","Prazo de Locação","int",0,1,25,0,0,0,'',1,0,'Meses');
	$valoraluguel = criarAtributo($conn,$entidadeID,"valoraluguel","Valor do Aluguel","int",0,0,13,1);
	$datainicialcontrato = criarAtributo($conn,$entidadeID,"datainicialcontrato","Data Inicial","date",0,1,11);
	$datafinalcontrato = criarAtributo($conn,$entidadeID,"datafinalcontrato","Data Final","date",0,1,11,0,0,0,'',1,1);
	$diapagamento = criarAtributo($conn,$entidadeID,"diapagamento","Dia Pagamento","int",0,1,25,1);
	$dataultimoacerto = criarAtributo($conn,$entidadeID,"dataultimoacerto","Data Último Acerto","date",0,1,11,0,0,0,'',1,1);
	$dataproximoreajuste = criarAtributo($conn,$entidadeID,"dataproximoreajuste","Data Próximo Reajuste","date",0,0,11,0,0,0,'',1,1);
	$dataefetivacaocontrato = criarAtributo($conn,$entidadeID,"dataefetivacaocontrato","Data de Efetivação","date",0,1,11);
	$usuarioefetivacao = criarAtributo($conn,$entidadeID,"usuarioefetivacao","Usuário Efetivação","int",0,1,16);
	$aditivodesocupacao = criarAtributo($conn,$entidadeID,"aditivodesocupacao","Aditivo Desocupação","int",0,1,25);
	$imovel = criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,0,22,0,installDependencia($conn,"erp_imobiliaria_imovel"),0,'',1,0);
	$categoriaimovel = criarAtributo($conn,$entidadeID,"categoriaimovel","Categoria do Imóvel","int",0,0,4,0,installDependencia($conn,"erp_imobiliaria_categoriaimovel"));
	$modalidadegarantia = criarAtributo($conn,$entidadeID,"modalidadegarantia","Modalidade Garantia","int",0,0,4,0,installDependencia($conn,"erp_imobiliaria_modalidadegarantia"));
	$datahorageracao = criarAtributo($conn,$entidadeID,"datahorageracao","Data e Hora","datetime",0,1,16);
	$usuariogeracao = criarAtributo($conn,$entidadeID,"usuariogeracao","Usuário Geração","int",0,1,16);
	$identificacaopartes = criarAtributo($conn,$entidadeID,"identificacaopartes","Identificação das Partes","text",0,1,16,1);

	criarAba($conn,$entidadeID,"Capa",
		array(
			$origem,$agenciamento,$atendente,$periocidade,$prazolocacao,$datainicialcontrato,$datafinalcontrato,$dataultimoacerto,$dataproximoreajuste,
			$dataefetivacaocontrato,$usuarioefetivacao,$aditivodesocupacao,$imovel,$categoriaimovel,$modalidadegarantia,$datahorageracao,$usuariogeracao,$valoraluguel
		)
	);


	$formapagamento = criarAtributo($conn,$entidadeID,"formapagamento","Forma de Pagamento","int",0,0,4,0,installDependencia($conn,"erp_imobiliaria_formapagamento"));
	$garantiatotal = criarAtributo($conn,$entidadeID,"garantiatotal","Garantia Total ?","int",0,1,7);
	$garantiaaluguel = criarAtributo($conn,$entidadeID,"garantiaaluguel","Meses de Garantia","int",0,0,25);
	$percentualtaxacontrato = criarAtributo($conn,$entidadeID,"percentualtaxacontrato","% Taxa Contrato","int",0,0,25);
	$numeroparcelas = criarAtributo($conn,$entidadeID,"numeroparcelas","Número Parcelas","int",0,1,25);
	$ramoatividade = criarAtributo($conn,$entidadeID,"ramoatividade","Ramo de Atividade","varchar",200,0,3);
	
	criarAba($conn,$entidadeID,"Condição",
		array(
			$formapagamento,$garantiatotal,$garantiaaluguel,$percentualtaxacontrato,$numeroparcelas,$ramoatividade
		)
	);
	
	$gerarboleto = criarAtributo($conn,$entidadeID,"gerarboleto","Gerar Boleto","tinyint",0,0,7);
	$imprimeboleto = criarAtributo($conn,$entidadeID,"imprimeboleto","Imprime Boleto","tinyint",0,0,7);
	$cobrartaxabancaria = criarAtributo($conn,$entidadeID,"cobrartaxabancaria","Cobrar Taxa Bancária","tinyint",0,0,7);
	$bancoboleto = criarAtributo($conn,$entidadeID,"bancoboleto","Banco Boleto","int",0,0,4,0,installDependencia($conn,"erp_imobiliaria_banco"));

	criarAba($conn,$entidadeID,"Boleto",
		array(
			$gerarboleto,$imprimeboleto,$cobrartaxabancaria,$bancoboleto
		)
	);

	// Inquilinos do contrato
	$entidadeIDInquilinocontrato = criarEntidade($conn,"erp_imobiliaria_contratoinquilino","Inquilinos do Contrato",1,0,0,0,0,0,1,1,0,0);
	$inquilino = criarAtributo($conn,$entidadeIDInquilinocontrato,"inquilino","Inquilino","int",0,0,22,1,installDependencia($conn,"erp_imobiliaria_pessoa"));
	$contrato = criarAtributo($conn,$entidadeIDInquilinocontrato,"contrato","Contrato","int",0,1,16,1,$entidadeID);
	$principal = criarAtributo($conn,$entidadeIDInquilinocontrato,"principal","Principal ?","tinyint",0,1,7);
	criarRelacionamento($conn,2,$entidadeID,$entidadeIDInquilinocontrato,"Inquilino",$contrato);

	// Fiadores do contrato
	$entidadeIDFiadorcontrato = criarEntidade($conn,"erp_imobiliaria_contratofiador","Fiadores do Contrato",1,0,0,0,0,0,1,1,0,0);
	$fiador = criarAtributo($conn,$entidadeIDFiadorcontrato,"fiador","Fiador","int",0,0,22,1,installDependencia($conn,"erp_imobiliaria_pessoa"));
	$contrato = criarAtributo($conn,$entidadeIDFiadorcontrato,"contrato","Contrato","int",0,1,16,1,$entidadeID);
	criarRelacionamento($conn,6,$entidadeID,$entidadeIDFiadorcontrato,"Fiador",$contrato);

	// Proprietarios do contrato
	$entidadeIDProprietariocontrato = criarEntidade($conn,"erp_imobiliaria_contratoproprietario","Proprietarios do Contrato",1,0,0,0,0,0,1,1,0,0);
	$proprietario = criarAtributo($conn,$entidadeIDProprietariocontrato,"proprietario","Proprietário","int",0,0,22,1,installDependencia($conn,"erp_imobiliaria_pessoa"));
	$percentualimovel = criarAtributo($conn,$entidadeIDProprietariocontrato,"percentualimovel","Percentual do Imóvel","int",0,1,26);
	$contrato = criarAtributo($conn,$entidadeIDProprietariocontrato,"contrato","Contrato","int",0,1,16,1,$entidadeID);
	criarRelacionamento($conn,2,$entidadeID,$entidadeIDProprietariocontrato,"Proprietário",$contrato);
	
	// Desconto do contrato
	$entidadeIDDescontocontrato = criarEntidade($conn,"erp_imobiliaria_descontocontrato","Desconto",1,0,0,0,0,0,1,1,0,0);
	$valor = criarAtributo($conn,$entidadeIDDescontocontrato,"valor","Valor","int",0,0,13,1);
	$datainicialdesconto = criarAtributo($conn,$entidadeIDDescontocontrato,"datainicial","Data Inicial","date",0,0,11,1);
	$datafinaldesconto = criarAtributo($conn,$entidadeIDDescontocontrato,"datafinal","Data Final","date",0,0,11,1);
	$contrato = criarAtributo($conn,$entidadeIDFiadorcontrato,"contrato","Contrato","int",0,0,16,1,$entidadeID);
	criarRelacionamento($conn,6,$entidadeID,$entidadeIDDescontocontrato,"Desconto",$contrato);	

	$entidadeIDParagrafoClausula = criarEntidade(
		$conn,
		"erp_imobiliaria_paragrafoclausula",
		"Parágrafo ( Cláusula )",
		$ncolunas=1,
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
	$descricao = criarAtributo($conn,$entidadeIDParagrafoClausula,"texto","Texto","text",0,0,21,1);
	$clausulaparagrafo = criarAtributo($conn,$entidadeIDParagrafoClausula,"clausula","Cláusula","int",0,1,16);

	$entidadeIDClausula = criarEntidade(
		$conn,
		"erp_imobiliaria_clausula",
		"Clausula",
		$ncolunas=1,
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
	$titulo = criarAtributo($conn,$entidadeIDClausula,"titulo","Título","varchar",200,1,3,1);
	$descricao = criarAtributo($conn,$entidadeIDClausula,"texto","Texto","text",0,1,21,0);
	criarRelacionamento($conn,6,$entidadeIDClausula,$entidadeIDParagrafoClausula,"Paragrafo",$clausulaparagrafo);
	
	criarAba($conn,$entidadeIDClausula,"Capa",array($titulo,$descricao));	
	
	$entidadeIDClausulacontrato = criarEntidade(
		$conn,
		"erp_imobiliaria_clausulacontrato",
		"Clausula Contrato",
		$ncolunas=1,
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
	$titulo = criarAtributo($conn,$entidadeIDClausulacontrato,"titulo","Título","varchar",200,0,3);
	$numero = criarAtributo($conn,$entidadeIDClausulacontrato,"numero","Número","int",0,0,3);
	$texto = criarAtributo($conn,$entidadeIDClausulacontrato,"texto","Texto","text",0,0,21);
	$contrato = criarAtributo($conn,$entidadeIDClausulacontrato,"contrato","Contrato","int",0,0,16,0,$entidadeID);
	$clausulacontratoref = criarAtributo($conn,$entidadeIDClausulacontrato,"clausula","Clausula","int",0,1,4,0,$entidadeIDClausula);

	$entidadeIDParagrafoClausulacontrato = criarEntidade(
		$conn,
		"erp_imobiliaria_paragrafoclausulacontrato",
		"Paragrafo Cláusula Contrato",
		$ncolunas=1,
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
	$titulo = criarAtributo($conn,$entidadeIDParagrafoClausulacontrato,"titulo","Título","varchar",200,0,3);
	$numero = criarAtributo($conn,$entidadeIDParagrafoClausulacontrato,"numero","Número","int",0,0,3);
	$texto = criarAtributo($conn,$entidadeIDParagrafoClausulacontrato,"texto","Texto","text",0,0,21);
	$contrato = criarAtributo($conn,$entidadeIDParagrafoClausulacontrato,"contrato","Contrato","int",0,0,16,0,$entidadeID);
	$paragrafocontratoref = criarAtributo($conn,$entidadeIDParagrafoClausulacontrato,"paragrafo","Paragrafo","int",0,0,4,1,$entidadeIDParagrafoClausula);
	$clausulacontratoref = criarAtributo($conn,$entidadeIDParagrafoClausulacontrato,"clausula","Clausula","int",0,1,4,0,$entidadeIDClausula);
	
	$entidadeIDTipoContrato = criarEntidade(
		$conn,
		"erp_imobiliaria_tipocontrato",
		"Tipo de Contrato",
		$ncolunas=1,
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
	$descricao = criarAtributo($conn,$entidadeIDTipoContrato,"descricao","Descrição","text",0,0,3,1);	
	
	$entidadeIDLayoutContrato = criarEntidade(
		$conn,
		"erp_imobiliaria_layoutcontrato",
		"Layout do Contrato",
		$ncolunas=1,
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
	$clausula = criarAtributo($conn,$entidadeIDLayoutContrato,"clausula","Cláusula","int",0,0,4,1,$entidadeIDParagrafoClausula);
	$tipocontrato = criarAtributo($conn,$entidadeIDLayoutContrato,"tipocontrato","Tipo de Contrato","int",0,0,4,1,$entidadeIDTipoContrato);
	
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Jurídico','#','',0,0,'juridico');

	// Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'juridico-' . $entidadeNome,$entidadeID, 'cadastro');