<?php
	switch($op){
		case 'listar':
			$propostas 	= array();
			$cliente	= isset($dados['cliente']) ? $dados['cliente'] : 0;
			$pedido_id	= $dados['pedido'];

			$where		 = " WHERE p.cliente = {$cliente}";
			$where 		.= ($pedido_id > 0) ? " AND p.id = {$pedido_id} " : "";
			$where		.= " AND pr.inativo <> true && pr.is_finalizada <> true";

			$sql 		= "
				SELECT 
					p.id,
					DATE_FORMAT(p.datahoraenvio,'%d/%m/%Y') datahoraenvio,
					p.cliente,
					i.descricao,
					p.observacao,
					m.categoria,
					ifnull(m.subcategoria,0) subcategoria,
					i.id item,
					ifnull(pr.loja,0) loja,
					pr.id proposta_id, 
					m.id produto_id,
					pr.oportunidade oportunidade,
					pr.oportunidade oportunidade_id
				FROM td_ecommerce_pedido p
				INNER JOIN td_ecommerce_proposta pr ON pr.pedido = p.id
				INNER JOIN td_ecommerce_pedidoitem i ON p.id = i.pedido
				INNER JOIN td_ecommerce_produto m ON m.id = i.produto
				{$where}
			";
			$query = Transacao::Get()->query($sql);
			while($linha = $query->fetch()){
				$cliente 			= tdc::pa("td_ecommerce_cliente",$linha["cliente"]);
				$localizacao 		= isset(tdc::d("vw_localidade_cliente")[0]) ? tdc::d("vw_localidade_cliente")[0] : '-';
				$usuario        	= getListaRegFilhoArray(getEntidadeId("ecommerce_cliente"),getEntidadeId("usuario"),$cliente['id'])[0];
				$item				= $linha["item"];
				$atributos 			= array();
				$atributo 			= 0;
				$oportunidade_id	= $linha['oportunidade_id'];

				// Atributos escolhidos pelo cliente
				foreach(tdc::d('td_ecommerce_clienteopcao',tdc::f('pedidoitem','=',$item)) as $opcao){
					if ($atributo != $opcao->atributo){
						$atributo 	= $opcao->atributo;
						array_push($atributos,array(
							'nome' 		=> tdc::p('td_ecommerce_atributoproduto',$atributo)->descricao
							//'valores'	=> getOpcoesCliente($item,$atributo)
						));
					}
				}

				$categoria_obj 		= tdc::pa('td_ecommerce_categoria',$linha['categoria']);
				$subcategoria_obj	= tdc::pa('td_ecommerce_subcategoria',$linha['subcategoria']);
				$loja_obj			= tdc::pa('td_ecommerce_loja',$linha['loja']);
				$localizacao		= isset($localizacao->localidade) ? tdc::utf8($localizacao->localidade) : '-';
				$ds_negociacao 		= tdc::da("td_ecommerce_negociacao",tdc::f('oportunidade','=',$oportunidade_id));				

				array_push($propostas,array(
					'pedido_id' 		=> $linha["id"],
					'data' 				=> $linha["datahoraenvio"],
					'cliente'			=> $cliente,
					'localizacao' 		=> $localizacao,
					'descricao'			=> tdc::utf8($linha["observacao"]),
					'status'			=> 'proposta-open',
					//'anexos'			=> $anexos,
					'categoria'			=> $categoria_obj,
					'subcategoria'		=> isset($subcategoria_obj['descricao']) ? tdc::utf8($subcategoria_obj['descricao']) : '',
					'atributos'			=> $atributos,
					'loja'				=> $loja_obj,
					'proposta_id'		=> $linha['proposta_id'],
					'produto'           => tdc::pa('td_ecommerce_produto',$linha['produto_id']),
					'oportunidade'		=> tdc::pa('td_ecommerce_oportunidade',$oportunidade_id),
					'pedido'			=> tdc::pa('td_ecommerce_pedido',$linha['id']),
					'usuario'			=> $usuario,
					'oportunidade_id'	=> $oportunidade_id,
					'negociacao'		=> sizeof($ds_negociacao) > 0 ? $ds_negociacao[0] : []
				));
			}
			echo json_encode($propostas);
		break;
		case 'iniciar':

			$oportunidade				= tdc::p('td_ecommerce_oportunidade',$dados['oportunidade_id']);
			$loja						= tdc::pa('td_ecommerce_loja',$oportunidade->loja);
			$pedido						= tdc::pa('td_ecommerce_pedido',$oportunidade->pedido);
			$pedidoitem					= tdc::da('td_ecommerce_pedidoitem',tdc::f('pedido','=',$pedido['id']));
			$produto					= tdc::pa('td_ecommerce_produto',$pedidoitem[0]['produto']);
			$categoria					= tdc::pa('td_ecommerce_categoria',$produto['categoria']);
			$cliente					= tdc::pa('td_ecommerce_cliente', $pedido['cliente']);

			$proposta 					= tdc::p('td_ecommerce_proposta');
			$proposta->oportunidade		= $oportunidade->id;
			$proposta->loja				= $loja['id'];
			$proposta->pedido			= $pedido['id'];
			$proposta->datahoracriacao	= date('Y-m-d H:i:s');
			$proposta->inativo 			= false;
			$proposta->is_finalizada	= false;
			$proposta->armazenar();

			$oportunidade->proposta 	= $proposta->id;
			$oportunidade->armazenar();

			// Retorno para armazenar os dados no RealTime Database
			$data_realtime				= array (
				'proposta'				=> array('id' => $proposta->id),
				'oportunidade'			=> array('id' => $oportunidade->id),
				'cliente'				=> array('id' => $cliente['id'], 'nome' => $cliente['nome']),
				'categoria'				=> tdc::utf8($categoria['descricao']),
				'loja'					=> array('id' => $loja['id'], 'nomefantasia' => $loja['nomefantasia'] , 'localizacao' => '' , 'logo_src' => $loja['logo_src']),
				'chat_msg_no_readed'	=> 1,
				'pedido'				=> array('id' => $proposta->pedido)
			);

			echo json_encode(array(
				"status" 	=> "success",
				"data"	=> $data_realtime
			));

		break;
		case "abertas":
			
		break;
		case 'finalizar':
			$proposta 				= tdc::p('td_ecommerce_proposta',$dados['_id']);
			$proposta->inativo 		= true;
			$proposta->is_finalizada= true;
			$proposta->armazenar();
		break;
	}