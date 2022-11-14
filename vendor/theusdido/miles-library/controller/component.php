<?php

	$_type_component 	= tdc::r('type');
	$is_scope_component	= false;

	if ($_type_component != '' && $_type_component != 'none'){
		$is_scope_component = true;
	}

	// CSS System
	$cssFile = PATH_SYSTEM_COMPONENT . $_component_path . '.css';
	if (file_exists($cssFile)){
		$css 		= tdc::o("link");
		$css->href 	= URL_COMPONENT . $_component_path . '.css';
		$css->rel 	= "stylesheet";
		if (!$is_scope_component){
			$css->mostrar();
		}
	}

    // CSS Current
	$cssFile = PATH_CURRENT_COMPONENT . $_component_path . '.css';
	if (file_exists($cssFile)){
		$css 		= tdc::o("link");
		$css->href 	=  URL_CURRENT_COMPONENT . $_component_path . '.css';
		$css->rel 	= "stylesheet";
		if (!$is_scope_component){
			$css->mostrar();
		}
	}
		
	// Carrega o HTML antes por causa do file_get_contents, erro de cabeçaho
	$htmlFile 	= PATH_SYSTEM_COMPONENT . $_component_path . '.html';
	if (file_exists($htmlFile)){
		$html_file =  @getUrl(URL_COMPONENT . $_component_path . '.html');
		if (!$is_scope_component){
			echo $html_file;
		}	
	}

	$htmlFile 	= PATH_CURRENT_COMPONENT . $_component_path . '.html';    
	if (file_exists($htmlFile)){
		if (!$is_scope_component){
			echo $html_file;
		}
	}

	// Carrega arquivo JavaScript
	$jsFile = PATH_SYSTEM_COMPONENT . $_component_path . '.js';
	if (file_exists($jsFile)){
		$js 		= tdc::o('script');
		$js->src 	= URL_COMPONENT . $_component_path . '.js';
		if (!$is_scope_component){
			$js->mostrar();
		}		
	}

	$jsFile = PATH_CURRENT_COMPONENT . $_component_path . '.js';
	if (file_exists($jsFile)){
		$js 		= tdc::o('script');
		$js->src 	= URL_CURRENT_COMPONENT . $_component_path . '.js';
		if (!$is_scope_component){
			$js->mostrar();
		}
	}

	switch($_type_component){
		case 'iframe':
			$_scope_iframe = tdc::o('iframe');
			$_scope_iframe->src = '?controller=component'
			. '&_component=' . $_component . '&type=none';
			$_scope_iframe->mostrar();
		break;
		case 'div':
			$_scope_div = tdc::o('div');
		break;
	}
