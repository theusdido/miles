<?php
    // Setando variáveis
    $entidadeNome       = "horaatendimento";
    $entidadeDescricao  = "Hora Atendimento";

    // Categoria
    $entidadeID = criarEntidade(
        $conn,
        $entidadeNome,
        $entidadeDescricao,
        $ncolunas = 3,
        $exibirmenuadministracao = 0,
        $exibircabecalho = 1,
        $campodescchave = "",
        $atributogeneralizacao = 0,
        $exibirlegenda = 1,
        $criarprojeto = 0,
        $criarempresa = 0,
        $criarauth = 0,
        $registrounico = 0
    );
	
    $dia 			= criarAtributo($conn, $entidadeID,"diasemana","Dia","int",0,0,4,1,installDependencia("geral_diasemana","geral/datas/diasemana"));
	$horainicial 	= criarAtributo($conn, $entidadeID,"horainicial","Hora Inicial","time",0,0,28);
	$horafinal	 	= criarAtributo($conn, $entidadeID,"horafinal","Hora Final","time",0,0,28);
	$observacao		= criarAtributo($conn, $entidadeID,"observacao","Observação","varchar",50,1,3);
	$isfechado		= criarAtributo($conn, $entidadeID,"isfechado","Fechado ?","boolean",0,1,7);