<?php
	$logo = tdClass::Criar("bloco",array('logo'));
	$logo->class = "col-md-3 col-sm-3";
	$img_logo = tdClass::Criar('imagem');
	$img_logo->src = Session::Get("URL_CURRENT_LOGO_PADRAO");
	$logo->add($img_logo);