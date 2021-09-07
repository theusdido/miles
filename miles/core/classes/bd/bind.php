<?php
	$entidade = "";
	$id = tdc::r("id");
	$binds = array();
	$htmlparams = tdc::r("html");

	if ($htmlparams != ""){
		$html = htmlespecialcaracteres(tdc::r("html"),7);
	}

	$bind = tdc::o("bind");
	$bind->id 			= $id;
	$bind->html 		= $html;
	$retorno["html"] 	= $bind->getHtml();
	echo json_encode($retorno);