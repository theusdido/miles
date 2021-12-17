<?php
	$entidadeID 	= tdClass::Read("entidade");
	$registrosID 	= tdClass::Read("registro");

	if ($entidadeID != "" && $registrosID != ""){
		$t = $entidadeID;
		
		if (is_numeric($t)){	
			$entidade = tdClass::Criar("persistent",array(ENTIDADE,$t));
			if ($conn = Transacao::get()){
				
				// Se a entidade relacionamento do tipo composição [ PAI ]
				$sql = tdClass::Criar("sqlcriterio");
				$sql->add(tdClass::Criar("sqlfiltro",array("pai","=",$t)));	
				$sql->add(tdClass::Criar("sqlfiltro",array("tipo","=",2))); # 2 é composição
				$relacionamento_composicao = tdClass::Criar("repositorio",array(RELACIONAMENTO));
				$dadosComposicao = $relacionamento_composicao->carregar($sql);				
				if ($relacionamento_composicao->quantia($sql) > 0){
					$atributo_relacionamento 	= tdClass::Criar("persistent",array(ATRIBUTO,$dadosComposicao[0]->atributo)); #pega o campo que faz a vinculação
					$campo_pai_relacionamento 	= $atributo_relacionamento->contexto->nome;
					$entidade_rel 				= $dadosComposicao[0]->filho;	

					//Exclui registros na composição
					$sql 				= tdClass::Criar("sqlcriterio");		
					$nome_entidade_rel 	= tdClass::Criar("persistent",array(ENTIDADE,$entidade_rel));		
					$dataset 			= tdClass::Criar("repositorio",array($nome_entidade_rel->contexto->nome));
					$sql->add(tdClass::Criar("sqlfiltro",array($campo_pai_relacionamento,"=",$registrosID)));
					$filhos 			= $dataset->carregar($sql);
					if (sizeof($filhos) > 0){
						foreach ($filhos as $filho){			
							if (is_numeric($filho->{$campo_pai_relacionamento})){
								getUrl(
									Session::Get('URL_SYSTEM'),
									array(
										"params" => array(
											'currentproject'	=> Session::Get()->currentproject,
											'controller'		=> 'excluirregistros',
											'entidade'			=> $entidade_rel,
											'registro'			=> $filho->id
										)
									)
								);
							}	
						}												
					}
				}
				
				//Excluindo arquivos 
				$sqlVArquivos 	= "SELECT nome FROM td_atributo WHERE entidade = {$entidade->contexto->id} and tipohtml in (19);";
				$queryVArquivos = $conn->query($sqlVArquivos);
				while ($linhaVArquivos = $queryVArquivos->fetch()){
					$campo 		= $linhaVArquivos["nome"];
					$valor 		= tdClass::Criar("persistent",array($entidade->contexto->nome,$registrosID))->contexto->{$campo};
					$filename 	= Session::Get("PATH_CURRENT_FILE") . $campo . "-" . $entidade->contexto->id . "-" . $registrosID . "." . getExtensao($valor);
					if (file_exists($filename)){
						unlink($filename);
					}
				}

				$sql = "DELETE FROM {$entidade->contexto->nome} WHERE id in ({$registrosID})";
				Transacao::log($sql);
				$resultado = $conn->exec($sql);
	
				Transacao::Fechar();
				echo 1;
			}else{
				throw new Exception("Não é transação ativa");
			}
		}else exit;
	}else exit;