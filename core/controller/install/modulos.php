<?php
    $op = tdc::r('op');

    switch($op){
        case 'instalarcomponente':
            $componente = tdc::r('componente');
            if ($componente != ''){
                $path_componente = explode("-",$componente);
                $path = "";
                foreach($path_componente as $c){
                    $path .= "/" . $c;
                }
                $componente_path = PATH_PACKAGE . $path. ".php";
                if (file_exists($componente_path)){
                    include_once PATH_PACKAGE . $path. ".php";
                    $componente_path = PATH_CURRENT_PACKAGE . $path. ".php";
                    if (file_exists($componente_path)){
                        include_once $componente_path;
                    }
                }else{
                    echo 'Arquivo não encontrado =>  [ <b>'.$componente_path.'</b> ].';
                }
            }
            $registro = json_decode(tdc::r("registro"));
			if ($registro != null){
				if ($registro->file != ''){
					$path_registro = explode("-",$registro->path);
					$path = "";
					foreach($path_registro as $r){
						$path .= $r . "/";
					}
					$path_registro = PATH_REGISTRO . $path . $registro->file . ".php";
					if (file_exists($path_registro)){
						include_once $path_registro;
					}else{
                        echo 'Arquivo não encontrado =>  [ <b>'.$path_registro.'</b> ].';
                    }

				}
			}
            echo 1;            
        break;
        case 'atualizar':
            $query = $conn->query("UPDATE td_instalacao SET pacoteconfigurado = 1 WHERE id = 1;");
            if ($query){
                echo 1;
            }
        break;
        case 'load':
            $package    = tdc::r('package');
            $module     = tdc::r('module');

            include PATH_PACKAGE . $package . '/modulos/' . $module .'.php';
            echo json_encode($modules);
        break;
        default:
            include 'core/install/configuracaopacotes.php';        
    }