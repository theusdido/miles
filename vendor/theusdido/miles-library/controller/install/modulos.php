<?php
    $op = tdc::r('op');
    switch($op){
        case 'instalarcomponente':
            $componente = tdc::r('componente');
            if ($componente != ''){
                $path               = str_replace('-','/', str_replace('--','-',$componente));
                $_componente_path   = PATH_PACKAGE . $path. ".php";
                if (file_exists($_componente_path)){
                    include_once $_componente_path;
                }else{
                    echo 'Arquivo componente não encontrado =>  [ <b>'.$_componente_path.'</b> ].';
                }
            }
            $registro = tdc::r("registro");
			if ($registro != null && $registro != ''){
                $registro = json_decode(tdc::r("registro"));
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
                        echo 'Arquivo registro não encontrado =>  [ <b>'.$path_registro.'</b> ].';
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
        case 'generate-menu':
            $menu = addMenu($conn,tdc::r('module_name'),'#','',0,0,tdc::r('module'));

            foreach(tdc::r('components') as $c){
                addMenu($conn,$c['title'],'#','',$menu,1,tdc::r('module') . '-' . $c['name'],0,'personalizado');
            }
        break;
        default:
            include PATH_MILES_LIBRARY . 'install/configuracaopacotes.php';
    }