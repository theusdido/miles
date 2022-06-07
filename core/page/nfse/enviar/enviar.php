<?php
    $nfse       = tdc::p('td_erp_nfse_nota',tdc::r('nota')['id']);
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
                        <ValServicos>'.$nfse->valservicos.'</ValServicos>
                        <ValDeducoes>'.$nfse->valdeducoes.'</ValDeducoes>
                        <ValPIS>'.$nfse->valpis.'</ValPIS>
                        <ValCOFINS>'.$nfse->valcofins.'</ValCOFINS>
                        <ValINSS>'.$nfse->valinss.'</ValINSS>
                        <ValIR>'.$nfse->valir.'</ValIR>
                        <ValCSLL>'.$nfse->valcsll.'</ValCSLL>
                        <ValBCPIS>'.$nfse->valbcpis.'</ValBCPIS>
                        <ValBCCOFINS>'.$nfse->valbccofins.'</ValBCCOFINS>
                        <ValBCINSS>'.$nfse->valbcinss.'</ValBCINSS>
                        <ValBCIRRF>'.$nfse->valbcirrf.'</ValBCIRRF>
                        <ValBCCSLL>'.$nfse->valbccsll.'</ValBCCSLL>
                        <ISSRetido>'.$nfse->isretido.'</ISSRetido>
                        <RespRetencao>'.$nfse->respretencao.'</RespRetencao>
                        <Tributavel />'.$nfse->tributavel.'</Tributavel>
                        <ValISS>'.$nfse->valiss.'</ValISS>
                        <ValISSRetido>'.$nfse->valissretido.'</ValISSRetido>
                        <ValBaseCalculo>'.$nfse->valbasecalculo.'</ValBaseCalculo>
                        <ValOutrasRetencoes>'.$nfse->valoutrasretencoes.'</ValOutrasRetencoes>
                        <ValAliqISS>'.$nfse->valaliqiss.'</ValAliqISS>
                        <ValAliqPIS>'.$nfse->valaliqpis.'</ValAliqPIS>
                        <PISRetido>'.$nfse->pisretido.'</PISRetido>
                        <ValAliqCOFINS>'.$nfse->valaliqcofins.'</ValAliqCOFINS>
                        <COFINSRetido>'.$nfse->cofinsretido.'</COFINSRetido>
                        <ValAliqIR>'.$nfse->valaliqir.'</ValAliqIR>
                        <IRRetido>'.$nfse->irretido.'<IRRetido>
                        <ValAliqCSLL>'.$nfse->valaliqcsll.'</ValAliqCSLL>
                        <CSLLRetido>'.$nfse->csllretido.'</CSLLRetido>
                        <ValAliqINSS>'.$nfse->valaliqinss.'</ValAliqINSS>
                        <INSSRetido>'.$nfse->inssretido.'</INSSRetido>
                        <ValLiquido>'.$nfse->valliquido.'</ValLiquido>
                        <ValAliqCpp>'.$nfse->valaliqcpp.'</ValAliqCpp>
                        <CppRetido>'.$nfse->cppretido.'</CppRetido>
                        <ValCpp>'.$nfse->valcpp.'</ValCpp>
                        <OutrasRetencoesRetido>'.$nfse->outrasretencoes.'</OutrasRetencoesRetido>
                        <ValAliqTotTributos>'.$nfse->valaliqtottributos.'</ValAliqTotTributos>
                        <ValLiquido>'.$nfse->valliquido.'</ValLiquido>
                        <ValDescIncond>'.$nfse->valdescincond.'</ValDescIncond>
                        <ValDescCond>'.$nfse->valdesccond.'</ValDescCond>
                        <ValAliqISSoMunic>'.$nfse->valaliqissmunic.'</ValAliqISSoMunic>
                        <InfValPIS>'.$nfse->infvalpis.'</InfValPIS>
                        <InfValCOFINS>'.$nfse->infvalcofins.'</InfValCOFINS>
                        <ValLiqFatura>'.$nfse->valliqfatura.'</ValLiqFatura>
                        <ValBCISSRetido>'.$nfse->valbcissretido.'</ValBCISSRetido>
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
                    <IteListServico>'.$nfse->itelistserv.'</IteListServico>
                    <Cnae>'.$nfse->cnae.'</Cnae>
                    <fPagamento>'.$nfse->fpagamento.'</fPagamento>
                    <Discriminacao>'.$nfse->discriminacao.'</Discriminacao>
                    <cMun>'.$nfse->cmun.'</cMun>
                    <cMunIncidencia>'.$nfse->cmunincidencia.'</cMunIncidencia>
                    <SerQuantidade>'.$nfse->serquantidade.'</SerQuantidade>
                    <SerUnidade>'.$nfse->serunidade.'</SerUnidade>
                    <SerNumAlva>'.$nfse->sernumalva.'</SerNumAlva>
                    <PaiPreServico>'.$nfse->paipreservico.'</PaiPreServico>
                    <dVencimento>'.$nfse->dvencimento.'</dVencimento>
                    <ObsInsPagamento>'.$nfse->obsinspagamento.'</ObsInsPagamento>
                    <ObrigoMunic>'.$nfse->obrigomunic.'</ObrigoMunic>
                    <TributacaoISS>'.$nfse->tributacaoiss.'</TributacaoISS>
                </Servico>
                <Tomador>
                    <TomaCNPJ>'.$nfse->tomacnpj.'</TomaCNPJ>
                    <TomaCPF>'.$nfse->tomacpf.'</TomaCPF>
                    <TomaIE>'.$nfse->tomaie.'</TomaIE>
                    <TomaIM>'.$nfse->tomaim.'</TomaIM>
                    <TomaRazaoSocial>'.$nfse->tomarazaosocial.'</TomaRazaoSocial>
                    <TomatpLgr>'.$nfse->tomatplgr.'</TomatpLgr>
                    <TomaEndereco>'.$nfse->tomaendereco.'</TomaEndereco>
                    <TomaNumero>'.$nfse->tomanumero.'</TomaNumero>
                    <TomaComplemento>'.$nfse->tomacomplemento.'</TomaComplemento>
                    <TomaBairro>'.$nfse->tomabairro.'</TomaBairro>
                    <TomacMun>'.$nfse->tomacmun.'</TomacMun>
                    <TomaxMun>'.$nfse->tomaxmun.'</TomaxMun>
                    <TomaUF>'.$nfse->tomars.'</TomaUF>
                    <TomaPais>BR</TomaPais>
                    <TomaCEP>'.$nfse->tomacep.'</TomaCEP>
                    <TomaTelefone>'.$nfse->tomatelefone.'</TomaTelefone>
                    <TomaEmail>'.$nfse->tomaemail.'</TomaEmail>
                    <TomaSite>'.$nfse->tomasite.'</TomaSite>
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

    
	$chaveComunicacao 	= md5('IbFgTGNW+kIUlDN9RJQD6aDr+md0xGJI' . $xmlNFSE);
	/*
    $fp = fopen('empCK.txt','w');
	fwrite($fp,$xmlNFSE);
	fwrite($fp,"\n-------------------------\n");
	fwrite($fp,$chaveComunicacao);
	fclose($fp);
    */

	$parametros = array(
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
		var_dump($retorno);
	}catch(SoapFault $e){
		echo 'Erro: ['.$e->getCode() . "] => " .$e->getMessage().'<br/>';
		echo '<pre>';
		var_dump($e);
		echo '</pre>';
	}
