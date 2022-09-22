<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_servico","Serviço");

	// Atributos
	$nfse	= $entidade->addAttr(
		array("nome" => "nfse" , "descricao" => "NFSE" , "tipohtml" => "numero_inteiro" , "chave_estrangeira" => getEntidadeId('erp_nfse_nota'))
	);
	$valservicos	= $entidade->addAttr(
		array("nome" => "valservicos" , "descricao" => "Valor do Serviço" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valdeducoes	= $entidade->addAttr(
		array("nome" => "valdeducoes" , "descricao" => "Valor Deduções" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valpis	= $entidade->addAttr(
		array("nome" => "valpis" , "descricao" => "Valor PIS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valcofins	= $entidade->addAttr(
		array("nome" => "valcofins" , "descricao" => "Valor COFINS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valinss	= $entidade->addAttr(
		array("nome" => "valinss" , "descricao" => "Valor INSS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valir	= $entidade->addAttr(
		array("nome" => "valir" , "descricao" => "Valor IR" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valcsll	= $entidade->addAttr(
		array("nome" => "valcsll" , "descricao" => "Valor CSLL" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$issretido	= $entidade->addAttr(
		array("nome" => "issretido" , "descricao" => "ISS Retido" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$respretencao	= $entidade->addAttr(
		array("nome" => "respretencao" , "descricao" => "Respretenção" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tributavel	= $entidade->addAttr(
		array("nome" => "tributavel" , "descricao" => "Tributável" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valiss	= $entidade->addAttr(
		array("nome" => "valiss" , "descricao" => "Valor do ISS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valissretido	= $entidade->addAttr(
		array("nome" => "valissretido" , "descricao" => "Valor do ISS Retido" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valoutrasretencoes	= $entidade->addAttr(
		array("nome" => "valoutrasretencoes" , "descricao" => "Valor de Outras Retenções" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valbasecalculo	= $entidade->addAttr(
		array("nome" => "valbasecalculo" , "descricao" => "Valor Base de Cálculo" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valaliqiss	= $entidade->addAttr(
		array("nome" => "valaliqiss" , "descricao" => "Valor Alíquota ISS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valaliqpis	= $entidade->addAttr(
		array("nome" => "valaliqpis" , "descricao" => "Valor Alíquota PIS" , "tipo" => "varchar" , "tamanho" => 25)
	);    
	$valaliqcofins	= $entidade->addAttr(
		array("nome" => "valaliqcofins" , "descricao" => "Valor Alíquota COFINS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valaliqir	= $entidade->addAttr(
		array("nome" => "valaliqir" , "descricao" => "Valor Alíquota IR" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valaliqcsll	= $entidade->addAttr(
		array("nome" => "valaliqcsll" , "descricao" => "Valor Alíquota CSLL" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valaliqinss	= $entidade->addAttr(
		array("nome" => "valaliqinss" , "descricao" => "Valor Alíquota INSS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valliquido	= $entidade->addAttr(
		array("nome" => "valliquido" , "descricao" => "Valor Líquido" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valdesccond	= $entidade->addAttr(
		array("nome" => "valdesccond" , "descricao" => "valdesccond" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valdescincond	= $entidade->addAttr(
		array("nome" => "valdescincond" , "descricao" => "valdescincond" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$valaliqissomunic	= $entidade->addAttr(
		array("nome" => "valaliqissomunic" , "descricao" => "Valor Alíquota ISS do Município" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$infvalpis	= $entidade->addAttr(
		array("nome" => "infvalpis" , "descricao" => "Inf. Valor PIS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$infvalcofins	= $entidade->addAttr(
		array("nome" => "infvalcofins" , "descricao" => "Inf. Valor COFINS" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$cserv	= $entidade->addAttr(
		array("nome" => "cserv" , "descricao" => "cserv" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$itelistserv	= $entidade->addAttr(
		array("nome" => "itelistserv" , "descricao" => "itelistserv" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$cnae	= $entidade->addAttr(
		array("nome" => "cnae" , "descricao" => "CNAE" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$fpagamento	= $entidade->addAttr(
		array("nome" => "fpagamento" , "descricao" => "Forma Pagamento" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tributmunicipio	= $entidade->addAttr(
		array("nome" => "tributmunicipio" , "descricao" => "Tributo Município" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$discriminacao	= $entidade->addAttr(
		array("nome" => "discriminacao" , "descricao" => "Discriminação", "tipo" => "varchar" , "tamanho" => 1000)
	);    
	$cmun	= $entidade->addAttr(
		array("nome" => "cmun" , "descricao" => "Código Município" , "tipo" => "varchar" , "tamanho" => 25)
	);    
	$serquantidade	= $entidade->addAttr(
		array("nome" => "serquantidade" , "descricao" => "serquantidade" , "tipo" => "varchar" , "tamanho" => 25)
	);    
	$serquantidade	= $entidade->addAttr(
		array("nome" => "serquantidade" , "descricao" => "serquantidade" , "tipo" => "varchar" , "tamanho" => 25)
	);        
	$serunidade	= $entidade->addAttr(
		array("nome" => "serunidade" , "descricao" => "serunidade" , "tipo" => "varchar" , "tamanho" => 25)
	);        
	$sernumalvara	= $entidade->addAttr(
		array("nome" => "sernumalvara" , "descricao" => "sernumalvara" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$paipreservico	= $entidade->addAttr(
		array("nome" => "paipreservico" , "descricao" => "paipreservico" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$cmunincidencia	= $entidade->addAttr(
		array("nome" => "cmunincidencia" , "descricao" => "cmunincidencia" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$obrigomunic	= $entidade->addAttr(
		array("nome" => "obrigomunic" , "descricao" => "obrigomunic" , "tipo" => "varchar" , "tamanho" => 25)
	);
	$tributacaoiss	= $entidade->addAttr(
		array("nome" => "tributacaoiss" , "descricao" => "tributacaoiss" , "tipo" => "varchar" , "tamanho" => 25)
	);   