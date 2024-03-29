<?php
	$bloco_breadcrumb = tdClass::Criar("bloco");
	$bloco_breadcrumb->class = "col-md-12";
	
	$breadcrumbID 	= "breadcrumb-principal";

	$ol 			= tdClass::Criar("ol");
	$ol->class 		= "breadcrumb";
	$ol->id 		= $breadcrumbID;

	$breadcrumb_nav 			= tdc::html('nav');
	$breadcrumb_nav->aria_label = 'breadcrumb';

	$li 		= tdClass::Criar("li");
	$li->href 	= "#";	
	$li->class	= "active";
	$li->id 	= "li-home-bc";
	$li->add("Home");

	$ol->add($li);
	$breadcrumb_nav->add($ol);

	$scriptBClass 		= tdClass::Criar("script");
	$scriptBClass->src 	= URL_CLASS_WIDGETS . "bootstrap/breadcrumb.class.js";

	$bloco_breadcrumb->add($scriptBClass,$breadcrumb_nav);

	// Desabilitado para revisão de utilidade
	#$template->addCorpo($bloco_breadcrumb);