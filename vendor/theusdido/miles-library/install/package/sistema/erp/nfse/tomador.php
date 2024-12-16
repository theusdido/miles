<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_tomador","Tomador");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_nfse_nota'))
	);
	
	$pessoa	= $entidade->addAttr(
		array("nome" => "pessoa" , "descricao" => "Pessoa" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => installDependencia('erp_geral_pessoa','package/sistema/erp/pessoa/pessoa'))
	);
	$tomacpf	= $entidade->addAttr(
		array("nome" => "tomacpf" , "descricao" => "tomacpf" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tomacnpj	= $entidade->addAttr(
		array("nome" => "tomacnpj" , "descricao" => "tomacnpj" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tomarazaosocial	= $entidade->addAttr(
		array("nome" => "tomarazaosocial" , "descricao" => "tomarazaosocial" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$tomaim	= $entidade->addAttr(
		array("nome" => "tomaim" , "descricao" => "tomaim" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tomasite	= $entidade->addAttr(
		array("nome" => "tomasite" , "descricao" => "tomasite" , "tipo" => "varchar" , "tamanho" => 200)
	);				
	$tomatplgr	= $entidade->addAttr(
		array("nome" => "tomatplgr" , "descricao" => "tomatplgr" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$tomaendereco	= $entidade->addAttr(
		array("nome" => "tomaendereco" , "descricao" => "tomaendereco" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$tomanumero	= $entidade->addAttr(
		array("nome" => "tomanumero" , "descricao" => "tomanumero" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tomacomplemento	= $entidade->addAttr(
		array("nome" => "tomacomplemento" , "descricao" => "tomacomplemento" , "tipo" => "varchar" , "tamanho" => 200)
	);
	$tombairro	= $entidade->addAttr(
		array("nome" => "tombairro" , "descricao" => "tombairro" , "tipo" => "varchar" , "tamanho" => 50)
	);
	$tomacmun	= $entidade->addAttr(
		array("nome" => "tomacmun" , "descricao" => "tomacmun" , "tipo" => "varchar" , "tamanho" => 50)
	);
	$tomaxmun	= $entidade->addAttr(
		array("nome" => "tomaxmun" , "descricao" => "tomaxmun" , "tipo" => "varchar" , "tamanho" => 50)
	);
	$tomauf	= $entidade->addAttr(
		array("nome" => "tomauf" , "descricao" => "tomauf" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tomapais	= $entidade->addAttr(
		array("nome" => "tomapais" , "descricao" => "tomapais" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tomacep	= $entidade->addAttr(
		array("nome" => "tomacep" , "descricao" => "tomacep" , "tipo" => "varchar" , "tamanho" => 10)
	);
	$tomatelefone	= $entidade->addAttr(
		array("nome" => "tomatelefone" , "descricao" => "tomatelefone" , "tipo" => "varchar" , "tamanho" => 50)
	);
	$tomaemail	= $entidade->addAttr(
		array("nome" => "tomaemail" , "descricao" => "tomaemail" , "tipo" => "varchar" , "tamanho" => 200)
	);