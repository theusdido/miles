<?php

    $dados = json_decode(tdc::r('dados'));
    switch($dados->op){
        case 'credito':
            $movimentacao                   = tdc::p('td_carteiradigital_movimentacao');
            if ($dados->perfil == 'L'){
                $movimentacao->loja      = $dados->loja;
            }else{
                $movimentacao->usuario   = $dados->cliente;
            }
            $movimentacao->valor            = $dados->valor;
            $movimentacao->transacao        = $dados->transacao;
            $movimentacao->datahora         = date('Y-m-d H:i:s');
            $movimentacao->is_finalizada    = true;
            $movimentacao->armazenar();
        break;

        case 'listar':
            $movimentacoes  = array();
            $criterio       = tdc::f('loja','=',$dados->loja);
            $criterio->setPropriedade('order','ID desc');
            $dataset        = tdc::d('td_carteiradigital_movimentacao',$criterio);

            foreach($dataset as $d){
                $transacao      = tdc::p('td_carteiradigital_transacao',$d->transacao);
                array_push($movimentacoes,array(
                    'id'        => $d->id,
                    'data'      => datetimeToMysqlFormat($d->datahora,true),
                    'operacao'  => $transacao->operacao==1?'D':'C',
                    'transacao' => $transacao->descricao,
                    'valor'     => $d->valor
                ));
            }

            echo json_encode($movimentacoes);
        break;
        case 'pontos':
            $cliente        = $dados->cliente;

            $criterio       = tdc::f('cliente','=',$cliente);
            $criterio->setPropriedade('order','ID desc');

            $trocar         = array();
            foreach(tdc::d('td_ecommerce_trocarprodutospontos',$criterio) as $troca){
                array_push(
                    $trocar,
                    array(
                        'id'            => $troca->id,
                        'movimentacao'  => tdc::p('td_ecommerce_produto',$troca->produto)->nome,
                        'operacao'      => 'Troca',
                        'data'          => dateToMysqlFormat($troca->data,true),
                        'pontos'        => $troca->pontos
                    )
                );
            }

            $criterio = tdc::f('cliente_remetente','=',$cliente);
            $criterio->setPropriedade('order','ID desc');
            $transferir     = array();
            foreach(tdc::d('td_ecommerce_transferirpontos',$criterio) as $transferencia){
                array_push(
                    $transferir,
                    array(
                        'id'            => $transferencia->id,
                        'movimentacao'  => tdc::p('td_ecommerce_cliente',$transferencia->cliente_destinatario)->nome,
                        'operacao'      => 'Transferência',
                        'data'          => dateToMysqlFormat($transferencia->datahora,true),
                        'pontos'        => $transferencia->pontos
                    )
                );                
            }
            echo json_encode(array_merge($trocar , $transferir));
        break;
    }