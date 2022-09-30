<?php
	// Setando variáveis
	$entidadeNome 		= "email";
	$entidadeDescricao 	= "E-Mail";

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
    $host            		= criarAtributo($conn,$entidadeID,"host","Host","varchar",200,0,3);
    $username 				= criarAtributo($conn,$entidadeID,"username","Nome de Usuário","varchar",200,0,3);
	$password 			    = criarAtributo($conn,$entidadeID,"password","Senha","varchar",120,0,6);
	$issmtp      			= criarAtributo($conn,$entidadeID,"issmtp","Usar SMTP ?","boolean",0,0,7);
	$smtpsecure				= criarAtributo($conn,$entidadeID,"smtpsecure","SMTP Secure","varchar",5,1,3);
	$port				    = criarAtributo($conn,$entidadeID,"port","Porta","varchar",5,1,3);
	$email				    = criarAtributo($conn,$entidadeID,"email","E-Mail","int",0,1,16);