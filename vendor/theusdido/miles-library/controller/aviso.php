<?php
	$bloco_aviso = tdClass::Criar("bloco");
	$bloco_aviso->class = "col-md-12";

	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("datainicio",'>=',date("Y-m-d"));
	$sql->addFiltro("datainicio",'<=',date("Y-m-d"));
	$sql->addFiltro("projeto",'=',CURRENT_PROJECT_ID);
	$sql->addFiltro("empresa",'=',Session::get()->empresa);
	$dataset = tdClass::Criar("repositorio",array(PREFIXO . "_aviso"))->carregar($sql);
	foreach($dataset as $aviso){
		$panel = tdClass::Criar("panel");
		$panel->head("AVISO");
		switch ($aviso->tipoaviso){
			case 1:
				$panel->tipo = "success";
			break;
			case 2:
				$panel->tipo = "warning";
			break;
			case 3:
				$panel->tipo = "danger";
			break;
			case 4:
				$panel->tipo = "info";
			break;
		}
		$panel->body($aviso->mensagem);
		$bloco_aviso->add($panel);
	}
	
	if (sizeof($dataset) > 0){
		$bloco_aviso->mostrar();
	}