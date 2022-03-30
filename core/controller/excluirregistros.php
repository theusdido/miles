<?php
	$entidadeID 	= tdClass::Read("entidade");
	$registrosID 	= tdClass::Read("registro");

	if ($entidadeID != "" && $registrosID != ""){
		
		if (is_numeric($entidadeID)){	
			$entidade = tdClass::Criar("persistent",array(ENTIDADE,$entidadeID));

			// Se a entidade relacionamento do tipo composição [ PAI ]
			$sql = tdClass::Criar("sqlcriterio");
			$sql->add(tdClass::Criar("sqlfiltro",array("pai","=",$entidadeID)));	
			#$sql->add(tdClass::Criar("sqlfiltro",array("tipo","=",2))); # 2 é composição
			$relacionamento_composicao 		= tdClass::Criar("repositorio",array(RELACIONAMENTO));
			$dadosComposicao 				= $relacionamento_composicao->carregar($sql);
			foreach($relacionamento_composicao->carregar($sql) as $dadosComposicao){
				$atributo_relacionamento 	= tdClass::Criar("persistent",array(ATRIBUTO,$dadosComposicao->atributo)); #pega o campo que faz a vinculação
				$campo_pai_relacionamento 	= $atributo_relacionamento->contexto->nome;
				$entidade_rel 				= $dadosComposicao->filho;

				// Excluir Lista
				foreach(getListaRegFilhoObject($entidade->contexto->id,$entidade_rel,$registrosID) as $reg_filho){
					$ft_lista = tdc::f();
					$ft_lista->addFiltro('entidadepai','=',$entidade->contexto->id);
					$ft_lista->addFiltro('entidadefilho','=',$entidade_rel);
					$ft_lista->addFiltro('regpai','=',$registrosID);
					$ft_lista->addFiltro('regfilho','=',$reg_filho->id);
					$lista = tdc::d(LISTA,$ft_lista);
					$lista[0]->deletar();
				}

				// Exclui registros na composição 1N e agregação 1N
				if ($dadosComposicao->tipo == 2 || $dadosComposicao->tipo == 6){
					$sql 				= tdClass::Criar("sqlcriterio");		
					$nome_entidade_rel 	= tdClass::Criar("persistent",array(ENTIDADE,$entidade_rel));		
					$dataset 			= tdClass::Criar("repositorio",array($nome_entidade_rel->contexto->nome));
					$sql->add(tdClass::Criar("sqlfiltro",array($campo_pai_relacionamento,"=",$registrosID)));
					$filhos 			= $dataset->carregar($sql);

					if (sizeof($filhos) > 0){
						foreach ($filhos as $filho){
							$filho->deletar();
						}
					}
				}
			}

			//Excluindo arquivos 
			$ft_excluir		= tdc::f();
			$ft_excluir->addFiltro('entidade','=',$entidade->contexto->id);
			$ft_excluir->addFiltro('tipohtml','=',19);
			foreach(tdc::da(ATRIBUTO,$ft_excluir) as $linhaVArquivos){
				$campo 		= $linhaVArquivos["nome"];
				$valor 		= tdClass::Criar("persistent",array($entidade->contexto->nome,$registrosID))->contexto->{$campo};
				$filename 	= PATH_CURRENT_FILE . $campo . "-" . $entidade->contexto->id . "-" . $registrosID . "." . getExtensao($valor);
				if (file_exists($filename)){
					unlink($filename);
				}
			}

			// Excluir o registro principal
			tdc::p($entidade->contexto->nome,$registrosID)->deletar();

			echo 1;
		}else exit;
	}else exit;