<?php
	$pedidos 	= [];
	$filtro 	= tdc::f(array(
		["isfinalizado","<>",1],["isfinalizado","IS",NULL,OU]
	));

	$filtro->setPropriedade("order","id DESC");
	foreach(tdc::da("td_ecommerce_pedido",$filtro) as $pedido){
		
		$valorfrete = $pedido["valorfrete"]==''?0:$pedido["valorfrete"];
		$valortotal 		= $pedido["valortotal"]==''?0:$pedido["valortotal"];
		$clienteID 			= $pedido["cliente"];
		$pedidoID			= $pedido["id"];
		$clienteEntidadeID 	= getEntidadeId('td_ecommerce_cliente');
		$enderecoEntidadeID	= getEntidadeId('td_ecommerce_endereco');
		
		// Dados Endereço
		$enderecoClienteID = 0;
		$enderecoCliente = "";
		$sqlEndereco = "
			SELECT b.id,b.logradouro,b.numero,b.complemento,b.cep,b.bairro,b.cidade FROM td_lista a
			INNER JOIN td_ecommerce_endereco b ON a.regfilho = b.id AND a.regpai = {$clienteID}
			AND a.entidadepai = {$clienteEntidadeID} AND a.entidadefilho = {$enderecoEntidadeID}
			ORDER BY b.id DESC
			LIMIT 1;
		";
		$queryEndereco = $conn->query($sqlEndereco);
		if ($linhaEndereco = $queryEndereco->fetch()){
			$enderecoClienteID = $linhaEndereco["id"];
			$enderecoCliente = $linhaEndereco["logradouro"] . "," . ($linhaEndereco["numero"] == ""?"S/N":$linhaEndereco["numero"])
			. ($linhaEndereco["complemento"] == ""?"":" - ".$linhaEndereco["complemento"]) . ". "
			. $linhaEndereco["bairro"] . " - " . $linhaEndereco["cidade"] . "/SC. "
			. $linhaEndereco["cep"];
		}
		
		$cliente	= tdc::p("td_ecommerce_cliente",$clienteID);
		array_push($pedidos,array(
			"id" 				=> completaString($pedidoID,3,"0"),
			"cliente" 			=> tdc::pa('td_ecommerce_cliente',$cliente->id),
			"endereco"			=> tdc::pa('td_ecommerce_endereco',$enderecoClienteID),
			"datahoraenvio" 	=> datetimeToMysqlFormat($pedido["datahoraenvio"],true),
			"valortotal" 		=> ($valortotal>0?moneyToFloat((double)$pedido["valortotal"],true):"0,00"),
			"valorfrete" 		=> ($valorfrete>0?moneyToFloat((double)$pedido["valorfrete"],true):"0,00"),
			"itens"				=> tdc::da('td_ecommerce_pedidoitem',tdc::f('pedido','=',$pedidoID))
		));
	}
	$retorno["dados"] = $pedidos;