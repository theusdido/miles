<?php

    $_costumer              = (int)tdc::r('cliente','0');
    $_cart_id               = tdc::r('id');
    $_cart_session_id       = tdc::r('sessionid');

	$wherecarrinho = '';
	if ($_costumer > 0){
		$wherecarrinho .= " OR cliente = {$_costumer}";
	}

    $_now    = date("Y-m-d H:i:s");
	$sqlCarrinhoV = "
		SELECT * 
		FROM td_ecommerce_carrinhocompras 
		WHERE (sessionid = '{$_cart_session_id}' 
		{$wherecarrinho}) 
		AND (inativo = false OR inativo IS NULL) 
		ORDER BY datahoracriacao DESC 
		LIMIT 1;
	";
	$queryCarrinhoV = $conn->query($sqlCarrinhoV);
	if ($queryCarrinhoV->rowCount() > 0){
		$linhaCarrinhoV 				= $queryCarrinhoV->fetch();
        $_id_card                       = $linhaCarrinhoV["id"];

		// Atualiza o carrinho de compras
		$carrinho_update						= tdc::p('td_ecommerce_carrinhocompras', $_id_card);
		$carrinho_update->datahoraultimoacesso	= $_now;
		$carrinho_update->cliente				= $_costumer;
		$carrinho_update->armazenar();

        $_msg_retorno                           = 'Carrinho de compras atualizado.';
	}else{

        // Cria um novo carrinho de compras
        $_cart						    = tdc::p('td_ecommerce_carrinhocompras');
        $_cart->datahoracriacao         = $_now;
        $_cart->sessionid               = session_id();
        $_cart->cliente                 = $_costumer;
        $_cart->datahoraultimoacesso    = $_now;
        $_cart->armazenar();
        
        $_msg_retorno                   = 'Novo carrinho de compras criado.';
        $_id_card                       = $_cart->id;
	}
    
    $_data  = array_merge(
        tdc::pa('td_ecommerce_carrinhocompras',$_id_card),
        array(
            'items' => tdc::da('td_ecommerce_carrinhoitem',tdc::f('carrinho','=',$_id_card))
        )
    );
    $retorno['status']  = 'success';
    $retorno['msg']     = $_msg_retorno;
    $retorno['data']    = $_data;