<?php
	
	switch($op){
		case 'salvar':
			
			try{
				// Dados do Produto
				$params 					= json_decode($dados["produto"]);

				$categoria 					= tdc::p("td_ecommerce_categoria",$params->categoria);
				$subcategoria 				= tdc::p("td_ecommerce_subcategoria",$params->subcategoria);

				// Atributos
				$atributos 					= $params->atributos;
				$atributos_concat			= array();
				foreach($atributos as $da){
					$atributoopcao 		= tdc::p("td_ecommerce_atributoprodutoopcao",$da->id);
					$atributoproduto	= tdc::p("td_ecommerce_atributoproduto",$atributoopcao->atributo);
					array_push($atributos_concat,
						$atributoproduto->descricao . ": " . $da->descricao
					);
				}

				// Monta a descrição do item baseados na descrição dos atributos
				if (count($atributos_concat) > 0)
					$descricaoItem 		= implode(", ",$atributos_concat) . ". ";
				
				if ($params->descricao != '')
					 $descricaoItem 	.= "\n " . $params->descricao;

				// Imagem Principal
				$imagem_name		= null;
				$imagem_temp		= '';
				$is_imagem			= isset($params->imagemproduto->file) ? true : false;
				if ($is_imagem){
					$imagem_name 	= $params->imagemproduto->file->name;
					$imagem_temp	= $params->imagemproduto->filename;
				}

				// Cadastra o produto
				$produto 					= tdc::p("td_ecommerce_produto");
				$produto->nome 				= $subcategoria->descricao;
				$produto->descricao 		= $descricaoItem;
				$produto->imagemprincipal	= $imagem_name;
				$produto->categoria			= $categoria->id;
				$produto->subcategoria		= $subcategoria->id;
				$produto->preco				= $params->valor;
				$produto->armazenar();
				
				// Efetiva a imagem
				if ($is_imagem){
					$path_temp 	= PATH_CURRENT_FILE_TEMP . $imagem_temp;					
					$file_name	= 'imagemprincipal-' . getEntidadeId('td_ecommerce_produto') . '-' . $produto->id . "." . getExtensao($imagem_name);
					$path		= PATH_CURRENT_FILE . $file_name;
					$src 		= URL_CURRENT_FILE . $file_name;

					if (file_exists($path_temp)){
						move_uploaded_file($path_temp, $file_name);
						echo json_encode(array(
							"filename" 	=> $imagem_name,
							"src"		=> $src
						));
					}
				}

				$lista 					= tdc::p(LISTA);
				$lista->entidadepai		= getEntidadeId("ecommerce_produto");
				$lista->entidadefilho	= getEntidadeId("ecommerce_loja");
				$lista->regpai			= $produto->id;
				$lista->regfilho		= $dados['loja'];
				$lista->armazenar();
				
				$retorno = array(
					"status" => 0,
					"id" => $produto->id
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