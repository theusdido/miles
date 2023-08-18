<?php
	switch($op){
		case 'salvar':
			// Nome real do arquivo
			$filename							= isset($dados['banner'])?$dados['banner']:'';
			$hash								= $dados['hash'];
			$link_externo						= $_url_root . 'view/' . $hash;

			// Propaganda
			$propaganda							= tdc::p('td_ecommerce_propaganda');
			$propaganda->descricao				= $dados['descricao'];
			$propaganda->datahorainicial		= $dados['data_inicial'];
			$propaganda->datahorafinal			= $dados['data_final'];
			$propaganda->linkexterno			= $link_externo;
			$propaganda->loja					= $dados['loja'];
			$propaganda->banner					= $filename;
			$propaganda->hash					= $hash;
			$propaganda->armazenar();

			if ($filename != ''){
				$path_temp	= PATH_CURRENT_FILE_TEMP . $filename;
				$path		= PATH_CURRENT_FILE . 'banner-'. getEntidadeId('td_ecommerce_propaganda') . '-' . $propaganda->id . "." . getExtensao($filename);
				
				if (file_exists($path_temp)){
					copy($path_temp, $path);
					unlink($path_temp);
				}
			}

			$retorno = array(
				"id"		=> $propaganda->id,
				"status" 	=> 0,
				"msg" 		=> "Salvo com Sucesso"
			);
			echo json_encode($retorno);
		break;
		case 'disponiveis':
			$retorno = array();
			$_filtro = tdc::f();
			$_filtro->isFalse('is_encerrado');
			$_filtro->setPropriedade('order','id DESC');
			//$_filtro->addFiltro("DATE_FORMAT(datahorainicial,'%Y-%m-%d %H:%i:%s')",'>=',"DATE_FORMAT(NOW(),'".date('Y-m-d H:i:s')."')");
			//$_filtro->addFiltro("DATE_FORMAT(datahorafinal,'%Y-%m-%d %H:%i:%s')",'<=',"DATE_FORMAT(NOW(),'".date('Y-m-d H:i:s')."')");
			//echo $_filtro->dump();
			//exit;
			foreach(tdc::da('td_ecommerce_propaganda',$_filtro) as $propaganda){
				$_data_inicial 	= $propaganda['datahorainicial'];
				$_data_final 	= $propaganda['datahorafinal'];
				if ($_data_final >= $_data_inicial){
					array_push($retorno, array(
						'propaganda'	=> $propaganda,
						'loja'			=> tdc::pa('td_ecommerce_loja',$propaganda['loja']),
						'hash'			=> $propaganda['hash']
					));
				}
			}
			echo json_encode($retorno);
		break;
		case 'hash':
			$retorno = array();
			foreach(tdc::da('td_ecommerce_propaganda',tdc::f()->addFiltro('hash','=',$dados['hash'])) as $propaganda){
				array_push($retorno, array(
					'propaganda'	=> $propaganda,
					'loja'			=> tdc::pa('td_ecommerce_loja',$propaganda['loja']),
					'hash'			=> $propaganda['hash']
				));
			}
			echo json_encode($retorno);
		break;
		case 'visto':
			$usuario 		= $dados['usuario'];
			$propaganda		= $dados['propaganda'];

			$criterio		= tdc::f();
			$criterio->addFiltro('propaganda','=',$propaganda);
			$criterio->addFiltro('usuario','=',$usuario);
			$criterio->addFiltro('is_visto','=',1);

			$count 	= tdc::c('td_ecommerce_propagandavisualizacao',$criterio);
			if ($count > 0){
				$visto				= false;
			}else{
				$visto				= true;
				$pv					= tdc::p('td_ecommerce_propagandavisualizacao');
				$pv->propaganda		= $propaganda;
				$pv->usuario		= $usuario;
				$pv->is_visto		= 1;
				$pv->datahora		= date('Y-m-d H:i:s');
				$pv->armazenar();				
			}

			$transacao						= tdc::p('td_carteiradigital_transacao',3);
			$valor_transacao				= (float)$transacao->pontos * (float)tdc::p('td_ecommerce_pontuacaocotacao',1)->valor;

            $movimentacao                   = tdc::p('td_carteiradigital_movimentacao');
            $movimentacao->usuario      	= $usuario;
            $movimentacao->valor            = $valor_transacao;
            $movimentacao->transacao     	= $transacao->id;
            $movimentacao->datahora         = date('Y-m-d H:i:s');
            $movimentacao->is_finalizada    = true;
            $movimentacao->armazenar();

			echo json_encode(array(
				'visto'				=> $visto,
				'valor_transacao' 	=> $valor_transacao,
				'pontos_transacao'	=> $transacao->pontos
			));
		break;
		case 'loja':
			$_filtro = tdc::f();
			$_filtro->addFiltro('loja','=',$dados['loja']);
			$_filtro->isFalse('is_encerrado');
			$_filtro->setPropriedade('order','id DESC');			
			tdc::wj( tdc::da('td_ecommerce_propaganda',$_filtro) );
		break;
		case 'encerrar':
			$_propaganda = tdc::p('td_ecommerce_propaganda', $dados['propaganda']);
			$_propaganda->is_encerrado = true;
			$_propaganda->armazenar();
		break;
	}