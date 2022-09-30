<?php
	$bloco_breadcrumb = tdClass::Criar("bloco");
	$bloco_breadcrumb->class = "col-md-12";
	
	$breadcrumbID = "breadcrumb-principal";

	$ol = tdClass::Criar("ol");
	$ol->class = "breadcrumb";
	$ol->id = $breadcrumbID;

	$li = tdClass::Criar("li");
	$li->href = "#";
	$li->add("Home");
	$li->class="active";
	$li->id = "li-home-bc";

	$ol->add($li);

	$scriptBClass 		= tdClass::Criar("script");
	$scriptBClass->src 	= URL_CLASS_WIDGETS . "bootstrap/breadcrumb.class.js";

	$bloco_breadcrumb->add($scriptBClass,$ol);
	$template->addCorpo($bloco_breadcrumb);