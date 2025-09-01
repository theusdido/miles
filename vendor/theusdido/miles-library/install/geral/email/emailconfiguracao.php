<?php
	// Setando variáveis
	$entidadeNome 		= "emailconfiguracao";
	$entidadeDescricao 	= "Configuração de E-Mail";

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
		$registrounico = 1
	);

	// Criando Atributos
    $host            		= criarAtributo($conn,$entidadeID,"host","Host","varchar",200,0,3);
	$smtpsecure				= criarAtributo($conn,$entidadeID,"smtpsecure","SMTP Secure","varchar",5,1,3);
	$port				    = criarAtributo($conn,$entidadeID,"port","Porta","varchar",5,1,3);
	$chartset			    = criarAtributo($conn,$entidadeID,"chartset","Charset","varchar",15,1,3);
	$smtpauth			    = criarAtributo($conn,$entidadeID,"smtpauth","SMTP Auth","boolean",0,1,7);
	$issmtp      			= criarAtributo($conn,$entidadeID,"issmtp","Usar SMTP ?","boolean",0,0,7);
	

	// Criando Acesso
	$menu_webiste 	= addMenu($conn,'Geral','#','',0,0,'geral');

	// Adicionando Menu
	addMenu($conn,$entidadeDescricao,"files/cadastro/".$entidadeID."/".getSystemPREFIXO().$entidadeNome.".html",'',$menu_webiste,8,'geral-' . $entidadeNome,$entidadeID,'cadastro');