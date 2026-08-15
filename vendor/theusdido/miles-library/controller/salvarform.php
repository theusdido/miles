<?php
	$relacionamentos = $entidadesIDRetorno = array();
	$retorno_id = $entidadeRetorno = $entidadePrincipalNome = "";
	
	// Salva os dados
	foreach (tdClass::read("dados") as $linha){

		$id				= (int)$linha['id'];
		$entidade_nome	= $linha['entidade'];
		$entidade 		= tdc::p($entidade_nome,$id);
		$atributo_relacionamento = $linha["relacionamento"]["atributo"];

		// Verifica se está modo de edição
		if ($entidade->hasData()){
			$entidade->isUpdate();
		}else{
			$id 			= $entidade->getUltimo()+1;
			$entidade->id 	= $id;
			$entidade->setIsNew();
		}

		$tipo_relacionamento = $linha["fp"] == "true" ? 0 : (int)$linha['tiporel'];		
		array_push($entidadesIDRetorno,array(
			"entidade" => $entidade_nome, "id" => $id,
			"tipo_relacionamento" => $tipo_relacionamento,
			"atributo_relacionamento" => $atributo_relacionamento
		));
		
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
			$objMain->atributo		= $atributo_relacionamento;
			$objMain->tipo_rel 		= $tipo_relacionamento;

		}else{
			$isfp = 0;

			// Dados para implementar a restrição de relacionamento
			$objRel 			= new stdClass;
			$objRel->entidade 	= $entidade_nome;
			$objRel->id			= $id;
			$objRel->is_fp 		= $isfp;
			$objRel->atributo	= $atributo_relacionamento;
			$objRel->tipo_rel 	= $tipo_relacionamento;
			array_push($relacionamentos,$objRel);
		}

		foreach ($linha["dados"] as $dado) {
			$_atributo 		= $dado["atributo"];

			if (isset($dado["valor"])) {
				$_valor 		= $dado["valor"];
				if (
					(
						strtolower($_atributo) == strtolower(PROJETO) ||
						strtolower($_atributo) == strtolower(EMPRESA) ||
						TdFormulario::isNumberDataType(getAtributoId($linha["entidade"], $_atributo))
					) &&
					($_valor == '' || $_valor == null || empty($_valor))
				) {
					$entidade->{$_atributo} = 0;				
				} else {
					$entidade->{$_atributo} = Config::Integridade($entidade->getID(), $_atributo, $_valor, $id);
				}
			}else if (FieldAdditionalType::isField($_atributo)){
				$entidade->{$_atributo} = NULL;
			}
		}

		if ($linha['fp'] == 'true'){
			// Seta Inativo
			$entidade->setInativar(tdc::r('inativo'));
		}

		$entidade_auxiliar 		= isset($linha['entidadeauxiliar']) ? (int)$linha['entidadeauxiliar'] : 0;
		$entidade->is_save_json	= $entidade_auxiliar == 0 ? false : true;

		// Armazena os registros do banco de dados principal
		$entidade->armazenar();
	}

	// Seta os relacionamentos
	foreach($relacionamentos as $rel){

		$entidadePai 		= getEntidadeId($objMain->entidade);
		$entidadeFilho 		= getEntidadeId($rel->entidade);
		$regPai 			= $objMain->id;
		$regFilho 			= $rel->id;

		if (!is_numeric($rel->atributo) && $rel->atributo != null && $rel->atributo != ''){
			// Seta o atributo de relacionamento
			$_entidade_rel 						= tdc::p($rel->entidade,$rel->id);
			$_entidade_rel->{$rel->atributo} 	= $objMain->id;
			$_entidade_rel->is_save_json		= tdc::e($entidadeFilho)->entidadeauxiliar == 0 ? false : true;
			$_entidade_rel->armazenar();
		}

		// Seta na LISTA
		tdLista::save($entidadePai,$entidadeFilho,$regPai,$regFilho,$rel->tipo_rel);
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
			$sql->addFiltro('entidadepai'		,'=', $entidadePai);
			$sql->addFiltro('entidadefilho'		,'=', $entidadeFilho);
			$sql->addFiltro('regpai'			,'=', $regPai);
			$sql->addFiltro('regfilho'			,'=', $regFilho);
			
			// Exclui todos os registros da Lista
			tdc::de(LISTA,$sql);

			// Adiciona novos registros na lista
			$_lista 				= tdc::p(LISTA);
			$_lista->entidadepai 	= $entidadePai;
			$_lista->entidadefilho	= $entidadeFilho;
			$_lista->regpai			= $regPai;
			$_lista->regfilho 		= $regFilho;
			$_lista->armazenar();

			// Atualiza entidade pai
			$_entidade_pai_obj 	= tdc::e($entidadePai);
			$_pai 				= tdc::p($_entidade_pai_obj->nome,$regPai);
			$_pai->armazenar();			

			// Atualiza lista no Firebase
			if (_IS_REPLICATION_FIREBASE){
				
				$firebase 		= new Firebase();
				$ref_lista 		= LISTA . '/' . $_lista->id;
				$firebase->ref($ref_lista)->set([
					'id'			=> $id,
					'entidadepai' 	=> $entidadePai,
					'entidadefilho'	=> $entidadeFilho,
					'regpai'		=> $regPai,
					'regfilho' 		=> $regFilho
				]);
				
				$firebase->addRelacionamento($_entidade_pai_obj->nome . '/' . $regPai);
			}
		}
	}

	// Retorno
	echo json_encode(array("status" => 1 , "id" => $retorno_id , "entidade" => (int)$entidadeRetorno , "entidadesID" => $entidadesIDRetorno));
