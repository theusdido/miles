<?php
	// Entidade
	$entidadeNome = getSystemPREFIXO() . "erp_financeiro_operacaobancaria";
	$campos			= array("descricao","codigo","banco");

	// -- Registros
	// Caixa  Econômica Federal
	$cef = tdc::d('td_erp_financeiro_banco',tdc::f('sigla','=','CEF'));
	if (sizeof($cef) > 0){
		$_banco = $cef[0]->id;
		inserirRegistro($conn,$entidadeNome,"DEFAULT",$campos,array("'Conta Corrente de Pessoa Física'","'001'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Conta Simples de Pessoa Física'","'002'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Conta Corrente de Pessoa Jurídica'","'003'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Entidades Públicas'","'006'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Depósitos Instituições Financeiras'","'007'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Poupança de Pessoa Física'","'013'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Poupança de Pessoa Jurídica'","'022'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Conta Caixa Fácil'","'023'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Poupança de Crédito Imobiliário'","'028'",$_banco));
		inserirRegistro($conn,$entidadeNome,'DEFAULT',$campos,array("'Depositários Lotéricos'","'048'",$_banco));
	}