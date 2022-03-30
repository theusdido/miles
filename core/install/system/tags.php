<?php
	// Setando variáveis
	$entidadeNome = "tags";
	$entidadeDescricao = "Tags";
	
	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=3,
		$exibirmenuadministracao = 1,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$entidade = criarAtributo($conn,$entidadeID,"entidade","Entidade","int",0,0,4,0,0,0,"");
	$pagina = criarAtributo($conn,$entidadeID,"pagina","Página","int",0,0,4,0,getEntidadeId("pagina",$conn),0,"");
	$nome = criarAtributo($conn,$entidadeID,"nome","Nome","varchar",50,0,3,1,0,0,"");
	$tagpai = criarAtributo($conn,$entidadeID,"tagpai","Tag ( Pai )","int",0,0,3,1,0,0,"");
	$texto = criarAtributo($conn,$entidadeID,"texto","Texto","varchar",200,0,3,1,0,0,"");
	$ordem = criarAtributo($conn,$entidadeID,"ordem","Ordem","int",0,1,3,1,0,0,"");