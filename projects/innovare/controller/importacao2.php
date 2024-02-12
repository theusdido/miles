<?php
	if ($_POST){
		if ($_POST["op"] == "salvar"){
			$camada1 = array("&nbsp;","-");
			$camada2 = array('style=""',"<span>","</span>","<span >");
			$camada1STR = str_replace($camada1,"",$_POST["dados"]);
			$camada2STR = str_replace($camada2,"",$camada1STR);
			$camada3STR = str_replace("<span style='mso-spacerun:yes'>","^",$camada2STR);
			$camada4STR = str_replace('<span style="msospacerun:yes">',"",$camada3STR);
			$linha = explode("^",trim($camada4STR)); 
			$nome = trim($linha[0]);
			$cnpj =str_replace("Â­","",utf8_encode($linha[1]));
			$cnpj = str_replace(array(".","/","-"),"",$cnpj);			
			$logradouro = trim($linha[2]);			
			$numero = trim($linha[3]);
			
			#$bairro = trim(substr($linha[4],4,strlen($linha[4])));
			#$bairro = str_replace(" ","",$bairro);
			$cidades = array(
				"TIMBO","BLUMENAU"	,"APIÚNA"	,"INDAIAL"	,"GASPAR"	,"ASCURRA"	,"RODEIO"	,"FLORIANÓPOLIS"	,"RIO NEGRINHO"	,"BRUSQUE"	,"TRES LAGOAS"	,"ITAJAI"	,"SÃO PAULO"	,"JARAGUA DO SUL"	,"PALHOCA"	,"APIUNA"	,"SAO JOSE"	,"POMERODE"	,"BARUERI"	,"BELO HORIZONTE"	,"JOINVILLE"	,"GASPAR"	,"ITAQUAQUECETUBA" 	,"CINGAPURA"	,"SAO LEOPOLDO",
				"SAO CRISTOVAO DO SUL","IRATI","GUARULHOS","ITALIA","GUABIRUBA","INDAIAL","ARAQUARI","MARINGA","ASCURRA","REBOUCAS","MASSARANDUBA","ASSAI","CAXIAS  DO SUL","BALNEARIO CAMBORIU","NOVA ODESSA","AMERICANA","RODEIO","CABO  DE SANTO  AGOSTIN","POCOS  DE CALDAS","IMBITUBA","ITUPEVA","NOVA TRENTO","TUBARAO","FORTALEZA","ALHANDRA","JOAO PESSOA","RIO NEGRO",
				"LAGES","GAROPABA","DIVINOPOLIS","ESTANCIA VELHA","SANTA ISABEL","SAO BERNARDO DO CAMP","PARANAGUA","GUARAMIRIM","BRASILIA","SAO JOSE DOS PINHAIS","CAIEIRAS","TIJUCAS","OSASCO","PORTO  VELHO","GUAXUPE","ICARA","RIO DE JANEIRO","CURITIBA","GUARAPUAVA","FOZ DO IGUACU","RIBEIRAO PRETO","ITAPEVA","CONTAGEM","POUSO  ALEGRE","CAMPINAS","SANTO  ANDRE"
			);
			$codCids = array(84		,62			,85			,86			,87			,88			,89			,65					,71				,98			,99				,100		,30				,32					,101		,85			,31			,96			,102		,103				,104			,87			,105				,106			,107,
				109,110,111,112,113,86,114,115,88,116,117,118,119,120,121,122,89,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158);
			$cidade = str_replace($cidades,$codCids,trim($linha[4]));			
			$estado = 1;
			$cep = str_replace("Â­","",utf8_encode($linha[7]));
			$telefone = str_replace("(0)","(00)",$linha[8]);
			$classificacao = 10;
			$valor = str_replace("R$","",trim($linha[13]));
			$valor = str_replace(" ","",$valor);
			$valor = str_replace(".","",$valor);
			$valor = str_replace(",",".",$valor);
			
			$natureza = 1;
			$tipoempresa = 1;
			if ($conn = Transacao::get()){
				try{
					$codigo = $conn->query("SELECT IFNULL(MAX(codigo),0)+1 FROM td_relacaocredores WHERE processo = 3");
					$cod = $codigo->fetch();
					$credor = tdClass::Criar("persistent",array("td_relacaocredores"));
					$credor->contexto->codigo = $cod[0];
					$credor->contexto->tipo = 1;
					$credor->contexto->origemcredor = 1;
					$credor->contexto->nome = $nome;
					$credor->contexto->cnpj = $cnpj;
					$credor->contexto->moeda = 1;
					$credor->contexto->valor = $valor;
					$credor->contexto->processo = 3;
					#$credor->contexto->cep = $cep;
					$credor->contexto->logradouro = $logradouro;
					$credor->contexto->numero = $numero;
					#$credor->contexto->bairro = $bairro;
					$credor->contexto->cidade = $cidade;
					#$credor->contexto->estado = $estado;
					$credor->contexto->natureza = $natureza;
					$credor->contexto->classificacao = $classificacao;
					$credor->contexto->tipoempresa = $tipoempresa;
					echo $nome ."^" . $cnpj . "^" . $logradouro . "^" . $numero."^" . $cidade . "^" . $estado . "^" . $cep . "^" . $telefone . "^" . $classificacao ."^".$valor."^".$natureza."^".$classificacao;
					$credor->contexto->armazenar();
					$conn->commit();
				}catch(Exception $e){
					echo $e->getMessage();
					$conn->rollback();
				}	
			}	
			exit;
		}
	}		
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>
<!--
<tr height=80 style='height:60.0pt'>
  <td height=80 class=xl65 width=262 style='height:60.0pt;width:197pt'>FORNECEDOR</td>
  <td class=xl65 width=160 style='width:120pt'>CNPJ</td>
  <td class=xl65 width=193 style='width:145pt'>LOGRADOURO</td>
  <td class=xl65 width=138 style='width:104pt'>NUMERO<span
  style='mso-spacerun:yes'>         </span>CIDADE</td>
  <td class=xl65 width=173 style='width:130pt'>UF</td>
  <td class=xl65 width=140 style='width:105pt'>NATUREZA</td>
  <td class=xl65 width=191 style='width:143pt'>VENCIMENTOS</td>
  <td class=xl65 width=190 style='width:143pt'>CLASSIFICAÇÃO</td>
  <td class=xl65 width=131 style='width:98pt'>REGISTRO CONTÁBIL</td>
  <td class=xl65 width=167 style='width:125pt'>VALOR</td>
  <td class=xl65 width=144 style='width:108pt'>DATA ATUALIZAÇÃO</td>
  <td class=xl65 width=64 style='width:48pt'>VALOR</td>
  <td class=xl65 width=197 style='width:148pt'>ATUALIZADO</td>
 </tr>--> 
<?php

$dados =  "
<table>
<tr height=14 style='mso-height-source:userset;height:10.7pt'>
  <td height=14 class=xl92 style='height:10.7pt'>WENDA CO., LTD.</td>
  <td class=xl93>&nbsp;</td>
  <td class=xl92>35922<span style='mso-spacerun:yes'>  </span>NO. 18.3RD<span
  style='mso-spacerun:yes'>  </span>SHENGMING ROAD.DD PORT.<span
  style='mso-spacerun:yes'>  </span>DALIAN<span style='mso-spacerun:yes'> 
  </span>CHINA</td>
  <td class=xl92>&nbsp;</td>
  <td class=xl92>&nbsp;</td>
  <td class=xl92>EX</td>
  <td class=xl92>(em branco)<span style='mso-spacerun:yes'>     
  </span>IMPORTAÇÃO</td>
  <td class=xl92>VENCIDA E VINCENDAS</td>
  <td class=xl92>QUIROGRAFÁRIOS<span style='mso-spacerun:yes'>  </span></td>
  <td class=xl94 align=right>1376</td>
  <td class=xl94>R$<span style='mso-spacerun:yes'> </span></td>
  <td class=xl94><span style='mso-spacerun:yes'>               
  </span>1.597.319,85<span style='mso-spacerun:yes'>   </span>27/05/0215<span
  style='mso-spacerun:yes'>                      </span></td>
  <td class=xl94>R$</td>
  <td class=xl96 align=right>1.597.319,85</td>
  <td></td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.9pt'>
  <td height=14 class=xl92 style='height:10.9pt'>WENDA CO., LTD.</td>
  <td class=xl93>&nbsp;</td>
  <td class=xl92>35922<span style='mso-spacerun:yes'>  </span>NO. 18.3RD<span
  style='mso-spacerun:yes'>  </span>SHENGMING ROAD.DD PORT.<span
  style='mso-spacerun:yes'>  </span>DALIAN<span style='mso-spacerun:yes'> 
  </span>CHINA</td>
  <td class=xl92>&nbsp;</td>
  <td class=xl92>&nbsp;</td>
  <td class=xl92>EX</td>
  <td class=xl92>(em branco)<span style='mso-spacerun:yes'>     
  </span>IMPORTAÇÃO</td>
  <td class=xl92>VINCENDAS</td>
  <td class=xl92>QUIROGRAFÁRIOS<span style='mso-spacerun:yes'>  </span></td>
  <td class=xl94 align=right>1778</td>
  <td class=xl94>R$<span style='mso-spacerun:yes'> </span></td>
  <td class=xl94>R$<span style='mso-spacerun:yes'>                 
  </span>3.094.180,11<span style='mso-spacerun:yes'>   </span>27/05/0215<span
  style='mso-spacerun:yes'>              </span></td>
  <td class=xl94>R$</td>
  <td class=xl96 align=right>3.094.180,11</td>
  <td></td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.9pt'>
  <td height=14 class=xl92 style='height:10.9pt'>YANGZI BRASIL<span
  style='mso-spacerun:yes'>  </span>CORPORATION LTDA</td>
  <td class=xl94>01.219.321/0001-44<span style='mso-spacerun:yes'>      </span></td>
  <td class=xl94>RUA AV SÓCRATES MARIANI BITTENCOURT 1050</td>
  <td class=xl94>&nbsp;</td>
  <td class=xl92>-<span style='mso-spacerun:yes'>                         
  </span>CONTAGEM</td>
  <td class=xl92>MG</td>
  <td class=xl92><span style='mso-spacerun:yes'>                   
  </span>FORNECEDORES</td>
  <td class=xl92>VINCENDAS<span style='mso-spacerun:yes'> </span></td>
  <td class=xl92>QUIROGRAFÁRIOS<span style='mso-spacerun:yes'>  </span></td>
  <td class=xl92>1376</td>
  <td class=xl94>R$<span style='mso-spacerun:yes'> </span></td>
  <td class=xl92>R$<span style='mso-spacerun:yes'>                        
  </span>1.306,37<span style='mso-spacerun:yes'>   </span>27/05/0215</td>
  <td class=xl94>R$</td>
  <td class=xl92>R$<span style='mso-spacerun:yes'>                        
  </span>1.306,37</td>
  <td></td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.9pt'>
  <td height=14 class=xl92 style='height:10.9pt'>ZEUS DO BRASIL<span
  style='mso-spacerun:yes'>  </span>LTDA</td>
  <td class=xl94>82.699.588/0001-88<span style='mso-spacerun:yes'>     </span></td>
  <td class=xl94><span style='mso-spacerun:yes'> </span>RODOVIA BR 470<span
  style='mso-spacerun:yes'>  </span>KM 63 8484</td>
  <td class=xl94>&nbsp;</td>
  <td class=xl92>-<span style='mso-spacerun:yes'>                         
  </span>BLUMENAU</td>
  <td class=xl92>SC</td>
  <td class=xl92><span style='mso-spacerun:yes'>                    
  </span>FORNECEDORES</td>
  <td class=xl92>VINCENDAS</td>
  <td class=xl92>QUIROGRAFÁRIOS<span style='mso-spacerun:yes'>  </span></td>
  <td class=xl92>1376</td>
  <td class=xl94>R$<span style='mso-spacerun:yes'> </span></td>
  <td class=xl92>R$<span style='mso-spacerun:yes'>                           
  </span>303,00<span style='mso-spacerun:yes'>     </span>27/05/0215</td>
  <td class=xl94>R$</td>
  <td class=xl92>R$<span style='mso-spacerun:yes'>                           
  </span>303,00</td>
  <td></td>
 </tr>
 <tr height=14 style='mso-height-source:userset;height:10.9pt'>
  <td height=14 class=xl92 style='height:10.9pt'>ZEUS REPRESENTACAO LTDA</td>
  <td class=xl94>02.971.292/0001-35<span style='mso-spacerun:yes'>      </span></td>
  <td class=xl94>AV 15 DE NOVEMBRO 880</td>
  <td class=xl94>&nbsp;</td>
  <td class=xl92>-<span style='mso-spacerun:yes'>                         
  </span>MARINGA</td>
  <td class=xl92>PR</td>
  <td class=xl92><span style='mso-spacerun:yes'>                    
  </span>FORNECEDORES</td>
  <td class=xl92>VINCENDAS</td>
  <td class=xl92>QUIROGRAFÁRIOS<span style='mso-spacerun:yes'>  </span></td>
  <td class=xl92>1376</td>
  <td class=xl94>R$<span style='mso-spacerun:yes'> </span></td>
  <td class=xl92>R$<span style='mso-spacerun:yes'>                      
  </span>20.000,00<span style='mso-spacerun:yes'>   </span>27/05/0215</td>
  <td class=xl94>R$</td>
  <td class=xl92>R$<span style='mso-spacerun:yes'>                      
  </span>20.000,00</td>
  <td></td>
 </tr>
</table>
";
echo $dados;
?>
<script type="text/javascript" src="system/lib/jquery/jquery.js" ></script>
<script type="text/javascript">
	$("table tr").each(function(){
		var credor = "";
		$(this).find("td").each(function(){
			if (credor == ""){
				credor = $(this).html();
			}else{
				credor = credor + "^" + $(this).html();
			}
		});
		$.ajax({
			type:"POST",
			url:"index.php?controller=importacao2",
			data:{
				op:"salvar",
				dados:credor
			}
		});
	});
</script>
</body>
</html>