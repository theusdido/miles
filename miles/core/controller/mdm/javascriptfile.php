<?php

	$mdmJSCompile = fopen(PATH_MDM_JS_COMPILE,"w");

	// Entidades do Sistema
	fwrite($mdmJSCompile,'
		var td_entidade = [];
		var td_atributo = [];
		var td_relacionamento = [];
		var td_permissoes = [];
		var td_filtroatributo = [];
		var td_consulta = [];
		var td_relatorio = [];
		var td_status = [];	
		var td_movimentacao = [];
	');
	
	$localCharset = 2;

	$dataset = tdClass::Criar("repositorio",array(ENTIDADE))->carregar();
	if ($dataset){
		foreach ($dataset as $entidade){		
			fwrite($mdmJSCompile,utf8charset("
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
					nomecompleto:'".(($entidade->pacote==""?"":$entidade->pacote."."))."{$entidade->nome}'
				};
			",$localCharset));		
		}	
	}

	// Atributos do Sistema	
	$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar();	
	if ($dataset){
		foreach ($dataset as $atributo){
			$descricaoAtributo = utf8charset($atributo->descricao,8);
			fwrite($mdmJSCompile,utf8charset("
				td_atributo[{$atributo->id}] = {
					id:{$atributo->id},
					td_entidade:'{$atributo->td_entidade}',
					nome:'{$atributo->nome}',
					descricao:'".$descricaoAtributo."',
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
					labelzerocheckbox:'".utf8charset($atributo->labelzerocheckbox,8)."',
					labelumcheckbox:'".utf8charset($atributo->labelumcheckbox,8)."',
					criarsomatoriogradededados:'{$atributo->criarsomatoriogradededados}',
					naoexibircampo:'{$atributo->naoexibircampo}'
				};
			",$localCharset));		
		}
	}
	// Relacionamentos do Sistema	
	$dataset = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar();		
	if ($dataset){
		foreach ($dataset as $relacionamento){
			fwrite($mdmJSCompile,utf8charset("
				td_relacionamento[{$relacionamento->id}] = {
					id:{$relacionamento->id},
					pai:'{$relacionamento->pai}',
					tipo:'{$relacionamento->tipo}',
					filho:'{$relacionamento->filho}',
					td_atributo:'{$relacionamento->td_atributo}',
					descricao:'{$relacionamento->descricao}',
					controller:'{$relacionamento->controller}',
					cardinalidade:'{$relacionamento->cardinalidade}'
				};
			",$localCharset));
		}
	}

	$sqlPermissoes = tdClass::Criar("sqlcriterio");
	$sqlPermissoes->addFiltro(USUARIO,"=",(isset(Session::Get()->userid)?Session::Get()->userid:0));
	$dataset = tdClass::Criar("repositorio",array(PERMISSOES))->carregar();
	if ($dataset){
		foreach ($dataset as $permissoes){
			fwrite($mdmJSCompile,utf8charset("
				td_permissoes[{$permissoes->id}] = {
					id:'{$permissoes->id}',
					td_projeto:'{$permissoes->td_projeto}',
					td_empresa:'{$permissoes->td_empresa}',
					td_entidade:'{$permissoes->td_entidade}',
					td_usuario:'{$permissoes->td_usuario}',
					inserir:'{$permissoes->inserir}',
					excluir:'{$permissoes->excluir}',
					editar:'{$permissoes->editar}',
					visualizar:'{$permissoes->visualizar}',
					atributos:{
			",$localCharset));
			fwrite($mdmJSCompile,utf8charset("
					}
				};
			",$localCharset));
		}
	}

	$dataset = tdClass::Criar("repositorio",array(FILTROATRIBUTO))->carregar();		
	if ($dataset){
		foreach ($dataset as $filtroatributo){
			fwrite($mdmJSCompile,utf8charset("
				td_filtroatributo[{$filtroatributo->id}] = {
					id:'{$permissoes->id}',
					td_atributo:'{$filtroatributo->td_atributo}',
					td_campo:'{$filtroatributo->td_campo}',
					operador:'{$filtroatributo->operador}',
					valor:'{$filtroatributo->valor}'
				};
			",$localCharset));
		}
	}

	$dataset = tdClass::Criar("repositorio",array(CONSULTA))->carregar();		
	if ($dataset){
		foreach ($dataset as $consultas){
			fwrite($mdmJSCompile,utf8charset("
				td_consulta[{$consultas->id}] = {
					id:'{$consultas->id}',
					td_projeto:'{$consultas->td_projeto}',
					td_empresa:'{$consultas->td_empresa}',
					td_entidade:'{$consultas->td_entidade}',
					td_movimentacao:'{$consultas->td_movimentacao}',
					descricao:'{$consultas->descricao}',
					filtros:{
			",$localCharset));			
			$sqlFiltros = "SELECT id,operador,td_atributo FROM td_consultafiltro a WHERE td_consulta = " . $consultas->id;
			$queryFiltros = $conn->query($sqlFiltros);
			$iAP = 1;
			$tAP = $queryFiltros->rowcount();
			While ($linhaFiltros = $queryFiltros->fetch()){
				fwrite($mdmJSCompile,utf8charset('"'.$linhaFiltros["id"].'":{"td_atributo":"'.$linhaFiltros["td_atributo"].'","operador":"'.$linhaFiltros["operador"].'"}',$localCharset));
				if ($iAP < $tAP) fwrite($mdmJSCompile,",");
				$iAP++;
			}
			fwrite($mdmJSCompile,"
					},
					status:{
			");
			$sqlStatus = "SELECT id,valor,operador,td_atributo,td_status FROM td_consultastatus a WHERE td_consulta = " . $consultas->id;
			$queryStatus = $conn->query($sqlStatus);
			$iAP = 1;
			$tAP = $queryStatus->rowcount();
			While ($linhaStatus = $queryStatus->fetch()){
				fwrite($mdmJSCompile,utf8charset('"'.$linhaStatus["id"].'":{"td_atributo":"'.$linhaStatus["td_atributo"].'","operador":"'.$linhaStatus["operador"].'","valor":"'.$linhaStatus["valor"].'","td_status":"'.$linhaStatus["td_status"].'"}',$localCharset));
				if ($iAP < $tAP) fwrite($mdmJSCompile,",");
				$iAP++;
			}
			fwrite($mdmJSCompile,"
					}
				};
			");
		}
	}

	$dataset = tdClass::Criar("repositorio",array(RELATORIO))->carregar();		
	if ($dataset){
		foreach ($dataset as $relatorios){
			fwrite($mdmJSCompile,utf8charset("
				td_relatorio[{$relatorios->id}] = {
					id:'{$relatorios->id}',
					td_projeto:'{$relatorios->td_projeto}',
					td_empresa:'{$relatorios->td_empresa}',
					td_entidade:'{$relatorios->td_entidade}',
					descricao:'{$relatorios->descricao}',
					filtros:{
			",$localCharset));
			$sqlFiltros = "SELECT id,operador,td_atributo FROM td_relatoriofiltro a WHERE td_relatorio = " . $relatorios->id;
			$queryFiltros = $conn->query($sqlFiltros);
			$iAP = 1;
			$tAP = $queryFiltros->rowcount();
			While ($linhaFiltros = $queryFiltros->fetch()){
				fwrite($mdmJSCompile,utf8charset('"'.$linhaFiltros["id"].'":{"td_atributo":"'.$linhaFiltros["td_atributo"].'","operador":"'.$linhaFiltros["operador"].'"}',$localCharset));
				if ($iAP < $tAP) fwrite($mdmJSCompile,",");
				$iAP++;
			}
			fwrite($mdmJSCompile,"
					},
					status:{
			");
			$sqlStatus = "SELECT id,valor,operador,td_atributo,td_status FROM td_relatoriostatus a WHERE td_relatorio = " . $relatorios->id;
			$queryStatus = $conn->query($sqlStatus);
			$iAP = 1;
			$tAP = $queryStatus->rowcount();
			While ($linhaStatus = $queryStatus->fetch()){
				fwrite($mdmJSCompile,utf8charset('"'.$linhaStatus["id"].'":{"td_atributo":"'.$linhaStatus["td_atributo"].'","operador":"'.$linhaStatus["operador"].'","valor":"'.$linhaStatus["valor"].'","td_status":"'.$linhaStatus["td_status"].'"}',$localCharset));
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
		foreach ($dataset as $status){
			fwrite($mdmJSCompile,utf8charset("
				td_status[{$status->id}] = {
					id:'{$status->id}',
					descricao:'{$status->descricao}',
					classe:'{$status->classe}'
				};
			",$localCharset));
		}
	}

	$dataset = tdClass::Criar("repositorio",array("td_movimentacao"))->carregar();
	if ($dataset){
		foreach ($dataset as $movimentacao){
			fwrite($mdmJSCompile,utf8charset("
				td_movimentacao[{$movimentacao->id}] = {
					id:'{$movimentacao->id}',
					descricao:'{$movimentacao->descricao}',
					classe:'{$movimentacao->td_entidade}'
				};
			",$localCharset));
		}
	}
	fclose($mdmJSCompile);