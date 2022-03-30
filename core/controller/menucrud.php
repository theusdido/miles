<?php

	$linha_menucrud1 = tdClass::Criar("div");
	$linha_menucrud1->class = "row-fluid";

	$linha_menucrud2 = tdClass::Criar("div");
	$linha_menucrud2->class = "row-fluid";


	// Coluna Cadastro
	$colCadastro = tdClass::Criar("div");
	$colCadastro->class = "col-md-6";

	// Panel Cadastro
	$panelCadastro = tdClass::Criar("panel");
	$panelCadastro->head("Cadastro" );
	$panelCadastro->tipo = "default";
	$panelCadastro->class = "panel-menucrud";

	// Coluna Cadastro
	$colConsulta = tdClass::Criar("div");
	$colConsulta->class = "col-md-6";

	// Panel Consulta
	$panelConsulta = tdClass::Criar("panel");
	$panelConsulta->head("Consulta" );
	$panelConsulta->tipo = "default";
	$panelConsulta->class = "panel-menucrud";

	// Coluna Movimentação
	$colMovimentacao = tdClass::Criar("div");
	$colMovimentacao->class = "col-md-6";

	// Panel Movimentação
	$panelMovimentacao = tdClass::Criar("panel");
	$panelMovimentacao->head("Movimentação");
	$panelMovimentacao->tipo = "default";
	$panelMovimentacao->class = "panel-menucrud";	

	// Coluna Relatório
	$colRelatorio = tdClass::Criar("div");
	$colRelatorio->class = "col-md-6";

	// Panel Relatório
	$panelRelatorio = tdClass::Criar("panel");
	$panelRelatorio->head("Relatório");
	$panelRelatorio->tipo = "default";
	$panelRelatorio->class = "panel-menucrud";

	
	$listaCadastro = tdClass::Criar("div");
	$listaCadastro->class = "list-group lista-item-menu-crud";

	$listaConsulta = tdClass::Criar("div");
	$listaConsulta->class = "list-group lista-item-menu-crud";

	$listaMovimentacao = tdClass::Criar("div");
	$listaMovimentacao->class = "list-group lista-item-menu-crud";

	$listaRelatorio = tdClass::Criar("div");
	$listaRelatorio->class = "list-group lista-item-menu-crud";

	if ($conn = Transacao::Get()){
		$sqlMenuCrud = "select descricao,link,tipo from td_menucruditens WHERE menucrud = " . $_GET["menu"];
		$queryMenuCrud = $conn->query($sqlMenuCrud);
		while ($linhaMenuCrud = $queryMenuCrud->fetch()){
			$a = tdClass::Criar("hyperlink");
			$a->class="list-group-item";
			$a->href = "#";
			$a->onclick = "menuprincipal.menuselecionado = ".$_GET["menu"].";console.log(".$_GET["menu"].");menuprincipal.carregarpagina('".$linhaMenuCrud["link"]."','#conteudoprincipal');";
			$a->add($linhaMenuCrud["descricao"]);

			switch($linhaMenuCrud["tipo"]){
				case 1: $listaCadastro->add($a); break;
				case 2: $listaConsulta->add($a); break;
				case 3: $listaMovimentacao->add($a); break;
				case 4: $listaRelatorio->add($a); break;
			}
		}
	}

	$panelCadastro->body($listaCadastro);
	$panelConsulta->body($listaConsulta);
	$panelMovimentacao->body($listaMovimentacao);
	$panelRelatorio->body($listaRelatorio);


	$colCadastro->add($listaCadastro->qtde_filhos>0?$panelCadastro:null);
	$colConsulta->add($listaConsulta->qtde_filhos>0?$listaConsulta:null);
	$colMovimentacao->add($listaMovimentacao->qtde_filhos>0?$listaMovimentacao:null);
	$colRelatorio->add($listaRelatorio->qtde_filhos>0?$listaRelatorio:null);


	$linha_menucrud1->add($colCadastro,$colConsulta);
	$linha_menucrud1->mostrar();
	$linha_menucrud2->add($colMovimentacao,$colRelatorio);
	$linha_menucrud2->mostrar();
