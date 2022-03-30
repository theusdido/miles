<?php
	// Setando variáveis
	$entidadeNome = "connectiondatabase";
	$entidadeDescricao = "Conexão Base de Dados";

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
	$base = criarAtributo($conn,$entidadeID,"base","Banco","varchar",50,0,3,1,0,0,"",1,0);
	$port = criarAtributo($conn,$entidadeID,"port","Porta","varchar",5,1,25,0,0,0,"",1,0);
	$user = criarAtributo($conn,$entidadeID,"user","Usuário","varchar",25,1,3,0,0,0,"",1,0);
	$password = criarAtributo($conn,$entidadeID,"password","Senha","varchar",50,1,3,0,0,0,"",1,0);
	$type = criarAtributo($conn,$entidadeID,"type","Tipo","int",0,0,4,1,getEntidadeId("typeconnectiondatabase",$conn),0,"",1,0);
	$projeto = criarAtributo($conn,$entidadeID,"projeto","Projeto","int",0,1,16,0,getEntidadeId("projeto",$conn),0,"",1,0);
	$sgdb = criarAtributo($conn,$entidadeID,"sgdb","SGDB","int",0,0,4,1,getEntidadeId("database",$conn),0,"",1,0);

	// Relacionamentos
	criarRelacionamento($conn,2,getEntidadeId("projeto",$conn),$entidadeID,"Banco de Dados",$projeto);