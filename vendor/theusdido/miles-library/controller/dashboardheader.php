<?php
	$dashboard_header 			= tdClass::Criar("bloco",array('dashboard_header'));
	$dashboard_header->class 	= "col-md-9 col-sm-8";

    $titulo_dashboard_header = tdClass::Criar("h",array(1));
    $titulo_dashboard_header->add('Dashboard');
	$dashboard_header->add($titulo_dashboard_header);
