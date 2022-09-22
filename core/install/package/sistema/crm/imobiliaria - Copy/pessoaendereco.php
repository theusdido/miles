<?php
	// Setando variáveis
	$entidadeNome = "crm_imobiliaria_pessoaendereco";
	$entidadeDescricao = "Cadastro de Pessoa Endereço";

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
	$numero = criarAtributo($conn,$entidadeID,"numero","Número","varchar",15,0,2);
	$complemento = criarAtributo($conn,$entidadeID,"complemento","Complemento","varchar",200,0,3);
	$endereco = criarAtributo($conn,$entidadeID,"endereco","Endereço","Int",0,0,4,0,getEntidadeId("crm_imobiliaria_endereco",$conn));
	$tipoendereco = criarAtributo($conn,$entidadeID,"tipoendereco","Tipo de Endereço","int",0,0,4,0,70);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');