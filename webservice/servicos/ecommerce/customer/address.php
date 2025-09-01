<?php
    require_once PATH_MILES_LIBRARY . 'classes/ecommerce/endereco.class.php';

    $cliente         = getListaRegPai(
        $_entidade_cliente_id,
        getEntidadeId('td_usuario'),
        tdc::r('_user_id')
    );

    if (sizeof($cliente) <= 0){
        $retorno['message'] = 'Usuário não está vinculado a nenhum cliente.';
        $retorno['status'] = 'error';
        tdc::wj($retorno);
        exit;
    }

    $cliente_id = $cliente[0]->id;
    $endereco_class = new Endereco();
    #$endereco_class->setCliente($cliente_id);    

    switch($_op){
        case 'get':
            $retorno['data']    = $endereco_class->getDados();
            $retorno['status']  = sizeof($retorno['data']) > 0 ? 'success' : 'error';
        break;
        case 'add':

            $endereco_dados     = json_decode(tdc::r('_address_data'),true);

            $logradouro			= $endereco_dados['logradouro'];
            $cep 				= $endereco_dados['cep'];
            $complemento 		= $endereco_dados['complemento'];
            $numero 			= $endereco_dados['numero'];
            $bairro 	        = $endereco_dados['bairro'];
            $cidade 	        = $endereco_dados['cidade'];
            $uf 				= $endereco_dados['estado'];
            $pais               = $endereco_dados['pais'];


            $endereco_class->setPais($pais);
            $endereco_class->setUf($uf);
            $endereco_class->setCidade($cidade);
            $endereco_class->setBairro($bairro);
            $endereco_class->setLogradouro($logradouro);
            $endereco_class->setNumero($numero);
            $endereco_class->setComplemento($complemento);
            $endereco_class->setCEP($cep);
            
            if ($endereco_class->salvar()){
                $retorno['status'] = 'success';
            }else{
                $retorno['status'] = 'error';
            }

            // $cidade_id = $endereco_class->addCidade($cidade, $uf);
            // $bairro_id = $endereco_class->addBairro($bairro, $cidade_id);

            // Cidade
            // $criterio = tdc::f();
            // $criterio->addFiltro('nome','%',$cidade);
            // $criterio->addFiltro('uf','=',$uf);

            // $cidade_obj = tdc::p('td_ecommerce_cidade')->newNotExistsCriteria($criterio);
            // $cidade_obj->nome = $cidade;
            // $cidade_obj->uf = $uf;
            // $cidade_obj->armazenar();
            // $cidade_id = $cidade_obj->id;

            // Bairro
            // $criterio = tdc::f();
            // $criterio->addFiltro('nome','%',$bairro);
            // $criterio->addFiltro('cidade','=',$cidade_id);

            // $bairro_obj = tdc::p('td_ecommerce_bairro')->newNotExistsCriteria($criterio);
            // $bairro_obj->nome = $bairro;
            // $bairro_obj->cidade = $cidade_id;
            // $bairro_obj->armazenar();
            // $bairro_id = $bairro_obj->id;

            // Endereço
            // $endereco = tdc::p('td_ecommerce_endereco');
            // $endereco->pais             = $pais;
            // $endereco->cidade           = $cidade_id;
            // $endereco->cidade_desc      = $cidade;
            // $endereco->bairro           = $bairro_id;
            // $endereco->bairro_desc      = $bairro;
            // $endereco->logradouro		= $logradouro;
            // $endereco->cep 				= $cep;
            // $endereco->complemento 		= $complemento;
            // $endereco->numero 			= $numero;
            // $endereco->uf 				= $uf;

            // if ($endereco->armazenar()){


            //     $retorno['status'] = 'success';
            // }else{
            //     $retorno['status'] = 'error';
            // }            
        break;
    }