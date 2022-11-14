<?php

	header("Content-Type: application/json", true);

	$entidade = tdc::r("entidade");
	if ($entidade == "" || (int)$entidade <= 0){
		echo 'Entidade não encontrada';
		exit;
	}

	$grade = new Grade($entidade);
	$grade->qtdadeMaximaRegistroPaginacao 	= tdc::r("qtdademaximaregistro",10);
	$grade->bloco 							= tdc::r("bloco",1);
	$grade->ordenacao(tdc::r("order",[]));
	$grade->filtros(tdc::r("filtros"));
	$grade->filtro(tdc::r("filtro"));
	$grade->filtro(tdc::r("filtroNN"));
	echo $grade->json();