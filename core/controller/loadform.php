<?php
	if ($conn = Transacao::get()){

		// Rever a variável $dadosParams
		$dadosParams 				= gettype(tdClass::Read("dados")) == 'string' ? json_decode(tdClass::Read("dados"),true) : tdClass::Read("dados");
		$rastrearrelacionamentos 	= tdc::r("rastrearrelacionamentos") != '' ? json_decode(tdc::r("rastrearrelacionamentos")) : false;
		$dados 						= array();

		// Rastreia os relacionamentos para a entidade principal
		if ($rastrearrelacionamentos){
			$dadosParams			= [];
			array_push($dadosParams,array(
				"entidade" 		=> tdc::r("entidadeprincipal"),
				"atributo" 		=> 'id',
				"valor" 		=> tdClass::Read("registroprincipal"),
				"tipoRel" 		=> 0,
				"entidadepai" 	=> 0
			));

			
			$dataset = tdc::d(RELACIONAMENTO,tdc::f("pai","=",tdc::r("entidadeprincipal")));
			foreach($dataset as $d){
				array_push($dadosParams,array(
					"entidade" 		=> (int)$d->filho,
					"atributo" 		=> $d->atributo!=0?tdc::a($d->atributo)->nome:0,
					"valor" 		=> tdClass::Read("registroprincipal"),
					"tipoRel" 		=> (int)$d->tipo,
					"entidadepai" 	=> (int)$d->pai
				));
			}

		}

		if ($dadosParams != false){
			foreach($dadosParams as $dado){
				if (is_numeric($dado["entidade"])){
					$entidadeID 	= (int)$dado["entidade"];
					$entidadeNome 	= tdc::e($entidadeID)->nome;

				}else{
					$entidadeID 	= tdClass::Criar("persistent",array($dado["entidade"]))->contexto->getId();
					$entidadeNome	= $dado["entidade"];
				}

				if (isset($dado["tipoRel"])){
					$tiporelacionamento = $dado["tipoRel"] == '' ? 0 : (int)$dado["tipoRel"];	
				}else{
					$tiporelacionamento = 0;
				}

				$valor 			= isset($dado["valor"]) ? $dado["valor"] : '';
				$atributoNome 	= isset($dado["atributo"]) ? $dado["atributo"] : '';

				if ($atributoNome != ''){
					$buscaPorValor 	= true;
					$whereBusca 	= ($valor!='') ? " WHERE {$atributoNome} = {$valor} " : '';
				}else{
					$buscaPorValor 	= false;
					$whereBusca 	= "";
				}

				if ($tiporelacionamento == 0 || $tiporelacionamento == 3 || $buscaPorValor){
					$sql = "SELECT * FROM {$entidadeNome} {$whereBusca}";
				}else{
					$entidadePai 	= isset($dado["entidadepai"]) ? $dado["entidadepai"] : 0;
					$sqlLista 		= tdClass::Criar("sqlcriterio");
					$sqlLista->addFiltro("entidadepai","=",$entidadePai);
					$sqlLista->addFiltro("entidadefilho","=",$entidadeID);

					if ($valor != '')
						$sqlLista->addFiltro("regpai","=",$valor);

					if ($tiporelacionamento == 1){
						$sqlLista->setPropriedade("order","ID DESC");
						$sqlLista->setPropriedade("limit",1);
					}

					$datasetLista = tdClass::Criar("repositorio",array(LISTA))->carregar($sqlLista);
					$IDs = "";
					foreach($datasetLista as $l){
						$IDs .= ($IDs=="")?$l->regfilho:"," . $l->regfilho;
					}
					if ($IDs == "") continue;
					$sql = "SELECT * FROM {$entidadeNome} WHERE id in ({$IDs})";				
				}
				try{
					$query = $conn->query($sql);	
				}catch(Exception $e){
					if (IS_SHOW_ERROR_MESSAGE){
						var_dump($tiporelacionamento);
						echo $sql;
						var_dump($conn->errorInfo());
					}
				}

				if ($query->rowcount() > 0){
					while ($linha = $query->fetch()){
						$sqlAttr 		= "SELECT id,nome,tipohtml FROM " . ATRIBUTO . " WHERE entidade = " . $entidadeID . " ORDER BY ordem ASC;";
						$queryAttr 		= $conn->query($sqlAttr);
						$dados_retorno 	= array();
						while ($linhaAttr = $queryAttr->fetch()){
							$valor			= $linha[$linhaAttr["nome"]];
							$dados_array = array(
								"atributo" 		=> $linhaAttr["nome"],
								"valor" 		=> tdc::utf8(getHTMLTipoFormato($linhaAttr["tipohtml"],$valor,$entidadeID,$linhaAttr["id"],tdClass::Read("registroprincipal"))),
								"valorreal" 	=> tdc::utf8($valor),
								"idatributo" 	=> $linhaAttr["id"]
							);
							array_push($dados_retorno,$dados_array);
						}
						$retorno["entidade"] 	= $entidadeID;
						$retorno["dados"] 		= $dados_retorno;
						$retorno["id"] 			= $linha["id"];
						$retorno["fp"]			= tdc::r("entidadeprincipal") == $entidadeID ? true : false;
						array_push($dados,$retorno);
					}	
				}else{
					$retorno["entidade"] 	= $entidadeID;
					$retorno["dados"] 		= "";
					$retorno["id"] 			= "";
					$retorno["fp"]			= true;
					array_push($dados,$retorno);
				}
			}
		}
		echo json_encode($dados);
	}
