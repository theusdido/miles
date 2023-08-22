<?php
    if (tdc::r('op') == 'instalar'){
        // Na instalação o path do arquivo miles deve sero do projeto
        $_path_project_miles_json	        = $_path_project_install . 'dev.miles.json';
        // Seta o id com o nome do diretório na instação
        $_project_name_identifify_params    = $_project_folder;
    }else{
        $_path_project_miles_json           = $_path_main_miles_json;
        if (file_exists($_path_project_miles_json)){
            unlink($_path_project_miles_json);
        }
    }

    try {

        
        $fpMilesJSON = fopen($_path_project_miles_json,"w");
        fwrite($fpMilesJSON,'
{
    "version": 2.0,
    "project":{
        "id":"'.$_project_name_identifify_params.'",
        "name":"'.$_project_name.'",
        "path":"'.$_project_folder.'/",
        "url":"'.$_project_dominio.'"
    },
    "currentproject": 1,
    "folder":"miles/",
    "system": {
        "url": {
            "lib": "https://teia.tec.br/miles/repository/lib/"
        },
        "request_protocol": "http",
        "packages": []
    },
    "theme": "desktop",
    "prefix": "'.$_project_prefix.'",
    "is_show_error_message": false,
    "is_show_warn_message": false,
    "is_transaction_log": false,
    "database_current": "desenv",
    "port": "hidden",
    "enviromment":"dev",
    "enviromments":{
        "dev":{
            "root":"/",
            "url":{
                "domain":"'.$_project_dominio.'"
            },
            "frameworks":{
                "angular":{
                    "port":4200
                }
            },
            "system":{
                "request_protocol": "http",
                "force_https":false,
                "is_fixed_domain":false
            }
        },
        "homolog":{
            "root":"/",
            "url":{
                "domain":"'.$_project_dominio.'"
            },
            "system":{
                "request_protocol": "https",
                "force_https":false,
                "is_fixed_domain":true
            }
        },                        
        "prod":{
            "root":"/",
            "url":{
                "domain":"'.$_project_dominio.'"
            },
            "system":{
                "request_protocol": "https",
                "force_https":true,
                "is_fixed_domain":true
            }
        }
    }
}
');
        fclose($fpMilesJSON);
        chmod($_path_project_miles_json,0777);
    }catch(Throwable $t){
        showMessage($t->getMessage());
        echo '<a href="/install">Cliqui aqui para instalar um novo projeto.</a>';
        exit;
    }