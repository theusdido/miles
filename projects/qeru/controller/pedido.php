<?php
	switch($op)
	{
		# -------------------------------------
		# case: Salvar
		# -------------------------------------
		case 'salvar':
			try
			{				
				$params 			= (object)$dados["pedido"];
				$categoria 			= tdc::p("td_ecommerce_categoria",$params->categoria);
				$atributos 			= array();

				$subcategoria_id	= 0;
				$subcategoria_desc	= '';
				if (isset($params->subcategoria['is_manual'])){
					$subcategoria				= tdc::p("td_ecommerce_subcategoria");
					$subcategoria->descricao	= $params->subcategoria['descricao'];
					$subcategoria->is_manual	= true;
					$subcategoria->armazenar();

					$subcategoria_id	= $subcategoria->id;
					$subcategoria_desc	= $subcategoria->descricao;					
				}else{
					$subcategoria_id	= $params->subcategoria['id'];
					$subcategoria_desc	= $params->subcategoria['descricao'];
				}

				foreach($params->atributos as $opcoes){
					$valoresopcaoatributo 	= array();
					$atributodescricao 		= '';
					foreach($opcoes as $opcao){
						$atributoproduto 	= tdc::p("td_ecommerce_atributoproduto",$opcao['id']['atributo']);
						$atributodescricao	= $atributoproduto->descricao;
						array_push($valoresopcaoatributo,$opcao['text']);
					}

					array_push($atributos,	
						$atributodescricao . ": " . implode(',',$valoresopcaoatributo) . ". "
					);
				}

				// Monta a descrição do item baseados na descrição dos atributos
				$descricaoItem = implode(" ",$atributos);

				// Nome do produto
				#$produto_nome = isset($params->produto['descricao']) ? $params->produto['descricao'] : $subcategoria_desc;
				$produto_nome = $params->observacao;

				// Cadastra o produto
				$produto 					= tdc::p("td_ecommerce_produto");
				$produto->nome 				= $produto_nome;
				$produto->descricao 		= $descricaoItem;
				$produto->categoria			= $categoria->id;
				$produto->subcategoria		= $subcategoria_id;
				$produto->inativo			= 0;
				$produto->exibirpreco		= 1;
				$produto->exibirhome		= 0;
				$produto->referencia		= '';
				$produto->destaque			= 0;
				$produto->armazenar();

				// Grava a imagem principal do produto
				if (isset($dados['anexos'][0])){
					$path_temp					= explode('/',$dados['anexos'][0]);
					$filename_timestamp			= explode('?',end($path_temp));
					$filename					= $filename_timestamp[0];
					$path_temp_filename			= PATH_CURRENT_FILE_TEMP . $filename;
					$path_filename				= PATH_CURRENT_FILE . 'imagemprincipal-' . getEntidadeId('ecommerce_produto'). '-' . $produto->id . '.' . getExtensao($filename);

					if (file_exists($path_temp_filename)){
						copy($path_temp_filename, $path_filename);
					}
					
					$produto_img					= tdc::p("td_ecommerce_produto",$produto->id);
					$produto_img->imagemprincipal	= $filename;
					$produto_img->armazenar();					
				}

				// Pedido
				$pedido 					= tdc::p("td_ecommerce_pedido");
				$pedido->cliente  			= $params->cliente;
				$pedido->datahoraenvio 		= date("Y-m-d H:i:s");
				$pedido->isfinalizado 		= false;
				$pedido->observacao			= $params->observacao;
				$pedido->inativo			= 0;
				$pedido->datahoraretorno	= '0000-00-00 00:00:00';
				$pedido->carrinhocompras	= 0;
				$pedido->status				= 0;
				$pedido->valorminimo		= $params->valorminimo;
				$pedido->valormaximo		= $params->valormaximo;
				$pedido->modalidade			= $params->modalidade;
				if (!$pedido->armazenar()){
					tdc::wj(array(
						"status" => 5,
						"msg" => 'Erro ao cadastrar pedido'
					));
					Transacao::rollback();
					exit;
				}

				// Item do Pedido
				$item 				= tdc::p("td_ecommerce_pedidoitem");
				$item->pedido 		= $pedido->id;
				$item->produto		= $produto->id;
				$item->qtde			= 1;
				$item->valor		= 0;
				$item->descricao	= $subcategoria_desc . " - " . $descricaoItem;
				$item->inativo		= 0;
				$item->armazenar();

				// Anexos do Pedido
				$anexos 			= $dados["anexos"];
				$anexo_id			= getProxId('td_ecommerce_pedidoanexos');
				//var_dump($anexo_id);
				foreach($anexos as $a){

					$filename				= explode('/',$a);
					$_filename				= end($filename);
										
					$anexo 				= tdc::p("td_ecommerce_pedidoanexos");
					$anexo->id			= $anexo_id;
					$anexo->pedido 		= $pedido->id;
					$anexo->arquivo		= $_filename;
					$anexo->armazenar();

					//var_dump($anexo_id);
					$path_temp_filename		= PATH_CURRENT_FILE_TEMP . $_filename;
					$path_filename			= PATH_CURRENT_FILE . 'arquivo-' . getEntidadeId('ecommerce_pedidoanexos'). '-' . $anexo_id . '.' . getExtensao($_filename);

					//var_dump($path_temp_filename);
					//var_dump($path_filename);

					if (file_exists($path_temp_filename)){
						copy($path_temp_filename, $path_filename);
						unlink($path_temp_filename);
					}
					
					$anexo_id++;
				}

				// Opção do cliente
				foreach($params->atributos as $opcoes){
					foreach($opcoes as $opcao){
						$clienteopcao 					= tdc::p("td_ecommerce_clienteopcao");
						$clienteopcao->pedidoitem		= $item->id;
						$clienteopcao->atributo			= $opcao['id']['atributo'];
						$clienteopcao->atributoopcao	= $opcao['id']['opcao'];
						$clienteopcao->armazenar();
					}
				}

				$usuario_entidade_id 	= getEntidadeId('usuario');
				$loja_entidade_id		= getEntidadeId('ecommerce_loja');
				$categoria_entidade_id	= getEntidadeId('ecommerce_categoria');
				$oportunidades 			= [];
				$cliente				= tdc::pa('td_ecommerce_cliente',$pedido->cliente);
				$pedido					= tdc::pa('td_ecommerce_pedido',$pedido->id);
				$produto				= tdc::pa('td_ecommerce_produto',$produto->id);

				$pedido_id				= $pedido['id'];

				$sql = "
					SELECT
						u.id userid,
						u.email,
						el.nomefantasia,
						el.id loja_id
					FROM td_lista l
					INNER JOIN td_ecommerce_loja el ON el.id = l.regpai
					INNER JOIN td_usuario u ON u.id = l.regfilho
					WHERE l.entidadepai = {$loja_entidade_id} AND l.entidadefilho = {$usuario_entidade_id}
					AND EXISTS (
						SELECT 1
						FROM td_lista lis
						WHERE lis.entidadepai = {$loja_entidade_id}
						AND lis.entidadefilho = {$categoria_entidade_id}
						AND lis.regpai = l.regpai
						AND	lis.regfilho = (
							SELECT pr.categoria FROM td_ecommerce_pedido p
							INNER JOIN td_ecommerce_pedidoitem i ON i.pedido = p.id
							INNER JOIN td_ecommerce_produto pr ON pr.id = i.produto
							LIMIT 1
						)
					)
					GROUP BY u.email;
				";
				$query = $conn->query($sql);
				while ($linha = $query->fetch()){

					/*
					*	Envia E-Mails de Oportunidades para os Lojistas
					*/

					$loja 					= tdc::pa('td_ecommerce_loja',$linha['loja_id']);
					$loja_id				= $linha['loja_id'];
					$loja_email 			= $linha['email'];
					$loja_nomefantasia 		= $linha['nomefantasia'];
					$loja_userid			= $linha['userid'];

					// Verifica se o comprador que está fazendo o pedido tem loja na mesma categoria
					if ($loja_userid == $dados['userid']) continue;

					# /-- E-Mail de Oportunidades
					$mail 			= new Enviar();
					$mail->debug 	= 0;
					$mail->subject 	= "Nova Oportunidade!";
					$mail->AddAddress("{$loja_email}","{$loja_nomefantasia}");
					$mail->setHeader(
						$loja_nomefantasia,
						"Você recebeu uma oportunidade",
						"Clique no link abaixo para voltar ao site."
					);
					$mail->setBody("
						<a 
							href='{$_url_root}/dashboard'
							target='_blank'
							style='background-color:#f9503f;color:#FFF;width:100px;padding:10px;'
						>Visualizar Oportunidade</a>
						<br/>
					");
					if($mail->Send()){

						// Registra a oportunidade
						$oportunidade 			= tdc::p('td_ecommerce_oportunidade');
						$oportunidade->pedido	= $pedido_id;
						$oportunidade->loja		= $loja_id;
						$oportunidade->data		= date('Y-m-d');
						$oportunidade->hora		= date('H:i:s');
						$oportunidade->salvar();

						$oportunidade_array['oportunidade']	= tdc::pa('td_ecommerce_oportunidade',$oportunidade->id);
						$oportunidade_array['pedido'] 		= $pedido;
						$oportunidade_array['cliente']		= $cliente;
						$oportunidade_array['loja']			= $loja;
						$oportunidade_array['produto']		= $produto;
						array_push($oportunidades,$oportunidade_array);
					}else{
						$retorno = array(
							"status" => 3,
							"msg" => 'Erro ao enviar E-Mail. Motivo: '.$mail->ErrorInfo
						);
					}
				}
				$retorno = array(
					"status" 	=> 1,
					"msg" 		=> "<b>Parabéns!</b> Sua desejo de compra foi enviada com sucesso",
					"data"		=> array(
						'pedido'			=> $pedido,
						'datahora_criacao' 	=> dateToMysqlFormat($pedido['datahoraenvio'],true),
						'cliente'			=> $cliente,
						'produto'			=> $produto,
						'oportunidades'		=> $oportunidades,
						'anexos'			=> tdc::da("td_ecommerce_pedidoanexos",$pedido['id'])
					)
				);
				Transacao::Commit();
			}catch(Throwable $t){
				Transacao::Rollback();
				$retorno = array(
					"status" => 2,
					"msg" => $t->getMessage()
				);
			}finally{
				echo json_encode($retorno);
			}
		break;
		# -------------------------------------
		# case: Listar
		# -------------------------------------		
		case 'listar':
			$propostas 	= [];
			$where		= '';
			$anexos		= [];
			if (isset($dados["pedido"])){
				$pedido		= $dados["pedido"];
				$where 		= "WHERE p.id = {$pedido}";

				// Anexos
				$anexos = tdc::da("td_ecommerce_pedidoanexos",tdc::f("pedido","=",$pedido));				
			}
			
			$categorias = isset($dados["categorias"]) ? $dados["categorias"] : null;
			if ($categorias != null){
				if (gettype($categorias) == 'array'){
					$categorias = implode(",",$categorias);
				}else{
					$categorias = strpos(",",$categorias) > -1?implode(",",$categorias):$categorias;
				}

				if ($categorias != ''){
					$where 		= "WHERE m.categoria IN ({$categorias})";
				}else{
					echo json_encode($propostas);
					exit;
				}
			}

			// Carrega os pedidos que não tem proposta
			$loja	= isset($dados['loja']) ? $dados['loja'] : 0;			
			if ((int)$loja > 0 && $categorias != null){
				$loja 			= $dados['loja'];
				$operador_not 	= $categorias!= null ? 'NOT' : '';

				$where .= " AND {$operador_not} EXISTS (
					SELECT 1 FROM td_ecommerce_proposta proposta WHERE proposta.pedido = p.id AND proposta.loja = {$loja}
				) ";
			}

			$sql = "
				SELECT 
					p.id,
					DATE_FORMAT(p.datahoraenvio,'%d/%m/%Y') datahoraenvio,
					p.cliente,
					i.descricao,
					p.observacao,
					m.categoria,
					ifnull(m.subcategoria,0) subcategoria,
					i.id item,
					i.produto produto_id,
					m.imagemprincipal,
					p.valormaximo,
					p.modalidade
				FROM td_ecommerce_pedido p
				INNER JOIN td_ecommerce_pedidoitem i ON p.id = i.pedido
				INNER JOIN td_ecommerce_produto m ON m.id = i.produto
				{$where}
				ORDER BY id DESC
				LIMIT 6
				;
			";
			$query = Transacao::Get()->query($sql);
			while($linha = $query->fetch()){				
				$cliente 		= tdc::p("td_ecommerce_cliente",$linha["cliente"]);
				$localizacao 	= isset(tdc::d("vw_localidade_cliente")[0]) ? tdc::d("vw_localidade_cliente")[0] : '';
				$item			= $linha["item"];
				
				$atributos 		= array();
				$atributo 		= 0;

				// Atributos escolhidos pelo cliente
				foreach(tdc::d('td_ecommerce_clienteopcao',tdc::f('pedidoitem','=',$item)) as $opcao){
					if ($atributo != $opcao->atributo){
						$atributo 	= $opcao->atributo;
						array_push($atributos,array(
							'nome' 		=> tdc::p('td_ecommerce_atributoproduto',$atributo)->descricao,
							'valores'	=> getOpcoesCliente($item,$atributo)
						));
					}
				}

				$produto_entidade_id	= getEntidadeId('ecommerce_produto');
				$usuario_entidad_id 	= getEntidadeId('td_usuario');
				$usuario 				= getListaRegFilhoObject(
					getEntidadeId("ecommerce_cliente"),
					$usuario_entidad_id,
					$cliente->id
				);

				$usuario_id 					= $usuario[0]->id;
				$filename_fotoperfil 			= 'fotoperfil-'. $usuario_entidad_id . '-' . $usuario_id . '.jpg';
				$path_fotoperfil 				= PATH_CURRENT_FILE . $filename_fotoperfil;
				$fotoperfil						= file_exists($path_fotoperfil) ? URL_CURRENT_FILE . $filename_fotoperfil : URL_CURRENT_IMG . 'usuario.png';
				$url_produto_imagemprincipal	= URL_CURRENT_FILE . 'imagemprincipal-' . $produto_entidade_id . '-' . $linha['produto_id'] . '.jpg';

				array_push($propostas,array(
					'id' 							=> $linha["id"],
					'data' 							=> $linha["datahoraenvio"],
					'cliente_id'					=> $cliente->id,
					'cliente_nome'					=> $cliente->nome,
					'cliente_fotoperfil'			=> $fotoperfil,
					'localizacao' 					=> isset($localizacao->localidade) ? $localizacao->localidade : '',
					'descricao'						=> $linha["observacao"],
					'status'						=> 'proposta-open',
					'anexos'						=> $anexos,
					'categoria'						=> tdc::da('td_ecommerce_categoria',tdc::f('id','=',$linha['categoria']))[0],
					'subcategoria'					=> tdc::p('td_ecommerce_subcategoria',$linha['subcategoria'])->descricao,
					'atributos'						=> $atributos,
					'produto'						=> tdc::da('td_ecommerce_produto',tdc::f('id','=',$linha['produto_id']))[0],
					'produto_imagemprincipal_src' 	=> $url_produto_imagemprincipal,
					'loja'							=> tdc::pa('td_ecommerce_loja',$loja),
					'oportunidade'					=> tdc::da('td_ecommerce_oportunidade',tdc::f('pedido','=',$linha['id']))[0],
					'formated_valormaximo'			=> moneyToFloat($linha['valormaximo'],true),
					'modalidade'					=> tdc::pa('td_ecommerce_modalidade',$linha['modalidade'])
				));
			}
			echo json_encode($propostas);
		break;
		# -------------------------------------
		# case: User
		# -------------------------------------		
		case 'user':
			$propostas 	= [];
			$where		= '';
			$anexos		= [];
			if (isset($dados["pedido"])){
				$pedido		= $dados["pedido"];
				$where 		= "WHERE p.id = {$pedido}";

				// Anexos
				$anexos = tdc::da("td_ecommerce_pedidoanexos",tdc::f("pedido","=",$pedido));
			}
			
			$categorias = isset($dados["categorias"]) ? $dados["categorias"] : null;
			if ($categorias != null){
				if (gettype($categorias) == 'array'){
					$categorias = implode(",",$categorias);
				}else{
					$categorias = strpos(",",$categorias) > -1?implode(",",$categorias):$categorias;
				}

				if ($categorias != ''){
					$where 		= "WHERE m.categoria IN ({$categorias})";
				}else{
					echo json_encode($propostas);
					exit;
				}
			}

			// Carrega os pedidos que não tem proposta
			$loja	= isset($dados['loja']) ? $dados['loja'] : 0;
			if ((int)$loja > 0 && $categorias != null){
				$loja 			= $dados['loja'];
				$operador_not 	= $categorias!= null ? 'NOT' : '';

				$where .= " AND {$operador_not} EXISTS (
					SELECT 1 FROM td_ecommerce_proposta proposta WHERE proposta.pedido = p.id AND proposta.loja = {$loja}
				) ";
			}

			$where .= " AND cliente = " . $dados['comprador'];
			$where .= " AND p.inativo <> true ";
			$where .= " AND p.isfinalizado <> true ";
			$sql = "
				SELECT 
					p.id,
					DATE_FORMAT(p.datahoraenvio,'%d/%m/%Y %H:%i') datahoraenvio,
					p.cliente,
					i.descricao,
					p.observacao,
					m.categoria,
					ifnull(m.subcategoria,0) subcategoria,
					i.id item,
					i.produto produto_id,
					m.imagemprincipal,
					p.modalidade
				FROM td_ecommerce_pedido p
				INNER JOIN td_ecommerce_pedidoitem i ON p.id = i.pedido
				INNER JOIN td_ecommerce_produto m ON m.id = i.produto
				{$where}
				ORDER BY id DESC
				LIMIT 6;
			";
			$query = Transacao::Get()->query($sql);
			while($linha = $query->fetch()){				
				$cliente 		= tdc::p("td_ecommerce_cliente",$linha["cliente"]);
				$localizacao 	= isset(tdc::d("vw_localidade_cliente")[0]) ? tdc::d("vw_localidade_cliente")[0] : '';
				$item			= $linha["item"];
				
				$atributos 		= array();
				$atributo 		= 0;

				// Atributos escolhidos pelo cliente
				foreach(tdc::d('td_ecommerce_clienteopcao',tdc::f('pedidoitem','=',$item)) as $opcao){
					if ($atributo != $opcao->atributo){
						$atributo 	= $opcao->atributo;
						array_push($atributos,array(
							'nome' 		=> tdc::p('td_ecommerce_atributoproduto',$atributo)->descricao,
							'valores'	=> getOpcoesCliente($item,$atributo)
						));
					}
				}

				$produto_entidade_id	= getEntidadeId('ecommerce_produto');
				$usuario_entidad_id 	= getEntidadeId('usuario');
				$usuario 				= getListaRegFilhoObject(
					getEntidadeId("ecommerce_cliente"),
					$usuario_entidad_id,
					$cliente->id
				);

				$usuario_id 					= URL_CURRENT_FILE . $usuario[0]->id;
				$filename_fotoperfil 			= 'fotoperfil-'. $usuario_entidad_id . '-' . $usuario_id . '.jpg';
				$path_fotoperfil 				= PATH_CURRENT_FILE . $filename_fotoperfil;
				$fotoperfil						= file_exists($path_fotoperfil) ? URL_CURRENT_FILE . $filename_fotoperfil : URL_CURRENT_IMG . 'usuario.png';
				$url_produto_imagemprincipal	= URL_CURRENT_FILE . 'imagemprincipal-' . $produto_entidade_id . '-' . $linha['produto_id'] . '.jpg';
				
				if (!file_exists($url_produto_imagemprincipal))
				{
					//$url_produto_imagemprincipal = './assets/img/semimagem.jpg';
				}

				$oportunidade		= tdc::da('td_ecommerce_oportunidade',tdc::f('pedido','=',$linha["id"]));
				if (isset($oportunidades[0])){
					$oportunidade		= $oportunidade[0];
					$oportunidade_id	= $oportunidade[0]['id'];
				}else{
					$oportunidade		= array('id' => 0 , 'pedido' => array('id' => $linha["id"]));
					$oportunidade_id	= 0;
				}				
				
				array_push($propostas,array(
					'id' 							=> $linha["id"],
					'data' 							=> $linha["datahoraenvio"],
					'td_cliente'					=> $cliente->id,					
					'cliente' 						=> $cliente->nome,
					'cliente_fotoperfil'			=> $fotoperfil,					
					'localizacao' 					=> isset($localizacao->localidade) ? $localizacao->localidade : '',
					'descricao'						=> $linha["observacao"],
					'status'						=> 'proposta-open',
					'anexos'						=> tdc::da("td_ecommerce_pedidoanexos",tdc::f("pedido","=",$linha['id'])),
					'categoria'						=> tdc::da('td_ecommerce_categoria',tdc::f('id','=',$linha['categoria'])),
					'subcategoria'					=> tdc::p('td_ecommerce_subcategoria',$linha['subcategoria'])->descricao,
					'atributos'						=> $atributos,
					'produto'						=> tdc::da('td_ecommerce_produto',tdc::f('id','=',$linha['produto_id']))[0],
					'produto_imagemprincipal_src' 	=> $url_produto_imagemprincipal,
					'oportunidade'					=> $oportunidade,
					'oportunidade_id'				=> $oportunidade_id,
					'propostas'						=> tdc::da('td_ecommerce_proposta',tdc::f('pedido','=',$linha['id'])),
					'modalidade'					=> tdc::pa('td_ecommerce_modalidade',$linha['modalidade'])
				));
			}
			echo json_encode($propostas);
		break;

		case 'finalizar':
			$_pedido 				= tdc::p('td_ecommerce_pedido',$dados['pedido']);
			$_pedido->isfinalizado 	= true;
			$_pedido->armazenar();

			$_propostas = tdc::d('td_ecommerce_proposta',tdc::f('pedido','=',$_pedido->id));
			foreach($_propostas as $p){
				$p->is_finalizada 	= true;
				$p->armazenar();
			}

			$_negociacoes_id 	= array();
			$_negociacoes 		= tdc::d('td_ecommerce_negociacao',tdc::f('pedido','=',$_pedido->id));
			foreach($_negociacoes as $n){
				array_push($_negociacoes_id,array(
					'id' 	=> $n->id,
					'loja'	 => $n->loja
				));
				$n->isaberto 	= false;
				$n->armazenar();
			}

			// Retorna as negociações
			tdc::wj($_negociacoes_id);
		break;

		case 'zerar':
			$sql = "
			SET SQL_SAFE_UPDATES = 0;

			# Variáveis
			SET @produto			= getEntidadeId('td_ecommerce_produto');
			SET @lojista			= getEntidadeId('td_ecommerce_lojista');
			SET @loja				= getEntidadeId('td_ecommerce_loja');
			SET @endereco			= getEntidadeId('td_ecommerce_endereco');
			SET @usuario			= getEntidadeId('td_usuario');
			SET @cliente			= getEntidadeId('td_ecommerce_cliente');
			SET @sbatributo			= getEntidadeId('td_ecommerce_subcategoriaatributo');
			
			# Produto 
			DELETE FROM td_ecommerce_produto;
			
			# Pedido
			DELETE FROM td_ecommerce_pedido;
			DELETE FROM td_ecommerce_pedidoitem;
			
			
			# Propostas
			DELETE FROM td_ecommerce_proposta;
			
			# Negociações
			DELETE FROM td_ecommerce_negociacao;
			
			# Anexos
			DELETE FROM td_ecommerce_pedidoanexos;
			
			# Pontuação
			DELETE FROM td_ecommerce_pontuacao;
			
			# Movimentação
			DELETE FROM td_carteiradigital_movimentacao;
			
			# Oportunidade
			DELETE FROM td_ecommerce_oportunidade;
			
			# Produto Foto
			DELETE FROM td_ecommerce_produtofoto;			
			";

			Transacao::Get()->exec($sql);
		break;
	}

	function getOpcoesCliente($item,$atributo){
		$filtro = tdc::f();
		$filtro->addFiltro('pedidoitem','=',$item);
		$filtro->addFiltro('atributo','=',$atributo);
		$opcoes = array();
		foreach(tdc::d('td_ecommerce_clienteopcao',$filtro) as $opcao){
			array_push($opcoes , tdc::p('td_ecommerce_atributoprodutoopcao',$opcao->atributoopcao)->descricao);
		}
		return $opcoes;
	}