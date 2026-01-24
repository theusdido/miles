<?php
	// Cria Entidade
	$entidade 	= new Entity("erp_nfse_configuracoes","Configurações");
	$entidade->setRegistroUnico();

	// Atributos
	$is_ambiente_producao	= $entidade->addAttr(
		array("nome" => "is_ambiente_producao" , "descricao" => "Ambiente de Produção" , "tipohtml" => "checkbox")
	);
	$senha_certificado	= $entidade->addAttr(
		array("nome" => "senha_certificado" , "descricao" => "Senha do Certificado" , "tipohtml" => "senha_sem_criptografia")
	);
