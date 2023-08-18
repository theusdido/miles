<?php
	$dados 	= json_decode(tdc::r("dados"),true);
	$op 	= $dados["op"];
	
	switch($op){
		case "cliente":

            $cliente 				= tdc::p("td_ecommerce_cliente",$dados['id']);
			$_entidade_id_cliente 	= getEntidadeId("ecommerce_cliente");
			$_entidade_id_endereco	= getEntidadeId("ecommerce_endereco");
			$lista_endereco  		= getListaRegFilhoArray($_entidade_id_cliente,$_entidade_id_endereco,$cliente->id);
			$_endereco_id 			= isset($lista_endereco[0]['id']) ? $lista_endereco[0]['id'] : 0;
            //var_dump($_entidade_id_cliente , $_entidade_id_endereco , $cliente->id);
            //var_dump($lista_endereco);

			if ($_endereco_id <= 0){				
				$_endereco 	    = [];
                $_status        = 1;
			}else{
                $_endereco      = $lista_endereco[0];
                $_status        = 0;
			}

            /*
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
            */
			echo json_encode(array(
				"status" 	=> $_status,
				"dados" 	=> $_endereco
			));
            
		break;
		case 'load':
			$filtro = tdc::f();
			$filtro->addFiltro("id","=",$dados["cliente"]);
			$filtro->onlyActive();
			echo tdc::dj('td_ecommerce_cliente',$filtro);
		break;

		case "checked_msg_parceiro":
			$cliente = tdc::p('td_ecommerce_cliente',$dados["cliente"]);
			$cliente->is_exibirmensagemparceiro = $dados["checked"];
			$cliente->salvar();
			Transacao::commit();
		break;
	}