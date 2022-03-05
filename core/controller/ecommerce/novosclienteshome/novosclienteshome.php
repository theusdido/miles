<?php	

	$cssNovosClientes = tdClass::Criar("style");
	$cssNovosClientes->add('
		#listanovosclienteshome.list-group .list-group-item{
			margin-bottom: 3px;
		}
	');
	$bloco_novosclientes 		= tdClass::Criar("bloco");
	$bloco_novosclientes->class = "col-md-6";

	$panel 			= tdClass::Criar("panel");
	$panel->head("Novos Clientes");
	$panel->tipo 	= "info";

	$listaNovosClientes 		= tdClass::Criar("div");
	$listaNovosClientes->class 	= "list-group";
	$listaNovosClientes->id 	= "listanovosclienteshome";

	$sql = tdClass::Criar("sqlcriterio");
	$sql->setPropriedade("limit",5);
	$sql->setPropriedade("order","id DESC");	
	$dataset = tdClass::Criar("repositorio",array("td_ecommerce_cliente"))->carregar($sql);
	foreach($dataset as $cliente){
		$a = tdClass::Criar("hyperlink");
		if ($cliente->inativo == 1){
			$descatarsinativo = 'list-group-item-danger';
		}else{
			$descatarsinativo = 'list-group-item-success';
		}
		$a->class		= "list-group-item ". $descatarsinativo;
		$a->href 		= "#";
		$a->onclick 	= "editarTDFormulario(".$cliente->getID().",".$cliente->id.");";
		$a->add("ID: " . $cliente->id . " - " . substr($cliente->nome,0,50));
		$listaNovosClientes->add($a);
	}
	$panel->body($listaNovosClientes);
	$bloco_novosclientes->add($cssNovosClientes,$panel);
	$bloco_novosclientes->mostrar();