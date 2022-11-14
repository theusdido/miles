<?php

	$mdmJSCompile = fopen(Asset::path('FILE_MDM_JS_COMPILE'),"w");

	// Entidades do Sistema
	fwrite($mdmJSCompile,'
		var td_entidade 		= [];
		var td_atributo 		= [];
		var td_relacionamento 	= [];
		var td_permissoes 		= [];
		var td_filtroatributo 	= [];
		var td_consulta 		= [];
		var td_relatorio 		= [];
		var td_status 			= [];
		var td_movimentacao 	= [];
		var formulario          = [];
	');
	
	$dataset 		= tdClass::Criar("repositorio",array(ENTIDADE))->carregar();
	if ($dataset){
		foreach ($dataset as $entidade){
			$filtro_atributo = tdc::f();
			$filtro_atributo->addFiltro('entidade','=',(int)$entidade->id);
			$filtro_atributo->setPropriedade('order','ordem ASC');

			// 
			$filtro_relacionamento	= tdc::f();
			$filtro_relacionamento->addFiltro("pai","=",$entidade->id);
			fwrite($mdmJSCompile,"
				td_entidade[{$entidade->id}] = {
					id:{$entidade->id},
					nome:'{$entidade->nome}',
					descricao:'{$entidade->descricao}',
					exibirmenuadministracao:'{$entidade->exibirmenuadministracao}',
					exibircabecalho:'{$entidade->exibircabecalho}',
					pai:'{$entidade->pai}',
					ncolunas:'{$entidade->ncolunas}',
					campodescchave:'{$entidade->campodescchave}',
					atributogeneralizacao:'{$entidade->atributogeneralizacao}',
					exibirlegenda:'{$entidade->exibirlegenda}',
					registrounico:{$entidade->registrounico},
					pacote:'{$entidade->pacote}',
					nomecompleto:'".(($entidade->pacote==""?"":$entidade->pacote."."))."{$entidade->nome}',
					atributos:".tdc::dj(ATRIBUTO,$filtro_atributo).",
					relacionamentos:".tdc::dj(RELACIONAMENTO,$filtro_relacionamento)."
				};
			");
		}
	}

	// Atributos do Sistema	
	$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar();	
	if ($dataset){
		foreach ($dataset as $atributo){
			fwrite($mdmJSCompile,"
				td_atributo[{$atributo->id}] = {
					id:{$atributo->id},
					entidade:'{$atributo->entidade}',
					nome:'{$atributo->nome}',
					descricao:'".$atributo->descricao."',
					tipo:'{$atributo->tipo}',
					tamanho:'{$atributo->tamanho}',
					omissao:'{$atributo->omissao}',
					collection:'{$atributo->collection}',
					atributos:'{$atributo->atributos}',
					nulo:'{$atributo->nulo}',
					indice:'{$atributo->indice}',
					autoincrement:'{$atributo->autoincrement}',
					comentario:'{$atributo->comentario}',
					exibirgradededados:'{$atributo->exibirgradededados}',
					chaveestrangeira:'{$atributo->chaveestrangeira}',
					tipohtml:'{$atributo->tipohtml}',
					dataretroativa:'{$atributo->dataretroativa}',
					ordem:'{$atributo->ordem}',
					readonly:'{$atributo->readonly}',
					inicializacao:'{$atributo->inicializacao}',
					exibirpesquisa:'{$atributo->exibirpesquisa}',
					tipoinicializacao:'{$atributo->tipoinicializacao}',
					atributodependencia:'{$atributo->atributodependencia}',
					labelzerocheckbox:'{$atributo->labelzerocheckbox}',
					labelumcheckbox:'{$atributo->labelumcheckbox}',
					criarsomatoriogradededados:'{$atributo->criarsomatoriogradededados}',
					naoexibircampo:'{$atributo->naoexibircampo}'
				};
			");		
		}
	}
	// Relacionamentos do Sistema	
	$dataset = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar();		
	if ($dataset){
		foreach ($dataset as $relacionamento){
			fwrite($mdmJSCompile,"
				td_relacionamento[{$relacionamento->id}] = {
					id:{$relacionamento->id},
					pai:'{$relacionamento->pai}',
					tipo:'{$relacionamento->tipo}',
					filho:'{$relacionamento->filho}',
					atributo:'{$relacionamento->atributo}',
					descricao:'{$relacionamento->descricao}',
					controller:'{$relacionamento->controller}',
					cardinalidade:'{$relacionamento->cardinalidade}'
				};
			");
		}
	}

	$sqlPermissoes = tdClass::Criar("sqlcriterio");
	$sqlPermissoes->addFiltro(USUARIO,"=",(isset(Session::Get()->userid)?Session::Get()->userid:0));
	$dataset = tdClass::Criar("repositorio",array(PERMISSOES))->carregar();
	if ($dataset){
		foreach ($dataset as $permissoes){
			fwrite($mdmJSCompile,"
				td_permissoes[{$permissoes->id}] = {
					id:'{$permissoes->id}',
					projeto:'{$permissoes->projeto}',
					empresa:'{$permissoes->empresa}',
					entidade:'{$permissoes->entidade}',
					usuario:'{$permissoes->usuario}',
					inserir:'{$permissoes->inserir}',
					excluir:'{$permissoes->excluir}',
					editar:'{$permissoes->editar}',
					visualizar:'{$permissoes->visualizar}',
					atributos:{
			");
			fwrite($mdmJSCompile,"
					}
				};
			");
		}
	}

	$dataset = tdClass::Criar("repositorio",array(FILTROATRIBUTO))->carregar();		
	if ($dataset){
		foreach ($dataset as $filtroatributo){
			fwrite($mdmJSCompile,"
				td_filtroatributo[{$filtroatributo->id}] = {
					id:'{$permissoes->id}',
					atributo:'{$filtroatributo->atributo}',
					campo:'{$filtroatributo->campo}',
					operador:'{$filtroatributo->operador}',
					valor:'{$filtroatributo->valor}'
				};
			");
		}
	}

	// Consultas 
	$dataset = tdClass::Criar("repositorio",array(CONSULTA))->carregar();		
	if ($dataset){
		foreach ($dataset as $consultas){

			// json_encode força o retorno de um boleano em string
			$exibirbotaoeditar		= json_encode($consultas->exibirbotaoeditar);
			$exibirbotaoexcluir		= json_encode($consultas->exibirbotaoexcluir);
			$exibirbotaoemmassa		= json_encode($consultas->exibirbotaoemmassa);

			fwrite($mdmJSCompile,"
				td_consulta[{$consultas->id}] = {
					id:'{$consultas->id}',
					projeto:'{$consultas->projeto}',
					empresa:'{$consultas->empresa}',
					entidade:'{$consultas->entidade}',
					movimentacao:'{$consultas->movimentacao}',
					descricao:'{$consultas->descricao}',
					exibireditar:{$exibirbotaoeditar},
					exibirexcluir:{$exibirbotaoexcluir},
					exibiremmassa:{$exibirbotaoemmassa},
					filtros:{
			");			
			$sqlFiltros = "SELECT id,operador,atributo FROM td_consultafiltro a WHERE consulta = " . $consultas->id;
			$queryFiltros = $conn->query($sqlFiltros);
			$iAP = 1;
			$tAP = $queryFiltros->rowcount();
			While ($linhaFiltros = $queryFiltros->fetch()){
				fwrite($mdmJSCompile,'"'.$linhaFiltros["id"].'":{"atributo":"'.$linhaFiltros["atributo"].'","operador":"'.$linhaFiltros["operador"].'"}');
				if ($iAP < $tAP) fwrite($mdmJSCompile,",");
				$iAP++;
			}
			fwrite($mdmJSCompile,"
					},
					status:{
			");
			$sqlStatus = "SELECT id,valor,operador,atributo,status FROM td_consultastatus a WHERE consulta = " . $consultas->id;
			$queryStatus = $conn->query($sqlStatus);
			$iAP = 1;
			$tAP = $queryStatus->rowcount();
			While ($linhaStatus = $queryStatus->fetch()){
				fwrite($mdmJSCompile,'"'.$linhaStatus["id"].'":{"atributo":"'.$linhaStatus["atributo"].'","operador":"'.$linhaStatus["operador"].'","valor":"'.$linhaStatus["valor"].'","status":"'.$linhaStatus["status"].'"}');
				if ($iAP < $tAP) fwrite($mdmJSCompile,",");
				$iAP++;
			}
			fwrite($mdmJSCompile,"
					},
					filtros_iniciais:[
			");

						$sqlCI 		= "SELECT id,atributo,operador,valor FROM td_consultafiltroinicial WHERE consulta = " . $consultas->id;
						$queryCI 	= $conn->query($sqlCI);
						$iCI 		= 1;
						$tCI 		= $queryCI->rowcount();		
						While ($linhaCI = $queryCI->fetch()){
							fwrite($mdmJSCompile,'{
								atributo:"'.tdc::a($linhaCI['atributo'])->nome.'",
								operador:"'.$linhaCI['operador'].'",
								valor:"'.$linhaCI['valor'].'"
							}');
							if ($iCI < $tCI) fwrite($mdmJSCompile,",");
						}
			fwrite($mdmJSCompile,"
					]
				};
			");
		}
	}

	$dataset = tdClass::Criar("repositorio",array(RELATORIO))->carregar();		
	if ($dataset){
		foreach ($dataset as $relatorios){
			fwrite($mdmJSCompile,"
				td_relatorio[{$relatorios->id}] = {
					id:'{$relatorios->id}',
					projeto:'{$relatorios->projeto}',
					empresa:'{$relatorios->empresa}',
					entidade:'{$relatorios->entidade}',
					descricao:'{$relatorios->descricao}',
					filtros:{
			");
			$sqlFiltros = "SELECT id,operador,atributo FROM td_relatoriofiltro a WHERE relatorio = " . $relatorios->id;
			$queryFiltros = $conn->query($sqlFiltros);
			$iAP = 1;
			$tAP = $queryFiltros->rowcount();
			While ($linhaFiltros = $queryFiltros->fetch()){
				fwrite($mdmJSCompile,'"'.$linhaFiltros["id"].'":{"atributo":"'.$linhaFiltros["atributo"].'","operador":"'.$linhaFiltros["operador"].'"}');
				if ($iAP < $tAP) fwrite($mdmJSCompile,",");
				$iAP++;
			}
			fwrite($mdmJSCompile,"
					},
					status:{
			");
			$sqlStatus = "SELECT id,valor,operador,atributo,status FROM td_relatoriostatus a WHERE relatorio = " . $relatorios->id;
			$queryStatus = $conn->query($sqlStatus);
			$iAP = 1;
			$tAP = $queryStatus->rowcount();
			While ($linhaStatus = $queryStatus->fetch()){
				fwrite($mdmJSCompile,'"'.$linhaStatus["id"].'":{"atributo":"'.$linhaStatus["atributo"].'","operador":"'.$linhaStatus["operador"].'","valor":"'.$linhaStatus["valor"].'","status":"'.$linhaStatus["status"].'"}');
				if ($iAP < $tAP) fwrite($mdmJSCompile,",");
				$iAP++;
			}
			fwrite($mdmJSCompile,"
					}
				};
			");
		}	
	}

	$dataset = tdClass::Criar("repositorio",array("td_status"))->carregar();
	if ($dataset){
		$td_status_descricao = array();
		foreach ($dataset as $status){
			fwrite($mdmJSCompile,"
				td_status[{$status->id}] = {
					id:'{$status->id}',
					descricao:'{$status->descricao}',
					classe:'{$status->classe}'
				};
			");
			array_push($td_status_descricao, "'".$status->classe."'");
		}
		fwrite($mdmJSCompile,'var td_status_class = ['.implode(',',$td_status_descricao).'];');
	}

	$dataset = tdClass::Criar("repositorio",array("td_movimentacao"))->carregar();
	if ($dataset){
		foreach ($dataset as $movimentacao){
			fwrite($mdmJSCompile,"
				td_movimentacao[{$movimentacao->id}] = {
					id:'{$movimentacao->id}',
					descricao:'{$movimentacao->descricao}',
					classe:'{$movimentacao->entidade}'
				};
			");
		}
	}
	fclose($mdmJSCompile);