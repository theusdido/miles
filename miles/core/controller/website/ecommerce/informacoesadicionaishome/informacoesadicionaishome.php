<?php
    // Carrega os dados dos indicadores
    include PATH_CURRENT_FILES_ECOMMERCE . 'indicadores.php';

	$bloco_informacoesadicionais = tdClass::Criar("div");
	$bloco_informacoesadicionais->class = "col-md-12";

	$listaInfoLeft= tdClass::Criar("div");
	$listaInfoLeft->class = "list-group";
	$listaInfoLeft->style = "float:left;width:49%";

	$a = tdClass::Criar("hyperlink");
	$a->class="list-group-item";
	$a->href = "#";
		$badge = tdClass::Criar("span");
		$badge->class = "badge";
		$badge->add(0);
	$a->add($badge,"Visitantes Online");
	$listaInfoLeft->add($a);

	$a = tdClass::Criar("hyperlink");
	$a->class="list-group-item";
	$a->href = getURLProject("index.php?controller=website/ecommerce/indicadores/totalvisitas");
	$a->target = "_blank";
		$badge = tdClass::Criar("span");
		$badge->class = "badge";
		$badge->add($totalAcessos);
	$a->add($badge,"Total de Visitas");
	$listaInfoLeft->add($a);

	$a = tdClass::Criar("hyperlink");
	$a->class="list-group-item";
    $a->href = getURLProject("index.php?controller=website/ecommerce/indicadores/carrinhosativos&currentproject=" . $_SESSION["currentproject"]);
    $a->target = "_blank";
		$badge = tdClass::Criar("span");
		$badge->class = "badge";
		$badge->add($carrinhosAtivos);
	$a->add($badge,"Carrinhos Ativos 2");
	$listaInfoLeft->add($a);

	$listaInfoRight= tdClass::Criar("div");
	$listaInfoRight->class = "list-group";
	$listaInfoRight->style = "float:right;width:49%";

	$a = tdClass::Criar("hyperlink");
	$a->class="list-group-item";
    $a->href = getURLProject("index.php?controller=website/ecommerce/indicadores/carrinhosabandonados");
    $a->target = "_blank";
		$badge = tdClass::Criar("span");
		$badge->class = "badge";
		$badge->add($carrinhosAbandonados);
	$a->add($badge,"Carrinhos Abandonados");
	$listaInfoRight->add($a);


	$a = tdClass::Criar("hyperlink");
	$a->class="list-group-item";
    $a->href = getURLProject("index.php?controller=website/ecommerce/indicadores/devolucoesetrocas");
    $a->target = "_blank";
		$badge = tdClass::Criar("span");
		$badge->class = "badge";
		$badge->add($pedidosDevolvidos);
	$a->add($badge,"Devoluções e Trocas");
	$listaInfoRight->add($a);

	$a = tdClass::Criar("hyperlink");
	$a->class="list-group-item";
    $a->href = getURLProject("index.php?controller=website/ecommerce/indicadores/produtosesgotados");
    $a->target = "_blank";
		$badge = tdClass::Criar("span");
		$badge->class = "badge";
		$badge->add($produtosEsgotados);
	$a->add($badge,"Produtos Esgotados");
	$listaInfoRight->add($a);

	$bloco_informacoesadicionais->add($listaInfoLeft,$listaInfoRight);
	$bloco_informacoesadicionais->mostrar();