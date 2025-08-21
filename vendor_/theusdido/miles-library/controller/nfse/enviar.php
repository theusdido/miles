<?php
    $op         = tdc::r('op');
    switch($op){
        case 'enviar':
            $env                = 1; // 1 - Produição, 2 - Homologação
            $layout_version     = 2; // 1 - Fly Betha, 2 - Betha Cloud

            $nfse       = tdc::p('td_erp_nfse_nota',tdc::r('nota')['id']);
            $servicos   = tdc::d('td_erp_nfse_servico',tdc::f('nfse','=',$nfse->id))[0];
            $item       = tdc::d('td_erp_nfse_item',tdc::f('nfse','=',$nfse->id))[0];
            $tomador    = tdc::d('td_erp_nfse_tomador',tdc::f('nfse','=',$nfse->id))[0];

            require "layout_v{$layout_version}.php";
            
            $xmlNFSE = preg_replace('/>\s+</', '><', $xmlNFSE); // Remove espaços entre tags
            $xmlNFSE = preg_replace('/\s+/', ' ', $xmlNFSE);    // Reduz múltiplos espaços a um só
            $xmlNFSE = trim($xmlNFSE);                          // Remove espaços no início/fim
            

            $xmlNFSE = str_replace(array("\r\n", "\r", "\n"), "", $xmlNFSE);
            $xmlNFSE            = preg_replace('/>\s+</','><',$xmlNFSE);
            var_dump($xmlNFSE);

            if ($env == 1){
                $url  	        = "https://app.invoicy.com.br/arecepcao.aspx?WSDL";
                $chave_acesso   = 'IbFgTGNW+kIUlDN9RJQD6fjp9507kLl8';
            }else{
                $url  	        = "https://homolog.invoicy.com.br/arecepcao.aspx?WSDL";
                $chave_acesso   = 'IbFgTGNW+kIUlDN9RJQD6aDr+md0xGJI';
            }

            $chaveComunicacao 	= md5($chave_acesso . $xmlNFSE);
            $parametros         = array(
                "Invoicyrecepcao" => array(
                    "Cabecalho" => array(
                        "EmpPK" => "nmct1WFo0Ie7Kn2ItUr1dg==",
                        "EmpCK" => $chaveComunicacao,
                        "EmpCO" => null
                    ),
                    "Informacoes" => array(
                        "Texto" => null
                    ),
                    "Dados" => array (
                        "DadosItem" => array(
                            "Documento" => $xmlNFSE,
                            "Parametros" => null
                        )
                    )
                )
            );

            $response = array();
            $response['status'] = 'success';
            try{                                
                $client     = new SoapClient($url , array("location" => $url));
                $retorno    = $client->Execute($parametros);

                if (!empty($retorno->Invoicyretorno)) {
                    $arrMensagemItem = $retorno->Invoicyretorno->Mensagem->MensagemItem;
                    // Se for enviado somente <DadosItem> de um mesmo tipo, retorna um objeto pronto.
                    // Se foram enviados <DadosItem> de mais de um tipo (NFC-e, NF-e, NFS-e, etc), retorna um array de objetos
                    // Ex.: Foram enviadas uma ou mais NF-e e uma ou mais NFC-e na mesma comunicação
                    if (!is_array($arrMensagemItem)) { // Se foi enviado apenas um tipo, transforma para array para poder reaproveitar o código do foreach
                        $arrMensagemItem = array($arrMensagemItem);
                    }
                    $msgs = '';
                    foreach($arrMensagemItem as $objMensagemItem) { // Para cada tipo enviado (NFC-e, NF-e, NFS-e, etc), retorna um MensagemItem
                        
                        if ($objMensagemItem->Codigo == 100) { // Código = 100 é sucesso
                            // Documentos processados
                            #echo '<br>Mensagem Item: <b>' . $objMensagemItem->Descricao . '</b><br>';
                            $arrDocumentosItem = $objMensagemItem->Documentos->DocumentosItem;
                            // Se foi enviado somente um <DadosItem> de um mesmo tipo, retorna um objeto pronto.
                            // Se foram enviados mais de um <DadosItem> de um tipo (NFC-e, NF-e, NFS-e, etc), retorna um array de objetos
                            // Ex.: Foram enviadas duas NF-e
                            if (!is_array($arrDocumentosItem)) { // Se foi enviado apenas uma nota, transforma para array para poder reaproveitar o código do foreach
                                $arrDocumentosItem = array($arrDocumentosItem);
                            }
                            
                            
                            foreach($arrDocumentosItem as $objDocumentosItem) {
                                $xmlDocumentoRetorno = $objDocumentosItem->Documento;
                                #echo 'Documento Retorno: ' . htmlentities($xmlDocumentoRetorno, ENT_QUOTES, 'UTF-8') . '<br>';
                                #$msgs .= htmlentities($xmlDocumentoRetorno, ENT_QUOTES, 'UTF-8') . '<br>';
                            }

                            // Atualiza o status da NFS-e no banco de dados 
                            #$nfse->status = 'E';
                            #$nfse->armazenar();
                        } else { // Código <> 100 é falha no processo
                            
                            $msgs .= 'Falha: ' . '[' . $objMensagemItem->Codigo . '] ' . $objMensagemItem->Descricao;
                            $response['status'] = 'error';
                        }
                    }
                } else {
                    $msgs .= 'Retorno inválido!';
                    $response['status'] = 'error';
                    #echo '<pre>';
                    #var_dump($retorno);
                    #echo '</pre>';
                }                
                                               
            }catch(SoapFault $e){
                $msgs .= 'Erro: ['.$e->getCode() . "] => " .$e->getMessage().'<br/>';
                $response['status'] = 'error';
                if (IS_SHOW_ERROR_MESSAGE){
                    echo '<pre>';
                    var_dump($e);
                    echo '</pre>';
                }
            } finally {
                $response['message'] = $msgs;
                tdc::wj($response);
            }
        break;
    }                