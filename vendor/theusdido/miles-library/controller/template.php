<?php	

	// Template
	$template = tdc::o("layout");

	// Breadcrumb
	include PATH_MVC_CONTROLLER . "breadcrumb.php";
	
	// Menu Lateral Esquerdo Principal
	$menulateralesquerdoprincipal = tdClass::Criar("bloco",array("menulateralesquerdoprincipal"));
	
	// Conteúdo Principal
	$conteudoprincipal = tdClass::Criar("div");
	$conteudoprincipal->id = "conteudoprincipal";
	
	// Bloco de Conteúdo Principal
	$blocoConteudoPrincipal = tdClass::Criar("bloco",array(""));	
	$blocoConteudoPrincipal->class = "col-md-12";
	
	// Loader Principal do Sistema
	$loadergeral = tdClass::Criar("div");
	$loadergeral->class = "loadergeral";
	$loadergeral->add('
		<center>
			<img width="32" align="middle" src="'.URL_LOADING.'">
			<p class="text-muted">Aguarde</p>
		</center>
	');

	// Carrega estrutura principal dod sistema
	$blocoConteudoPrincipal->add($loadergeral,$conteudoprincipal);
	$template->addCorpo($menulateralesquerdoprincipal,$blocoConteudoPrincipal);
	$template->mostrar();