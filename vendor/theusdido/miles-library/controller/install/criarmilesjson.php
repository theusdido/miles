<?php
    if (!file_exists($path_miles_json)){
        $fpMilesJSON = fopen($path_miles_json,"w");
        fwrite($fpMilesJSON,'
            {
                "version": 2.0,
                "project":{
                    "name":"Miles"
                },
                "currentproject": 1,
                "folder":"'.str_replace('//','',removeBarraRoot($uri)).'/",
                "system": {
                    "url": {
                        "lib": "https://teia.tec.br/miles/repository/lib/"
                    },
                    "request_protocol": "http",
                    "packages": []
                },
                "theme": "desktop",
                "prefix": "td",
                "is_show_error_message": false,
                "is_transaction_log": false,
                "database_current": "desenv",
                "port": "hidden",
                "enviromment":"dev",
                "enviromments":{
                    "dev":{
                        "root":"/",
                        "url":{
                            "domain":"localhost"
                        },
                        "frameworks":{
                            "angular":{
                                "port":4200
                            }
                        },
                        "system":{
                            "request_protocol": "http",
                            "force_https":false
                        }
                    },
                    "prod":{
                        "root":"/",
                        "url":{
                            "domain":""
                        },
                        "system":{
                            "request_protocol": "https",
                            "force_https":true
                        }
                    }
                }
            }   
        ');
        fclose($fpMilesJSON);
    }