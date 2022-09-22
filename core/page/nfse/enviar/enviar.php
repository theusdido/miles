<?php
    $op         = tdc::r('op');
    switch($op){
        case 'enviar':
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
                        <dCompetencia>'.$nfse->dcompetencia.'<dCompetencia />
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
                        <tpAmb>2</tpAmb>
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
                                <ISSRetido>'.$servicos->isretido.'</ISSRetido>
                                <RespRetencao>'.$servicos->respretencao.'</RespRetencao>
                                <Tributavel />'.$servicos->tributavel.'</Tributavel>
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
                                <IRRetido>'.$servicos->irretido.'<IRRetido>
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
                            <IteListServico>'.$item->itelistserv.'</IteListServico>
                            <Cnae>'.$item->cnae.'</Cnae>
                            <fPagamento>'.$item->fpagamento.'</fPagamento>
                            <Discriminacao>'.$item->discriminacao.'</Discriminacao>
                            <cMun>'.$item->cmun.'</cMun>
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
                            <TomaTelefone>'.$tomador->tomatelefone.'</TomaTelefone>
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
                
            $xmlNFSE    = '
                <Envio>
                    <ModeloDocumento>NFSE</ModeloDocumento>
                    <Versao>1.0</Versao>
                    <RPS>
                        <RPSNumero>4</RPSNumero>
                        <RPSSerie>U</RPSSerie>
                        <RPSTipo>1</RPSTipo>
                        <dEmis>2014-07-10T00:00:00</dEmis>
                        <dCompetencia />
                        <LocalPrestServ />
                        <natOp>3</natOp>
                        <Operacao />
                        <NumProcesso />
                        <RegEspTrib>1</RegEspTrib>
                        <OptSN>2</OptSN>
                        <IncCult>2</IncCult>
                        <Status>1</Status>
                        <cVerificaRPS />
                        <EmpreitadaGlobal />
                        <tpAmb>2</tpAmb>
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
                            <xFant />
                            <IM />
                            <IE />
                            <CMC />
                            <enderPrest>
                                <TPEnd />
                                <xLgr />
                                <nro />
                                <xCpl />
                                <xBairro />
                                <cM>
                                <UF />
                                <CEP />
                                <fone />
                                <Email />
                            </enderPrest>
                        </Prestador>
                        <ListaItens />
                        <ListaParcelas />
                        <Servico>
                            <Valores>
                                <ValServicos>200.00</ValServicos>
                                <ValDeducoes>0.00</ValDeducoes>
                                <ValPIS>0.00</ValPIS>
                                <ValCOFINS>0.00</ValCOFINS>
                                <ValINSS>0.00</ValINSS>
                                <ValIR>0.00</ValIR>
                                <ValCSLL>0.00</ValCSLL>
                                <ValBCPIS>0.00</ValBCPIS>
                                <ValBCCOFINS>0.00</ValBCCOFINS>
                                <ValBCINSS>0.00</ValBCINSS>
                                <ValBCIRRF>0.00</ValBCIRRF>
                                <ValBCCSLL>0.00</ValBCCSLL>
                                <ISSRetido>2</ISSRetido>
                                <RespRetencao />
                                <Tributavel />
                                <ValISS>4.00</ValISS>
                                <ValISSRetido />
                                <ValBaseCalculo>200.00</ValBaseCalculo>
                                <ValOutrasRetencoes />
                                <ValAliqISS>2.00</ValAliqISS>
                                <ValAliqPIS>0</ValAliqPIS>
                                <PISRetido />
                                <ValAliqCOFINS>0.0800</ValAliqCOFINS>
                                <COFINSRetido/>
                                <ValAliqIR>0.0000</ValAliqIR>
                                <IRRetido />
                                <ValAliqCSLL>0.0000</ValAliqCSLL>
                                <CSLLRetido />
                                <ValAliqINSS>0.0000</ValAliqINSS>
                                <INSSRetido />
                                <ValLiquido />
                                <ValAliqCpp />
                                <CppRetido />
                                <ValCpp />
                                <OutrasRetencoesRetido />
                                <ValAliqTotTributos />
                                <ValLiquido>200.00</ValLiquido>
                                <ValDescIncond>0.00</ValDescIncond>
                                <ValDescCond>0.00</ValDescCond>
                                <ValAliqISSoMunic />
                                <InfValPIS />
                                <InfValCOFINS />
                                <ValLiqFatura />
                                <ValBCISSRetido />
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
                            <IteListServico>1405</IteListServico>
                            <Cnae>0</Cnae>
                            <fPagamento />
                            <Discriminacao>Servico de recapagem de pneus.</Discriminacao>
                            <cMun>4305108</cMun>
                            <cMunIncidencia>4305108</cMunIncidencia>
                            <SerQuantidade />
                            <SerUnidade />
                            <SerNumAlva />
                            <PaiPreServico />
                            <dVencimento />
                            <ObsInsPagamento />
                            <ObrigoMunic />
                            <TributacaoISS />
                        </Servico>
                        <Tomador>
                            <TomaCNPJ>00000000000000</TomaCNPJ>
                            <TomaCPF />
                            <TomaIE />
                            <TomaIM />
                            <TomaRazaoSocial>Henrique</TomaRazaoSocial>
                            <TomatpLgr>RUA</TomatpLgr>
                            <TomaEndereco>RUA DO COMERCIO</TomaEndereco>
                            <TomaNumero>3000</TomaNumero>
                            <TomaComplemento>321</TomaComplemento>
                            <TomaBairro>UNIVERSITARIO</TomaBairro>
                            <TomacMun>4305108</TomacMun>
                            <TomaxMun>Caxias do Sul</TomaxMun>
                            <TomaUF>RS</TomaUF>
                            <TomaPais>BR</TomaPais>
                            <TomaCEP>98700000</TomaCEP>
                            <TomaTelefone>5535354800</TomaTelefone>
                            <TomaEmail>teste@migrate.com.br</TomaEmail>
                            <TomaSite>www.migratecompany.com.br</TomaSite>
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
            #$xmlNFSE    		= '<Envio>  <ModeloDocumento>NFSE</ModeloDocumento>     <Versao>1.0</Versao>    <RPS>       <RPSNumero>4</RPSNumero>        <RPSSerie>U</RPSSerie>      <RPSTipo>1</RPSTipo>                                <dEmis>2014-07-10T00:00:00</dEmis>      <dCompetencia />        <LocalPrestServ />      <natOp>3</natOp>                                                    <Operacao />        <NumProcesso />         <RegEspTrib>1</RegEspTrib>                              <OptSN>2</OptSN>                                <IncCult>2</IncCult>                                <Status>1</Status>                              <cVerificaRPS />        <EmpreitadaGlobal />        <tpAmb>2</tpAmb>        <RPSSubs>           <SubsNumero />          <SubsSerie />           <SubsTipo />            <SubsNFSeNumero />          <SubsDEmisNFSe />       </RPSSubs>      <Prestador>             <CNPJ_prest>83248021000158</CNPJ_prest>             <xNome>Góes Empreendimentos Imobiliários e Cobrança de Títulos Ltda</xNome>             <xFant />           <IM />          <IE />          <CMC />             <enderPrest>                <TPEnd />               <xLgr />                <nro />                 <xCpl />                <xBairro />                 <cMun />                <UF />              <CEP />                 <fone />                <Email />           </enderPrest>       </Prestador>        <ListaItens />      <ListaParcelas />       <Servico>           <Valores>               <ValServicos>200.00</ValServicos>               <ValDeducoes>0.00</ValDeducoes>                 <ValPIS>0.00</ValPIS>               <ValCOFINS>0.00</ValCOFINS>                 <ValINSS>0.00</ValINSS>                 <ValIR>0.00</ValIR>                 <ValCSLL>0.00</ValCSLL>                 <ValBCPIS>0.00</ValBCPIS>               <ValBCCOFINS>0.00</ValBCCOFINS>                 <ValBCINSS>0.00</ValBCINSS>                 <ValBCIRRF>0.00</ValBCIRRF>                 <ValBCCSLL>0.00</ValBCCSLL>                             <ISSRetido>2</ISSRetido>                <RespRetencao />                <Tributavel />              <ValISS>4.00</ValISS>               <ValISSRetido />                <ValBaseCalculo>200.00</ValBaseCalculo>                 <ValOutrasRetencoes />              <ValAliqISS>2.00</ValAliqISS>                                       <ValAliqPIS>0</ValAliqPIS>              <PISRetido />               <ValAliqCOFINS>0.0800</ValAliqCOFINS>               <COFINSRetido/>                 <ValAliqIR>0.0000</ValAliqIR>               <IRRetido />                <ValAliqCSLL>0.0000</ValAliqCSLL>               <CSLLRetido />              <ValAliqINSS>0.0000</ValAliqINSS>               <INSSRetido />              <ValLiquido />              <ValAliqCpp />              <CppRetido />               <ValCpp />              <OutrasRetencoesRetido />               <ValAliqTotTributos />              <ValLiquido>200.00</ValLiquido>                 <ValDescIncond>0.00</ValDescIncond>                 <ValDescCond>0.00</ValDescCond>                 <ValAliqISSoMunic />                <InfValPIS />                <InfValCOFINS />                <ValLiqFatura />                <ValBCISSRetido />         </Valores>          <LocalPrestacao>                <SerEndTpLgr />                 <SerEndLgr />               <SerEndNumero />                <SerEndComplemento />               <SerEndBairro />                 <SerEndxMun />             <SerEndcMun />              <SerEndUF />                 <SerEndCep />              <SerEndSiglaUF />            </LocalPrestacao>          <cServ />            <IteListServico>1405</IteListServico>                                                       <Cnae>0</Cnae>                                                     <fPagamento />          <Discriminacao>Servico de recapagem de pneus.</Discriminacao>           <cMun>4305108</cMun>                                    <cMunIncidencia>4305108</cMunIncidencia>            <SerQuantidade />           <SerUnidade />          <SerNumAlva />          <PaiPreServico />           <dVencimento />             <ObsInsPagamento />             <ObrigoMunic />             <TributacaoISS />       </Servico>      <Tomador>           <TomaCNPJ>00000000000000</TomaCNPJ>             <TomaCPF />              <TomaIE />          <TomaIM />         <TomaRazaoSocial>Henrique</TomaRazaoSocial>             <TomatpLgr>RUA</TomatpLgr>          <TomaEndereco>RUA DO COMERCIO</TomaEndereco>            <TomaNumero>3000</TomaNumero>           <TomaComplemento>321</TomaComplemento>          <TomaBairro>UNIVERSITARIO</TomaBairro>          <TomacMun>4305108</TomacMun>            <TomaxMun>Caxias do Sul</TomaxMun>           <TomaUF>RS</TomaUF>             <TomaPais>BR</TomaPais>            <TomaCEP>98700000</TomaCEP>             <TomaTelefone>5535354800</TomaTelefone>              <TomaEmail>teste@migrate.com.br</TomaEmail>            <TomaSite>www.migratecompany.com.br</TomaSite>          <TomaIME />         </Tomador>      <IntermServico>              <IntermRazaoSocial />           <IntermCNPJ />          <IntermCPF />           <IntermIM />        </IntermServico>        <ConstCivil>            <CodObra />             <Art />             <ObraLog />             <ObraCompl />          <ObraNumero />           <ObraBairro />         <ObraCEP />              <ObraMun />             <ObraUF />         <ObraPais />             <ObraCEI />            <ObraMatricula />           <ObraValRedBC />        </ConstCivil>        <ListaDed />       <Transportadora>            <TraNome />              <TraCPFCNPJ />          <TraIE />           <TraPlaca />            <TraEnd />         <TraMun />          <TraUF />           <TraPais />             <TraTipoFrete />         </Transportadora>       <NFSOutrasinformacoes />        <Arquivo />                        		<ExtensaoArquivo />      </RPS> </Envio>';
           # $xmlNFSE = str_replace(array("\r\n", "\r", "\n"), "", $xmlNFSE);
            echo $xmlNFSE;
            exit;
            //$xmlNFSE            = preg_replace('/>\s+</','><',$xmlNFSE);
            #echo $xmlNFSE;
            #exit;            
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
                $url  	= "https://homolog.invoicy.com.br/arecepcao.aspx?wsdl";
                $client = new SoapClient($url , array("location" => $url));
                $retorno = $client->Execute($parametros);
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