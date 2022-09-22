<?php
	if ($session = Session::get()){		
		$PREFIXO_endereco 	= PREFIXO . "_endereco";
		$PREFIXO_cidade	 	= PREFIXO . "_cidade";
		$PREFIXO_estado		= PREFIXO . "_estado";
		$empresa 			= tdClass::Criar("persistent",array(EMPRESA,$session->empresa));
		
		$sql_empresas = tdClass::Criar("sqlcriterio");
		if (Session::get()->permitirtrocarempresa==0){
			$sql_empresas->addFiltro("id",'=',Session::get()->empresa);
		}
		$empresas = tdClass::Criar("repositorio",array(PREFIXO . "_empresa"))->carregar($sql_empresas);		
		$select_empresa = tdClass::Criar("select");
		$select_empresa->id = "selecionar-empresa-info";

		$nome_projeto 			= tdClass::Criar("h",array(2));
		$nome_projeto->class 	= "nome-projeto";
		$info 					= tdClass::Criar("bloco",array("info"));
		$info->class 			= "col-md-7 col-sm-5";

		foreach ($empresas as $empresa){
			if (isset($empresa->{$PREFIXO_endereco})){
				$endereco 	= tdClass::Criar("persistent",array($PREFIXO_endereco,$empresa->{$PREFIXO_endereco}));
			}else{
				$endereco = null;
			}
			
			
			$logradouro = $numero = $cidade = $estado = $bairro = "";
			if (isset($empresa)) $nomefantasia 	= $empresa->nomefantasia;
			if (isset($endereco)){
				if ($endereco->contexto!=null){
					$logradouro 	= $endereco->contexto->logradouro;
					$numero 		= $endereco->contexto->numero;
					$cidade 		= tdClass::Criar("persistent",array($PREFIXO_cidade,$endereco->contexto->{$PREFIXO_cidade}))->contexto->nome;
					$estado			= tdClass::Criar("persistent",array($PREFIXO_estado,$endereco->contexto->{$PREFIXO_estado}))->contexto->sigla;
					$bairro			= $endereco->contexto->bairro;
				}
			}
			$enderecoDescricao = isset($endereco)?" - " . $logradouro . "," . $numero . " - " .$bairro." - " . $cidade ."/" . $estado:"";
			$op = tdClass::Criar("option");
			$op->value = $empresa->id;
			$op->add($nomefantasia . $enderecoDescricao);
			if ($empresa->id == $session->empresa) $op->selected = "true";
			$select_empresa->add($op);
		}

		$js_select_empresa = tdClass::Criar("script");
		$js_select_empresa->add('
			$("#selecionar-empresa-info").change(function(){
				location.href = "?controller=trocarempresa&empresa=" + $(this).val();
			});
		');
		$br = tdClass::Criar("br");
		$nome_projeto->add(PROJECT_NAME,$br,(sizeof($empresas)>0?$select_empresa:""),$js_select_empresa);
		$info->add($nome_projeto);
	}