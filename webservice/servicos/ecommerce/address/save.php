<?php

    $endereco_id        = tdc::r('id');
	$logradouro			= tdc::r('logradouro');
	$cep 				= tdc::r('cep');
	$complemento 		= tdc::r('complemento');
	$numero 			= tdc::r('numero');
    $bairro 	        = tdc::r('bairro');
    $cidade 	        = tdc::r('cidade');
	$uf 				= tdc::r('uf');

    $endereco = tdc::p('td_ecommerce_endereco',$endereco_id);

    if ($endereco->cidade_nome != $cidade){
        // Cidade
        $sqlCidade 	    = "SELECT id FROM td_ecommerce_cidade WHERE nome = '{$cidade}' and uf = {$uf};";
        $queryCidade    = $conn->query($sqlCidade);
        if ($queryCidade->rowCount() > 0){
            $linhaCidade    = $queryCidade->fetch();
            $cidade_id      = $linhaCidade["id"];
        }else{
            $idcidade   = getProxId("ecommerce_cidade",$conn);
            $sqlCidadeI = "INSERT INTO td_ecommerce_cidade (id,nome,uf) VALUES (".$idcidade.",'{$cidade}',{$uf});";
            $queryCidadeI = $conn->query($sqlCidadeI);
            if ($queryCidadeI){
                $cidade_id = $idcidade; 
            }else{
                $cidade_id = 0;
            }
        }
        $endereco->cidade = $cidade_id;
    }

    // Bairro
    if ($endereco->bairro_nome != $bairro){
        $sqlBairro      = "SELECT id FROM td_ecommerce_bairro WHERE nome = '{$bairro}' and cidade = {$cidade};";
        $queryBairro    = $conn->query($sqlBairro);
        if ($queryBairro->rowCount() > 0){
            $linhaBairro    = $queryBairro->fetch();
            $bairro_id         = $linhaBairro["id"];
        }else{
            $idbairro   = getProxId("ecommerce_bairro",$conn);
            $sqlBairroI = "INSERT INTO td_ecommerce_bairro (id,nome,cidade) VALUES (".$idbairro.",'{$bairro}',{$cidade});";
            //addDebug("SQL INSERT BAIRRO CADASTRO ",$sqlBairroI);
            $queryBairroI = $conn->query($sqlBairroI);
            if ($queryBairroI){
                $bairro_id = $idbairro;
            }else{
                $bairro_id = 0;
            }
        }
        $endereco->bairro = $bairro_id;
    }

    $endereco->bairro_nome      = $bairro;
    $endereco->cidade_nome      = $cidade;
	$endereco->logradouro		= $logradouro;
	$endereco->cep 				= $cep;
	$endereco->complemento 		= $complemento;
	$endereco->numero 			= $numero;
    $endereco->uf 				= $uf;

    if ($endereco->armazenar()){
        $retorno['status'] = 'success';
    }else{
        $retorno['status'] = 'error';
    }