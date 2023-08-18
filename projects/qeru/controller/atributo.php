<?php
	switch($op){
		case 'load':
			$retorno 		= [];
			$subcategoria 	= $dados["subcategoria"];
			$ft				= tdc::f('subcategoria','=',$subcategoria);
			$dataset 		= tdc::d('td_ecommerce_subcategoriaatributo',$ft);
			foreach($dataset as $l){
				$atributo	= tdc::p('td_ecommerce_atributoproduto',$l->atributo);
				array_push($retorno,array(
					"id" 		=> $atributo->id,
					"descricao" => $atributo->descricao,
					"itens" 	=> tdc::da("td_ecommerce_atributoprodutoopcao",tdc::f("atributo","=",$atributo->id))
				));
			}
			echo json_encode($retorno);
		break;
		case 'load-categoria':
			$categoria 	= $dados["categoria"];
			$loja		= $dados['loja'];
			$ft			= tdc::f();
			$ft->addFiltro("categoria","=",$categoria);
			$ft->addFiltro("loja","=",$loja);
			$subcategorias = tdc::da("td_ecommerce_subcategoria",$ft);
			$retorno 	= [];
			foreach($subcategorias as $sb){
				array_push($retorno,array(
					'id' 			=> $sb['id'],
					'descricao' 	=> $sb['descricao'],
					'categoria' 	=> $sb['categoria'],
					'imagem'		=> $sb['imagem_src'],
					"inativo" 		=> ($sb['inativo']==1?true:false),
					'atributos' 	=> Atributo::load($sb['id'])
				));
			}
			echo json_encode($retorno);
		break;
		case 'add':
			try{
				$atributo 				= tdc::p("td_ecommerce_atributoproduto");
				$atributo->descricao 	= $dados["nome"];
				$atributo->inativo		= false;
				$atributo->armazenar();
				
				$lista 					= tdc::p(LISTA);
				$lista->entidadepai		= getEntidadeId("ecommerce_subcategoria");
				$lista->entidadefilho	= getEntidadeId("ecommerce_atributoproduto");
				$lista->regpai			= $dados["subcategoria"];
				$lista->regfilho		= $atributo->id;
				$lista->armazenar();
				
				$retorno = array(
					"status" => 0,
					"id" => $atributo->id
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
		case 'add-opcao':
			try{
				$opcao 				= tdc::p("td_ecommerce_atributoprodutoopcao");
				$opcao->descricao 	= $dados["descricao"];
				$opcao->atributo	= $dados["atributo"];
				$opcao->inativo		= false;
				$opcao->armazenar();
				
				$lista 					= tdc::p(LISTA);
				$lista->entidadepai		= getEntidadeId("ecommerce_atributoproduto");
				$lista->entidadefilho	= getEntidadeId("ecommerce_atributoprodutoopcao");
				$lista->regpai			= $dados["atributo"];
				$lista->regfilho		= $opcao->id;
				$lista->armazenar();
				
				$retorno = array(
					"status" => 0,
					"id" => $opcao->id
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
				$sb 				= tdc::p("td_ecommerce_atributoproduto",$dados["id"]);
				$sb->inativo 		= $dados["status"];
				$sb->armazenar();

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
		case 'desvincular':
			try{
				$sb 				= tdc::p("td_ecommerce_atributoproduto",$dados["id"]);
				$sb->deletar();
				
				$f = tdc::f();
				$f->addFiltro("entidadepai","=",getEntidadeId("ecommerce_subcategoria"));
				$f->addFiltro("entidadefilho","=",getEntidadeId("ecommerce_atributoproduto"));
				$f->addFiltro("regpai","=",$sb->id);
				tdc::de(LISTA,$f);

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
		case 'inativar-opcao':
			try{
				$opcao 				= tdc::p("td_ecommerce_atributoprodutoopcao",$dados["id"]);
				$opcao->inativo 	= $dados["inativo"] == 1 ? false : true;
				$opcao->armazenar();

				$retorno = array(
					"status" => 0,
					"inativo" => $opcao->inativo
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
	}