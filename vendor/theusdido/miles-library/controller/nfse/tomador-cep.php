<?php
    $op  = tdc::r('op');
    switch($op){
        case 'upload':
            set_time_limit(3600);
            $link = PATH_CURRENT_FILE_TEMP . date('Y-m-d h:i:s') . ".xml";
            Session::append("NFSE_XML_TOMADOR_CEP_FILE",$link);
            if (file_exists($link)){
                unlink($link);
            }
            $uploaded   = move_uploaded_file(tdc::r("arquivo")["tmp_name"],$link);
            if (file_exists($link)){
                $config = $config = file ($link);
                echo sizeof($config);
            }else{		
                echo 0;
            }  
        break;
        case 'salvar':

            $tags_erradas 	= array("<NFSRpsNumero>"	,"</NFSRpsNumero>"	,"<NFSRPSSerie>","</NFSRPSSerie>"	,"<<ENTER>>"	,"<<ENTER>"	,"<ENTER>>"	,"<ENTER>"	,"&" 	, "§"	,"xC7"	,"xA7"	,"xC3"	,"APTǠ"	,"ď"	,"ȁ"	,"鼯");
            $tags_corretas 	= array("<RPSNumero>"		,"</RPSNumero>"		,"<RPSSerie>"	,"</RPSSerie>"		,""				,""			,""			,""			,"e" 	, ""	,"C"	,"o"	,"a"	,"APTo"	,"d"	,"Ç"	,"<");

            $indice         = $_GET["indice"];
            $link           = Session::Get('NFSE_XML_TOMADOR_CEP_FILE');

            $config         = file ($link);
            $qtde           = sizeof($config);

            $linha          = $config[$indice-1];
            $rps            = substr($linha,4,5);

            $mesano     = str_replace("/",'',tdc::r('referencia'));  
            $rpsnumero  = conteudo_tag($linha,"RPSNumero");          
            $tomacpf    = conteudo_tag($linha, "TomaCPF");
            $tomacnpj   = conteudo_tag($linha, "TomaCNPJ");
            $cep        = conteudo_tag($linha,"TomaCEP");

            try {
                // 1. Configurar o PDO para lançar exceções (caso ainda não esteja configurado)
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql = "
                    UPDATE td_erp_nfse_tomador a 
                    LEFT JOIN td_erp_nfse_nota b ON b.id = a.nfse
                    SET a.tomacep = '".$cep."'
                    WHERE b.rpsnumero = '$rpsnumero'
                    AND b.mesano = '$mesano'
                    AND b.status <> 'E' 
                    AND b.situacao <> 'E'
                    AND (a.tomacpf = '$tomacpf' OR a.tomacnpj = '$tomacnpj');
                ";
                
                #Debug::log($sql);
                $conn->exec($sql);
                tdc::wj(array(
                    'status' => 'success'
                ));
                Transacao::Commit();
            } catch (PDOException $e) {
                // 4. Capturar o erro específico do banco de dados
                tdc::wj([
                    'status'  => 'error',
                    'message' => 'Erro no SQL: ' . $e->getMessage(),
                    'code'    => $e->getCode()
                ]);
                Transacao::Rollback();
            } catch (Exception $e) {
                // 5. Capturar erros gerais do PHP
                tdc::wj([
                    'status'  => 'error',
                    'message' => 'Erro geral: ' . $e->getMessage()
                ]);
                Transacao::Rollback();
            }
        break;
    }