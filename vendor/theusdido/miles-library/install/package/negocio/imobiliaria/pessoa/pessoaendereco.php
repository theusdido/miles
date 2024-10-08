<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_pessoaendereco";
	$entidadeDescricao = "Pessoa Endereço";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$endereco = criarAtributo($conn,$entidadeID,"endereco","Endereço","int",0,1,24,1,installDependencia('imobiliaria_endereco','package/negocio/imobiliaria/endereco'),0,"",1,0);
	$numero = criarAtributo($conn,$entidadeID,"numero","Número","varchar","5",1,3,1,0,0,"");
	$complemento = criarAtributo($conn,$entidadeID,"complemento","Complemento","varchar","200",1,3,1,0,0,"");
	$tipoendereco = criarAtributo($conn,$entidadeID,"tipoendereco","Tipo de Endereço","int",0,1,4,0,installDependencia('imobiliaria_tipoendereco','package/negocio/imobiliaria/tipoendereco'),0,"",1,0);
	$pessoa = criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,1,16,0,installDependencia('imobiliaria_pessoa','package/negocio/imobiliaria/pessoa'),0,"",1,0);	
	
	// Criando Relacionamento
	criarRelacionamento(
		$conn,
		2,
		installDependencia('imobiliaria_pessoa','package/negocio/imobiliaria/pessoa'),
		$entidadeID,
		"Endereço",
		$pessoa
	);