<?php
    switch($_op){
        case 'gerar':
            foreach(tdc::d('td_erp_financeiro_despesa') as $despesa){
                $cp                     = tdc::p('td_erp_financeiro_contasapagar');
                $cp->descricao 		    = $despesa->descricao;
                $cp->despesa 		    = $despesa->id;
                $cp->fornecedor 	    = $despesa->fornecedor;
                $cp->elementocusto 	    = $despesa->elementocusto;
                $cp->valor 			    = $despesa->valor;
                $cp->dataemissao 	    = date('Y-m-d');
                $cp->formapagamento     = $despesa->formapagamento;
                $cp->pago 			    = 0;
                $cp->armazenar();
            }
        break;
        case 'get':
            tdc::wj( tdc::pa('td_erp_financeiro_despesa',tdc::r('cp')) );
        break;
    }