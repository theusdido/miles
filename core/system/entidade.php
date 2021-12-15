<?php
	// Formato do prefixo do MILES
	$prefixo_format = getSystemPREFIXO();

	// Constantes de entidade padrão
	define("ENTIDADE",				$prefixo_format . "entidade");
	define("ATRIBUTO",				$prefixo_format . "atributo");
	define("MENU",					$prefixo_format . "menu");
	define("USUARIO",				$prefixo_format . "usuario");
	define("USUARIOGRUPO",			$prefixo_format . "grupousuario");
	define("EMPRESA",				$prefixo_format . "empresa");
	define("PROJETO",				$prefixo_format . "projeto");
	define("ABAS",					$prefixo_format . "abas");
	define("RELACIONAMENTO",		$prefixo_format . "relacionamento");
	define("COLUNAENTIDADE",						  "entidade");
	define("LISTA",					$prefixo_format . "lista");
	define("CONFIG",				$prefixo_format . "config");
	define("PERMISSOES",			$prefixo_format . "entidadepermissoes");
	define("PERMISSOESATRIBUTO",	$prefixo_format . "atributopermissoes");
	define("FILTROATRIBUTO",		$prefixo_format . "atributofiltro");
	define("CONSULTA",				$prefixo_format . "consulta");
	define("FILTROCONSULTA",		$prefixo_format . "consultafiltro");
	define("RELATORIO",				$prefixo_format . "relatorio");
	define("FILTRORELATORIO",		$prefixo_format . "relatoriofiltro");
	define("MOVIMENTACAO",			$prefixo_format . "movimentacao");
	define("HISTORICOMOVIMENTACAO",	$prefixo_format . "movimentacaohistorico");
	define("ALTERARMOVIMENTACAO",	$prefixo_format . "movimentacaoalterar");
	define('MENUPERMISSOES',		$prefixo_format . "menupermissoes");
	define('INSTALACAO',			$prefixo_format . "instalacao");
	define('FUNCAO',				$prefixo_format . 'funcao');
	define('FUNCOESPERMISSOES',		$prefixo_format . "funcaopermissoes");
	define("LOG",					$prefixo_format . "log");
	define('CONNECTIONDATABASE',	$prefixo_format . 'connectiondatabase');
	define('AVISO',					$prefixo_format . 'aviso');

	// Formato do prefixo para pacotes
	$prefixo_package = $prefixo_format .  $mjc->system->package . '_';

	// ENTIDADES PERSONALIZADAS
	define('TD_PRODUTO',$prefixo_package . 'produto');