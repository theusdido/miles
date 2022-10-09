<?php
	$loader = tdClass::Criar("div");
	$loader->class = "loadergeral";
	
	$lcenter = tdClass::Criar("center");
	
	$limagem 		= tdClass::Criar("imagem");
	$limagem->align = "middle";
	$limagem->src 	= URL_LOADING;
	
	$lp = tdClass::Criar("p");
	$lp->class = "text-muted">
	$lp->add("Aguarde");
	
	$lcenter($limagem,$lp);
	$loader->add($lcenter);
	
	$loader->mostrar();