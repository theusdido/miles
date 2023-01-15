<?php

	$_pagseguro 				= tdc::ru("td_ecommerce_pagseguro");
	if ($_pagseguro->hasData()){
		// Credenciais - Dados do Vendedor
		$credenciais =  array(
			"email" 			=> trim($_pagseguro->email),
			"token" 			=> trim($_pagseguro->token),
			'notificacao_url' 	=> trim($_pagseguro->notificacaourl),
			'is_producao' 		=> trim($_pagseguro->producao) == 0 ? false : true,
			'environment'		=> trim($_pagseguro->producao) == 0 ? 'sandbox.' : ''
		);
	}else{
		echo '<div class="alert alert-danger text-center" role="alert"><b>Ops!</b> Credenciais do vendedor do PagSeguro não encontrada.</div>';
		exit;
	}