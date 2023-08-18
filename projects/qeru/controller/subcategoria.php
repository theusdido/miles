<?php
	
	switch($op){
		case 'load':
		case 'default':
			$categoria_id 	= isset($dados["categoria"]) ? $dados["categoria"] : 0;
			$loja_id		= $dados["loja"];
			$ft 			= tdc::f();
			$ft->addFiltro("categoria","=",$categoria_id);
			$ft->addFiltro("loja","=",$loja_id);

			echo tdc::dj("td_ecommerce_subcategoria",$ft);
		break;
		case 'add':

			try{
				$sb 				= tdc::p("td_ecommerce_subcategoria");
				$sb->descricao 		= $dados["nome"];
				$sb->categoria		= $dados["categoria"];
				$sb->loja			= $dados["loja"];
				$sb->inativo		= false;
				$sb->armazenar();

				$lista 					= tdc::p(LISTA);
				$lista->entidadepai		= getEntidadeId("ecommerce_categoria");
				$lista->entidadefilho	= getEntidadeId("ecommerce_subcategoria");
				$lista->regpai			= $sb->categoria;
				$lista->regfilho		= $sb->id;
				$lista->armazenar();

				$retorno = array(
					"status" => 0,
					"id" => $sb->id
				);
			}catch(Throwable $t){
				$retorno = array(
					"status" => 1,
					"id" => $t->getMessage()
				);
			}finally{
				echo json_encode($retorno);	
			}
		break;
		case 'inativar':
			try{
				$sb 				= tdc::p("td_ecommerce_subcategoria",$dados["id"]);
				$sb->inativo 		= (bool)$dados["inativo"] ? false : true;
				$sb->armazenar();

				$retorno = array(
					"status" => 0,
					"inativo" => $sb->inativo
				);
			}catch(Throwable $t){
				$retorno = array(
					"status" => 1,
					"msg" => $t->getMessage()
				);
			}finally{
				echo json_encode($retorno);	
			}
		break;
		case 'desvincular':
			try{

				$sb 				= tdc::p("td_ecommerce_subcategoria",$dados["id"]);
				$sb->deletar();

				$retorno = array(
					"status" => 0,
				);
			}catch(Throwable $t){
				$retorno = array(
					"status" => 1,
					"id" => $t->getMessage()
				);
			}finally{
				echo json_encode($retorno);	
			}
		break;
		case 'excluir-imagem':
			$subcategoria			= tdc::p("td_ecommerce_subcategoria",$dados["subcategoria"]);
			$subcategoria->imagem 	= '';
			$subcategoria->armazenar();
		break;
	}