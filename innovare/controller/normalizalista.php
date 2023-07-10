<?php
	set_time_limit(7200);
	
	$op = tdc::r("op");
	
	if ($op == "getentidades"){
		echo json_encode(tdc::da(ENTIDADE));
		exit;
	}
	
	if ($op == "addToFilhoInPai"){
		$entidadePai		= tdc::r("entidadepai");
		$entidadeFilho 		= tdc::r("entidadefilho");
		$regpai				= tdc::r("regpai");
		$regfilho			= tdc::r("regfilho");
		
		switch((int)$entidadePai){
			case 15: $campoFiltro = "td_processo"; break;
			case 20: $campoFiltro = "farein"; break;
			default: $campoFiltro = "id";
		}

		$sql = tdClass::Criar("sqlcriterio");
		$sql->addFiltro($campoFiltro,"=",$regpai); 
		$dataset = tdClass::Criar("repositorio",array(tdc::e($entidadeFilho)->nome))->carregar($sql);
		foreach($dataset as $d){

			// Add Lista
			$lista = tdClass::Criar("persistent",array(LISTA))->contexto;
			$lista->entidade_pai 	= $entidadePai;
			$lista->entidade_filho 	= $entidadeFilho;
			$lista->reg_pai 		= $regpai;
			$lista->reg_filho 		= $d->id;
			
			echo $entidadePai . "-" . $entidadeFilho . "-" . $regpai . "-" . $d->id . "</br>";
			$lista->armazenar();
		}

		Transacao::fechar();
		exit;
	}
	
	include Session::Get("PATH_CURRENT_VIEW") . "normalizalista.php";
?>