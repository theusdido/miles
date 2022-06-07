<?php
    $op  = tdc::r('op');
    switch($op){
        case 'upload':
            set_time_limit(3600);
            $link = PATH_CURRENT_FILE_TEMP . date('Y-m-d h:i:s') . ".xml";
            Session::append("NFSE_XML_FILE",$link);
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
            $link           = Session::Get('NFSE_XML_FILE');
            $config         = file ($link);
            $qtde           = sizeof($config);

            $linha          = $config[$indice-1];
            $rps            = substr($linha,4,5);

            if ($rps != ""){
                
                // Grava as Notas na base de dados	
                $nfse                       = tdc::p('td_erp_nfse_nota');
                $nfse->rpsnumero  			= conteudo_tag($linha,"RPSNumero");						
                $nfse->rpsserie 			= conteudo_tag($linha,"RPSSerie");
                $nfse->rpstipo				= conteudo_tag($linha,"RPSTipo");
                $nfse->demis				= conteudo_tag($linha,"dEmis");
                $nfse->dcompetencia			= conteudo_tag($linha,"dCompetencia");
                $nfse->natop				= conteudo_tag($linha,"natOp");
                $nfse->operacao				= conteudo_tag($linha,"Operacao");
                $nfse->numprocesso			= conteudo_tag($linha,"NumProcesso");
                $nfse->regesptrib			= conteudo_tag($linha,"RegEspTrib");
                $nfse->optsn				= conteudo_tag($linha,"OptSN");
                $nfse->inccult				= conteudo_tag($linha,"IncCult");
                $nfse->status				= 'P';
                $nfse->nfsoutrasinformacoes	= conteudo_tag($linha,"NFSOutrasinformacoes");
                $nfse->armazenar();

                $nfseID = $nfse->id;

                $item = tdc::p("td_erp_nfse_item");
                $item->nfse             = $nfseID;
                $item->itemseq  		= conteudo_tag($linha,"ItemSeq");
                $item->itemcod  		= conteudo_tag($linha,"ItemCod");									
                $item->itemdesc  		= conteudo_tag($linha,"ItemDesc");						
                $item->itemqtde  		= conteudo_tag($linha,"ItemQtde");	
                $item->itemvunit  	    = conteudo_tag($linha,"ItemvUnit");	
                $item->itemumed  		= conteudo_tag($linha,"ItemuMed");	
                $item->itemvlded  	    = conteudo_tag($linha,"ItemvlDed");	
                $item->itemtributavel   = conteudo_tag($linha,"ItemTributavel");	
                $item->itemccnae  	    = conteudo_tag($linha,"ItemcCnae");	
                $item->itemservmunic    = conteudo_tag($linha,"ItemServMunic");
                $item->itemnalvara  	= conteudo_tag($linha,"ItemnAlvara");
                $item->itemviss  		= conteudo_tag($linha,"ItemvISS");
                $item->itemvdesconto    = conteudo_tag($linha,"ItemvDesconto");
                $item->itemaliquota  	= conteudo_tag($linha,"ItemAliquota");
                $item->itemvlrtotal  	= conteudo_tag($linha,"ItemVlrTotal");
                $item->armazenar();

                $parcelas                       = tdc::p("td_erp_nfse_parcelas");
                $parcelas->nfse                 = $nfseID;
                $parcelas->prcsequencial  	    = conteudo_tag($linha,"PrcSequencial");
                $parcelas->prcvalor  			= conteudo_tag($linha,"PrcValor");							
                $parcelas->prcdtavencimento  	= conteudo_tag($linha,"PrcDtaVencimento");
                $parcelas->armazenar();

                $servico                        = tdc::p("td_erp_nfse_servico");
                $servico->nfse                  = $nfseID;
                $servico->valservicos  		    = conteudo_tag($linha,"ValServicos");
                $servico->valdeducoes  		    = conteudo_tag($linha,"ValDeducoes");
                $servico->valpis  			    = conteudo_tag($linha,"ValPIS");
                $servico->valcofins  		    = conteudo_tag($linha,"ValCOFINS");
                $servico->valinss  			    = conteudo_tag($linha,"ValINSS");	
                $servico->valir  			    = conteudo_tag($linha,"ValIR");	
                $servico->valcsll  			    = conteudo_tag($linha,"ValCSLL");	
                $servico->issretido  		    = conteudo_tag($linha,"ISSRetido");
                $servico->respretencao  		= conteudo_tag($linha,"RespRetencao");
                $servico->tributavel  		    = conteudo_tag($linha,"Tributavel");
                $servico->valiss  			    = conteudo_tag($linha,"ValISS");	
                $servico->valissretido  		= conteudo_tag($linha,"ValISSRetido");	
                $servico->valoutrasretencoes    = conteudo_tag($linha,"ValOutrasRetencoes");	
                $servico->valbasecalculo  	    = conteudo_tag($linha,"ValBaseCalculo");	
                $servico->valaliqiss  		    = conteudo_tag($linha,"ValAliqISS");	
                $servico->valaliqpis  		    = conteudo_tag($linha,"ValAliqPIS");	
                $servico->valaliqcofins  	    = conteudo_tag($linha,"ValAliqCOFINS");	
                $servico->valaliqir  		    = conteudo_tag($linha,"ValAliqIR");	
                $servico->valaliqcsll  		    = conteudo_tag($linha,"ValAliqCSLL");	
                $servico->valaliqinss  		    = conteudo_tag($linha,"ValAliqINSS");	
                $servico->valliquido  		    = conteudo_tag($linha,"ValLiquido");	
                $servico->valdesccond  		    = conteudo_tag($linha,"ValDescCond");	
                $servico->valdescincond  	    = conteudo_tag($linha,"ValDescIncond");	
                $servico->valaliqissomunic 	    = conteudo_tag($linha,"ValAliqISSoMunic");	
                $servico->infvalpis  		    = conteudo_tag($linha,"InfValPIS");	
                $servico->infvalcofins   	    = conteudo_tag($linha,"InfValCOFINS");	
                $servico->cserv 			    = conteudo_tag($linha,"cServ");	
                $servico->itelistserv  		    = conteudo_tag($linha,"IteListServ");	
                $servico->cnae  			    = conteudo_tag($linha,"Cnae");	
                $servico->fpagamento  		    = conteudo_tag($linha,"fPagamento");
                $servico->tributmunicipio	    = conteudo_tag($linha,"TributMunicipio");
                $servico->discriminacao  	    = conteudo_tag($linha,"Discriminacao");	
                $servico->cmun  			    = conteudo_tag($linha,"cMun");	
                $servico->serquantidade  	    = conteudo_tag($linha,"SerQuantidade");	
                $servico->serunidade  		    = conteudo_tag($linha,"SerUnidade");	
                $servico->sernumalvara 		    = conteudo_tag($linha,"SerNumAlvara");	
                $servico->paipreservico  	    = conteudo_tag($linha,"PaiPreServico");	
                $servico->cmunincidencia  	    = conteudo_tag($linha,"cMunIncidencia");	
                $servico->obrigomunic  		    = conteudo_tag($linha,"ObrigoMunic");	
                $servico->tributacaoiss  	    = conteudo_tag($linha,"TributacaoISS");
                $servico->armazenar();

                $tomador                        = tdc::p("td_erp_nfse_tomador");
                $tomador->nfse                  = $nfseID;
                $tomador->tomacpf				= conteudo_tag($linha,"TomaCPF");
                $tomador->tomacnpj              = conteudo_tag($linha,"TomaCNPJ");
                $tomador->tomarazaosocial    	= conteudo_tag($linha,"TomaRazaoSocial");
                $tomador->tomaim	            = conteudo_tag($linha,"TomaIM");
                $tomador->tomasite  			= conteudo_tag($linha,"TomaSite");
                $tomador->tomatplgr				= conteudo_tag($linha,"TomatpLgr");
                $tomador->tomaendereco 			= conteudo_tag($linha,"TomaEndereco");	
                $tomador->tomanumero  			= conteudo_tag($linha,"TomaNumero");	
                $tomador->tomacomplemento  		= conteudo_tag($linha,"TomaComplemento");	
                $tomador->tombairro  			= conteudo_tag($linha,"TomBairro");	
                $tomador->tomacmun  			= conteudo_tag($linha,"TomacMun");	
                $tomador->tomaxmun  			= conteudo_tag($linha,"TomaxMun");	
                $tomador->tomauf  				= conteudo_tag($linha,"TomaUF");	
                $tomador->tomapais  			= conteudo_tag($linha,"TomaPais");	
                $tomador->tomacep  				= conteudo_tag($linha,"TomaCEP");		
                $tomador->tomatelefone 			= conteudo_tag($linha,"TomaTelefone");	
                $tomador->tomaemail  			= conteudo_tag($linha,"TomaEmail");
                $tomador->armazenar();

                $intermediario                      = tdc::p("td_erp_nfse_intermediario");
                $intermediario->nfse                = $nfseID;
                $intermediario->intermrazaosocial 	= conteudo_tag($linha,"IntermRazaoSocial");
                $intermediario->intermcnpj  		= conteudo_tag($linha,"IntermCNPJ");
                $intermediario->intermcpf  			= conteudo_tag($linha,"IntermCPF");
                $intermediario->intermim  			= conteudo_tag($linha,"IntermIM");
                $intermediario->armazenar();

                $construcaocivil                = tdc::p("td_erp_nfse_construcaocivil");
                $construcaocivil->nfse            = $nfseID;
                $construcaocivil->codobra 		= conteudo_tag($linha,"CodObra");
                $construcaocivil->art  			= conteudo_tag($linha,"Art");
                $construcaocivil->armazenar();

                echo 1;

            }
        break;
    }