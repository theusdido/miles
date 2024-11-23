<?php
	// Setando variáveis
	$entidadeNome = "imobiliaria_movimentacaomensal";
	$entidadeDescricao = "Movimentação Mensal";
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
	$descricao = criarAtributo($conn,$entidadeID,"descricao","Descrição","varchar",200,0,3);
	$pessoa = criarAtributo($conn,$entidadeID,"pessoa","Pessoa","int",0,1,22,0,installDependencia('imobiliaria_contrato','package/negocio/imobiliaria/contrato'),0,"",1,0);
	$mesano = $descricao = criarAtributo($conn,$entidadeID,"mesano","Mês/Ano","char",6,0,3);
	$contrato = criarAtributo($conn,$entidadeID,"contrato","Contrato","int",0,1,4,0,installDependencia('imobiliaria_contrato','package/negocio/imobiliaria/contrato'),0,"",1,0);
	$evento = criarAtributo($conn,$entidadeID,"evento","Evento","int",0,1,4,0,installDependencia('imobiliaria_movimentacaomensalevento','package/negocio/imobiliaria/movimentacaomensalevento'),0,"",1,0);
	$valor = criarAtributo($conn,$entidadeID,"valor","Valor","float",0,1,13);
	$tipopessoa = criarAtributo($conn,$entidadeID,"tipopessoa","Tipo Pessoa","int",0,1,16);
	$tipo = criarAtributo($conn,$entidadeID,"tipo","Tipo","int",1,1,4,0,installDependencia('imobiliaria_movimentacaomensaltipoevento','package/negocio/imobiliaria/movimentacaomensaltipoevento'),0,"",1,0);
	

	// Criando Acesso
	$menu_webiste = addMenu($conn,'Geral','#','',0,0,'Geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID, 'cadastro');