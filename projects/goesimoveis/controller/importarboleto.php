<?php
	$arq = "lote.txt"; // Arquivo temp do lote

	#if (isset($_FILES["carregar_arquivo"])){

		#if (file_exists($arq)) unlink($arq);
		#move_uploaded_file($_FILES["carregar_arquivo"]["tmp_name"],$arq);
		#$linhas	 = file($arq);
		#foreach($linhas as $linha){
            $dados                          = explode('^','"24/01/2022^4230^07/02/2022^1799,13^202201.8553^SM COM. E ASSIST. TECNICA DE INFORMATICA LTDA^07.496.736/0001-41^RODOVIA JOSE SPILLERE,1184 -Ed. SL 01 E 02 PASSAR E-MAIL-SM INFORMATICA^CARAVAGIO^NOVA VENEZA^SC^88.868-000" "^samueldb@sminfosc.com.br^Total s/desc 1799,13<BR>Total c/multa 1979,04 Ate15/02/2022<BR>Apos 15/02/2022 cobrar mora diaria de" "<BR>R$3,96-sobre o total c/ multa<BR>Protestar após 15 dias do vencimento<BR>^DM^Nao Aceite^R$^24/01/2022^C11 DESC.NO ALUGUEL~374,84" "#D01 ALUGUEL  Mes ref:    202201~2074,84#D07 SEGURO Par. 3/4~99,13" "" "" "^GOES IMOVEIS LTDA^CRICIUMA^9^136^8^1707^546585^Pagavel em qualquer banco ate a data do vencimento^Rua Mal. Deodoro, 355 - Centro - Criciuma/SC^88.801-110^(48)3437-2552^21" "^8772^RODOVIA JOSE SPILLERE, 1184, 01 e 02 -CARAVAGIO - 88.868-000 -NOVA VENEZA IM: 5058 Pasta: 757^10/07/2022"');
            $_boleto                        = new MongoDB('boleto');
            $dt_proc 				        = $dados[0];
            $_boleto->num_bol 				= trim($dados[1]);
            $_boleto->dt_vencto				= $dados[2];

            $_boleto->vlr_documento			= $dados[3];
            $_boleto->documento				= trim($dados[4]);
            $array_doc 					    = explode(".",$_boleto->documento);
            $_boleto->contrato 				= (int)$array_doc[1];
            $_boleto->anomes				= $array_doc[0];
            $_boleto->pagador				= $dados[5];
            $_boleto->cpfj					= $dados[6];
            $_boleto->endereco				= $dados[7];
            $_boleto->bairro				= $dados[8];
            $_boleto->cidade 				= $dados[9];
            $_boleto->uf					= $dados[10];
            $_boleto->cep_pagador			= $dados[11];
            $_boleto->email					= strtolower($dados[12]);
            $_boleto->instrucoes			= trim($dados[13]);
            $_boleto->especie_doc			= $dados[14];
            $_boleto->aceite				= $dados[15];					
            $_boleto->especie				= $dados[16];
            $_boleto->dt_emissao			= $dados[17];
            $_boleto->eventos				= $dados[18];

            $_boleto->empresa				= $dados[19];
            $_boleto->municipio				= $dados[20];
            $_boleto->nosso_numero			= $_boleto->num_bol;
            $_boleto->moeda					= $dados[21];					
            $_boleto->banco					= $dados[22];
            $_boleto->digito_banco			= $dados[23];
            $_boleto->beneficiario			= $_boleto->empresa;
            $_boleto->agencia				= $dados[24];
            $_boleto->conta					= $dados[25];
            $_boleto->local_pagto			= utf8_encode($dados[26]);
            $_boleto->endereco_beneficiario = $dados[27];
            $_boleto->cep_beneficiario		= $dados[28];
            $_boleto->fone					= $dados[29];
            $_boleto->prefixo_nossonumero	= trim($dados[30]);
            $_boleto->enderecoimovel		= $dados[32];
            $_boleto->datareajuste			= $dados[33];

            // Registro de LOG
            
            #$bounce->datahora       = date('d/m/Y H:i:s');
            #$bounce->data           = date('d/m/Y');
            #$bounce->hora           = date('H:i:s');
            #$bounce->email          = $email;
            #$bounce->status         = $status;
            #$bounce->extrato        = $extrato;
            #$bounce->proprietario   = $proprietario;
            #$bounce->referencia     = $anomesref;
            $_boleto->salvar();
            
            //echo json_encode($bol);
		#}
	#}