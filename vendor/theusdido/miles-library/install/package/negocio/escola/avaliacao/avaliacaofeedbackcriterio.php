<?php
	
	// Setando variáveis
	$entidadeNome       = "erp_escola_avaliacaofeedbackcriterio";
	$entidadeDescricao  = "Feedback dos Critérios de Avaliação";

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
    $aluno              = criarAtributo($conn,$entidadeID,"aluno","Aluno","int",0,1,22,1,installDependencia("erp_escola_aluno","package/sistema"));
    $avaliacao          = criarAtributo($conn,$entidadeID,"avaliacao","Avaliação","int",0,1,22,1,installDependencia("erp_escola_avaliacaoaluno","package/sistema"));
    $criterio           = criarAtributo($conn,$entidadeID,"criterio","Critério","int",0,1,22,1,installDependencia("erp_escola_criterioavaliacao","package/sistema"));
	$comentario			= criarAtributo($conn,$entidadeID,"comentario","Comentário","text",0,1,21);
	$peso				= criarAtributo($conn,$entidadeID,"peso","Peso","float",0,0,26);

	// Seta o campo descrição
	Entity::setDescriptionField($conn,$entidadeID,$comentario,true);
	
	// Criando Acesso
	$menu = addMenu($conn,'Pedagógico','#','',0,0,'escola-pedagogico-avaliacao');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu,1,'escola-' . $entidadeNome,$entidadeID,'cadastro');