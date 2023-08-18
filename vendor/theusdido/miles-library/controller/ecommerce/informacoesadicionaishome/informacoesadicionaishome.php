<?php
    // Carrega os dados dos indicadores
    include PATH_ECOMMERCE . 'indicadores.php';

	$bloco_informacoesadicionais 			= tdClass::Criar("div");
	$bloco_informacoesadicionais->class 	= "col-md-12";

	$style = tdc::o('style');
	$style->add('
		.dashboard-indicador
		{
			display:flex;
			
			flex-warp:warp;
			justify-content:space-between;
			flex-basis: flex-basis:  | auto; 
		}

		.dashboard-indicador .list-group-item
		{	
			width:25rem;
			flex-grow:1;
			margin:1rem;		
			height:120px;
			border-radius:5px;
			background-color:#FF9000;
			color:#EEE;
			border-bottom:2px solid transparent;
		}
		.dashboard-indicador .list-group-item:hover,
		.dashboard-indicador .list-group-item:active,
		.dashboard-indicador .list-group-item:visited
		{
			color:#FFF;
			border-bottom:2px solid #AAA;
		}		

		.dashboard-indicador .badge
		{
			font-size:20px;
			width:50px;
			background-color:rgba(255,255,255,0.3);
			padding:0;
			line-height:50px;
		}
		.dashboard-indicador .text{
			font-size:2rem;
			position: absolute;
			bottom: 5px;
			left: 10px;
		}			
		.dashboard-indicador .value{
			font-size:3rem;
			position: absolute;
			bottom: 30px;
			left: 10px;
		}

		.dashboard-indicador .list-group-item:first-child
		{
			margin-left:0;
		}
		.dashboard-indicador .list-group-item:last-child
		{
			margin-right:0;
		}
	');
	#$bloco_informacoesadicionais->add($style);

	$listaInfoLeft			= tdClass::Criar("div");
	$listaInfoLeft->class 	= "list-group dashboard-indicador";

	$dados_informacoesadicionais = array (
		// array(
		// 	'text' 	=> 'Visitantes Online',
		// 	'value' => 0,
		// 	'href'	=> '#',
		// 	'bg'	=> '#9FCC2E',
		// 	'icon'	=> 'fa-user'
		// ),
		array(
			'text' 	=> 'Total de Visitas',
			'value' => $totalAcessos,
			'href'	=> getURLProject("index.php?controller=ecommerce/indicadores/totalvisitas"),
			'bg'	=> '#FF9000',
			'icon'	=> 'fa-person'
		),
		array(
			'text' 	=> 'Carrinhos Ativos',
			'value' => $carrinhosAtivos,
			'href'	=> getURLProject("index.php?controller=ecommerce/indicadores/carrinhosativos&currentproject=" . $_SESSION["currentproject"]),
			'bg'	=> '#25476A',
			'icon'	=> 'fa-cart-shopping'
		),
		// array(
		// 	'text' 	=> 'Carrinhos Abandonados',
		// 	'value' => $carrinhosAbandonados,
		// 	'href'	=> getURLProject("index.php?controller=ecommerce/indicadores/carrinhosabandonados"),
		// 	'bg'	=> '#03A9F4',
		// 	'icon'	=> 'fa-bag-shopping'
		// ),
		array(
			'text' 	=> 'Devoluções e Trocas',
			'value' => $pedidosDevolvidos,
			'href'	=> getURLProject("index.php?controller=ecommerce/indicadores/devolucoesetrocas"),
			'bg'	=> '#343C43',
			'icon'	=> 'fa-right-left'
		),
		array(
			'text' 	=> 'Produtos Esgotados',
			'value' => $produtosEsgotados,
			'href'	=> getURLProject("index.php?controller=ecommerce/indicadores/produtosesgotados"),
			'bg'	=> '#AB47BC',
			'icon'	=> 'fa-gift'
		)
	);
	
	$lista_informacoes_adicionais 			= tdClass::Criar("div");
	$lista_informacoes_adicionais->class 	= "list-group dashboard-indicador";	
	#$itens_informacoes_adicionais 			= array();	
	foreach($dados_informacoesadicionais as $info){
		$a 			= tdClass::Criar("hyperlink");
		$a->class	="list-group-item";
		$a->href 	= $info['href'];
		$a->style 	= 'background-color:' . $info['bg'] . ';';
		$a->target 					= "_blank";
			$icon					= tdc::html('i');
			$icon->class			= 'fa ' . $info['icon'];
			$badge 					= tdClass::Criar("span");
			$badge->class 			= "badge";
			$badge->add($icon);
			$label_valor			= tdc::html('label',(string)$info['value']);
			$label_valor->class 	= "value";
			$label_texto			= tdc::html('label',$info['text']);
			$label_texto->class 	= 'text';
		$a->add($badge,$label_valor,$label_texto);
		#$listaInfoRight->add($a);
		#$bloco_informacoesadicionais->add($a);
		#array_push($itens_informacoes_adicionais,$a);
		$lista_informacoes_adicionais->add($a);
	}

	// $a = tdClass::Criar("hyperlink");
	// $a->class="list-group-item disabled";
	// $a->href = "#";
	// 	$badge = tdClass::Criar("span");
	// 	$badge->class = "badge";
	// 	$badge->add(0);
	// $a->add($badge,"");
	// $listaInfoLeft->add($a);

	// $a = tdClass::Criar("hyperlink");
	// $a->class="list-group-item disabled";
	// $a->href = getURLProject("index.php?controller=ecommerce/indicadores/totalvisitas");
	// $a->target = "_blank";
	// 	$badge = tdClass::Criar("span");
	// 	$badge->class = "badge";
	// 	$badge->add($totalAcessos);
	// $a->add($badge,"Total de Visitas");
	// $listaInfoLeft->add($a);

	// $a = tdClass::Criar("hyperlink");
	// $a->class="list-group-item";
    // $a->href = getURLProject("index.php?controller=ecommerce/indicadores/carrinhosativos&currentproject=" . $_SESSION["currentproject"]);
    // $a->target = "_blank";
	// 	$badge = tdClass::Criar("span");
	// 	$badge->class = "badge";
	// 	$badge->add($carrinhosAtivos);
	// $a->add($badge,"Carrinhos Ativos");
	// $listaInfoLeft->add($a);

	$listaInfoRight= tdClass::Criar("div");
	$listaInfoRight->class = "list-group dashboard-indicador";
	#$listaInfoRight->style = "float:right;width:49%";

	// $a 			= tdClass::Criar("hyperlink");
	// $a->class	="list-group-item";
    // $a->href 	= getURLProject("index.php?controller=ecommerce/indicadores/carrinhosabandonados");
    // $a->target 			= "_blank";
	// 	$badge 			= tdClass::Criar("span");
	// 	$badge->class 	= "badge";
	// 	$badge->add($carrinhosAbandonados);
	// 	$label			= tdc::html('label','Carrinhos Abandonados');
	// 	$label->class 	= 'texto';
	// $a->add($badge,$label);
	// $listaInfoRight->add($a);


	// $a = tdClass::Criar("hyperlink");
	// $a->class="list-group-item";
    // $a->href = getURLProject("index.php?controller=ecommerce/indicadores/devolucoesetrocas");
    // $a->target = "_blank";
	// 	$badge = tdClass::Criar("span");
	// 	$badge->class = "badge";
	// 	$badge->add($pedidosDevolvidos);
	// 	$label			= tdc::html('label','Devoluções e Trocas');
	// 	$label->class 	= 'texto';
	// $a->add($badge,$label);
	// $listaInfoRight->add($a);

	// $a = tdClass::Criar("hyperlink");
	// $a->class="list-group-item";
    // $a->href = getURLProject("index.php?controller=ecommerce/indicadores/produtosesgotados");
    // $a->target = "_blank";
	// 	$badge = tdClass::Criar("span");
	// 	$badge->class = "badge";
	// 	$badge->add($produtosEsgotados);
	// $a->add($badge,"Produtos Esgotados");
	// $listaInfoRight->add($a);

	#$bloco_informacoesadicionais->add($style,$listaInfoLeft,$listaInfoRight);
	$bloco_informacoesadicionais->add($style,$lista_informacoes_adicionais);
	$bloco_informacoesadicionais->mostrar();