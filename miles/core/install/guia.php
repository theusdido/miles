<?php
	$usuario = $senha = $base = $host = $tipo = $porta = "";
	$check_criarbase = $check_instalacaosistema = $check_pacoteconfigurado = $_SESSION["URL_SYSTEM_THEME"] . 'check-no.gif';

	/*
	$sqlinstalacao = "SELECT * FROM td_instalacao WHERE id = 1;";
	$queryinstalacao = $conn->query($sqlinstalacao);
	if ($queryinstalacao){
		$linhainstalacao = $queryinstalacao->fetch();
		
		if ($linhainstalacao["bancodedadoscriado"] == 1){
			$check_criarbase = $_SESSION["URL_SYSTEM_THEME"] . 'check.gif';
			$_SESSION["CRIARBASE"] = 1;
		}
		if ($linhainstalacao["sistemainstalado"] == 1){
			$check_instalacaosistema = $_SESSION["URL_SYSTEM_THEME"] . 'check.gif';				
			$_SESSION["INSTALACAOSISTEMA"] = 1;
		}
		if ($linhainstalacao["pacoteconfigurado"] == 1){
			$check_pacoteconfigurado = $_SESSION["URL_SYSTEM_THEME"] . 'check.gif';
			$_SESSION["PACOTECONFIGURACAO"] = 1;
		}
	}
	*/

?>
<div class="list-group">
	<a href="index.php?currentproject=<?=$_SESSION["currentproject"]?>" class="list-group-item">
		Criar Banco de Dados
		<img src="<?=$check_criarbase?>" class="check-no" id="guia-base"/>
	</a>
	<a href="<?=$_SESSION["URL_MILES"]?>?controller=install/pacotes" class="list-group-item">
		Instalação do Sistema
		<img src="<?=$check_instalacaosistema?>" class="check-no" id="guia-instalacao"/>
	</a>
	<a href="<?=$_SESSION["URL_MILES"]?>?controller=install/modulos" class="list-group-item">
		Configuração dos Pacotes
		<img src="<?=$check_pacoteconfigurado?>" class="check-no" id="guia-pacote"/>
	</a>
	<a href="../index.php?controller=teste/instalacao&currentproject=<?=$_SESSION["currentproject"]?>" class="list-group-item" target="_blank">
		Check Out
		<img src="<?=$_SESSION["URL_SYSTEM_THEME"]?>check-no.gif" class="check-no" id="guia-checkout"/>
	</a>
</div>