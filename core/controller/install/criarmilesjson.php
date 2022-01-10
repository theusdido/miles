<?php
    $fpMilesJSON = fopen($path_miles_json,"w");
    fwrite($fpMilesJSON,'
        {
            "version":2.0,
            "currentproject":1,
            "folder":"'.removeBarraRoot($uri).'",
            "system":{
                "url":{
                    "lib":"https://teia.tec.br/miles/sistema/lib/"
                },
                "request_protocol":"https",
                "package":"erp",
                "packages":[]
            },
            "theme":"desktop",
            "prefix":"td",
            "is_show_error_message":false,
            "is_transaction_log":false
        }
    ');
    fclose($fpMilesJSON);