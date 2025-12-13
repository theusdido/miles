<?php
	$dados 	= json_decode(tdc::r("dados"),true);
	$op 	= $dados["op"];
	
	switch($op){
		case "salvar":

			// Cliente
			$cliente 					= tdc::p("td_ecommerce_cliente");
			$cliente->nome 				= $dados["cliente"]["nome"];
			$cliente->cpf 				= $dados["cliente"]["cpf"];
			$cliente->datanascimento	= $dados["cliente"]["datanascimento"];
			$cliente->telefone			= $dados["cliente"]["celular"];
			$cliente->email				= $dados["cliente"]["email"];
			$cliente->armazenar();

			// Endereço
			$endereco 				= tdc::p("td_ecommerce_endereco");
			#$endereco->td_cidade 	= Endereco::addCidade($dados["cliente"]["cidade"],$dados["cliente"]["estado"]);
			#$endereco->td_bairro 	= Endereco::addBairro($dados["cliente"]["bairro"],$endereco->cidade);
			$endereco->cidade 	= 1;
			$endereco->bairro 	= 1;		
			$endereco->logradouro 	= $dados["cliente"]["endereco"];
			$endereco->numero 		= isset($dados["cliente"]["numero"])?$dados["cliente"]["numero"]:'';
			$endereco->complemento	= isset($dados["cliente"]["complemento"])?$dados["cliente"]["complemento"]:'';
			$endereco->cep 			= $dados["cliente"]["cep"];
			$endereco->armazenar();

			// Lista Cliente x Endereço
			$lista = tdc::p(LISTA);
			$lista->entidadepai 		= getEntidadeId("ecommerce_cliente");
			$lista->entidadefilho 		= getEntidadeId("ecommerce_endereco");
			$lista->regpai 				= $cliente->id;
			$lista->regfilho 			= $endereco->id;
			$lista->armazenar();

			echo json_encode(array(
				"status" 	=> 0,
				"msg" 		=> "Salvo com Sucesso",
				"id" 		=> $cliente->id,
				"userid" 	=> $usuario->id,
				"username" 	=> $usuario->nome,
				"usergroup" => $usuario->grupousuario
			));
		break;
		case 'load':
			echo tdc::dj('td_ecommerce_cliente',tdc::f("id","=",$dados["cliente"]));
		break;

		case "checked_msg_parceiro":
			$cliente = tdc::p('td_ecommerce_cliente',$dados["cliente"]);
			$cliente->is_exibirmensagemparceiro = $dados["checked"];
			$cliente->salvar();
			Transacao::commit();
		break;
	}