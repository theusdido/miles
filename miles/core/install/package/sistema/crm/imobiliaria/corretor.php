<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_corretor";
	$entidadeDescricao = "Corretor";

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
	$cresi = criarAtributo($conn,$entidadeID,"cresi","CRESI","varchar",15,0,2);
	$pessoa = criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,1,16,installDependencia($conn,"erp_imobiliaria_pessoa"));
	
	// Criando Acesso
	$menu_webiste = addMenu($conn,'CRM','#','','','','crm');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'crm-' . $entidadeNome);

	// Relacionamento
	$relacionamento = criarRelacionamento($conn,3,installDependencia($conn,"erp_imobiliaria_pessoa"),$entidadeID,"Corretor",$pessoa,$entidadeID,'cadastro');