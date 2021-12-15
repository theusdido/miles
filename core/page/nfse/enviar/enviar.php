<?php
    $op         = tdc::r('op');
    switch($op){
        case 'enviar':
            $nfse       = tdc::p('td_erp_nfse_nota',tdc::r('nota')['id']);
            $servicos   = tdc::d('td_erp_nfse_servico',tdc::f('nfse','=',$nfse->id))[0];
            $item       = tdc::d('td_erp_nfse_item',tdc::f('nfse','=',$nfse->id))[0];
            $tomador    = tdc::d('td_erp_nfse_tomador',tdc::f('nfse','=',$nfse->id))[0];

            $xmlNFSE            = '<Envio><ModeloDocumento>NFSE</ModeloDocumento><Versao>1.0</Versao><RPS><RPSNumero>'.$nfse->rpsnumero.'</RPSNumero><RPSSerie>'.$nfse->rpsserie.'</RPSSerie><RPSTipo>'.$nfse->rpstipo.'</RPSTipo><dEmis>'.$nfse->demis.'T00:00:00</dEmis><dCompetencia /><LocalPrestServ /><natOp>'.$nfse->natop.'</natOp><Operacao /><NumProcesso /><RegEspTrib>'.$nfse->regesptrib.'</RegEspTrib><OptSN>'.$nfse->optsn.'</OptSN><IncCult>'.$nfse->inccuilt.'</IncCult><Status>1</Status><cVerificaRPS /><EmpreitadaGlobal /><tpAmb>2</tpAmb><RPSSubs><SubsNumero /><SubsSerie /><SubsTipo /><SubsNFSeNumero /><SubsDEmisNFSe /></RPSSubs><Prestador><CNPJ_prest>83248021000158</CNPJ_prest><xNome>Góes Empreendimentos Imobiliários e Cobrança de Títulos Ltda</xNome><xFant /><IM /><IE /><CMC /><enderPrest><TPEnd /><xLgr /><nro /><xCpl /><xBairro /><cMun /><UF /><CEP /><fone /><Email /></enderPrest></Prestador><ListaItens /><ListaParcelas /><Servico><Valores><ValServicos>'.$servicos->valservicos.'</ValServicos><ValDeducoes>0.00</ValDeducoes><ValPIS>0.00</ValPIS><ValCOFINS>0.00</ValCOFINS><ValINSS>0.00</ValINSS><ValIR>0.00</ValIR><ValCSLL>0.00</ValCSLL><ValBCPIS>0.00</ValBCPIS><ValBCCOFINS>0.00</ValBCCOFINS><ValBCINSS>0.00</ValBCINSS><ValBCIRRF>0.00</ValBCIRRF><ValBCCSLL>0.00</ValBCCSLL><ISSRetido>'.$servicos->isretido.'</ISSRetido><RespRetencao /><Tributavel /><ValISS>'.$servicos->valiss.'</ValISS><ValISSRetido /><ValBaseCalculo>'.$servicos->valbasecalculo.'</ValBaseCalculo><ValOutrasRetencoes /><ValAliqISS>'.$servicos->valaliqiss.'</ValAliqISS><ValAliqPIS>0</ValAliqPIS><PISRetido /><ValAliqCOFINS>0.0800</ValAliqCOFINS><COFINSRetido/><ValAliqIR>0.0000</ValAliqIR><IRRetido /><ValAliqCSLL>0.0000</ValAliqCSLL><CSLLRetido /><ValAliqINSS>0.0000</ValAliqINSS><INSSRetido /><ValLiquido /><ValAliqCpp /><CppRetido /><ValCpp /><OutrasRetencoesRetido /><ValAliqTotTributos /><ValLiquido>'.$servicos->valliquido.'</ValLiquido><ValDescIncond>0.00</ValDescIncond><ValDescCond>0.00</ValDescCond><ValAliqISSoMunic /><InfValPIS /><InfValCOFINS /><ValLiqFatura /><ValBCISSRetido /></Valores><LocalPrestacao><SerEndTpLgr /><SerEndLgr /><SerEndNumero /><SerEndComplemento /><SerEndBairro />   <SerEndxMun /><SerEndcMun /><SerEndUF /><SerEndCep /><SerEndSiglaUF /></LocalPrestacao><cServ /><IteListServico>'.$item->itelistserv.'</IteListServico><Cnae>0</Cnae><fPagamento /><Discriminacao>'.$item->discriminacao.'</Discriminacao><cMun>'.$item->cmun.'</cMun><cMunIncidencia>'.$item->cmun.'</cMunIncidencia><SerQuantidade /><SerUnidade /><SerNumAlva /><PaiPreServico /><dVencimento /><ObsInsPagamento /><ObrigoMunic /><TributacaoISS /></Servico><Tomador><TomaCNPJ>'.$tomador->tomacnpj.'</TomaCNPJ><TomaCPF>'.$tomador->tomacpf.'</TomaCPF><TomaIE /><TomaIM /><TomaRazaoSocial>'.$tomador->tomarazaosocial.'</TomaRazaoSocial><TomatpLgr>'.$tomador->tomatplgr.'</TomatpLgr><TomaEndereco>'.$tomador->tomaendereco.'</TomaEndereco>   <TomaNumero>'.$tomador->tomanumero.'</TomaNumero><TomaComplemento>'.$tomador->tomacomplemento.'</TomaComplemento><TomaBairro>'.$tomador->tomabairro.'</TomaBairro><TomacMun>'.$tomador->tomacmun.'</TomacMun><TomaxMun>'.$tomador->tomaxmun.'</TomaxMun><TomaUF>'.$tomador->tomauf.'</TomaUF><TomaPais>BR</TomaPais><TomaCEP>'.$tomador->tomacep.'</TomaCEP><TomaTelefone>'.$tomador->tomatelefone.'</TomaTelefone><TomaEmail>'.$tomador->tomaemail.'</TomaEmail><TomaSite>'.$tomador->tomasite.'</TomaSite><TomaIME /></Tomador><IntermServico><IntermRazaoSocial /><IntermCNPJ /><IntermCPF /><IntermIM /></IntermServico><ConstCivil><CodObra /><Art /><ObraLog /><ObraCompl /><ObraNumero /><ObraBairro /><ObraCEP /><ObraMun /><ObraUF /><ObraPais /><ObraCEI /><ObraMatricula /><ObraValRedBC /></ConstCivil><ListaDed /><Transportadora><TraNome /><TraCPFCNPJ /><TraIE /><TraPlaca /><TraEnd /><TraMun /><TraUF /><TraPais /><TraTipoFrete /></Transportadora><NFSOutrasinformacoes /><Arquivo /><ExtensaoArquivo /></RPS></Envio>';
            $chaveComunicacao 	= md5('IbFgTGNW+kIUlDN9RJQD6aDr+md0xGJI' . $xmlNFSE);
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
        
            try{
                $url  	    = "https://homolog.invoicy.com.br/arecepcao.aspx?wsdl";
                $client     = new SoapClient($url , array("location" => $url));
                $retorno    = $client->Execute($parametros);
                $codigo     = (int)json_encode($retorno->Invoicyretorno->Mensagem->MensagemItem->Codigo);
                if ($codigo == 100){
                    $nfse->status = 'E';
                    $nfse->armazenar();
                }
                echo (int)$codigo;
            }catch(SoapFault $e){
                echo 'Erro: ['.$e->getCode() . "] => " .$e->getMessage().'<br/>';
                echo '<pre>';
                var_dump($e);
                echo '</pre>';
            }
        break;
    }