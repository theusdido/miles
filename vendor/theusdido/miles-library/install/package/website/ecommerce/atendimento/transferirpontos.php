<?php
	// Setando variáveis
	$entidadeNome 		= "ecommerce_transferirpontos";
	$entidadeDescricao 	= "Transferência de Pontos";

	// Criando Entidade
	$entidadeID = criarEntidade(
		$conn,
		$entidadeNome,
		$entidadeDescricao,
		$ncolunas=2,
		$exibirmenuadministracao = 0,
		$exibircabecalho = 1,
		$campodescchave = "",
		$atributogeneralizacao = 0,
		$exibirlegenda = 1,
		$criarprojeto = 1,
		$criarempresa = 1,
		$criarauth = 0,
		$registrounico = 0
	);

    $_cliente_entidade_id         = installDependencia("ecommerce_cliente","package/website/ecommerce/geral/cliente");

	// Criando Atributos
    $tdp_cliente_remetente        = criarAtributo($conn,$entidadeID,"cliente_remetente","Remetente","int",0,1,16,0,$_cliente_entidade_id,0,"");
    $tdp_cliente_destinatario     = criarAtributo($conn,$entidadeID,"cliente_destinatario","Destinatário","int",0,1,16,0,$_cliente_entidade_id,0,"");
    $tdp_pontos 	              = criarAtributo($conn,$entidadeID,"pontos","Pontos","float",0,1,26,0,0,0,"");
    $tdp_datahora 		          = criarAtributo($conn,$entidadeID,"datahora","Data/Hora","datetime",0,0,23,1,0,0,"");