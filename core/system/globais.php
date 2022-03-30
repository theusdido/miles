<?php

	// Define o diretório raiz do MILES caso não seja informado manualmente
	if (!defined('PATH_MILES')) define('PATH_MILES',dirname($_SERVER['SCRIPT_FILENAME']) . '/');
	
	// Seta as constantes
	require 'constantes.php';