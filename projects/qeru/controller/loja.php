<?php
	switch($op){
        case 'all':
            $ft     = tdc::f();
            $ft->addFiltro('inativo','<>',1);
            $ft->addFiltro('inativo','IS',NULL,OU);
            echo tdc::dj('td_ecommerce_loja',$ft);
		break;
        case 'get':
            $loja_id    = $dados['loja'];
            $ft         = tdc::f('id','=',$loja_id);
            $ds         = tdc::da('td_ecommerce_loja',$ft);

			// Lista - Loja x Endereço
            $lista_endereco = getListaRegFilhoObject(
                getEntidadeId("ecommerce_loja"),
                getEntidadeId("ecommerce_endereco"),
                $loja_id
            );

            if (sizeof($lista_endereco) > 0){
                $endereco = $lista_endereco[0]->getDataArray();
                $endereco['cidade_desc'] = tdc::p('td_ecommerce_cidade',$endereco['cidade'])->nome;
                $endereco['bairro_desc'] = tdc::p('td_ecommerce_bairro',$endereco['bairro'])->nome;
            }else{
                $endereco = new stdClass;
            }

            
			// Seleciona as categorias vinculadas a uma loja
            $categorias = array();
            $lista_categorias		= getListaRegFilhoObject(
                getEntidadeId("ecommerce_loja"),
                getEntidadeId("ecommerce_categoria"),
                $loja_id
            );
            
            foreach($lista_categorias as $l){
                array_push($categorias,$l->id);
            }

            $entrega        = tdc::pa('td_ecommerce_lojaentrega',$loja_id);
			$loja_dados     = isset($ds[0]) ? $ds[0] : array();
            $retorno        = array_merge($loja_dados, array( 
                'endereco'      =>  $endereco, 
                'entrega'       => $entrega,
                'categorias'    => $categorias
            ));
            echo json_encode($retorno);
        break;

		case "salvar":
            $loja_id    = isset($dados['loja']['id']) ? $dados['loja']['id'] : 0;
            $is_new     = $loja_id == 0 ? true : false;
			$usuario_id	= $dados['userid'];

            if ($loja_id > 0){
                $loja = tdc::p("td_ecommerce_loja",$loja_id);
            }else{
                $loja = tdc::p("td_ecommerce_loja");
            }

            //UF
            $uf                     = tdc::p('td_ecommerce_uf',$dados['loja']['endereco']["uf"]);

            // Localização da loja Cidade/Estado (sigla)
            $localizacao            = $dados['loja']['endereco']["cidade_desc"] . '/' . $uf->sigla;

			// Loja
			$loja->nomefantasia		= $dados['loja']["nomefantasia"];
			$loja->razaosocial		= $dados['loja']["razaosocial"];
			$loja->cnpj 			= $dados['loja']["cnpj"];
			$loja->telefone			= $dados['loja']["telefone"];
			$loja->inativo			= false;
            $loja->localizacao      = $localizacao;
            $loja->url			    = $dados['loja']["url"];

            // A loja sempre é cadastrada como não oficial
            if ($is_new){
                $loja->is_oficial       = false;
            }

			$loja->armazenar();

            // Atualiza o ID da Loja após a insercão no banco de dados
            if ($is_new){
                $loja_id = $loja->id;

                // Lista - Loja x Usuário
                $lista 					= tdc::p(LISTA);
                $lista->entidadepai 	= getEntidadeId("ecommerce_loja");
                $lista->entidadefilho 	= getEntidadeId("usuario");
                $lista->regpai 			= $loja_id;
                $lista->regfilho 		= $usuario_id;
                $lista->armazenar();

            }
            
			// Lista - Loja x Endereço
            $lista_endereco = getListaRegFilhoObject(
                getEntidadeId("ecommerce_loja"),
                getEntidadeId("ecommerce_endereco"),
                $loja_id
            );

            if (count($lista_endereco) > 0){
                $endereco = $lista_endereco[0];
            }else{
                $endereco = tdc::p("td_ecommerce_endereco");
            }

			// Endereço
            include PATH_CLASS_ECOMMERCE . 'endereco.class.php';
            
            $endereco->cidade 		= Endereco::addCidade($dados['loja']['endereco']["cidade_desc"],$dados['loja']['endereco']["uf"]);
			$endereco->bairro 		= Endereco::addBairro($dados['loja']['endereco']["bairro_desc"],$endereco->cidade);
			$endereco->logradouro 	= $dados['loja']["endereco"]['logradouro'];
			$endereco->numero 		= isset($dados['loja']['endereco']["numero"])?$dados['loja']['endereco']["numero"]:'S/N';
			$endereco->complemento	= isset($dados['loja']['endereco']["complemento"])?$dados['loja']['endereco']["complemento"]:'';
			$endereco->cep 			= $dados['loja']['endereco']["cep"];
            $endereco->uf        	= $dados['loja']['endereco']["uf"];
			$endereco->armazenar();

            if ($is_new){

                // Lista - Loja x Endereço
                $lista = tdc::p(LISTA);
                $lista->entidadepai 	= getEntidadeId("ecommerce_loja");
                $lista->entidadefilho 	= getEntidadeId("ecommerce_endereco");
                $lista->regpai 			= $loja->id;
                $lista->regfilho 		= $endereco->id;
                $lista->armazenar();

            }

			$_entidade_loja_id		= getEntidadeId("ecommerce_loja");
			$_entidade_categoria_id	= getEntidadeId("ecommerce_categoria");

			// Categorias
			$categorias = gettype($dados["categorias"]) == 'array' ? $dados["categorias"] : json_decode($dados["categorias"]);

			// Exclui os relacionamentos das categorias
			$ft_categoria_excluir = tdc::f();
			$ft_categoria_excluir->addFiltro('entidadepai','=',$_entidade_loja_id);
			$ft_categoria_excluir->addFiltro('entidadefilho','=',$_entidade_categoria_id);
			$ft_categoria_excluir->addFiltro('regpai','=',$loja->id);
			tdc::de(LISTA,$ft_categoria_excluir);

			// Lista - Loja x Categoria
			foreach($categorias as $categoria){
                $lista                  = tdc::p(LISTA);
                $lista->entidadepai 	= $_entidade_loja_id;
                $lista->entidadefilho 	= $_entidade_categoria_id;
                $lista->regpai 			= $loja->id;
                $lista->regfilho 		= $categoria;
                $lista->armazenar();
			}

			echo json_encode(array(
				"status" 	=> 0,
				"msg" 		=> "Salvo com Sucesso",
				"loja" 		=> $loja->id,
                "loja_nome" => $loja->nomefantasia
			));
		break;
        case 'excluir-loja-logo':
            $loja           = tdc::p('td_ecommerce_loja',$dados['loja']);
            $logo_name      = $loja->logo;
            $loja->logo     = '';
            $loja->armazenar();

            $filename	= PATH_CURRENT_FILE . 'logo-'. getEntidadeId('td_ecommerce_loja') . '-' . $loja->id . "." . getExtensao($logo_name);
            if (file_exists($filename)) unlink($filename);
        break;
        case 'excluir-loja-topo':
            $loja               = tdc::p('td_ecommerce_loja',$dados['loja']);
            $bannertopo         = $loja->bannertopo;
            $loja->bannertopo   = '';
            $loja->armazenar();

            $filename	= PATH_CURRENT_FILE . 'bannertopo-'. getEntidadeId('td_ecommerce_loja') . '-' . $loja->id . "." . getExtensao($bannertopo);
            if (file_exists($filename)) unlink($filename);
        break;        
        case 'page':

            $loja       = tdc::du('td_ecommerce_loja',['url','=',$dados['url']]);
            $loja_id    = $loja->id;
            $ft         = tdc::f('url','=',$loja_id);
            $ds         = tdc::da('td_ecommerce_loja',$ft);

			// Lista - Loja x Endereço
            $lista_endereco = getListaRegFilhoObject(
                getEntidadeId("ecommerce_loja"),
                getEntidadeId("ecommerce_endereco"),
                $loja_id
            );

            if (sizeof($lista_endereco) > 0){
                $endereco = $lista_endereco[0]->getDataArray();
                $endereco['cidade_desc'] = tdc::p('td_ecommerce_cidade',$endereco['cidade'])->nome;
                $endereco['bairro_desc'] = tdc::p('td_ecommerce_bairro',$endereco['bairro'])->nome;
            }else{
                $endereco = new stdClass;
            }

            // Loja
            $loja                   = tdc::pa('td_ecommerce_loja',$loja_id);
            if ($loja['bannertopo'] == ''){
                $loja['bannertopo_src'] = URL_CURRENT_ASSETS . 'topo-loja-default.jpg';
            }
            $subcategoria           = tdc::da('td_ecommerce_subcategoria',tdc::f('loja','=',$loja_id));
			$loja_dados             = isset($ds[0]) ? $ds[0] : array();
            $retorno                = array_merge(
                $loja,
                $loja_dados,
                array('endereco'  => $endereco),
                array('produtos' => $subcategoria)
            );
            echo json_encode($retorno);
        break;
        case 'lojas-oficiais':

            $retorno = array();
            $ft      = tdc::f();
            $ft->isTrue('is_oficial');
            $ft->onlyActive();

            $dataset = tdc::da('td_ecommerce_loja',$ft);
            foreach($dataset as  $loja){
                // Lista - Loja x Endereço
                $lista_endereco = getListaRegFilhoObject(
                    getEntidadeId("ecommerce_loja"),
                    getEntidadeId("ecommerce_endereco"),
                    $loja['id']
                );

                if (sizeof($lista_endereco) > 0){
                    $endereco = $lista_endereco[0]->getDataArray();
                    $endereco['cidade_desc'] = tdc::p('td_ecommerce_cidade',$endereco['cidade'])->nome;
                    $endereco['bairro_desc'] = tdc::p('td_ecommerce_bairro',$endereco['bairro'])->nome;
                }else{
                    $endereco = [];
                }
                array_push($retorno,array_merge($loja , $endereco));
            }
            echo json_encode($retorno);
        break;
        case 'verificar-url':
            $filtro = tdc::f();
            $filtro->addFiltro('url','=',$dados['url']);
            $filtro->addFiltro('id','<>',$dados['loja']);
            $loja       = tdc::da('td_ecommerce_loja',$filtro);
            echo json_encode(array('is_exists' => (sizeof($loja) > 0 ? true : false)));
        break;
        case 'entrega':

            $_entidade_loja_id		    = getEntidadeId("ecommerce_loja");
			$_entidade_lojaentrega_id	= getEntidadeId("ecommerce_lojaentrega");
            
            $loja_id                            = $dados['loja'];
            $loja_entrega                       = tdc::p('ecommerce_lojaentrega')->newNotExists('loja','=',$loja_id);
            $loja_entrega->loja                 = $loja_id;
            $loja_entrega->is_entrega           = $dados['entrega']['is_possui_entrega'];
            $loja_entrega->is_entrega_feriado   = $dados['entrega']['is_entrega_feriado'];
            $loja_entrega->valor_minimo         = $dados['entrega']['valor_minimo'];
            $loja_entrega->quantidade_minima    = $dados['entrega']['quantidade_minima_entrega'];
            $loja_entrega->armazenar();


            $lista_endereco = getListaRegFilhoObject(
                $_entidade_loja_id,
                $_entidade_lojaentrega_id,
                $loja_id
            );

            if (sizeof($lista_endereco) <= 0){
                // Lista - Loja x Loja Entrega
                $lista                  = tdc::p(LISTA);
                $lista->entidadepai 	= getEntidadeId("ecommerce_loja");
                $lista->entidadefilho 	= getEntidadeId("ecommerce_lojaentrega");
                $lista->regpai 			= $loja_id;
                $lista->regfilho 		= $loja_entrega->id;
                $lista->armazenar();
            }
        break;
    }