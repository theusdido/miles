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
                include_once PATH_PACKAGE . $path. ".php";
            }
            $registro = tdc::r("registro");
            if ($registro != ''){
                $path_registro = PATH_REGISTRO . $_POST["registro"]. ".php";
                if (file_exists($path_registro)){
                    include_once $path_registro;
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
        default:
            include 'core/install/configuracaopacotes.php';        
    }