<?php

    require PATH_CLASS . 'nfse/snnfse.class.php';

    /*
    $nfse = new snNFSE();
    $nfse->clientCert = __DIR__ . '/chave_privada.pem';
    $nfse->publicCert = __DIR__ . '/certificado_publico.pem';
    $nfse->certPass   = 'goes1234';

    $xml = '<LoteDps xmlns="http://www.sped.fazenda.gov.br/nfse" Id="Lote1"><idLote>1</idLote><qtdDps>1</qtdDps><listaDps><DPS versao="1.00"><infDPS Id="DPS420460882324802100015800001000000000109125"><tpAmb>1</tpAmb><dhEmi>2025-12-18T00:00:00-03:00</dhEmi><verAplic>1.0.0</verAplic><serie>1</serie><nDPS>109125</nDPS><dCompet>2025-12-01</dCompet><tpEmit>1</tpEmit><cLocEmi>4204608</cLocEmi><prest><CNPJ>83248021000158</CNPJ><regTrib><opSimpNac>3</opSimpNac><regApTribSN>1</regApTribSN><regEspTrib>0</regEspTrib></regTrib></prest><toma><CPF>99933063987</CPF><xNome>VIVIANE GRUNDLER VEFAGO</xNome><end><xLgr>RUA EXEMPLO</xLgr><nro>100</nro><xBairro>CENTRO</xBairro><cMun>4204608</cMun><UF>SC</UF><CEP>88900000</CEP></end></toma><serv><locPrest><cLocPrestacao>4204608</cLocPrestacao></locPrest><cServ><cTribNac>100501</cTribNac><cNBS>110012200</cNBS></cServ><xDescServ>PRESTACAO DE SERVICOS</xDescServ></serv><valores><vServPrest><vServ>185.61</vServ></vServPrest><trib><tribMun><tribISSQN>1</tribISSQN><tpRetISSQN>1</tpRetISSQN><vAliqISSQN>2.00</vAliqISSQN><vISSQN>3.71</vISSQN></tribMun><totTrib><vTotTrib><vTotTribFed>0.00</vTotTribFed><vTotTribEst>0.00</vTotTribEst><vTotTribMun>3.71</vTotTribMun></vTotTrib></totTrib></trib></valores></infDPS></DPS></listaDps></LoteDps>';

    $nfse->setLoteXml($xml); // LoteDps com DPS assinado
    $res = $nfse->send();

    print_r($res);
    */

    $rpsnumero = tdc::r('nota')['rpsnumero'];

    $nfse = new snNFSE();
    #$nfse->clientCert   = __DIR__ . '/chave_privada.pem';
    #$nfse->publicCert   = __DIR__ . '/certificado_publico.pem';
    #$nfse->cafile       = '/etc/ssl/certs/ca-certificates.crt';
    $nfse->addLoteRPS([$rpsnumero]);
    #$nfse->send();
    $res = $nfse->send();
    #$res = $nfse->sendGPT();
    tdc::wj($res);    