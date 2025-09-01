<?php	

	$cssNovosClientes = tdClass::Criar("style");
	$cssNovosClientes->add('
		#listanovosclienteshome.list-group .list-group-item{
			
		}

		#listanovosclienteshome .media
		{
			padding:10px;
			margin:10px;
			cursor:pointer;
			border-radius:5px;
		}

		#listanovosclienteshome .media:hover,
		#listanovosclienteshome .media-heading:hover
		{
			background-color:#6faae5;
			color:#FFF;
		}
		#listanovosclienteshome .media-left .media-object
		{
			font-size:2rem;
			background-color:#EEE;
			padding:10px;
			border-radius:100%;
			color:#25476A;
		}

		#listanovosclienteshome .media-body .media-body-content
		{
			display:flex;
			flex-direction:column;
			align-content:center;
		}
		#listanovosclienteshome .media-body .media-body-content *
		{
			margin:0;
		}

		#listanovosclienteshome .media-heading
		{
			font-weight:bold;
		}
		
	');
	$bloco_novosclientes 		= tdClass::Criar("bloco");
	$bloco_novosclientes->class = "col-md-6";

	$panel 			= tdClass::Criar("panel");
	$panel->head("Novos Clientes");
	$panel->tipo 	= "info";

	$listaNovosClientes 		= tdClass::Criar("div");
	#$listaNovosClientes->class 	= "list-group";
	$listaNovosClientes->id 	= "listanovosclienteshome";
	$listaNovosClientes->class 	= 'media-list';

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
		#$a->class		= "list-group-item ". $descatarsinativo;
		#$a->href 		= "#";
		#$a->onclick 	= "editarTDFormulario(".$cliente->getID().",".$cliente->id.");";
		#$a->add("ID: " . $cliente->id . " - " . substr($cliente->nome,0,50));
		#$listaNovosClientes->add($a);
		

    // Endereço do Cliente
    $endereco_cliente                       = new Endereco();
	$endereco_cliente->cliente				= $cliente->id;
	$endereco_cliente->entidadecliente 	    = $cliente->getID();
	$endereco_cliente->entidadeendereco 	= getEntidadeId('td_ecommerce_endereco');

		$media 				= tdc::html('div');
		$media->class 		= 'media';
		$media->onclick 	= "editarTDFormulario(".$cliente->getID().",".$cliente->id.");";

		$media_left 		= tdc::html('div');
		$media_left->class	= 'media-left media-middle';

		$media_object			= tdc::o('i');
		$media_object->class	= 'fa fa-user media-object';

		$media_body 		= tdc::html('div');
		$media_body->class	= 'media-body';

		$media_body_content	= tdc::html('div');
		$media_body_content->class = 'media-body-content';

		$media_title		= tdc::o('h',[5]);
		$media_title->class	= 'media-heading';
		$media_title->add('#'.$cliente->id.' - '.$cliente->nome);

		$endereco_cliente 	= tdc::html('small',substr(tdc::utf8($endereco_cliente->getLinha()),0,70));

		$icon_edit			= tdc::o('i');
		$icon_edit->class	= 'fa fa-pencil';
		
		$media_right 		= tdc::html('div');
		$media_right->class	= 'media-right media-middle';

		$media_right->add($icon_edit);
		$media_left->add($media_object);
		$media_body_content->add($media_title, $endereco_cliente);
		$media_body->add($media_body_content);
		$media->add($media_left,$media_body,$media_right);
		$listaNovosClientes->add($media);
	}
	$panel->body($listaNovosClientes);
	$bloco_novosclientes->add($cssNovosClientes,$panel);
	$bloco_novosclientes->mostrar();