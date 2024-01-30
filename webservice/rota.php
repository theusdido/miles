<?php	
	try {
		$service_router 		= $service == '' ? $_service : $service;
		$servico 				= str_replace(['.','-'],"/",$service_router);
		$path_service			= "servicos/{$servico}.php";
		$path_service_current 	= PATH_CURRENT_WEBSERVICE . $path_service;

		if (file_exists($path_service)){
			require_once $path_service;
		}else if(file_exists($path_service_current)){
			require_once $path_service_current;
		}else{
			$retorno['status'] 	= 'error';
			$retorno['msg']		= 'Serviço não encontrado';
		}
	}catch(Exeception $e){
		echo json_encode($e->getMessage());
	}