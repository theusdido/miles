<?php
	// Setando variáveis
	$entidadeNome 		= "erp_escola_sequenciadidadica";
	$entidadeDescricao 	= "Sequencia Didádica";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=1,
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

    // Dependências 
    $entidade_id_periodicidade              = installDependencia("td_geral_periodicidade","package/geral/data/periodicidade");
    $entidade_id_serie                      = installDependencia("td_erp_escola_serie","package/negocio/escola/secretaria/serie");
    $entidade_id_areaconhecimento           = installDependencia("td_erp_escola_areaconhecimento","package/negocio/escola/secretaria/areaconhecimento");
	$entidade_id_unidadecurricular			= installDependencia("td_erp_escola_unidadecurricular","package/negocio/escola/secretaria/unidadecurricular");
    $entidade_id_curso                      = installDependencia("td_erp_escola_curso","package/negocio/escola/secretaria/curso");

    $entidade_id_competencia                = installDependencia("td_erp_escola_competencia","package/negocio/escola/itinerarioformativo/competencia");
    $entidade_id_habilidade                 = installDependencia("td_erp_escola_habilidade","package/negocio/escola/itinerarioformativo/habilidade");
    $entidade_id_objetivoespecifico         = installDependencia("td_erp_escola_objetivoespecifico","package/negocio/escola/itinerarioformativo/objetivoespecifico");
    $entidade_id_criterioavaliacao          = installDependencia("td_erp_escola_criterioavaliacao","package/negocio/escola/avaliacao/criterioavaliacao");
    $entidade_id_metodologia                = installDependencia("td_erp_escola_metodologia","package/negocio/escola/pedagogico/metodologia");
    $entidade_id_instrumentoavaliacao       = installDependencia("td_erp_escola_instrumentoavaliacao","package/negocio/escola/instrumentoavaliacao");

	// Criando Atributos
    $ano_letivo                     = criarAtributo($conn,$entidadeID,"anoletivo","Ano Letivo","int",0,0,25,0);
    $numero                         = criarAtributo($conn,$entidadeID,"numero","Número","int",0,0,25,1);
    $periodicidade                  = criarAtributo($conn,$entidadeID,"periodicidade","Periodicidade","int",0,1,4,0,$entidade_id_periodicidade);
    $serie                          = criarAtributo($conn,$entidadeID,"serie","Série","int",0,1,4,0,$entidade_id_serie);
    $data_inicial                   = criarAtributo($conn,$entidadeID,"datainicial","Data Inicial","date",0,0,11,1);
    $data_final                     = criarAtributo($conn,$entidadeID,"datafinal","Data Final","date",0,1,11,1);
    $area_conhecimento              = criarAtributo($conn,$entidadeID,"areaconhecimento","Área do Conhecimento","int",0,1,4,0,$entidade_id_areaconhecimento);
	$unidade_curricular             = criarAtributo($conn,$entidadeID,"unidadecurricular","Unidade Curricular","int",0,1,22,0,$entidade_id_unidadecurricular);
    $curso                          = criarAtributo($conn,$entidadeID,"curso","Curso","int",0,0,22,0,$entidade_id_curso);
	$quantidadeaula                 = criarAtributo($conn,$entidadeID,"quantidadeaula","Quantidade Aula","int",0,0,25,1);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$numero,true);

	// Criando Acesso
	$menu = addMenu($conn,'Pedagógico','#','',0,0,'escola-pedagogico');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');

	// Relacionamentos
	criarRelacionamento(
		$conn , #1
		$tipo = 11, #2
		$entidadePai = $entidade_id_competencia, #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Competências Específicas" ,#5
		$atributo = 0 #6
	);

    criarRelacionamento(
		$conn , #1
		$tipo = 11, #2
		$entidadePai = $entidade_id_habilidade, #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Habilidades" ,#5
		$atributo = 0 #6
	);
    
    criarRelacionamento(
		$conn , #1
		$tipo = 11, #2
		$entidadePai = $entidade_id_objetivoespecifico, #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Objetivos Específicos" ,#5
		$atributo = 0 #6
	);
    
    criarRelacionamento(
		$conn , #1
		$tipo = 11, #2
		$entidadePai = $entidade_id_criterioavaliacao, #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Critérios de Avaliação" ,#5
		$atributo = 0 #6
	);

    criarRelacionamento(
		$conn , #1
		$tipo = 11, #2
		$entidadePai = $entidade_id_metodologia, #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Metodologias de Ensino" ,#5
		$atributo = 0 #6
	);
    
    criarRelacionamento(
		$conn , #1
		$tipo = 11, #2
		$entidadePai = $entidade_id_instrumentoavaliacao, #3
		$entidadeFilho = $entidadeID,#4
		$descricao = "Instrumentos de Avaliação" ,#5
		$atributo = 0 #6
	);