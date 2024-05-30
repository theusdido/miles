<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_corretorendereco";
	$entidadeDescricao = "Endereço do Corretor";

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
	$estado = criarAtributo($conn,$entidadeID,"estado","Estado","int",0,0,4,0,getEntidadeId("crm_imobiliaria_estado",$conn));
	$cidade = criarAtributo($conn,$entidadeID,"cidade","Cidade","int",0,0,4,0,getEntidadeId("crm_imobiliaria_cidade",$conn));
	$pais = criarAtributo($conn,$entidadeID,"pais","País","int",0,0,4,0,getEntidadeId("crm_imobiliaria_pais",$conn));
	$bairro = criarAtributo($conn,$entidadeID,"bairro","Bairro","int",0,0,4,0,getEntidadeId("crm_imobiliaria_Bairro",$conn));
	$rua = criarAtributo($conn,$entidadeID,"rua","Rua","varchar",200,0,3);
	$numero = criarAtributo($conn,$entidadeID,"numero","Número","varchar",200,0,3);
	$complemento = criarAtributo($conn,$entidadeID,"complemento","Complemento","varchar",200,0,3);
	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');