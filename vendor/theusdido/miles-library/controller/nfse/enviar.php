<?php
    $op         = tdc::r('op');
    switch($op){
        case 'enviar':

            $env        = 1; // 1 - Produição, 2 - Homologação
            $nfse       = tdc::p('td_erp_nfse_nota',tdc::r('nota')['id']);
            $servicos   = tdc::d('td_erp_nfse_servico',tdc::f('nfse','=',$nfse->id))[0];
            $item       = tdc::d('td_erp_nfse_item',tdc::f('nfse','=',$nfse->id))[0];
            $tomador    = tdc::d('td_erp_nfse_tomador',tdc::f('nfse','=',$nfse->id))[0];

            $xmlNFSE    = '
                <Envio>
                    <ModeloDocumento>NFSE</ModeloDocumento>
                    <Versao>1.0</Versao>
                    <RPS>
                        <RPSNumero>'.$nfse->rpsnumero.'</RPSNumero>
                        <RPSSerie>'.$nfse->rpsserie.'</RPSSerie>
                        <RPSTipo>'.$nfse->rpstipo.'</RPSTipo>
                        <dEmis>'.$nfse->demis.'T00:00:00</dEmis>
                        <dCompetencia>'.$nfse->dcompetencia.'</dCompetencia>
                        <LocalPrestServ />
                        <natOp>'.$nfse->natop.'</natOp>
                        <Operacao />
                        <NumProcesso />
                        <RegEspTrib>'.$nfse->regesptrib.'</RegEspTrib>
                        <OptSN>'.$nfse->optsn.'</OptSN>
                        <IncCult>'.$nfse->inccuilt.'</IncCult>
                        <Status>1</Status>
                        <cVerificaRPS />
                        <EmpreitadaGlobal />
                        <tpAmb>'.$env.'</tpAmb>
                        <RPSSubs>
                            <SubsNumero />
                            <SubsSerie />
                            <SubsTipo />
                            <SubsNFSeNumero />
                            <SubsDEmisNFSe />
                        </RPSSubs>
                        <Prestador>
                            <CNPJ_prest>83248021000158</CNPJ_prest>
                            <xNome>Góes Empreendimentos Imobiliários e Cobrança de Títulos Ltda</xNome>
                            <xFant>Góes Imóveis</xFant>
                            <IM>001169</IM>
                            <IE>Isenta</IE>
                            <CMC />
                            <enderPrest>
                                <TPEnd>Rua</TPEnd>
                                <xLgr>Marechal Deodoro</xLgr>
                                <nro>355</nro>
                                <xCpl />
                                <xBairro>Centro</xBairro>
                                <cMun>Criciúma</cMun>
                                <UF>SC</UF>
                                <CEP>88801110</CEP>
                                <fone>(48) 3437-2552</fone>
                                <Email>financas@goesimoveis.com.br</Email>
                            </enderPrest>
                        </Prestador>
                        <ListaItens />
                        <ListaParcelas />
                        <Servico>
                            <Valores>
                                <ValServicos>'.$servicos->valservicos.'</ValServicos>
                                <ValDeducoes>'.$servicos->valdeducoes.'</ValDeducoes>
                                <ValPIS>'.$servicos->valpis.'</ValPIS>
                                <ValCOFINS>'.$servicos->valcofins.'</ValCOFINS>
                                <ValINSS>'.$servicos->valinss.'</ValINSS>
                                <ValIR>'.$servicos->valir.'</ValIR>
                                <ValCSLL>'.$servicos->valcsll.'</ValCSLL>
                                <ValBCPIS>'.$servicos->valbcpis.'</ValBCPIS>
                                <ValBCCOFINS>'.$servicos->valbccofins.'</ValBCCOFINS>
                                <ValBCINSS>'.$servicos->valbcinss.'</ValBCINSS>
                                <ValBCIRRF>'.$servicos->valbcirrf.'</ValBCIRRF>
                                <ValBCCSLL>'.$servicos->valbccsll.'</ValBCCSLL>
                                <ISSRetido>'.$servicos->issretido.'</ISSRetido>
                                <RespRetencao>'.$servicos->respretencao.'</RespRetencao>
                                <Tributavel>'.$servicos->tributavel.'</Tributavel>
                                <ValISS>'.$servicos->valiss.'</ValISS>
                                <ValISSRetido>'.$servicos->valissretido.'</ValISSRetido>
                                <ValBaseCalculo>'.$servicos->valbasecalculo.'</ValBaseCalculo>
                                <ValOutrasRetencoes>'.$servicos->valoutrasretencoes.'</ValOutrasRetencoes>
                                <ValAliqISS>'.$servicos->valaliqiss.'</ValAliqISS>
                                <ValAliqPIS>'.$servicos->valaliqpis.'</ValAliqPIS>
                                <PISRetido>'.$servicos->pisretido.'</PISRetido>
                                <ValAliqCOFINS>'.$servicos->valaliqcofins.'</ValAliqCOFINS>
                                <COFINSRetido>'.$servicos->cofinsretido.'</COFINSRetido>
                                <ValAliqIR>'.$servicos->valaliqir.'</ValAliqIR>
                                <IRRetido>'.$servicos->irretido.'</IRRetido>
                                <ValAliqCSLL>'.$servicos->valaliqcsll.'</ValAliqCSLL>
                                <CSLLRetido>'.$servicos->csllretido.'</CSLLRetido>
                                <ValAliqINSS>'.$servicos->valaliqinss.'</ValAliqINSS>
                                <INSSRetido>'.$servicos->inssretido.'</INSSRetido>
                                <ValLiquido>'.$servicos->valliquido.'</ValLiquido>
                                <ValAliqCpp>'.$servicos->valaliqcpp.'</ValAliqCpp>
                                <CppRetido>'.$servicos->cppretido.'</CppRetido>
                                <ValCpp>'.$servicos->valcpp.'</ValCpp>
                                <OutrasRetencoesRetido>'.$servicos->outrasretencoes.'</OutrasRetencoesRetido>
                                <ValAliqTotTributos>'.$servicos->valaliqtottributos.'</ValAliqTotTributos>
                                <ValLiquido>'.$servicos->valliquido.'</ValLiquido>
                                <ValDescIncond>'.$servicos->valdescincond.'</ValDescIncond>
                                <ValDescCond>'.$servicos->valdesccond.'</ValDescCond>
                                <ValAliqISSoMunic>'.$servicos->valaliqissmunic.'</ValAliqISSoMunic>
                                <InfValPIS>'.$servicos->infvalpis.'</InfValPIS>
                                <InfValCOFINS>'.$servicos->infvalcofins.'</InfValCOFINS>
                                <ValLiqFatura>'.$servicos->valliqfatura.'</ValLiqFatura>
                                <ValBCISSRetido>'.$servicos->valbcissretido.'</ValBCISSRetido>
                            </Valores>
                            <LocalPrestacao>
                                <SerEndTpLgr />
                                <SerEndLgr />
                                <SerEndNumero />
                                <SerEndComplemento />
                                <SerEndBairro />
                                <SerEndxMun />
                                <SerEndcMun />
                                <SerEndUF />
                                <SerEndCep />
                                <SerEndSiglaUF />
                            </LocalPrestacao>
                            <cServ />
                            <IteListServico>'.$servicos->itelistserv.'</IteListServico>
                            <Cnae>'.$item->cnae.'</Cnae>
                            <fPagamento>'.$item->fpagamento.'</fPagamento>
                            <Discriminacao>'.$item->discriminacao.'</Discriminacao>
                            <cMun>'.$servicos->cmun.'</cMun>
                            <cMunIncidencia>'.$item->cmunincidencia.'</cMunIncidencia>
                            <SerQuantidade>'.$item->serquantidade.'</SerQuantidade>
                            <SerUnidade>'.$item->serunidade.'</SerUnidade>
                            <SerNumAlva>'.$item->sernumalva.'</SerNumAlva>
                            <PaiPreServico>'.$item->paipreservico.'</PaiPreServico>
                            <dVencimento>'.$item->dvencimento.'</dVencimento>
                            <ObsInsPagamento>'.$item->obsinspagamento.'</ObsInsPagamento>
                            <ObrigoMunic>'.$item->obrigomunic.'</ObrigoMunic>
                            <TributacaoISS>'.$item->tributacaoiss.'</TributacaoISS>
                        </Servico>
                        <Tomador>
                            <TomaCNPJ>'.$tomador->tomacnpj.'</TomaCNPJ>
                            <TomaCPF>'.$tomador->tomacpf.'</TomaCPF>
                            <TomaIE>'.$tomador->tomaie.'</TomaIE>
                            <TomaIM>'.$tomador->tomaim.'</TomaIM>
                            <TomaRazaoSocial>'.$tomador->tomarazaosocial.'</TomaRazaoSocial>
                            <TomatpLgr>'.$tomador->tomatplgr.'</TomatpLgr>
                            <TomaEndereco>'.$tomador->tomaendereco.'</TomaEndereco>
                            <TomaNumero>'.$tomador->tomanumero.'</TomaNumero>
                            <TomaComplemento>'.$tomador->tomacomplemento.'</TomaComplemento>
                            <TomaBairro>'.$tomador->tomabairro.'</TomaBairro>
                            <TomacMun>'.$tomador->tomacmun.'</TomacMun>
                            <TomaxMun>'.$tomador->tomaxmun.'</TomaxMun>
                            <TomaUF>'.$tomador->tomars.'</TomaUF>
                            <TomaPais>BR</TomaPais>
                            <TomaCEP>'.$tomador->tomacep.'</TomaCEP>
                            <TomaTelefone>'.substr($tomador->tomatelefone,0,11).'</TomaTelefone>
                            <TomaEmail>'.$tomador->tomaemail.'</TomaEmail>
                            <TomaSite>'.$tomador->tomasite.'</TomaSite>
                            <TomaIME />
                        </Tomador>
                        <IntermServico>
                            <IntermRazaoSocial />
                            <IntermCNPJ />
                            <IntermCPF />
                            <IntermIM />
                        </IntermServico>
                        <ConstCivil>
                            <CodObra />
                            <Art />
                            <ObraLog />
                            <ObraCompl />
                            <ObraNumero />
                            <ObraBairro />
                            <ObraCEP />
                            <ObraMun />
                            <ObraUF />
                            <ObraPais />
                            <ObraCEI />
                            <ObraMatricula />
                            <ObraValRedBC />
                        </ConstCivil>
                        <ListaDed />
                        <Transportadora>
                            <TraNome />
                            <TraCPFCNPJ />
                            <TraIE />
                            <TraPlaca />
                            <TraEnd />
                            <TraMun />
                            <TraUF />
                            <TraPais />
                            <TraTipoFrete />
                        </Transportadora>
                        <NFSOutrasinformacoes />
                        <Arquivo />
                        <ExtensaoArquivo />
                    </RPS>
                </Envio>
            ';            
               

            $xmlNFSE = '<Envio>                    <ModeloDocumento>NFSE</ModeloDocumento>                    <Versao>1.0</Versao>                    <RPS>                        <RPSNumero>'.$nfse->rpsnumero.'</RPSNumero>                        <RPSSerie>'.$nfse->rpsserie.'</RPSSerie>                        <RPSTipo>'.$nfse->rpstipo.'</RPSTipo>                       <dEmis>'.$nfse->demis.'T00:00:00</dEmis>                       <dCompetencia>'.$nfse->dcompetencia.'</dCompetencia>                        <LocalPrestServ />                        <natOp>'.$nfse->natop.'</natOp>                        <Operacao />                        <NumProcesso />                        <RegEspTrib>'.$nfse->regesptrib.'</RegEspTrib>                        <OptSN>'.$nfse->optsn.'</OptSN>                        <IncCult>'.$nfse->inccuilt.'</IncCult>                        <Status>1</Status>                        <cVerificaRPS />                        <EmpreitadaGlobal />                        <tpAmb>'.$env.'</tpAmb>                        <RPSSubs>                            <SubsNumero />                            <SubsSerie />                            <SubsTipo />                            <SubsNFSeNumero />                            <SubsDEmisNFSe />                        </RPSSubs>                        <Prestador>                            <CNPJ_prest>83248021000158</CNPJ_prest>                            <xNome>Góes Empreendimentos Imobiliários e Cobrança de Títulos Ltda</xNome>                            <xFant>Góes Imóveis</xFant>                            <IM>001169</IM>                            <IE>Isenta</IE>                            <CMC />                            <enderPrest>                                <TPEnd>Rua</TPEnd>                                <xLgr>Marechal Deodoro</xLgr>                                <nro>355</nro>                                <xCpl />                                <xBairro>Centro</xBairro>                                <cMun>Criciúma</cMun>                                <UF>SC</UF>                                <CEP>88801110</CEP>                                <fone>(48) 3437-2552</fone>                                <Email>financas@goesimoveis.com.br</Email>                            </enderPrest>                        </Prestador>                        <ListaItens />                        <ListaParcelas />                        <Servico>                            <Valores>                                <ValServicos>'.$servicos->valservicos.'</ValServicos>                                <ValDeducoes>'.$servicos->valdeducoes.'</ValDeducoes>                                <ValPIS>'.$servicos->valpis.'</ValPIS>                                <ValCOFINS>'.$servicos->valcofins.'</ValCOFINS>                                <ValINSS>'.$servicos->valinss.'</ValINSS>                                <ValIR>'.$servicos->valir.'</ValIR>                                <ValCSLL>'.$servicos->valcsll.'</ValCSLL>                                <ValBCPIS>'.$servicos->valbcpis.'</ValBCPIS>                                <ValBCCOFINS>'.$servicos->valbccofins.'</ValBCCOFINS>                                <ValBCINSS>'.$servicos->valbcinss.'</ValBCINSS>                                <ValBCIRRF>'.$servicos->valbcirrf.'</ValBCIRRF>                                <ValBCCSLL>'.$servicos->valbccsll.'</ValBCCSLL>                                <ISSRetido>'.$servicos->issretido.'</ISSRetido>                                <RespRetencao>'.$servicos->respretencao.'</RespRetencao>                                <Tributavel>'.$servicos->tributavel.'</Tributavel>                                <ValISS>'.$servicos->valiss.'</ValISS>                                <ValISSRetido>'.$servicos->valissretido.'</ValISSRetido>                                <ValBaseCalculo>'.$servicos->valbasecalculo.'</ValBaseCalculo>                                <ValOutrasRetencoes>'.$servicos->valoutrasretencoes.'</ValOutrasRetencoes>                                <ValAliqISS>'.$servicos->valaliqiss.'</ValAliqISS>                                <ValAliqPIS>'.$servicos->valaliqpis.'</ValAliqPIS>                                <PISRetido>'.$servicos->pisretido.'</PISRetido>                                <ValAliqCOFINS>'.$servicos->valaliqcofins.'</ValAliqCOFINS>                                <COFINSRetido>'.$servicos->cofinsretido.'</COFINSRetido>                                <ValAliqIR>'.$servicos->valaliqir.'</ValAliqIR>                                <IRRetido>'.$servicos->irretido.'</IRRetido>                                <ValAliqCSLL>'.$servicos->valaliqcsll.'</ValAliqCSLL>                                <CSLLRetido>'.$servicos->csllretido.'</CSLLRetido>                                <ValAliqINSS>'.$servicos->valaliqinss.'</ValAliqINSS>                                <INSSRetido>'.$servicos->inssretido.'</INSSRetido>                                <ValLiquido>'.$servicos->valliquido.'</ValLiquido>                                <ValAliqCpp>'.$servicos->valaliqcpp.'</ValAliqCpp>                                <CppRetido>'.$servicos->cppretido.'</CppRetido>                                <ValCpp>'.$servicos->valcpp.'</ValCpp>                                <OutrasRetencoesRetido>'.$servicos->outrasretencoes.'</OutrasRetencoesRetido>                                <ValAliqTotTributos>'.$servicos->valaliqtottributos.'</ValAliqTotTributos>                                <ValLiquido>'.$servicos->valliquido.'</ValLiquido>                                <ValDescIncond>'.$servicos->valdescincond.'</ValDescIncond>                                <ValDescCond>'.$servicos->valdesccond.'</ValDescCond>                                <ValAliqISSoMunic>'.$servicos->valaliqissmunic.'</ValAliqISSoMunic>                                <InfValPIS>'.$servicos->infvalpis.'</InfValPIS>                                <InfValCOFINS>'.$servicos->infvalcofins.'</InfValCOFINS>                                <ValLiqFatura>'.$servicos->valliqfatura.'</ValLiqFatura>                                <ValBCISSRetido>'.$servicos->valbcissretido.'</ValBCISSRetido>                            </Valores>                            <LocalPrestacao>                                <SerEndTpLgr />                                <SerEndLgr />                                <SerEndNumero />                                <SerEndComplemento />                                <SerEndBairro />                                <SerEndxMun />                                <SerEndcMun />                                <SerEndUF />                                <SerEndCep />                                <SerEndSiglaUF />                            </LocalPrestacao>                            <cServ />                            <IteListServico>'.$servicos->itelistserv.'</IteListServico>                            <Cnae>'.$item->cnae.'</Cnae>                            <fPagamento>'.$item->fpagamento.'</fPagamento>                            <Discriminacao>'.$item->discriminacao.'</Discriminacao>                            <cMun>'.$servicos->cmun.'</cMun>                            <cMunIncidencia>'.$item->cmunincidencia.'</cMunIncidencia>                            <SerQuantidade>'.$item->serquantidade.'</SerQuantidade>                            <SerUnidade>'.$item->serunidade.'</SerUnidade>                            <SerNumAlva>'.$item->sernumalva.'</SerNumAlva>                            <PaiPreServico>'.$item->paipreservico.'</PaiPreServico>                            <dVencimento>'.$item->dvencimento.'</dVencimento>                            <ObsInsPagamento>'.$item->obsinspagamento.'</ObsInsPagamento>                            <ObrigoMunic>'.$item->obrigomunic.'</ObrigoMunic>                            <TributacaoISS>'.$item->tributacaoiss.'</TributacaoISS>                        </Servico>                        <Tomador>                            <TomaCNPJ>'.$tomador->tomacnpj.'</TomaCNPJ>                            <TomaCPF>'.$tomador->tomacpf.'</TomaCPF>                            <TomaIE>'.$tomador->tomaie.'</TomaIE>                            <TomaIM>'.$tomador->tomaim.'</TomaIM>                            <TomaRazaoSocial>'.$tomador->tomarazaosocial.'</TomaRazaoSocial>                            <TomatpLgr>'.$tomador->tomatplgr.'</TomatpLgr>                            <TomaEndereco>'.$tomador->tomaendereco.'</TomaEndereco>                            <TomaNumero>'.$tomador->tomanumero.'</TomaNumero>                            <TomaComplemento>'.$tomador->tomacomplemento.'</TomaComplemento>                            <TomaBairro>'.$tomador->tomabairro.'</TomaBairro>                            <TomacMun>'.$tomador->tomacmun.'</TomacMun>                            <TomaxMun>'.$tomador->tomaxmun.'</TomaxMun>                            <TomaUF>'.$tomador->tomars.'</TomaUF>                            <TomaPais>BR</TomaPais>                            <TomaCEP>'.$tomador->tomacep.'</TomaCEP>                            <TomaTelefone>'.substr($tomador->tomatelefone,0,11).'</TomaTelefone>                            <TomaEmail>'.$tomador->tomaemail.'</TomaEmail>                            <TomaSite>'.$tomador->tomasite.'</TomaSite>                            <TomaIME />                        </Tomador>                        <IntermServico>                            <IntermRazaoSocial />                            <IntermCNPJ />                            <IntermCPF />                            <IntermIM />                        </IntermServico>                        <ConstCivil>                            <CodObra />                            <Art />                            <ObraLog />                            <ObraCompl />                            <ObraNumero />                            <ObraBairro />                            <ObraCEP />                            <ObraMun />                            <ObraUF />                            <ObraPais />                            <ObraCEI />                            <ObraMatricula />                            <ObraValRedBC />                        </ConstCivil>                        <ListaDed />                        <Transportadora>                            <TraNome />                            <TraCPFCNPJ />                            <TraIE />                            <TraPlaca />                            <TraEnd />                            <TraMun />                            <TraUF />                            <TraPais />                            <TraTipoFrete />                        </Transportadora>                        <NFSOutrasinformacoes />                        <Arquivo />                        <ExtensaoArquivo />                    </RPS>                </Envio>';
            
            // $fp = fopen('inline.txt','w');
            // fwrite($fp,str_replace(array("\r\n", "\r", "\n"), "", $xmlNFSE));
            #exit;
            $xmlNFSE = str_replace(array("\r\n", "\r", "\n"), "", $xmlNFSE);
            #echo $xmlNFSE;
            #exit;
            $xmlNFSE            = preg_replace('/>\s+</','><',$xmlNFSE);
            #echo $xmlNFSE;
            #exit;            
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
                    foreach($arrMensagemItem as $objMensagemItem) { // Para cada tipo enviado (NFC-e, NF-e, NFS-e, etc), retorna um MensagemItem
                        
                        if ($objMensagemItem->Codigo == 100) { // Código = 100 é sucesso
                            // Documentos processados
                            echo '<br>Mensagem Item: <b>' . $objMensagemItem->Descricao . '</b><br>';
                            $arrDocumentosItem = $objMensagemItem->Documentos->DocumentosItem;
                            // Se foi enviado somente um <DadosItem> de um mesmo tipo, retorna um objeto pronto.
                            // Se foram enviados mais de um <DadosItem> de um tipo (NFC-e, NF-e, NFS-e, etc), retorna um array de objetos
                            // Ex.: Foram enviadas duas NF-e
                            if (!is_array($arrDocumentosItem)) { // Se foi enviado apenas uma nota, transforma para array para poder reaproveitar o código do foreach
                                $arrDocumentosItem = array($arrDocumentosItem);
                            }
                            
                            foreach($arrDocumentosItem as $objDocumentosItem) {
                                $xmlDocumentoRetorno = $objDocumentosItem->Documento;
                                echo 'Documento Retorno: ' . htmlentities($xmlDocumentoRetorno, ENT_QUOTES, 'UTF-8') . '<br>';
                            }
                            
                        } else { // Código <> 100 é falha no processo
                            
                            echo 'Falha: ' . '[' . $objMensagemItem->Codigo . '] ' . $objMensagemItem->Descricao . '<br>';
                            
                        }
                    }
                } else {
                    echo 'Retorno inválido!';
                    echo '<pre>';
                    var_dump($retorno);
                    echo '</pre>';
                }                
                                               
            }catch(SoapFault $e){
                if (IS_SHOW_ERROR_MESSAGE){
                    echo 'Erro: ['.$e->getCode() . "] => " .$e->getMessage().'<br/>';
                    echo '<pre>';
                    var_dump($e);
                    echo '</pre>';
                }
            }
        break;
    }                