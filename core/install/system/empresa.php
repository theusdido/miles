 <?php
	// Setando variáveis
	$entidadeNome 		= "empresa";
	$entidadeDescricao 	= "Empresa";
	
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
		$criarprojeto = 0,
		$criarempresa = 0,
		$criarauth = 0,
		$registrounico = 0
	);

	// Criando Atributos
	$codigo 				= criarAtributo($conn,$entidadeID,"codigo","Código","int",0,1,25);
	$nomefantasia 			= criarAtributo($conn,$entidadeID,"nomefantasia","Nome Fantasia","varchar",120,0,3,1,0,0,"");	
	$razaosocial 			= criarAtributo($conn,$entidadeID,"razaosocial","Razão Social","varchar",200,1,3);
	$cnpj 					= criarAtributo($conn,$entidadeID,"cnpj","CNPJ","varchar",25,1,15);
	$inscricaoestadual 		= criarAtributo($conn,$entidadeID,"inscricaoestadual","Inscrição Estadual","varchar",40,1,3);
	$inscricaomunicipal 	= criarAtributo($conn,$entidadeID,"inscricaomunicipal","Inscrição Municipal","varchar",40,1,3);
	$telefone 				= criarAtributo($conn,$entidadeID,"telefone","Telefone","varchar",25,1,8);
	$email 					= criarAtributo($conn,$entidadeID,"email","E-Mail","varchar",200,1,12);

	// Cria Relacionamento com endereco
	criarRelacionamento($conn,1,$entidadeID,installDependencia("endereco","system/endereco"),"Endereço",0);
