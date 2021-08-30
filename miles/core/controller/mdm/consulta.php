<?php

    $id 	= $_POST["id"];
    $path 		= PATH_FILES_CONSULTA . $id . "/";

    $fp = fopen($path . $_POST["filename"] ,'w');
    fwrite($fp,htmlespecialcaracteres($_POST["html"],1));
    fclose($fp);			

    $jsFile = $path . "/" . $_POST["filenamejs"];
    if (!file_exists($jsFile)){
        $fp = fopen($jsFile ,'w');
        fwrite($fp,"// JS da Consulta");			
        fclose($fp);
    }