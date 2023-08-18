<?php
	switch($op){
		case 'criar':
			$oportunidade	= tdc::p("td_ecommerce_oportunidade",$dados["oportunidade_id"]);
			$pedido			= tdc::p("td_ecommerce_pedido",$oportunidade->pedido);
			$loja			= tdc::pa("td_ecommerce_loja",$oportunidade->loja);
			$cliente		= tdc::pa('td_ecommerce_cliente',$pedido->cliente);
			$pedidoitem		= tdc::da('td_ecommerce_pedidoitem',tdc::f('pedido','=',$pedido->id));
			$produto		= tdc::pa('td_ecommerce_produto',$pedidoitem[0]['produto']);
			$categoria		= tdc::pa('td_ecommerce_categoria',$produto['categoria']);

			$ft = tdc::f();
			$ft->addFiltro("pedido","=",$pedido->id);
			$ft->addFiltro("loja","=",$loja['id']);
			$ds_negociacao = tdc::da("td_ecommerce_negociacao",$ft);

			// Encerra processo pois a negociação já foi aberta
			if (sizeof($ds_negociacao) > 0){
				echo json_encode(array(
					'status'		=> 'error',
					'msg'			=> 'Negociação já foi criada.',
					'data'			=> array (
						'negociacao' 	=> array('id' => $ds_negociacao[0]['id'], 'is_aberto' => $ds_negociacao[0]['isaberto'])						
					)
				));
				exit;
			}

			$is_iniciada					= false;
			$is_aberto						= 0;
			$negociacao 					= tdc::p('td_ecommerce_negociacao');
			$negociacao->pedido				= $pedido->id;
			$negociacao->cliente 			= $pedido->cliente;
			$negociacao->loja 				= $loja['id'];
			$negociacao->oportunidade		= $oportunidade->id;
			$negociacao->proposta			= $oportunidade->proposta;
			$negociacao->isaberto			= $is_aberto;
			$negociacao->is_iniciada		= $is_iniciada;
			$negociacao->datahoraabertura	= date("Y-m-d H:i:s");
			$negociacao->armazenar();

			$oportunidade->negociacao		= $negociacao->id;
			$oportunidade->armazenar();

			// Retorno para armazenar os dados no RealTime Database
			$data_realtime				= array (
				'negociacao'			=> tdc::pa('td_ecommerce_negociacao',$negociacao->id),
				'oportunidade'			=> tdc::pa('td_ecommerce_oportunidade',$oportunidade->id),
				'categoria'				=> $categoria,
				'loja'					=> $loja,
				'chat_msg_no_readed'	=> 0,
				'is_aberto'				=> $is_aberto,
				'produto'				=> $produto,
				'cliente'				=> $cliente,
				'pedido'				=> tdc::pa('td_ecommerce_pedido',$pedido->id)
			);

			echo json_encode(array(
				'status'			=> 'success',
				"data"				=> $data_realtime
			));
		break;
		case 'iniciar':
			$oportunidade	= tdc::p("td_ecommerce_oportunidade",$dados["oportunidade_id"]);
			$pedido			= tdc::p("td_ecommerce_pedido",$oportunidade->pedido);
			$loja			= tdc::pa("td_ecommerce_loja",$oportunidade->loja);
			$cliente		= tdc::pa('td_ecommerce_cliente',$pedido->cliente);
			$pedidoitem		= tdc::da('td_ecommerce_pedidoitem',tdc::f('pedido','=',$pedido->id));
			$produto		= tdc::pa('td_ecommerce_produto',$pedidoitem[0]['produto']);
			$categoria		= tdc::pa('td_ecommerce_categoria',$produto['categoria']);

			$ft = tdc::f();
			$ft->addFiltro("pedido","=",$pedido->id);
			$ft->addFiltro("loja","=",$loja['id']);
			$ds_negociacao = tdc::da("td_ecommerce_negociacao",$ft);

			// Encerra processo pois a negociação já foi aberta
			if (sizeof($ds_negociacao) > 0){
				echo json_encode(array(
					'status'		=> 'error',
					'msg'			=> 'Negociação já foi criada.',
					'data'			=> array (
						'negociacao' 	=> array('id' => $ds_negociacao[0]['id'], 'is_aberto' => $ds_negociacao[0]['isaberto'])						
					)
				));
				exit;
			}

			$is_iniciada					= true;
			$is_aberto						= 1;
			//$negociacao 					= tdc::d('td_ecommerce_negociacao',tdc::f('oportunidade','=',$oportunidade->id))[0];
			$negociacao 					= tdc::p('td_ecommerce_negociacao');
			$negociacao->isaberto			= $is_aberto;
			$negociacao->is_iniciada		= $is_iniciada;
			$negociacao->proposta			= $oportunidade->proposta;
			//$negociacao->armazenar();

			//$negociacao 					= tdc::p('td_ecommerce_negociacao');
			$negociacao->pedido				= $pedido->id;
			$negociacao->cliente 			= $pedido->cliente;
			$negociacao->loja 				= $loja['id'];
			$negociacao->oportunidade		= $oportunidade->id;
			//$negociacao->proposta			= $oportunidade->proposta;
			//$negociacao->isaberto			= $is_aberto;
			//$negociacao->is_iniciada		= $is_iniciada;
			$negociacao->datahoraabertura	= date("Y-m-d H:i:s");
			$negociacao->armazenar();

			$oportunidade->negociacao		= $negociacao->id;
			$oportunidade->armazenar();

			$transacao						= tdc::p('td_carteiradigital_transacao',2);
			$valor_transacao				= (float)$transacao->pontos * (float)tdc::p('td_ecommerce_pontuacaocotacao',1)->valor;
            $movimentacao                   = tdc::p('td_carteiradigital_movimentacao');
            $movimentacao->loja      		= $loja['id'];
            $movimentacao->valor            = $valor_transacao;
            $movimentacao->transacao     	= $transacao->id;
            $movimentacao->datahora         = date('Y-m-d H:i:s');
            $movimentacao->is_finalizada    = true;
            $movimentacao->armazenar();

			// Retorno para armazenar os dados no RealTime Database
			$data_realtime				= array (
				'negociacao'			=> tdc::pa('td_ecommerce_negociacao',$negociacao->id),
				'oportunidade'			=> tdc::pa('td_ecommerce_oportunidade',$oportunidade->id),
				'categoria'				=> $categoria,
				'loja'					=> $loja,
				'chat_msg_no_readed'	=> 1, // Interação iniciada pelo usuário
				'is_aberto'				=> $is_aberto,
				'produto'				=> $produto,
				'cliente'				=> $cliente,
				'pedido'				=> tdc::pa('td_ecommerce_pedido',$pedido->id)
			);

			echo json_encode(array(
				'status'			=> 'success',
				'is_iniciada'		=> $is_iniciada,
				'valor_transacao' 	=> $valor_transacao,
				'pontos_transacao'	=> $transacao->pontos,
				'realtime'			=> $data_realtime
			));
		break;
		case 'aberta':
			$negociacoes 	= [];
			$loja			= isset($dados["loja"])?(int)$dados["loja"]:0;
			$where 			= ' AND n.loja = ' . $loja;

			$sql = "
				SELECT 
					n.id,
					p.datahoraenvio,
					n.loja,
					i.descricao,
					p.observacao,
					n.pedido,
					p.cliente cliente_id,
					i.produto produto_id,
					n.id negociacao_id,
					n.oportunidade
				FROM td_ecommerce_pedido p
				INNER JOIN td_ecommerce_pedidoitem i ON p.id = i.pedido
				INNER JOIN td_ecommerce_produto m ON m.id = i.produto
				INNER JOIN td_ecommerce_negociacao n ON n.pedido = p.id AND n.cliente = p.cliente
				WHERE n.isaberto = 1
				{$where};
			";
			$query = Transacao::Get()->query($sql);
			while($linha = $query->fetch()){
				$loja 			= tdc::p("td_ecommerce_loja",$linha["loja"]);
				$localizacao 	= isset(tdc::d("vw_localidade_loja")[0]->localidade)?tdc::d("vw_localidade_loja")[0]:'';
				$cliente		= tdc::p('td_ecommerce_cliente',$linha['cliente_id']);
				$produto		= tdc::p('td_ecommerce_produto',$linha['produto_id']);

				$loja_entidade_id		= getEntidadeId('ecommerce_loja');
				$produto_entidade_id	= getEntidadeId('ecommerce_produto');
				$usuario_entidad_id 	= getEntidadeId('usuario');
				$usuario 				= getListaRegFilhoObject(
					getEntidadeId("ecommerce_cliente"),
					$usuario_entidad_id,
					$cliente->id
				);
				
				$usuario_id 					= $usuario[0]->id;
				array_push($negociacoes,array(
					'id' 			=> completaString($linha["id"],3),
					'pedido'		=> tdc::pa('td_ecommerce_pedido',$linha["pedido"]),
					'data' 			=> datetimeToMysqlFormat($linha["datahoraenvio"],true),
					'loja'			=> tdc::pa('td_ecommerce_loja',$loja->id),
					'observacao'	=> $linha["observacao"],
					'status'		=> 'negociacao-open',
					'cliente'		=> tdc::pa('td_ecommerce_cliente',$cliente->id),
					'produto'		=> tdc::pa('td_ecommerce_produto',$produto->id),					
					'negociacao'	=> tdc::pa('td_ecommerce_negociacao',$linha['negociacao_id']),
					'oportunidade'	=> tdc::pa('td_ecommerce_oportunidade',$linha['oportunidade']),
					'usuario'		=> tdc::pa('td_usuario',$usuario_id),
					'localizacao'	=> $localizacao,
					'categoria'		=> tdc::pa('td_ecommerce_categoria',$produto->categoria)
				));
			}
			echo json_encode($negociacoes);
		break;
		case 'pedido':
			$negociacoes 		= [];
			$pedido				= isset($dados["pedido"])?(int)$dados["pedido"]:0;
			$loja_entidade_id	= getEntidadeId('ecommerce_loja');
			$sql = "
				SELECT 
					p.id pedido,
					p.datahoraenvio,
					ifnull(p.loja,0) loja,
					i.descricao,
					p.observacao,
					p.cliente cliente
				FROM td_ecommerce_pedido p
				INNER JOIN td_ecommerce_pedidoitem i ON p.id = i.pedido
				INNER JOIN td_ecommerce_produto m ON m.id = i.produto
				#INNER JOIN ecommerce_negociacao n ON n.pedido = p.id AND n.cliente = p.cliente
				WHERE p.id = {$pedido};
			";
			$query = Transacao::Get()->query($sql);
			while($linha = $query->fetch()){
				$loja_id		= $linha['loja'];
				$loja 			= tdc::pa('td_ecommerce_loja',$loja_id);
				$localizacao 	= isset(tdc::d("vw_localidade_loja")[0]) ? tdc::d("vw_localidade_loja")[0] : '';
				$cliente		= tdc::da('td_ecommerce_cliente',tdc::f('id','=',$linha['cliente']));
				
				$filename_logo_loja				= URL_CURRENT_FILE . 'logo-'. $loja_entidade_id . '-' . $loja_id . '.jpg';

				if (!file_exists(PATH_CURRENT_FILE . $filename_logo_loja)){
					$usuario				= tdc::d('td_usuario',tdc::f('email','=',$cliente[0]['email']));
					$filename_logo_loja		= 'fotoperfil-'. $usuario[0]->getID() . '-' . $usuario[0]->id . ".jpg";
				}

				if (sizeof($loja) <= 0){
					$loja['id'] 			= $cliente[0]['id'];
					$loja['nomefantasia']	= $cliente[0]['nome'];
				}

				$loja['url_logo']				= URL_CURRENT_FILE . $filename_logo_loja;
				array_push($negociacoes,array(
					'id' 			=> $linha["pedido"],
					'data' 			=> $linha["datahoraenvio"],
					'loja' 			=> $loja,
					'cliente'		=> $cliente[0], 
					'localizacao' 	=> isset($localizacao->localidade) ? $localizacao->localidade : '',
					'produto' 		=> $linha["observacao"],
					'status'		=> 'negociacao-open'
				));
			}
			echo json_encode($negociacoes);
		break;
		case 'finalizar':
			$negociacao 			= tdc::p('td_ecommerce_negociacao',$dados['negociacao_id']);
			$negociacao->isaberto 	= 0;
			$negociacao->salvar();
		break;
	}