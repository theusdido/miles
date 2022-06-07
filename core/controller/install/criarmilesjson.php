<?php
    $fpMilesJSON = fopen($path_miles_json,"w");
    fwrite($fpMilesJSON,'
        {
<<<<<<< HEAD
            "version":2.0,
            "currentproject":1,
            "folder":"'.removeBarraRoot($uri).'",
            "system":{
                "url":{
                    "lib":"https://teia.tec.br/miles/sistema/lib/"
                },
                "request_protocol":"http",
                "packages":[]
            },
            "theme":"desktop",
            "prefix":"td",
            "is_show_error_message":false,
            "is_transaction_log":false,
            "database_current": "desenv",
            "port": "auto"
=======
            "currentproject":1,
            "folder":"miles/",
            "system":{
                "url":{
                    "lib":"http://teia.tec.br/miles/sistema/lib/"
                },
                "request_protocol":"http",
                "package":"erp"
            },
            "theme":"desktop",
            "prefix":"td"
>>>>>>> 287b430 (instalação góes)
        }
    ');
    fclose($fpMilesJSON);