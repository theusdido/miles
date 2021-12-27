<?php
	$usuario = $senha = $base = $host = $tipo = $porta = "";
	$check_criarbase = $check_instalacaosistema = $check_pacoteconfigurado = $_SESSION["URL_SYSTEM_THEME"] . 'check-no.gif';
?>


<div class="list-group">
	<a href="<?=$_SESSION["URL_MILES"]?>?controller=install" class="list-group-item">
		Criar Banco de Dados
		<img src="<?=$check_criarbase?>" class="check-no" id="guia-pacote"/>
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