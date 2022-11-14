<?php

	// Avisos
	include PATH_MVC_CONTROLLER . "aviso.php";

	// Favorito
	include PATH_MVC_CONTROLLER . "favorito.php";

	// Chamado
	include PATH_MVC_CONTROLLER . "chamado.php";

	// Adiciona pacotes na home 
	foreach($mjc->system->packages as $p){
		switch($p){
			case 'ecommerce':
				include PATH_ECOMMERCE . 'novosclienteshome/novosclienteshome.php';
				#include PATH_ECOMMERCE . 'maisvendidoshome/maisvendidoshome.php';
				include PATH_ECOMMERCE . 'pedidohome/pedidohome.php';
				include PATH_ECOMMERCE . 'informacoesadicionaishome/informacoesadicionaishome.php';
			break;
		}
	}