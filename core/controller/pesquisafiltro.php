<?php
	// Entidade 
	$entidade = tdClass::Criar("persistent",array(ENTIDADE,$_GET["entidade"]));	
	
	// Paginação
	$paginacao = tdClass::Criar('paginacao');
	$paginacao->repositorio = tdClass::Criar("repositorio",array($entidade->contexto->nome));
	$paginacao->entidade = $entidade; # Usado apenas para montar a grade ( não deveria ser assim )
	$paginacao->retornaregistro = "buscarFiltro";
	$paginacao->qbloco = 5;
	$paginacao->contexto = $_GET["contexto"];
	$paginacao->mostrar();