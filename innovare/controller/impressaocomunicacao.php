<?php
set_time_limit(7200);
date_default_timezone_set('America/Sao_Paulo');
$gerarpdf = isset($_GET["gerarpdf"]) ? true : false;
function isFeriado($data){
	$datacoleta_array = explode("-",$data);					
	$sqlFeriado = tdClass::Criar("sqlcriterio");
	$sqlFeriado->addFiltro("diames","=",$datacoleta_array[2].$datacoleta_array[1]);
	
	if (tdClass::Criar("repositorio",array("td_feriado"))->quantia($sqlFeriado) > 0){
		return true;				
	}else{
		return false;
	}		
}
function retornaDataColeta(){
	
	$datacoleta = date("Y-m-d");
	if (date("H") <= 12){
		 $datacoleta = date('Y-m-d', strtotime('+2 days', strtotime($datacoleta)));
	}else{
		$datacoleta = date('Y-m-d', strtotime('+3 days', strtotime($datacoleta)));
	}
	
	do{
		$status = 0;						
		if (date("w",strtotime($datacoleta)) == 6){
			$status = 1;
		}else if(date("w",strtotime($datacoleta)) == 0){
			$status = 1;
		}else if (isFeriado($datacoleta)){
			$status = 1;
		}

		if (date("H") <= 12){

			 $datacoleta = date('Y-m-d', strtotime('+2 days', strtotime($datacoleta)));
		}else{
			$datacoleta = date('Y-m-d', strtotime('+3 days', strtotime($datacoleta)));
		}
	}while($status == 0);
	$retorno_data = explode("-",$datacoleta);
	return $retorno_data[2] . $retorno_data[1] . $retorno_data[0];
}
	$configuracoesetiqueta		= tdClass::Criar("persistent",array("td_comunicacaocredoresconfiguracoes",1));
	$NumeroEtiqueInicio			= substr(($configuracoesetiqueta=="")?1:$configuracoesetiqueta->contexto->ultimonumeroetiqueta,0,8);	

	set_time_limit(36000);	
	@session_start("impressaocomunicacao");
	$falencia = $recuperacao = false;
	
	if (isset($_GET["farein"])){
		$farein_array = explode("^",$_GET["farein"]);
		$processo 		= $farein_array[1];
		$farein 		= $farein_array[0];
		$tipo_farein 	= $farein_array[2];
	
		$conn = Transacao::get();
		$result = $conn->query("SELECT * FROM td_processo WHERE id ={$processo}");		
		$processo = "";
		foreach ($result as $key => $value){
			$processo = $value["id"];
			$numero = $value["numeroprocesso"];
			$dataenvio = utf8_decode(dataExtenso(date("d/m/Y")));
			$juizo = (tdClass::Criar("persistent",array("td_juizo",$value["td_juizo"]))->contexto->descricao);
			$comarca = tdClass::Criar("persistent",array("td_comarca",$value["td_comarca"]))->contexto->descricao;

			$resul_falida = $conn->query("SELECT * FROM td_falencia WHERE td_processo = '{$processo}' AND id = {$farein}");
			$_SESSION["tipoprocesso"] = $value["tipoprocesso"];
			if ($tipo_farein=="FA"){							
				foreach ($resul_falida as $falida){					
					$falencia = true;						
					$razaosocial = strtoupper($falida["razaosocial"]);
					$cnpj_cpf = $falida["cnpj"];
					$logradouro = $falida["logradouro"];
					$numero_rua = $falida["numero"];
					$bairro = $falida["bairro"];
					$cidade = tdClass::Criar("persistent",array("td_cidade",$falida["td_cidade"]))->contexto->nome;
					$estado = tdClass::Criar("persistent",array("td_estado",$falida["td_estado"]))->contexto->nome;
					$estado_sigla = tdClass::Criar("persistent",array("td_estado",$falida["td_estado"]))->contexto->sigla;
					$cep = $falida["cep"];
					$data = dataExtenso($falida["datasentenca"]);
				}
			}
			$resul_recuperanda = $conn->query("SELECT * FROM td_recuperanda WHERE td_processo = '{$processo}' AND id = {$farein}");
			if ($tipo_farein=="RE"){
				foreach ($resul_recuperanda as $recuperanda){
					$recuperacao = true;
					$razaosocial = strtoupper($recuperanda["razaosocial"]);
					$cnpj_cpf = $recuperanda["cnpj"];
					$logradouro = $recuperanda["logradouro"];
					$numero_rua = $recuperanda["numero"];
					$bairro = $recuperanda["bairro"];
					$cidade = tdClass::Criar("persistent",array("td_cidade",$recuperanda["cidade"]))->contexto->nome;
					$estado = tdClass::Criar("persistent",array("td_estado",$recuperanda["estado"]))->contexto->nome;
					$estado_sigla = tdClass::Criar("persistent",array("td_estado",$recuperanda["estado"]))->contexto->sigla;
					$cep = $recuperanda["cep"];
					$data = dataExtenso($recuperanda["datapedido"]);			
				}
			}
		}
		if ($processo==""){
			echo 'Nenhum registro encontrado';
			exit;
		}	
	}else{
		echo 'Nenhum registro encontrado';
		exit;
	}

?>
<html>
	<head>
		<title>Innovare</title>
		<style rel="stylesheet" type="text/css">
			@page {
				margin:5px;				
			}	
			@media print{
				body {-webkit-print-color-adjust: exact;							
				}
			}
			#margem{						
				padding:0.5cm;
			}
			body{
				padding:0px;
				margin:0px;	
			}
			.borda{
				border:1px solid #000;
			}			
			.quadro{
				border-right:1px solid #000;
			}
			.texto{
				font-size:10px;
				font-family:Tahoma;
			}
			.digito_banco{
				font-size:24px;
				font-weight:bold;		
			}
			span{
				font-size:8px;
				margin:3px;
			}
			@media print {
				.quebra_pagina {page-break-after: always;}
			}
			.pagina{
				float:left;
				clear:left;
				border:1px solid #FFF;
				font-size:13px;
				padding:10px;
				margin-top:50px;
				font-family:Arial;
			}
			table tr td {
				font-size:10px;
				font-family:Tahoma;
			}
			label{
				margin:3px;
			}
			div[class="pagina"]{
				margin-top:30px;
				text-align:justify;
			}
			@media screen {
				.pagina{
					width:21cm;
					height:29cm;
				}
			}
			.logo{
				width:250px;
			}
			.b-ar{
				float:right;
				width:100px;
				margin:5px;				
			}
			.panel-body{
				word-wrap: break-word;
			}
		</style>
		<?php
			$bootstrap = tdClass::Criar("link");
			$bootstrap->href = PATH_LIB . "bootstrap/3.3.1/css/bootstrap.css";
			$bootstrap->rel = "stylesheet";
			$bootstrap->mostrar();

			$jquery = tdClass::Criar("script");
			$jquery->type = "text/javascript";
			$jquery->src = PATH_LIB . "jquery/jquery.js";
			$jquery->mostrar();
			
			$bootstrap_js = tdClass::Criar("script");
			$bootstrap_js->type = "text/javascript";
			$bootstrap_js->src = PATH_LIB . "bootstrap/3.3.1/js/bootstrap.js";
			$bootstrap_js->mostrar();
		?>	
	<body>
		<?php
			$where_codigo = "";
			if (isset($_GET["codigo"])){
				if ($_GET["codigo"] != ""){
					$codigo = $_GET["codigo"];
					if (strpos($codigo,":") > 0){
						$c = explode(":",$codigo);
						$inicio = $c[0];
						$fim = $c[1];
						$where_codigo = "AND codigo BETWEEN $inicio and $fim ";
					}else if(strpos($codigo,"-") > 0){
						$codigos = explode("-",$codigo);
						$where_codigo = "AND codigo in (";
						$i = "";
						foreach($codigos as $c){
							if ($c != "")
								$i .= ($i=="")?$c:",".$c;
						}
						$where_codigo .= $i . ") ";
					}else{
						$where_codigo = "AND (codigo = " . $_GET["codigo"] . ")";
					}
				}				
			}
			
			$where_classificacao = "";
			if (isset($_GET["classificacao"])){
				if ($_GET["classificacao"]!=""){
					$where_classificacao = "and td_classificacao not in (".$_GET["classificacao"].")";
				}
			}
			$where_origem = " td_origemcredor = 10 ";
			
				if ($gerarpdf){
					$where_origem = " td_origemcredor = 11";
					$where_codigo = " AND id = " . tdClass::Read("codigo");
				}
			
			$sql = "SELECT * FROM td_relacaocredores WHERE td_processo = {$processo} and farein = {$farein} {$where_codigo} {$where_classificacao} and ({$where_origem} OR td_origemcredor = NULL or td_origemcredor = '' or td_origemcredor is null) ORDER BY td_classificacao asc, nome ";
			$result = $conn->query($sql);
			if ($result->rowcount() == 0){
				echo '<b>Nenhum credor encontrados para esses par&acirc;metros.</b>';
				return false;
			} 
			foreach ($result as $key => $value){				
				switch($value["td_moeda"]){
					case 1: $simboloMoeda = "R$"; break;
					case 2: $simboloMoeda = "US$"; break;
					case 3: $simboloMoeda = "EUR€"; break;						
				}				
				$credor = (strtoupper($value["nome"]));
				$valor = moneyToFloat($value["valor"],false);
				$valor = $simboloMoeda . " " . number_format($value["valor"], 2, ',', '.');
				#$valor = $value["valor"];
				$classificacao = tdClass::Criar("persistent",array("td_classificacao",$value["td_classificacao"]))->contexto->descricao;

				$natureza = tdClass::Criar("persistent",array("td_natureza",$value["td_natureza"]))->contexto->descricao;				
				
				$login =  $value["codigocredor"];
				$senha =  geraSenha(4,true,true,false);
				
				# Se for Falida			
				if ($falencia){
		?>		
					<div class="pagina">
						<table border="0" width="100%">
							<tr>
								<td align="left">
									<img src="projects/2/imagens/comunicacao-topo.jpg" width="100%">
								<td>
							</tr>		
						</table>
						<br /><br /><br /><br />
						<p><strong><?=$credor?></strong></p>
						<p>Prezado(s) Senhor(es), </p>
						<p style="text-align:center">COMUNICA&Ccedil;&Atilde;O DE FAL&Ecirc;NCIA</p>
						<div>
							<dd>
								&emsp;&emsp;A teor do contido no art. 22, I, "a", da Lei n.&ordm; 11.101/2005, e na qualidade de administradora judicial nomeada nos autos da recupera&ccedil;&atilde;ao sob n.&ordm; <strong><?=$numero?></strong>, em tr&acirc;mite perante a <strong><?=$juizo?><strong></strong> de <?=$comarca?></strong>, vimos, por meio desta, comunicar a decreta&ccedil;&atilde;o da fal&ecirc;ncia da sociedade empres&aacute;ria <strong><?=$razaosocial?></strong>, inscrita no <strong>CNPJ n.&ordm; <?=$cnpj_cpf?></strong>, com sede sito &agrave; <strong>rua <?=$logradouro?>, n.&ordm; <?=$numero_rua?></strong>, bairro <strong><?=$bairro?></strong>, <strong><?=$cidade?></strong> - <strong><?=$estado?></strong>, CEP <strong><?=$cep?></strong>, por forca de senten&ccedil;a prolatada em <strong><?=$data?></strong>.
							</dd>	
						</div>
						<div>
							<dd>
								&emsp;&emsp;Informamos, outrossim, que consta, em rela&ccedil;&atilde;o nominal de credores elaborada pela sociedade empres&aacute;ria devedora e juntada aos autos falimentares mencionados em ep&iacute;grafe, um cr&eacute;dito, em seu favor, de <strong>natureza <?=$natureza?></strong>, na quantia de <strong><?=$valor?></strong>, classificado como credor <strong><?=$classificacao?></strong>.
							</dd>
						</div>
						<div>
							<dd>
								&emsp;&emsp;Na hip&oacute;tese de discord&acirc;ncia quanto ao valor ou classifica&ccedil;&atilde;o do cr&eacute;dito relacionado, o credor poder&aacute; apresentar pedido de diverg&ecirc;ncia diretamente &agrave; administradora judicial (por meio eletr&ocirc;nico), no prazo de 15 (quinze) dias, &aacute; luz do art. 7.&ordm;, &sect; 1.&ordm;, da Lei n.&ordm; 11.101/2005, por meio digital no link <a href="http://www.innovareadministradora.com.br/documentos" target="_blank">http://www.innovareadministradora.com.br</a> na aba "Documentos".
							</dd>	
						</div>
						<div>
							<dd>
								&emsp;&emsp;Colocamo-nos &agrave; disposi&ccedil;&atilde;o para maiores informa&ccedil;&otilde;es ou esclarecimentos que se fizerem necess&aacute;rios, no endere&ccedil;o sito &agrave; Travessa Germano, n.&ordm; 100, edif&iacute;cio PARTHERNON, sala n.&ordm; 407, bairro Centro, em Crici&uacute;ma - Santa Catarina, CEP 88.802-090, das 09:00 &agrave;s 18:00 horas, onde poder&atilde;o ter acesso aos livros e documentos da sociedade falida. Os interessados poder&atilde;o acessar o site <a href="http://www.innovareadministradora.com.br/" target="_blank">http://www.innovareadministradora.com.br</a> para obter demais informa&ccedil;&otilde;es.
							</dd>	
						</div>
						<p><dd>Atenciosamente, </dd></p>
						<br /><br /><br />
						<p style="text-align:center;">
							<img src="projects/2/imagens/comunicacao-assinatura1.jpg" />
							<img src="projects/2/imagens/comunicacao-assinatura2.jpg" />
							<br/>
							_______________________________________________________________________
							<br />
							INNOVARE - ADMINISTRADORA EM FAL&Ecirc;NCIA E RECUPERA&Ccedil;&Atilde;O SS - ME
							<br />
							Administradora Judicial
							<br />
							MAURICIO COLLE DE FIGUEIREDO 
							<br />
							FLAVIO CARLOS
						</p>
						<div style="width:100%;border-top:1px solid #000;text-align:center;">
							<img src="projects/2/imagens/comunicacao-rodape.jpg" width="100%" />
						</div>		
					</div>
		<?php
				}
				# Se for Recuperanda
				if ($recuperacao){
		?>
					<div class="pagina">
						<table border="0" width="100%">
							<tr>
								<td align="center">
									<img src="projects/2/imagens/comunicacao-topo.jpg" width="100%">
								<td>
							</tr>
						</table>
						<br /><br /><br /><br />
						<p><strong><?=$credor?></strong></p>
						<p>Prezado(s) Senhor(es), </p>
						<p style="text-align:center;font-weight:bold;">COMUNICA&Ccedil;&Atilde;O DE RECUPERA&Ccedil;&Atilde;O JUDICIAL</p>
						<div>
							<dd>
								 &emsp;&emsp;A teor do contido no art. 22, I, "a", da Lei n.&ordm; 11.101/2005, e na qualidade de administradora judicial nomeada nos autos  da recupera&ccedil;&atilde;o sob n.&ordm; <strong><?=$numero?></strong>, em tr&acirc;mite perante a <strong><?=$juizo?><strong></strong> de <?=$comarca?></strong>, vimos, por meio desta, comunicar o pedido de recupera&ccedil;&atilde;o judicial protocolado em <strong><?=$data?></strong> em favor da sociedade empres&aacute;rio <strong><?=$razaosocial?></strong>, inscrita no <strong>CNPJ n.&ordm; <?=$cnpj_cpf?></strong>, com sede sito &agrave; <strong>rua <?=$logradouro?>, n.&ordm; <?=$numero_rua?></strong>, bairro <strong><?=$bairro?></strong>, <strong><?=$cidade?></strong> - <strong><?=$estado?></strong>, CEP <strong><?=$cep?></strong>.
							</dd>	
						</div>
						<div>
							<dd>
								&emsp;&emsp;Informamos, outrossim, que consta, em rela&ccedil;&atilde;o nominal de credores elaborada pela sociedade empresária devedora e juntada aos autos falimentares mencionados em ep&iacute;grafe, um cr&eacute;dito, em seu favor, de <strong>natureza <?=$natureza?></strong>, na quantia de <strong> <?=$valor?></strong>, classificado como credor <strong><?=$classificacao?></strong>.
							</dd>
						</div>
						<div>
							<dd>
								&emsp;&emsp;Na hip&oacute;tese de discord&acirc;ncia quanto ao valor ou classifica&ccedil;&atilde;o do cr&eacute;dito relacionado, o credor poder&aacute; apresentar pedido de diverg&ecirc;ncia diretamente &agrave; administradora judicial (por meio eletr&ocirc;nico), no prazo de 15 (quinze) dias, &aacute; luz do art. 7.&ordm;, &sect; 1.&ordm;, da Lei n.&ordm; 11.101/2005, por meio digital no link <a href="http://www.innovareadministradora.com.br/documentos" target="_blank">http://www.innovareadministradora.com.br/</a> na aba "Documentos".
							</dd>	
						</div>
						<div>
							<dd>
								&emsp;&emsp;Colocamo-nos &agrave; disposi&ccedil;&atilde;o para maiores informa&ccedil;&otilde;es ou esclarecimentos que se fizerem necess&aacute;rios, no endere&ccedil;o sito &agrave; Travessa Germano, n.&ordm; 100, edif&iacute;cio PARTHERNON, sala n.&ordm; 407, bairro Centro, em Crici&uacute;ma - Santa Catarina, CEP 88.802-090, das 09:00 &agrave;s 18:00 horas. Os interessados poder&atilde;o acessar o site <a href="http://www.innovareadministradora.com.br/" target="_blank">http://www.innovareadministradora.com.br</a> para obter demais informa&ccedil;&otilde;es.
							</dd>	
						</div>
						<p><dd>Atenciosamente, </dd></p>
						<br /><br /><br />
						<p style="text-align:center;">
							<img src="projects/2/imagens/comunicacao-assinatura1.jpg" />
							<img src="projects/2/imagens/comunicacao-assinatura2.jpg" />
							<br/>
							_______________________________________________________________________
							<br />
							INNOVARE - ADMINISTRADORA EM FAL&Ecirc;NCIA E RECUPERA&Ccedil;&Atilde;O SS - ME
							<br />
							Administradora Judicial
							<br />
							MAURICIO COLLE DE FIGUEIREDO 
							<br />
							FLAVIO CARLOS
						</p>
						<div style="width:100%;border-top:1px solid #000;text-align:center;">
							<img src="projects/2/imagens/comunicacao-rodape.jpg" width="100%" />
						</div>		
					</div>	
		<?php
				
				}
				//echo "<p style='page-break-after: always;'></p>";
				if (!$gerarpdf){
					echo "<div style='page-break-before:always;'>&nbsp</div>";
				}
			}	
		?>
	</body>
</html>