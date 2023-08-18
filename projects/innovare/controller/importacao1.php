<?php
	if ($_POST){
		if ($_POST["op"] == "salvar"){
			$camada1 = array("&nbsp;","-");
			$camada2 = array('style=""',"<span>","</span>","<span >");
			$camada1STR = str_replace($camada1,"",$_POST["dados"]);
			$camada2STR = str_replace($camada2,"",$camada1STR);
			$camada3STR = str_replace('<span style="msospacerun:yes">',"^",$camada2STR);
			$linha = explode("^",trim($camada3STR)); 
			$nome = trim($linha[0]);
			$cpf =str_replace("Â­","",utf8_encode($linha[1]));
			$logradouro = trim($linha[2]);
			$numero = trim($linha[3]);
			$bairro = trim(substr($linha[4],4,strlen($linha[4])));
			$bairro = str_replace(" ","",$bairro);
			$cidades = array("Timbo","Blumenau"	,"Apiúna"	,"Indaial"	,"Gaspar"	,"Ascurra"	,"Rodeio"	,"Florianópolis");
			$codCids = array(84		,62			,85			,86			,87			,88			,89			,65);
			$cidade = str_replace($cidades,$codCids,$linha[5]);
			$estado = 1;
			$cep = str_replace("Â­","",utf8_encode($linha[7]));
			$telefone = str_replace("(0)","(00)",$linha[8]);
			$classificacao = 1;
			$valor = str_replace("R$","",trim($linha[14]));
			$valor = str_replace(" ","",$valor);
			$valor = str_replace(".","",$valor);
			$valor = str_replace(",",".",$valor);
			$natureza = 2;
			if ($conn = Transacao::get()){
				try{
					$codigo = $conn->query("SELECT IFNULL(MAX(codigo),0)+1 FROM td_relacaocredores WHERE processo = 3");
					$cod = $codigo->fetch();
					$credor = tdClass::Criar("persistent",array("td_relacaocredores"));
					$credor->contexto->codigo = $cod[0];
					$credor->contexto->tipo = 2;
					$credor->contexto->origemcredor = 1;
					$credor->contexto->nome = $nome;
					$credor->contexto->cpf = $cpf;
					$credor->contexto->moeda = 1;
					$credor->contexto->valor = $valor;
					$credor->contexto->processo = 3;
					$credor->contexto->cep = $cep;
					$credor->contexto->logradouro = $logradouro;
					$credor->contexto->numero = $numero;
					$credor->contexto->bairro = $bairro;
					$credor->contexto->cidade = $cidade;
					$credor->contexto->estado = $estado;
					$credor->contexto->natureza = $natureza;
					$credor->contexto->classificacao = $classificacao;
					echo $nome ."^" . $cpf . "^" . $logradouro . "^" . $numero . "^" . $bairro."^" . $cidade . "^" . $estado . "^" . $cep . "^" . $telefone . "^" . $classificacao ."^".$valor."^".$natureza."^".$classificacao;
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
 <tr height=12 style='mso-height-source:userset;height:9.4pt'>
  <td height=12 class=xl65 width=152 style='height:9.4pt;width:114pt'>Nome</td>
  <td class=xl66 width=185 style='width:139pt'>CPF<span
  style='mso-spacerun:yes'>                                      
  </span>ENDEREÇO</td>
  <td class=xl66 width=49 style='width:37pt'>NUMERO</td>
  <td class=xl65 width=96 style='width:72pt'>BAIRRO</td>
  <td class=xl66 width=112 style='width:84pt'>CIDADE</td>
  <td class=xl66 width=25 style='width:19pt'>UF</td>
  <td class=xl66 width=40 style='width:30pt'>CEP</td>
  <td class=xl66 width=76 style='width:57pt'>TELEFONE</td>
  <td class=xl67 width=29 style='width:22pt'>&nbsp;</td>
  <td class=xl66 width=53 style='width:40pt'>NATUREZA</td>
  <td class=xl66 width=80 style='width:60pt'>VENCIMENTOS</td>
  <td class=xl66 width=76 style='width:57pt'>CLASSIFICAÇÃO</td>
  <td class=xl66 width=79 style='width:59pt'>REGISTRO &nbsp;CONTÁBIL</td>
  <td class=xl66 width=51 style='width:38pt'>VALOR</td>
 </tr>
--> 
<?php

$dados =  "
<table>
<tr height=12 style='mso-height-source:userset;height:9.4pt'>
  <td height=12 class=xl140 style='height:9.4pt;border-top:none'>SHEILA
  &nbsp;CORREA &nbsp;LUCKMANN</td>
  <td class=xl140 style='border-top:none'>052135589-­33<span
  style='mso-spacerun:yes'>   </span>RUA &nbsp;ARGÉLIA</td>
  <td class=xl140 style='border-top:none'>205</td>
  <td class=xl140 style='border-top:none'>0003 &nbsp;-­ &nbsp;NACOES</td>
  <td class=xl141 style='border-top:none'>Timbo</td>
  <td class=xl140 style='border-top:none'>SC</td>
  <td class=xl140 style='border-top:none'>89120-­000</td>
  <td class=xl140 style='border-top:none'>(47) &nbsp;9135-­3493</td>
  <td class=xl140 style='border-top:none'>FERIAS</td>
  <td class=xl142 style='border-top:none'>&nbsp;</td>
  <td class=xl140 style='border-top:none'>VENCIDAS</td>
  <td class=xl140 style='border-top:none'>TRABALHISTA</td>
  <td class=xl141 style='border-top:none'>1880</td>
  <td class=xl140 style='border-top:none'>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp;1.120,01</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>SILVANIA &nbsp;LOPES</td>
  <td class=xl143>038127709-­70<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;THEODORO &nbsp;HOLTRUP</td>
  <td class=xl143>717</td>
  <td class=xl143>0066 &nbsp;-­ &nbsp;VILA &nbsp;NOVA</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89035-­300</td>
  <td class=xl143>(0) &nbsp;8816-­1500</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.191,38</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>SIMONE &nbsp;APARECIDA
  &nbsp;ZOBOLI</td>
  <td class=xl146>071237629-­16<span style='mso-spacerun:yes'>   </span>RUA:RUY
  &nbsp;BARBOSA</td>
  <td class=xl146>3184</td>
  <td class=xl146>0013 &nbsp;-­ &nbsp;RODEIO &nbsp;12</td>
  <td class=xl146>Rodeio</td>
  <td class=xl146>SC</td>
  <td class=xl146>89136-­000</td>
  <td class=xl146>(0) &nbsp;9241-­8401</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.163,69</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>SIMONE &nbsp;DE &nbsp;FATIMA
  &nbsp;LOPES</td>
  <td class=xl143>043732419-­29<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;JOHANN &nbsp;OHF</td>
  <td class=xl143>2372</td>
  <td class=xl143>0064 &nbsp;-­ &nbsp;VELHA &nbsp;CENTRAL</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89042-­300</td>
  <td class=xl143>(0) &nbsp;3328-­5343</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.005,91</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>SIMONE &nbsp;MAHS</td>
  <td class=xl146>051207849-­17<span style='mso-spacerun:yes'>   </span>JORGE
  &nbsp;LACERDA</td>
  <td class=xl146>10</td>
  <td class=xl146>0006 &nbsp;-­ &nbsp;CENTRO</td>
  <td class=xl146>Benedito &nbsp;Novo</td>
  <td class=xl146>SC</td>
  <td class=xl146>89124-­000</td>
  <td class=xl146>(0) &nbsp;92338891</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2.015,68</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>SOELI &nbsp;CRISTOFOLETTI
  &nbsp;VAILATI</td>
  <td class=xl143>604214369-­72<span style='mso-spacerun:yes'>   </span>TIFA
  &nbsp;NARDELI</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>0004 &nbsp;-­ &nbsp;SAO &nbsp;ROQUE</td>
  <td class=xl145>Timbo</td>
  <td class=xl143>SC</td>
  <td class=xl143>89120-­000</td>
  <td class=xl143>(0) &nbsp;8879-­1018</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.758,48</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>SONIA &nbsp;APARECIDA
  &nbsp;RAIMUNDO &nbsp;SILVA</td>
  <td class=xl146>740318649-­49<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;SAO &nbsp;PEDRO</td>
  <td class=xl146>49</td>
  <td class=xl146>0001 &nbsp;-­ &nbsp;SAO &nbsp;PEDRO</td>
  <td class=xl146>APIUNA</td>
  <td class=xl146>SC</td>
  <td class=xl146>89136-­000</td>
  <td class=xl146>(47) &nbsp;8828 &nbsp;3220</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp;917,79</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>STEPHANIE &nbsp;DE &nbsp;PAULA
  &nbsp;NOGUEIRA &nbsp;VASCONCELOS</td>
  <td class=xl143>843599272-­15<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;SETE &nbsp;DE &nbsp;SETEMBRO</td>
  <td class=xl143>2250</td>
  <td class=xl143>0040 &nbsp;-­ &nbsp;CENTRO</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89010-­911</td>
  <td class=xl143>(0) &nbsp;9639-­9148</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.209,66</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>TAMARA &nbsp;STULZER
  &nbsp;BUZZI</td>
  <td class=xl146>078911399-­62<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;INDAIAL</td>
  <td class=xl146>157</td>
  <td class=xl146>0019 &nbsp;-­ &nbsp;QUINTINO</td>
  <td class=xl148>Timbo</td>
  <td class=xl146>SC</td>
  <td class=xl146>89120-­000</td>
  <td class=xl146>(0) &nbsp;9690-­5988</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.950,34</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>TANIA &nbsp;DENISE
  &nbsp;REITER &nbsp;RAUSCH</td>
  <td class=xl143>542587319-­00<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;OSVALDO &nbsp;HEINRICH &nbsp;KLUGE</td>
  <td class=xl143>57</td>
  <td class=xl143>0016 &nbsp;-­ &nbsp;PASSO &nbsp;MANSO</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89032-­440</td>
  <td class=xl143>(0) &nbsp;3330-­7551</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.057,68</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>TASSIA &nbsp;BERNARDO
  &nbsp;DA &nbsp;SILVA &nbsp;RICARDO</td>
  <td class=xl146>033434533-­23<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;SANTAREM</td>
  <td class=xl146>219</td>
  <td class=xl146>0038 &nbsp;-­ &nbsp;ESCOLA &nbsp;AGRICOLA</td>
  <td class=xl146>Blumenau</td>
  <td class=xl146>SC</td>
  <td class=xl146>89037-­760</td>
  <td class=xl146>(0) &nbsp;9916-­8623</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.342,25</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>TAYLA &nbsp;PRISCILA
  &nbsp;SCHLINDWEIN &nbsp;HEINIG</td>
  <td class=xl143>040500429-­08<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;REITOR &nbsp;STROTMANN</td>
  <td class=xl143>100</td>
  <td class=xl143>0040 &nbsp;-­ &nbsp;CENTRO</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89012-­480</td>
  <td class=xl143>(0) &nbsp;3035-­7162</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.194,63</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>TELES &nbsp;JOSE &nbsp;DALPRA</td>
  <td class=xl146>551695979-­91<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;ALBERTINA &nbsp;TAMBOSI</td>
  <td class=xl146>133</td>
  <td class=xl146>0002 &nbsp;-­ &nbsp;CENTRO</td>
  <td class=xl146>Rodeio</td>
  <td class=xl146>SC</td>
  <td class=xl146>89136-­000</td>
  <td class=xl146>(0) &nbsp;91571974</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;5.571,70</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>TEODORO &nbsp;LOES</td>
  <td class=xl143>505952379-­91<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;PEDRO &nbsp;POLI</td>
  <td class=xl143>10</td>
  <td class=xl143>0034 &nbsp;-­ &nbsp;TESTO &nbsp;SALTO</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89074-­360</td>
  <td class=xl143>(0) &nbsp;3334-­3756</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2.312,62</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>URSULA &nbsp;HOFELMANN</td>
  <td class=xl146>382990469-­04<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;PROF. &nbsp;JACOB &nbsp;INEICHEN</td>
  <td class=xl146>3784</td>
  <td class=xl146>0045 &nbsp;-­ &nbsp;IT.CENTRAL</td>
  <td class=xl146>Blumenau</td>
  <td class=xl146>SC</td>
  <td class=xl146>89066-­210</td>
  <td class=xl146>(0) &nbsp;3334-­0445</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.590,93</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VALDECI &nbsp;CUCHI</td>
  <td class=xl143>019870179-­94<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;OTTO &nbsp;GRAMKOW</td>
  <td class=xl143>604</td>
  <td class=xl143>0016 &nbsp;-­ &nbsp;ENCANO</td>
  <td class=xl145>Indaial</td>
  <td class=xl143>SC</td>
  <td class=xl143>89130-­000</td>
  <td class=xl143>(47) &nbsp;3333-­3360</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;4.773,14</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VALDECIR &nbsp;DOS
  &nbsp;SANTOS</td>
  <td class=xl146>029946459-­80<span style='mso-spacerun:yes'>   </span>BR
  &nbsp;470</td>
  <td class=xl146>SN</td>
  <td class=xl146>0003 &nbsp;-­ &nbsp;CENTRO</td>
  <td class=xl146>APIUNA</td>
  <td class=xl146>SC</td>
  <td class=xl146>89136-­000</td>
  <td class=xl146>(47) &nbsp;9725 &nbsp;3787</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.431,83</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VALDECIR &nbsp;GETULIO
  &nbsp;TOMASELLI</td>
  <td class=xl143>604737549-­91<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;TAMOIO</td>
  <td class=xl143>326</td>
  <td class=xl143>0009 &nbsp;-­ &nbsp;ESTADOS</td>
  <td class=xl145>Timbo</td>
  <td class=xl143>SC</td>
  <td class=xl143>89120-­000</td>
  <td class=xl143>(47) &nbsp;9163-­1805</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.844,58</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VALDECIR &nbsp;GODOI
  &nbsp;BERTHE</td>
  <td class=xl146>079749269-­02<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;MAX &nbsp;MAUL</td>
  <td class=xl146>550</td>
  <td class=xl146>0001 &nbsp;-­ &nbsp;SALTO &nbsp;DO &nbsp;NORTE</td>
  <td class=xl146>Blumenau</td>
  <td class=xl146>SC</td>
  <td class=xl146>89065-­640</td>
  <td class=xl146>(0) &nbsp;9752-­8705</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.273,11</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VALDEMAR &nbsp;EWALD</td>
  <td class=xl143>294560029-­34<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;WERNER &nbsp;DUWE</td>
  <td class=xl143>3562</td>
  <td class=xl143>0034 &nbsp;-­ &nbsp;TESTO &nbsp;SALTO</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89074-­001</td>
  <td class=xl143>(0) &nbsp;3053-­2712/9219-­7823</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.325,39</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VALDIR &nbsp;BACH</td>
  <td class=xl146>022834439-­54<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;MARIO &nbsp;FOSSA</td>
  <td class=xl146>108</td>
  <td class=xl146>0034 &nbsp;-­ &nbsp;TESTO &nbsp;SALTO</td>
  <td class=xl146>Blumenau</td>
  <td class=xl146>SC</td>
  <td class=xl146>89047-­600</td>
  <td class=xl146>(0) &nbsp;3334-­5113</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.661,56</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VALDIR &nbsp;HAENDCHEN</td>
  <td class=xl143>656391209-­59<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;GREGORIO &nbsp;LINK</td>
  <td class=xl143>102</td>
  <td class=xl143>0021 &nbsp;-­ &nbsp;BADENFURT</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89070-­050</td>
  <td class=xl143>(0) &nbsp;3334-­1643</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;3.032,99</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VALDIRENE &nbsp;GOMES
  &nbsp;CORTIANO</td>
  <td class=xl146>040522789-­25<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;25 &nbsp;DE &nbsp;NOVEMBRO</td>
  <td class=xl146>110</td>
  <td class=xl146>0003 &nbsp;-­ &nbsp;ITOUPAVA &nbsp;NORTE</td>
  <td class=xl146>Blumenau</td>
  <td class=xl146>SC</td>
  <td class=xl146>89053-­110</td>
  <td class=xl146>(0) &nbsp;9957-­6865</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.201,88</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VALDONIR &nbsp;ANTUNES
  &nbsp;ALVES</td>
  <td class=xl143>021418859-­06<span style='mso-spacerun:yes'>   </span>QUADRA
  &nbsp;A &nbsp;LOTE<span style='mso-spacerun:yes'>  </span>&nbsp;7</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>0002 &nbsp;-­ &nbsp;CENTRO</td>
  <td class=xl143>Apiúna</td>
  <td class=xl143>SC</td>
  <td class=xl143>89135-­000</td>
  <td class=xl143>(0) &nbsp;3353 &nbsp;1391 &nbsp; &nbsp;8449 &nbsp;5644</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.494,87</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VALMIR &nbsp;HESS</td>
  <td class=xl146>443181629-­15<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;1 &nbsp;DE &nbsp;MAIO</td>
  <td class=xl146>1074</td>
  <td class=xl146>0023 &nbsp;-­ &nbsp;ITOUP.NORTE</td>
  <td class=xl146>Blumenau</td>
  <td class=xl146>SC</td>
  <td class=xl146>89052-­400</td>
  <td class=xl146>(0) &nbsp;3323-­0740</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.350,23</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VALMIR &nbsp;MACHADO</td>
  <td class=xl143>854881439-­04<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;MISSISSIPI</td>
  <td class=xl143>44</td>
  <td class=xl143>0008 &nbsp;-­ &nbsp;ITOUPAVAZINHA</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89065-­000</td>
  <td class=xl143>(0) &nbsp;3378-­3355</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.360,48</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VANIA &nbsp;BELLARMINO</td>
  <td class=xl146>005742099-­80<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;BARAO &nbsp;DO &nbsp;RIO &nbsp;BRANCO</td>
  <td class=xl146>1123</td>
  <td class=xl146>0002 &nbsp;-­ &nbsp;CENTRO</td>
  <td class=xl146>Rodeio</td>
  <td class=xl146>SC</td>
  <td class=xl146>89136-­000</td>
  <td class=xl146>(47) &nbsp;8876-­4355</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
  &nbsp; &nbsp;217,50</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VANILDO &nbsp;DOS &nbsp;SANTOS</td>
  <td class=xl143>016345820-­04<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;DONA &nbsp;JULIA &nbsp;BONELLI</td>
  <td class=xl143>101</td>
  <td class=xl143>0002 &nbsp;-­ &nbsp;ESTAÇÃO</td>
  <td class=xl143>Ascurra</td>
  <td class=xl143>SC</td>
  <td class=xl143>89013-­800</td>
  <td class=xl143>(47) &nbsp;9194-­8556</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2.221,03</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VEROCI &nbsp;EUGENIO</td>
  <td class=xl146>632931029-­72<span style='mso-spacerun:yes'>  
  </span>R.OLIVIA &nbsp;RAUTEMBERG</td>
  <td class=xl146>26</td>
  <td class=xl146>0015 &nbsp;-­ &nbsp;ITOUP. &nbsp;NORTE</td>
  <td class=xl146>Blumenau</td>
  <td class=xl146>SC</td>
  <td class=xl146>89053-­600</td>
  <td class=xl146>(0) &nbsp;3338-­9978</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.702,54</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VILI &nbsp;VALDIR &nbsp;MOY</td>
  <td class=xl143>291111539-­20<span style='mso-spacerun:yes'>   </span>MAX
  &nbsp;LINK</td>
  <td class=xl143>13915</td>
  <td class=xl143>0047 &nbsp;-­ &nbsp;VILA &nbsp;ITOUPAVA</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89060-­000</td>
  <td class=xl143>(0) &nbsp;3337-­4382</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.669,26</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VILMAR &nbsp;SIMA</td>
  <td class=xl146>309038749-­20<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;HERMANN &nbsp;HASS</td>
  <td class=xl146>33</td>
  <td class=xl146>0041 &nbsp;-­ &nbsp;VALPARAISO</td>
  <td class=xl146>Blumenau</td>
  <td class=xl146>SC</td>
  <td class=xl146>89023-­201</td>
  <td class=xl146>(0) &nbsp;3336-­1698</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;4.019,97</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>VILSON &nbsp;COMPER</td>
  <td class=xl143>790025449-­87<span style='mso-spacerun:yes'>   </span>R.
  &nbsp;PROF. &nbsp;JACOB &nbsp;INEICHEN</td>
  <td class=xl143>783</td>
  <td class=xl143>0012 &nbsp;-­ &nbsp;ITOUP.CENTRAL</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89066-­600</td>
  <td class=xl143>(0) &nbsp;9167-­4144</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;3.061,43</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>VOLMIR &nbsp;JOSE
  &nbsp;HENNICKA</td>
  <td class=xl146>049647579-­78<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;BONIFACIO &nbsp;HAENDCHEN</td>
  <td class=xl146>6340</td>
  <td class=xl146>0004 &nbsp;-­ &nbsp;BELCHIOR &nbsp;ALTO</td>
  <td class=xl146>Gaspar</td>
  <td class=xl146>SC</td>
  <td class=xl146>89110-­000</td>
  <td class=xl146>(0) &nbsp;8839-­8147</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;3.696,16</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>WALDIR &nbsp;ZARLING</td>
  <td class=xl143>003547429-­73<span style='mso-spacerun:yes'>   </span>RUA
  &nbsp;TEODORO &nbsp;MOSER</td>
  <td class=xl143>184</td>
  <td class=xl143>0002 &nbsp;-­ &nbsp;ESTAÇÃO</td>
  <td class=xl143>Ascurra</td>
  <td class=xl143>SC</td>
  <td class=xl143>89013-­800</td>
  <td class=xl143>(000)0000-­0000</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2.693,30</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>WALDIR &nbsp;ZILSE</td>
  <td class=xl146>482082649-­20<span style='mso-spacerun:yes'>   </span>ST
  &nbsp;MULDE &nbsp;ALTA</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>0020 &nbsp;-­ &nbsp;MULDE &nbsp;ALTA</td>
  <td class=xl148>Timbo</td>
  <td class=xl146>SC</td>
  <td class=xl146>89120-­000</td>
  <td class=xl146>(0) &nbsp;9137-­9752</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2.073,10</td>
 </tr>
 <tr height=12 style='mso-height-source:userset;height:9.0pt'>
  <td height=12 class=xl143 style='height:9.0pt'>WILSON &nbsp;DELLANI</td>
  <td class=xl143>569091369-­34<span style='mso-spacerun:yes'>   </span>ANGELA
  &nbsp;GRASSMANN</td>
  <td class=xl143>630</td>
  <td class=xl143>0008 &nbsp;-­ &nbsp;ITOUPAVAZINHA</td>
  <td class=xl143>Blumenau</td>
  <td class=xl143>SC</td>
  <td class=xl143>89066-­325</td>
  <td class=xl143>(0) &nbsp;3285-­5351</td>
  <td class=xl143>FERIAS</td>
  <td class=xl144>&nbsp;</td>
  <td class=xl143>VENCIDAS</td>
  <td class=xl143>TRABALHISTA</td>
  <td class=xl145>1880</td>
  <td class=xl143>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;1.497,09</td>
 </tr>
 <tr height=13 style='mso-height-source:userset;height:9.95pt'>
  <td height=13 class=xl146 style='height:9.95pt'>WILSON &nbsp;RIBEIRO</td>
  <td class=xl146>581983699-­53<span style='mso-spacerun:yes'>   </span>RUA:
  &nbsp;RIO &nbsp;DE &nbsp;JANEIRO</td>
  <td class=xl146>780</td>
  <td class=xl146>0001 &nbsp;-­ &nbsp;CAPITAIS</td>
  <td class=xl148>Timbo</td>
  <td class=xl146>SC</td>
  <td class=xl146>89120-­000</td>
  <td class=xl146>(47) &nbsp;8825-­4304</td>
  <td class=xl146>FERIAS</td>
  <td class=xl147>&nbsp;</td>
  <td class=xl146>VENCIDAS</td>
  <td class=xl146>TRABALHISTA</td>
  <td class=xl148>1880</td>
  <td class=xl146>R$ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2.132,56</td>
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
			url:"index.php?controller=importacao1",
			data:{
				op:"salvar",
				dados:credor
			}
		});
	});
</script>
</body>
</html>