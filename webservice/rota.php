<?php	
	try {
		$servico = str_replace(".","/",tdClass::Read("service"));
		require_once "servicos/{$servico}.php";
	}catch(Exeception $e){
		echo json_encode($e->getMessage());
	}