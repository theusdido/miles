<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_resgistroatendimento";
	$entidadeDescricao = "Registro de Atendimento";

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
	$hora = criarAtributo($conn,$entidadeID,"hora","Hora","time",0,0,28);
	$data = criarAtributo($conn,$entidadeID,"data","Data","date",0,0,11);
	$cliente = criarAtributo($conn,$entidadeID,"cliente","Cliente","int",0,0,4,0,getEntidadeId("crm_imobiliaria_cliente",$conn));
	$atendente = criarAtributo($conn,$entidadeID,"atendente","Atendente","int",0,0,4,0,installDependencia($conn,"crm_imobiliaria_atendente"));
	$passivo = criarAtributo($conn,$entidadeID,"passivo","Passivo","tinyint",0,0,7);
	$tipoatendimento = criarAtributo($conn,$entidadeID,"tipoatendimento","Tipo Atendimento","int",0,0,4,0,getEntidadeId("crm_imobiliaria_tipoatendimento",$conn));
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","text",0,0,21);
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');