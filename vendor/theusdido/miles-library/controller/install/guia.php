<?php
    switch($_op){
        case 'status':
            $response   = array();
            $check_no   = URL_SYSTEM_THEME . 'check-no.gif';        
            $check_yes  = URL_SYSTEM_THEME . 'check.gif';

            $conn_temp  = Conexao::getDados('temp');
            $_install   = tdInstall::getInstallDB();

            // Se tiver um arquivo temporário a ainda não tiver uma instação
            if (!$_is_installed && $conn_temp != NULL){
                $response['check_criarbase']            = $_install->bancodedadoscriado ? $check_yes : $check_no;
                $response['check_instalacaosistema']    = $_install->sistemainstalado   ? $check_yes : $check_no;
                $response['check_pacoteconfigurado']    = $_install->pacoteconfigurado  ? $check_yes : $check_no;
                $response['database']                   = $conn_temp;
                $response['installed']                  = false;
            }else if (!$_is_installed){
                $response['check_criarbase']            = $check_no;
                $response['check_instalacaosistema']    = $check_no;
                $response['check_pacoteconfigurado']    = $check_no;
                //$response['database']                   = Conexao::getDados();
                $response['installed']                  = false;
            }else if ($_install != NULL){
                $response['check_criarbase']            = $_install->bancodedadoscriado ? $check_yes : $check_no;
                $response['check_instalacaosistema']    = $_install->sistemainstalado   ? $check_yes : $check_no;
                $response['check_pacoteconfigurado']    = $_install->pacoteconfigurado  ? $check_yes : $check_no;
                $response['database']                   = Conexao::getDados();
                $response['installed']                  = true;
            }else if ($conn_temp == NULL){
                $response['check_criarbase']            = $check_no;
                $response['check_instalacaosistema']    = $check_no;
                $response['check_pacoteconfigurado']    = $check_no;
                $response['installed']                  = false;
            }else{
                $new_conn               = new stdClass;
                $new_conn->type         = $conn_temp["tipo"];
                $new_conn->host         = $conn_temp["host"];
                $new_conn->base         = $conn_temp["base"];
                $new_conn->user         = $conn_temp["usuario"];
                $new_conn->password     = $conn_temp["senha"];
                $new_conn->port         = $conn_temp["porta"];
                $response['installed']  = false;
                //$install                                = tdc::rua('td_instalacao');
                $response['check_criarbase']            = tdInstall::isCreateDatabase($new_conn)  ? $check_yes : $check_no;
                //$response['check_instalacaosistema']    = $install['sistemainstalado']    ? $check_yes : $check_no;
                //$response['check_pacoteconfigurado']    = $install['pacoteconfigurado']   ? $check_yes : $check_no;
            }
            tdc::wj($response);
        break;
    }