<?php
	
	// Setando variáveis
	$entidadeNome 		= "erp_escola_planejamento";
	$entidadeDescricao 	= "Planejamento";

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
	$numero					= criarAtributo($conn,$entidadeID,"numero","Número","tinyint",0,0,25,1);
	$periodicidade			= criarAtributo($conn,$entidadeID,"periodicidade","Periodicidade","int",0,1,4,0,installDependencia("periodicidade","geral"));
	$data_inicial			= criarAtributo($conn,$entidadeID,"data_inicial","Data Inicial","date",0,1,11,0);
	$data_final				= criarAtributo($conn,$entidadeID,"data_final","Data Final","date",0,1,11,0);
	$professor				= criarAtributo($conn,$entidadeID,"professor","Professor","int",0,0,22,0,installDependencia("erp_escola_professor","package/negocio/escola/rh/professor"));
	$unidadecurricular 		= criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,0,22,1,installDependencia("erp_escola_unidadecurricular","package/negocio/escola/itinerarioformativo/unidadecurricular"));
	$ano					= criarAtributo($conn,$entidadeID,"anoletivo","Ano Letivo","int",0,1,4,0,installDependencia("ano","geral"));
	$curso					= criarAtributo($conn,$entidadeID,"curso","Curso","int",0,0,22,0,installDependencia("erp_escola_curso","package/negocio/escola/itinerarioformativo/curso"));
	$trilha					= criarAtributo($conn,$entidadeID,"trilha","Trilha","int",0,0,22,0,installDependencia("erp_escola_trilha","package/negocio/escola/itinerarioformativo/trilha"));
	$eixo					= criarAtributo($conn,$entidadeID,"eixo","Eixo","int",0,0,22,0,installDependencia("erp_escola_eixo","package/negocio/escola/itinerarioformativo/eixo"));

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$numero,true);

	// Criando Acesso
	$menu = addMenu($conn,'Escola','#','',0,0,'escola');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');

	// Competência - Relacionamento
	criarRelacionamento($conn,11,$entidadeID,installDependencia("erp_escola_competencia",'package/negocio/escola/itinerarioformativo/competencia'),"Competências");

	// Conteúdo - Relacionamento
	criarRelacionamento($conn,11,$entidadeID,installDependencia("erp_escola_conteudo",'package/negocio/escola/itinerarioformativo/conteudo'),"Conteúdos");

	// Habilidade - Relacionamento
	criarRelacionamento($conn,11,$entidadeID,installDependencia("erp_escola_habilidade",'package/negocio/escola/itinerarioformativo/habilidade'),"Habilidades");

	// Metodologia de Ensino - Relacionamento
	criarRelacionamento($conn,11,$entidadeID,installDependencia("erp_escola_metodologia",'package/negocio/escola/pedagogico/metodologia'),"Métodologias de Ensino");

	// Instrumentos de Avaliação - Relacionamento
	criarRelacionamento($conn,11,$entidadeID,installDependencia("erp_escola_instrumentoavaliacao",'package/negocio/escola/pedagogico/instrumentoavaliacao'),"Instrumentos de Avaliação");

	// Assuntos - Relacionamento
	criarRelacionamento($conn,12,$entidadeID,installDependencia("erp_escola_assunto",'package/negocio/escola/pedagogico/assunto'),"Assuntos");