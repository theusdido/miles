<?php
	$_service = tdClass::Read("service");
	if ($_service == ''){
		echo 'Serviço não especificado';
		exit;
	}
	try {
		$servico = str_replace(".","/",tdClass::Read("service"));
		require_once "servicos/{$servico}.php";
	}catch(Exeception $e){
		echo json_encode($e->getMessage());
	}