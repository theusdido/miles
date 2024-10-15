<?php

	// Setando variáveis
	$entidadeNome 		= "erp_escola_encontro";
	$entidadeDescricao 	= "Encontro";

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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$professor  					= criarAtributo($conn,$entidadeID,"professor","Professor","int",0,0,22,1,installDependencia("erp_escola_professor",'package/negocio/escola/rh/professor'));
    $turma  			            = criarAtributo($conn,$entidadeID,"turma","Turma","int",0,0,22,1,installDependencia("erp_escola_turma",'package/negocio/escola/secretaria/turma'));
	$unidadecurricular 				= criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,0,22,1,installDependencia("erp_escola_unidadecurricular","package/sistema"));
	$ambiente 						= criarAtributo($conn,$entidadeID,"ambiente","Ambiente","int",0,0,22,1,installDependencia("erp_escola_ambiente","package/sistema"));
	$data                           = criarAtributo($conn,$entidadeID,"data","Data","date",0,0,11,1);
    $horainicial                    = criarAtributo($conn,$entidadeID,"horainicial","Hora Inicial","time",0,0,28,1);
    $horafinal                      = criarAtributo($conn,$entidadeID,"horafinal","Hora Final","time",0,1,28,0);
    $is_ocorrido                    = criarAtributo($conn,$entidadeID,"is_ocorrido","Ocorreu ?","boolean",0,1,7,0);

	// Criando Acesso
	$menu = addMenu($conn,'Administrativo','#','',0,0,'escola-administrativo');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');