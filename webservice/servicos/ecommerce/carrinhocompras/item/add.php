<?php

    try{
        $product            = tdc::p('td_ecommerce_produto',tdc::r('_product'));
        $quantity           = (int)tdc::r('_quantity',1);
        $price              = (double)tdc::r('_price',1);
        $session_id         = tdc::r('_session_id',session_id());

        $idProduto 		    = $product->id;
        $qtdeProduto 		= $quantity;
        $descricaoProduto 	= addslashes($product->nome);
        #$imgsrcProduto 	    = $product->getSRC('imagemprincipal');
        $valorProduto 		= $price;
        $tamanhoProduto 	= tdc::r('tamanho','0');
        $corProduto 		= tdc::r('cor','0');
        $tamanho_desc		= $tamanhoProduto!=0?tdc::p('td_ecommerce_produtotamanho',$tamanhoProduto)->descricao:'';
        $cor_desc			= $corProduto!=0?tdc::p('td_ecommerce_produtotamanho',$corProduto)->descricao:'';

        $insert_variacao_field 	= '';
        $insert_variacao_value	= '';

        // if (tdEcommerceProduto::isVariacaoPeso($idProduto)){
        //     $insert_variacao_value	= ','.$dados["id"];
        //     $where                 .= " AND variacaopeso = {$variacaopeso} ";
        //     $insert_variacao_field  = ',variacaopeso';
        // }

        $produtonome		= $product->nome;
        $referencia			= $product->referencia;
        $_cart              = tdc::d('td_ecommerce_carrinhocompras',tdc::f('sessionid','=',$session_id));

        if (sizeof($_cart) == 0){
            $retorno['status']  = 'error';
            $retorno['msg']     = 'Carrinho de compras não encontrado.';
            $retorno['code']    = 1;
        }else{
            $_carrinho_id       = $_cart[0]->id;

            $criteria_delete_cartitem   = tdc::f();   
            $criteria_delete_cartitem->addFiltro('carrinho','=',$_carrinho_id);
            $criteria_delete_cartitem->addFiltro('produto','=',$idProduto);
            $delete_cartitem            = tdc::de('td_ecommerce_carrinhoitem',$criteria_delete_cartitem);
    
            $cartitem               = tdc::p('td_ecommerce_carrinhoitem');
            $cartitem->carrinho     = $_carrinho_id;
            $cartitem->produto      = $idProduto;
            $cartitem->qtde         = $qtdeProduto;
            $cartitem->descricao    = $descricaoProduto;
            $cartitem->valor        = $valorProduto;
            $cartitem->valortotal   = $qtdeProduto*$valorProduto;
            $cartitem->tamanho      = $tamanhoProduto;
            $cartitem->cor          = $corProduto;
            $cartitem->produtonome  = $produtonome;
            $cartitem->referencia   = $referencia;
            $cartitem->armazenar();
    
            $retorno['data']                = tdc::pa('td_ecommerce_carrinhoitem',$cartitem->id);
            $retorno['status']              = 'success';
            $retorno['msg']                 = 'Item adicionado ao carrinho de compras.';
            $retorno['code']                = 0;
        }
    }catch(Throwable $e){
        if (IS_SHOW_ERROR_MESSAGE){
            var_dump($e->getMessage());
        }
        $retorno['status']  = 'error';
        $retorno['msg']     = 'Carrinho de compras não encontrado.';
        $retorno['code']    = 2;
    }finally{
        #addDebug($sqlExcluirItensCarrinho);
        #addDebug($sqlInsertItem);
    }