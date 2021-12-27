<?php
	$relacionamentos = $relIDS = $entidadesIDRetorno = array();
	$idRetorno = $entidadeRetorno = $entidadePrincipalNome = "";
	$i = 0;
	
	// Salva os dados
	foreach (tdClass::read("dados") as $linha){
		
		$id				= (int)$linha['id'];		
		$entidade_nome	= $linha['entidade'];
		$entidade 		= tdc::p($entidade_nome,$id);

		// Verifica se está modo de edição
		if ($entidade->hasData()){
			$entidade->isUpdate();
		}else{
			$id 			= $entidade->getUltimo()+1;
			$entidade->id 	= $id;
		}
		
		$tipo_relacionamento = $linha["fp"] == "true" ? 0 : (int)$linha['tiporel'];
		array_push($entidadesIDRetorno,array("entidade" => $entidade_nome, "id" => $id , "tipo_relacionamento" => $tipo_relacionamento));
		
		// Seta os registros para fazer o relacionamento no final do salvamento
		$relIDS[$entidade_nome] = $id;
		$i++;

		// Retorno para a requisição
		if ($linha['fp'] == 'true'){
			$idRetorno 				= $id;
			$entidadeRetorno 		= $entidade->getID();
			$entidadePrincipalNome	= $entidade_nome;
			$isfp = 1;
		}else{
			$isfp = 0;
		}

		// Dados para implementar a restrição de relacionamento
		$objRel 			= new stdClass;
		$objRel->entidade 	= $entidade_nome;
		$objRel->id			= $id;
		$objRel->is_fp 		= $isfp;
		array_push($relacionamentos,$objRel);

		foreach ($linha["dados"] as $dado){
			if (isset($dado["valor"])){
				if (
						(
							strtolower($dado["atributo"]) == strtolower(PROJETO) || strtolower($dado["atributo"]) == strtolower(EMPRESA) || TdFormulario::isNumberDataType(getAtributoId($linha["entidade"],$dado["atributo"]))
						)
					&&
						($dado["valor"] == '' || $dado["valor"] == null || empty($dado["valor"]))
				){
					$entidade->{$dado["atributo"]} = 0;
				}else{
					$entidade->{$dado["atributo"]} = Config::Integridade($entidade->getID(),$dado["atributo"],$dado["valor"],$id);
				}
			}
		}
		$entidade->armazenar();

		
	}

	$i = 0;
	// Seta os relacionamentos
	foreach($relacionamentos as $rel){
		foreach (tdClass::read("dados") as $linha){
			$atributo_relacionamento = isset($linha["relacionamento"]["atributo"])?$linha["relacionamento"]["atributo"]:0;
			if ($linha["entidade"] == $rel->entidade && !$rel->is_fp){
				if (!isset($linha["relacionamento"])){
					echo 'Indice relacionamento não encontrado';
					break;
				}

				// Dados da entidade principal
				if ($linha["relacionamento"] != ""){
					if ($conn = Transacao::get()){
						if (!is_numeric($atributo_relacionamento) && $atributo_relacionamento != null){
							try{
								// Seta o atributo de relacionamento
								$sqlUpdateRel = "UPDATE " . $linha["entidade"] . " SET " . $atributo_relacionamento . " = " . $relIDS[$entidadePrincipalNome] . " WHERE id = " . $relIDS[$rel->entidade];
								var_dump($mjc->is_transaction_log);
								Transacao::log($sqlUpdateRel);
								$updater = $conn->exec($sqlUpdateRel);
							}catch(Exception $e){
								var_dump($conn->errorInfo());
								echo $sqlUpdateRel;
							}
						}

                        // Seta na LISTA
                        $entidadePai 		= getEntidadeId($linha["relacionamento"]["entidade"]);
                        $entidadeFilho 		= getEntidadeId($linha["entidade"]);
                        $regPai 			= isset($relIDS[$linha["relacionamento"]["entidade"]]) ? $relIDS[$linha["relacionamento"]["entidade"]] : '';
                        $regFilho 			= isset($rel->id) ? $rel->id : '';

						if ($regPai != '' && $regFilho != ''){
							$whereListaV 	= " WHERE entidadepai = {$entidadePai} AND entidadefilho = {$entidadeFilho} AND regpai = {$regPai} AND regfilho = {$regFilho}";
							$sqlListaV 		= "SELECT id FROM " . LISTA . $whereListaV;
							$queryListaV 	= $conn->query($sqlListaV);
							if ($queryListaV->rowCount() <= 0 ){
								$id = tdClass::Criar("persistent",array(LISTA))->contexto->getUltimo() + 1;
								$sqlUpdateLista = "INSERT INTO " . LISTA . " (id,entidadepai,entidadefilho,regpai,regfilho) VALUES ({$id},{$entidadePai},{$entidadeFilho},{$regPai},{$regFilho});";
								$conn->Exec($sqlUpdateLista);
							}
						}
					}
				}
			}
		}
	}
	$i++;
	Transacao::fechar();
	// Retorno
	echo json_encode(array("status" => 1 , "id" => $idRetorno , "entidade" => (int)$entidadeRetorno , "entidadesID" => $entidadesIDRetorno));
