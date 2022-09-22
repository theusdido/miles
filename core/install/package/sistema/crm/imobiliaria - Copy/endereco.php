<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_endereco";
	$entidadeDescricao = "Cadastro de Endereço";

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
	$cep = criarAtributo($conn,$entidadeID,"cep","CEP","char",9,0,9);
	$estado = criarAtributo($conn,$entidadeID,"estado","Estado","Int",0,0,4,0,getEntidadeId("crm_imobiliaria_estado",$conn));
	$cidade = criarAtributo($conn,$entidadeID,"cidade","Cidade","Int",0,0,4,0,getEntidadeId("crm_imobiliaria_cidade",$conn));
	$logradouro = criarAtributo($conn,$entidadeID,"logradouro","Logradouro","varchar",200,3,0,1);
	$tipologradouro = criarAtributo($conn,$entidadeID,"tipologradouro","Tipo de Logradouro","Int",0,0,4,getEntidadeId("crm_imobiliaria_tipologradouro",$conn));
	$pais = criarAtributo($conn,$entidadeID,"pais","País","Int",0,0,4,0,getEntidadeId("crm_imobiliaria_pais",$conn));
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');