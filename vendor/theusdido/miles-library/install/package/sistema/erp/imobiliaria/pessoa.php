<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_pessoa";
	$entidadeDescricao = "Pessoa";

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
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar","200",1,3,1,0,0,"");
	$tipopessoa = criarAtributo($conn,$entidadeID,"tipopessoa","Tipo","int",0,0,16,1,0,0,'',1,0);
	$datacadastro = criarAtributo($conn,$entidadeID,"datacadastro","Data de Cadastro","date",0,1,11);
	$observacao = criarAtributo($conn,$entidadeID,"observacao","Observação","text",0,1,21);

	// Seta atributo generalização
	setAtributoGeneralizacao($conn,$entidadeID,$tipopessoa);

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,0,'geral-' . $entidadeNome,$entidadeID, 'cadastro');

	$aba = criarAba($conn,$entidadeID,"Capa",array($nome,$tipopessoa,$datacadastro,$observacao));

	$pessoafisica = installDependencia($conn,"erp_imobiliaria_pessoafisica");
	$pessoajuridica = installDependencia($conn,"erp_imobiliaria_pessoajuridica");
	$pessoaendereco = installDependencia($conn,"erp_imobiliaria_pessoaendereco");