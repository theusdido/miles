<?php
	// Setando variáveis
	$entidadeNome = "connectionftp";
	$entidadeDescricao = "Conexão com FTP";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 0,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 0,
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$host = criarAtributo($conn,$entidadeID,"host","Host","varchar",255,0,3,1,0,0,"",1,0);
	$port = criarAtributo($conn,$entidadeID,"port","Porta","varchar",5,1,25,0,0,0,"",1,0);
	$user = criarAtributo($conn,$entidadeID,"user","Usuário","varchar",25,1,3,0,0,0,"",1,0);
	$password = criarAtributo($conn,$entidadeID,"password","Senha","varchar",50,1,3,0,0,0,"",1,0);
	$projeto = criarAtributo($conn,$entidadeID,"projeto","Projeto","int",0,1,16,0,getEntidadeId("projeto",$conn),0,"",1,0);

	// Relacionamentos
	criarRelacionamento($conn,1,getEntidadeId("projeto",$conn),$entidadeID,"FTP",$projeto);