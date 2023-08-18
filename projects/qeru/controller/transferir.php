<?php

	switch($op){
		case 'clientes':
            $termo      = $dados['termo'];
            $criterio   = tdc::f();
            $criterio->addFiltro('id','<>',$dados['cliente']);
            $criterio->addFiltro('tipopessoa','=',1);
            if ($termo != ''){
                $criterio->addFiltro('nome','%',$termo);
                $criterio->addFiltro('email','%',$termo,OU);
            }
            echo tdc::dj("td_ecommerce_cliente",$criterio);
        break;
        case 'add':
            $transferir                         = tdc::p('ecommerce_transferirpontos');
            $transferir->cliente_remetente      = $dados['remetente'];
            $transferir->cliente_destinatario   = $dados['destinatario'];
            $transferir->pontos                 = $dados['pontos'];
            $transferir->datahora               = date('Y-m-d H:i:s');
            $transferir->armazenar();
        break;
	}