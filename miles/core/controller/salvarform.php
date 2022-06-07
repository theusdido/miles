<?php
	$relacionamento = $relIDS = $entidadesIDRetorno = array();
	$idRetorno = $entidadeRetorno = "";
	$i = 0;
	
	// Salva os dados
	foreach (tdClass::read("dados") as $linha){
		
		if ($linha["id"] == ""){
			$entidade = tdClass::Criar("persistent",array($linha["entidade"]))->contexto;
			$id = $entidade->getUltimo()+1;
		}else{
			$entidade = tdClass::Criar("persistent",array($linha["entidade"],$linha["id"]))->contexto;
			$id = (int)$linha["id"];
		}
		
		array_push($entidadesIDRetorno,array("entidade" => $linha["entidade"] ,"id" => $id));

		$relIDS[$linha["entidade"]] = $id;
		
		$i++;
		if ($linha["fp"] == "true"){
			$idRetorno = $id;
			$entidadeRetorno = $entidade->getID();
			$isfp = 1;
		}else{
			$isfp = 0;
		}
		array_push($relacionamento,$linha["entidade"] . "^" . $id . "^" . $isfp);
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
					$entidade->{$dado["atributo"]} = Campos::Integridade($entidade->getID(),$dado["atributo"],$dado["valor"],$id);
				}
			}
		}
		$entidade->armazenar();
	}

	$i = 0;
	// Seta os relacionamentos
	foreach($relacionamento as $rel){
		$r = explode("^", $rel);
		foreach (tdClass::read("dados") as $linha){
			if ($linha["entidade"] == $r[0] && $r[2] == 0){
				if (!isset($linha["relacionamento"])){
					echo 'Indice relacionamento não encontrado';
					break;
				}
				if ($linha["relacionamento"] != ""){					
					if ($conn = Transacao::get()){
						if (isset($linha["relacionamento"]["atributo"])){
							// Seta o atributo de relacionamento
							if ($linha["relacionamento"]["atributo"] != "" && $linha["relacionamento"]["atributo"] != null){
								try{
									$sqlUpdateRel = "UPDATE " . $linha["entidade"] . " SET " . $linha["relacionamento"]["atributo"] . " = " . $relIDS[$linha["relacionamento"]["entidade"]] . " WHERE id = " . $r[1];
									$updater = $conn->exec($sqlUpdateRel);
								}catch(Exception $e){
									var_dump($conn->errorInfo());
									echo $sqlUpdateRel;
								}
							}
						}

                        // Seta na LISTA
                        $entidadePai 		= getEntidadeId($linha["relacionamento"]["entidade"]);
                        $entidadeFilho 		= getEntidadeId($linha["entidade"]);
                        $regPai 			= $relIDS[$linha["relacionamento"]["entidade"]];
                        $regFilho 			= $r[1];

                        $whereListaV = " WHERE entidadepai = {$entidadePai} AND entidadefilho = {$entidadeFilho} AND regpai = {$regPai} AND regfilho = {$regFilho}";
                        $sqlListaV = "SELECT id FROM " . LISTA . $whereListaV;

                        $queryListaV = $conn->query($sqlListaV);
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
	$i++;
	Transacao::fechar();
	// Retorno
	echo json_encode(array("status" => 1 , "id" => $idRetorno , "entidade" => $entidadeRetorno , "entidadesID" => $entidadesIDRetorno));
?>