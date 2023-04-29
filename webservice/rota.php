<?php	
	try {
		$service_router = $service == '' ? $_service : $service;
		$servico 		= str_replace(['.','-'],"/",$service_router);
		$path_service	= "servicos/{$servico}.php";

		if (file_exists($path_service)){
			require_once $path_service;
		}else{
			$retorno['status'] 	= 'error';
			$retorno['msg']		= 'Serviço não encontrado';
		}
	}catch(Exeception $e){
		echo json_encode($e->getMessage());
	}