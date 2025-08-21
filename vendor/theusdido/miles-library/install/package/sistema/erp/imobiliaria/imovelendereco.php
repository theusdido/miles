<?php
	// Setando variáveis
	$entidadeNome = "erp_imobiliaria_imovelendereco";
	$entidadeDescricao = "Imóvel Endereço";

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
	$numero = criarAtributo($conn,$entidadeID,"numero","Número","varchar","5",1,3,1,0,0,"");
	$complemento = criarAtributo($conn,$entidadeID,"complemento","Complemento","varchar","200",1,3,1,0,0,"");
	$tipoendereco = criarAtributo($conn,$entidadeID,"tipoendereco","Tipo de Endereço","int",0,0,4,0,installDependencia($conn,'erp_imobiliaria_tipoendereco'),0,"",1,0);
	$imovel = criarAtributo($conn,$entidadeID,"imovel","Imóvel","int",0,1,16,0,installDependencia($conn,'erp_imobiliaria_imovel'),0,"",1,0);
	$endereco = criarAtributo($conn,$entidadeID,"endereco","Endereço","int",0,0,24,0,installDependencia($conn,'erp_imobiliaria_endereco'),0,"",1,0);

	// Criando Relacionamento
	criarRelacionamento(
		$conn,
		7,
		installDependencia($conn,'erp_imobiliaria_imovel'),
		$entidadeID,
		"Endereço",
		$imovel
	);