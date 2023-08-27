<?php
	$relacionamentos = $entidadesIDRetorno = array();
	$retorno_id = $entidadeRetorno = $entidadePrincipalNome = "";
	
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

		// Retorno para a requisição
		if ($linha['fp'] == 'true'){
			$retorno_id 			= $id;
			$entidadeRetorno 		= $entidade->getID();
			$entidadePrincipalNome	= $entidade_nome;
			$isfp = 1;

			// Dados da entidade principal
			$objMain 				= new stdClass;
			$objMain->entidade 		= $entidade_nome;
			$objMain->id			= $id;
			$objMain->is_fp 		= $isfp;
			$objMain->atributo		= $linha["relacionamento"]["atributo"];
			$objMain->tipo_rel 		= $tipo_relacionamento;			
		}else{
			$isfp = 0;

			// Dados para implementar a restrição de relacionamento
			$objRel 			= new stdClass;
			$objRel->entidade 	= $entidade_nome;
			$objRel->id			= $id;
			$objRel->is_fp 		= $isfp;
			$objRel->atributo	= $linha["relacionamento"]["atributo"];
			$objRel->tipo_rel 	= $tipo_relacionamento;
			array_push($relacionamentos,$objRel);
		}

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

		// Grava os registros em JSON para as entidades auxiliares ( Lista )
		$_entidade = tdc::e($entidade->getID());
		if ($_entidade->entidadeauxiliar){
			Entity::saveJSON($_entidade->id);
		}
		$entidade->armazenar();
	}

	// Seta os relacionamentos
	foreach($relacionamentos as $rel){

		if (!is_numeric($rel->atributo) && $rel->atributo != null && $rel->atributo != ''){
			// Seta o atributo de relacionamento
			$_entidade_rel 						= tdc::p($rel->entidade,$rel->id);
			$_entidade_rel->{$rel->atributo} 	= $objMain->id;
			$_entidade_rel->armazenar();
		}

		// Seta na LISTA
		$entidadePai 		= getEntidadeId($objMain->entidade);
		$entidadeFilho 		= getEntidadeId($rel->entidade);
		$regPai 			= $objMain->id;
		$regFilho 			= $rel->id;

		if (!exists_lista($entidadePai,$entidadeFilho,$regPai,$regFilho)){
			$lista 					= tdc::p(LISTA);
			$lista->entidadepai 	= $entidadePai;
			$lista->entidadefilho	= $entidadeFilho;
			$lista->regpai 			= $regPai;
			$lista->regfilho 		= $regFilho;
			$lista->armazenar();
		}
	}

	// Checklist
	$_checklist = tdc::r('checklist');
	if ($_checklist != ''){
		foreach(tdc::r('checklist') as $checklist){

			// Seta na LISTA
			$entidadePai 		= $checklist['entidade_pai'];
			$entidadeFilho 		= $checklist['entidade_filho'];
			$regPai 			= $retorno_id;
			$regFilho 			= $checklist['valor'];
			
			$sql	= tdc::f();
			$sql->addFiltro('entidadepai','=', $entidadePai);
			$sql->addFiltro('entidadefilho','=', $entidadeFilho);
			$sql->addFiltro('regpai','=',$regPai);
			$sql->addFiltro('regfilho','=',$regFilho);
			
			// Exclui todos os registros da Lista
			tdc::de(LISTA,$sql);

			// Adiciona novos registros na lista
			$_lista 				= tdc::p(LISTA);
			$_lista->entidadepai 	= $entidadePai;
			$_lista->entidadefilho	= $entidadeFilho;
			$_lista->regpai			= $regPai;
			$_lista->regfilho 		= $regFilho;
			$_lista->armazenar();
		}
	}
	
	// Retorno
	echo json_encode(array("status" => 1 , "id" => $retorno_id , "entidade" => (int)$entidadeRetorno , "entidadesID" => $entidadesIDRetorno));
