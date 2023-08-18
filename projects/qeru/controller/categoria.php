<?php
	switch($op){
		case 'load':
			$retorno 	= [];
			// id da categoria
			$id 		= isset($dados["id"]) ? $dados["id"] : 0;

			$filtro			= tdc::f();
			$filtro->onlyActive();
			
			if ($id > 0){
				$filtro->onlyActive();
				$retorno 	=  tdc::da("td_ecommerce_categoria",$filtro);
			}else{
				$loja 			= isset($dados["loja"]) ? $dados['loja'] : 0;
				$modalidade		= isset($dados["modalidade"]) ? $dados['modalidade'] : 0;

				if ($modalidade > 0){
					$filtro->addFiltro('modalidade','=',$modalidade);
				}

				// Seleciona as categorias vinculadas a uma loja
				$categorias		= getListaRegFilhoObject(
					getEntidadeId("ecommerce_loja"),
					getEntidadeId("ecommerce_categoria"),
					$loja
				);

				// Seleciona todas as categorias caso não tenha loja vin
				if (count($categorias) <= 0){					
					$categorias = tdc::d("td_ecommerce_categoria",$filtro);
				}

				foreach($categorias as $l){
					array_push($retorno,array(
						"id" 			=> $l->id,
						"descricao" 	=> tdc::utf8($l->descricao),
						"icon" 			=> $l->icon,
						"modalidade"	=> tdc::pa('td_ecommerce_modalidade',$l->modalidade)
					));
				}
			}
			echo json_encode($retorno);
		break;
		case 'salvar':
			$loja_entidade_id 		= getEntidadeId("ecommerce_loja");
			$categoria_entidade_id	= getEntidadeId("ecommerce_categoria");
			$loja 					= isset($dados["loja"]) ? $dados['loja'] : 0;
			$categoria_id			= isset($dados["categoria"]) ? $dados['categoria'] : 0;

			$id 			= tdClass::Criar("persistent",array(LISTA))->contexto->getUltimo() + 1;			
			$sqlInsertLista = "INSERT INTO " . LISTA . " (id,entidadepai,entidadefilho,regpai,regfilho,projeto,empresa) VALUES ({$id},{$loja_entidade_id},{$categoria_entidade_id},{$loja},{$categoria_id},0,0);";
			$conn->exec($sqlInsertLista);

		break;
		case 'excluir':
			$loja_entidade_id 		= getEntidadeId("ecommerce_loja");
			$categoria_entidade_id	= getEntidadeId("ecommerce_categoria");
			$loja 					= isset($dados["loja"]) ? $dados['loja'] : 0;
			$categoria_id			= isset($dados["categoria"]) ? $dados['categoria'] : 0;

			$sqlDeleteLista = "DELETE FROM " . LISTA . " WHERE entidadepai = {$loja_entidade_id} AND entidadefilho = {$categoria_entidade_id} AND regpai = {$loja} AND regfilho = {$categoria_id};";
			$conn->Exec($sqlDeleteLista);

		break;
	}