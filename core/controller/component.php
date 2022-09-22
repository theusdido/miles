<?php

    // Controla a variável dentro do mesmo arquivo
    $_component = $_component_path;

	// CSS System
	$cssFile = PATH_SYSTEM_COMPONENT . $_component . '.css';
	if (file_exists($cssFile)){
		$css 		= tdc::o("link");
		$css->href 	= URL_COMPONENT . $_component . '.css';
		$css->rel 	= "stylesheet";
		$css->mostrar();
	}

    // CSS Current
	$cssFile = PATH_CURRENT_COMPONENT . $_component . '.css';
	if (file_exists($cssFile)){
		$css 		= tdc::o("link");
		$css->href 	=  URL_CURRENT_COMPONENT . $_component . '.css';
		$css->rel 	= "stylesheet";
		$css->mostrar();
	}
		
	// Carrega o HTML antes por causa do file_get_contents, erro de cabeçaho
	$htmlFile 	= PATH_SYSTEM_COMPONENT . $_component . '.html';
	if (file_exists($htmlFile)){
		echo @getUrl(URL_COMPONENT . $_component . '.html');
	}

	$htmlFile 	= PATH_CURRENT_COMPONENT . $_component . '.html';    
	if (file_exists($htmlFile)){
		echo @getUrl(URL_CURRENT_COMPONENT . $_component . '.html');
	}

	// Carrega arquivo JavaScript
	$jsFile = PATH_SYSTEM_COMPONENT . $_component . '.js';
	if (file_exists($jsFile)){
		$js 		= tdc::o('script');
		$js->src 	= URL_COMPONENT . $_component . '.js';
		$js->mostrar();
	}

	$jsFile = PATH_CURRENT_COMPONENT . $_component . '.js';
	if (file_exists($jsFile)){
		$js 		= tdc::o('script');
		$js->src 	= URL_CURRENT_COMPONENT . $_component . '.js';
		$js->mostrar();
	}