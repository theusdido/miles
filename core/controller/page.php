<?php

	// Carrega o arquivo CSS
	$cssFile = PATH_SYSTEM_PAGE . $_page . '.css';
	if (file_exists($cssFile)){
		$css 		= tdc::o("link");
		$css->href 	= URL_PAGE . $_page . '.css';
		$css->rel 	= "stylesheet";
		$css->mostrar();
	}

	$cssFile = PATH_CURRENT_PAGE . $_page . '.css';
	if (file_exists($cssFile)){
		$css 		= tdc::o("link");
		$css->href 	=  URL_CURRENT_PAGE . $_page . '.css';
		$css->rel 	= "stylesheet";
		$css->mostrar();
	}
		
	// Carrega o HTML antes por causa do file_get_contents, erro de cabeçaho
	$htmlFile 	= PATH_SYSTEM_PAGE . $_page . '.html';
	if (file_exists($htmlFile)){
		echo @getUrl(URL_PAGE . $_page . '.html');
	}

	$htmlFile 	= PATH_CURRENT_PAGE . $_page . '.html';
	if (file_exists($htmlFile)){
		echo @getUrl(URL_CURRENT_PAGE . $_page . '.html');
	}

	// Carrega arquivo JavaScript
	$jsFile = PATH_SYSTEM_PAGE . $_page . '.js';
	if (file_exists($jsFile)){
		$js 		= tdc::o('script');
		$js->src 	= URL_PAGE . $_page . '.js';
		$js->mostrar();
	}

	$jsFile = PATH_CURRENT_PAGE . $_page . '.js';
	if (file_exists($jsFile)){
		$js 		= tdc::o('script');
		$js->src 	= URL_CURRENT_PAGE . $_page . '.js';
		$js->mostrar();
	}