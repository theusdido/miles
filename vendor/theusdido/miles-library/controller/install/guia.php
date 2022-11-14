<?php
    switch($_op){
        case 'status':
            
            $response   = array();
            $check_no   = URL_SYSTEM_THEME . 'check-no.gif';        
            $check_yes  = URL_SYSTEM_THEME . 'check.gif';

            $conn_temp              = Conexao::getDados('temp');
            if ($conn_temp == NULL){
                $response['check_criarbase']            = $check_no;
                $response['check_instalacaosistema']    = $check_no;
                $response['check_pacoteconfigurado']    = $check_no;
            }else{
                $new_conn               = new stdClass;
                $new_conn->type         = $conn_temp["tipo"];
                $new_conn->host         = $conn_temp["host"];
                $new_conn->base         = $conn_temp["base"];
                $new_conn->user         = $conn_temp["usuario"];
                $new_conn->password     = $conn_temp["senha"];
                $new_conn->port         = $conn_temp["porta"];

                //$install                                = tdc::rua('td_instalacao');
                $response['check_criarbase']            = tdInstall::isCreateDatabase($new_conn)  ? $check_yes : $check_no;
                //$response['check_instalacaosistema']    = $install['sistemainstalado']    ? $check_yes : $check_no;
                //$response['check_pacoteconfigurado']    = $install['pacoteconfigurado']   ? $check_yes : $check_no;
            }
            tdc::wj($response);
        break;
    }