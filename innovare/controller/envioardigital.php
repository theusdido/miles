<?php
set_time_limit(100000000);
if (isset($_GET["op"])){
	if ($_GET["op"] == "download"){
		$tipo = $_GET["tipo"];
		$arquivo = $_GET["arquivo"];
		header("Content-Type: ".$tipo); // informa o tipo do arquivo ao navegador
		header("Content-Length: ".filesize($arquivo)); // informa o tamanho do arquivo ao navegador
		header("Content-Disposition: attachment; filename=".basename($arquivo)); // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
		readfile($arquivo); // lê o arquivo	
		exit;
	}	
}
date_default_timezone_set('America/Sao_Paulo');
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
	
	// Gerar AR 
	if (isset($_POST["op"])){
		if ($_POST["op"] == "gerar_ar"){
			// Configuração da Comunicação Credores
			$configComunicacao = tdClass::Criar("persistent",array("td_comunicacaocredoresconfiguracoes",1));
				
			echo '<div class="btn-group btn-group-justified" role="group" aria-label="...">';

			$nomeArquivo = "SGDInnovare_" . completaString($_POST["farein"],3) . "_" . date("dmY") . ".txt";
			$filename = "arquivos/{$nomeArquivo}";
			$fp = fopen($filename,"w+");
			fwrite($fp,$_POST["header"] . utf8_decode($_POST["detalhe"]));
			fclose($fp);
			
			echo '
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" onclick=window.open("index.php?controller=envioardigital&op=download&tipo=txt&arquivo='.$filename.'","_blank");>SGD</button>
				</div>
			';
			
			$nomeArquivo = "InnovareSARA_" . completaString($_POST["farein"],3) . "_" . date("dmY") . ".txt";
			$filename = "arquivos/{$nomeArquivo}";
			$fp = fopen($filename,"w+");
			fwrite($fp,utf8_decode($_POST["sara"]));
			fclose($fp);

			echo '
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" onclick=window.open("index.php?controller=envioardigital&op=download&tipo=txt&arquivo='.$filename.'","_blank");>SARA</button>
				</div>
			';

			$nomeArquivo = "InnovareDADOS_" . completaString($_POST["farein"],3) . "_" . date("dmY") . ".txt";
			$filename = "arquivos/{$nomeArquivo}";
			$fp = fopen($filename,"w+");
			fwrite($fp,utf8_decode($_POST["dado"]));
			fclose($fp);
			
			echo '
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" onclick=window.open("index.php?controller=envioardigital&op=download&tipo=txt&arquivo='.$filename.'","_blank");>DADOS</button>
				</div>
			';		

			echo '</div>';

			$bootstrap = tdClass::Criar("link");
			$bootstrap->href = PATH_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
			$bootstrap->rel = 'stylesheet';
			$bootstrap->mostrar();
				
			// Gravação da Remessa
			$remessa = tdClass::Criar("persistent",array("td_comunicacaocredoresremessa"))->contexto;
			$remessa->projeto = Session::get()->projeto;
			$remessa->empresa = Session::get()->empresa;
			$remessa->td_usuario = Session::get()->userid;
			$remessa->datahoraenvio = date("Y-m-d H:i:s");
			$remessa->numeroinicialetiqueta = $NumeroEtiqueInicio;
			$remessa->numerofinaletiqueta = tdClass::Read("numeroetiquetainicio");
			$remessa->enviadocorreios = 1;
			$remessa->numeroremessa = $remessa->getUltimo() + 1;
			$remessa->td_processo = tdClass::Read("processo");
			$remessa->farein = tdClass::Read("farein");
			$remessa->armazenar();
			
			// Seta o próximo número da etiqueta
			$configuracoesetiqueta->contexto->ultimonumeroetiqueta = (double)tdClass::Read("numeroetiquetainicio") + 1;
			$configuracoesetiqueta->contexto->indiceremessa = $configuracoesetiqueta->contexto->indiceremessa + 1;
			$configuracoesetiqueta->contexto->armazenar();
			
			// Grava os credores da remessa
			
			$credores_remessa = explode(",",tdClass::Read("credores_remessa"));
			foreach($credores_remessa as $c){
				$cr = explode("^",$c);
				$comunicacaocredor = tdClass::Criar("persistent",array("td_comunicacaocredores"))->contexto;
				$comunicacaocredor->projeto = Session::get()->projeto;
				$comunicacaocredor->empresa = Session::get()->empresa;
				$comunicacaocredor->td_credor = $cr[0];
				$comunicacaocredor->login = $cr[1];//geraSenha(4,false,true,false);
				$comunicacaocredor->senha = $cr[2];//geraSenha(4,true,true,true);
				$comunicacaocredor->numero = completaString($remessa->numeroremessa,4) . "/" . completaString($comunicacaocredor->getUltimo() + 1,8);
				$comunicacaocredor->td_remessa = $remessa->numeroremessa;
				$comunicacaocredor->armazenar();
			}
			
			Transacao::fechar();
			exit;
		}
	}	
	

	set_time_limit(36000);	
	@session_start("envioardigital");
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
			$dataenvio = dataExtenso(date("d/m/Y"));
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
	
	// AR Digital - Header
	$ARHeaderTipoRegistro 		= 8;
	$ARHeaderCodigoCliente		= "5950"; // Faltando
	$ARHeaderFilter1 			= completaString("0",15);
	$ARHeaderNomeCliente 		= completaString("INNOVARE",40," ",STR_PAD_RIGHT);
	$ARHeaderDataGeracao 		= date("dmY");
	$ARHeaderQtdeRegistro		= 1; // Sera incrementado ao longo do script
	$ARHeaderFilter2 			= completaString("0",94);
	$ARHeaderNumArquivoRemessa	= completaString($configuracoesetiqueta->contexto->indiceremessa,5); // Buscar da base de dados
	$ARHeaderNumRemessa			= "0000001"; // Número Registro

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
			
			$ARDetalhe = tdClass::Criar("textarea");
			$ARDetalhe->id = "detalhe";
			$ARDetalhe->name = "detalhe";
			$ARDetalhe->style = "display:none";
			$ARDetalhe->add("
"); // AR Detalhe - Quebra de linha

			$ARSara = tdClass::Criar("textarea");
			$ARSara->id = "sara";
			$ARSara->name = "sara";
			$ARSara->style = "display:none";
			$ARSara->add("
"); // AR Detalhe - Quebra de linha

			$ARDado = tdClass::Criar("textarea");
			$ARDado->id = "dado";
			$ARDado->name = "dado";
			$ARDado->style = "display:none";
			$ARDado->add("
"); // AR Detalhe - Quebra de linha
			
			
			$credores_remessa = "";
			$ARDado->add('DATAATUAL|CREDOR|TIPO DE COMUNICACAO|NUMERO DO PROCESSO|JUIZO|COMARCA|DATA DE ABERTURA DO PROCESSO|RECUPERANDA/FALIDA/INSOLVENTE|CNPJ|LOGRADOURO|NUMERO|BAIRRO|CIDADE|ESTADO|CEP|NATUREZA|VALOR|CLASSIFICACAO|REGISTRADO|CEP_CREDOR|LOGRADOURO_CREDOR|NUMERO_CREDOR|COMPLEMENTO_CREDOR|BAIRRO_CREDOR|CIDADE_CREDOR|ESTADO_CREDOR|ETIQUETA');
			
			$where_classificacao = "";
			if (isset($_GET["classificacao"])){
				if ($_GET["classificacao"]!=""){
					$where_classificacao = "and td_classificacao not in (".$_GET["classificacao"].")";
				}
			}
			$sql = "SELECT * FROM td_relacaocredores WHERE td_processo = {$processo} and farein = {$farein} {$where_codigo} {$where_classificacao} and (td_origemcredor = 1 OR td_origemcredor = 10  OR td_origemcredor = NULL or td_origemcredor = '' or td_origemcredor is null) ORDER BY cep asc,td_classificacao asc, nome asc limit 49";
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

				$credor = strtoupper($value["nome"]);
				$valor = moneyToFloat($value["valor"],false);
				$valor = $simboloMoeda . " " . number_format($value["valor"], 2, ',', '.');
				#$valor = $value["valor"];
				$classificacao = tdClass::Criar("persistent",array("td_classificacao",$value["td_classificacao"]))->contexto->descricao;

				$natureza = tdClass::Criar("persistent",array("td_natureza",$value["td_natureza"]))->contexto->descricao;

				$login =  geraSenha(4,false,true,false);
				$senha =  geraSenha(4,true,true,false);

				$credor = tdClass::Criar("persistent",array("td_relacaocredores",$value["id"]))->contexto;
				
				$ARHeaderQtdeRegistro++;
				
				// Previsão de Postagem
				$ARDetalhe->add("\n"); // AR Detalhe - Quebra de linha
				$ARDetalhe->add(9); // AR Detalhe - Tipo de Registro

				$ARDetalhe->add("5950"); // AR Detalhe - Código do Cliente
				$ARDetalhe->add("IX"); // AR Detalhe - Identificador do Cliente
				$ARDetalhe->add("JC"); // AR Detalhe - Sigla do Objeto ( Tipo Postal )
				$ARDetalhe->add($NumeroEtiqueInicio.getDiv((string)$NumeroEtiqueInicio)); // AR Detalhe - Número do Objeto ( Faixa fornecida pela ETC )
				$ARDetalhe->add("BR"); // AR Detalhe - Pais
				$ARDetalhe->add("1101"); // AR Detalhe - Código da Operação (1101 - Envio / 1102 - Exclusão )
				$ARDetalhe->add("                "); // Campo Livre
				$ARDetalhe->add(substr(completaString(preg_replace('/\s/',' ',$credor->nome),40," ",STR_PAD_RIGHT),0,40)); // AR Detalhe - Nome do Destinatário
				$ARDetalhe->add(substr(preg_replace('/\s/',' ',completaString($credor->logradouro . ($credor->numero!=""?"," . $credor->numero:"") .  ($credor->complemento!=""?" - " . $credor->complemento:""),40," ",STR_PAD_RIGHT)),0,40)); // AR Detalhe - Endereço
				$ARDetalhe->add(substr(completaString(tdClass::Criar("persistent",array("td_cidade",$credor->cidade))->contexto->nome,30," ",STR_PAD_RIGHT),0,30)); // AR Detalhe - Cidade
				$ARDetalhe->add(tdClass::Criar("persistent",array("td_estado",$credor->td_estado))->contexto->sigla); // AR Detalhe - Estado ( Sigla )
				$ARDetalhe->add(str_replace(array("-","."),"",$credor->cep)); // AR Detalhe - CEP
				$ARDetalhe->add(completaString("0",8)); // AR Detalhe - Filtro
				$ARDetalhe->add(completaString($configuracoesetiqueta->contexto->indiceremessa,5)); // AR Detalhe - Número da Remessa
				$ARDetalhe->add(completaString($ARHeaderQtdeRegistro,7)); // AR Detalhe - Número Registro
				
				// Arquivo de Dados
				//DATAATUAL|CREDOR|TIPO DE COMUNICAÇÃO|NUMERO DO PROCESSO|JUIZO|COMARCA|DATA DE ABERTURA DO PROCESSO|RECUPERANDA/FALIDA/INSOLVENTE|CNPJ|LOGRADOURO|NUMERO|BAIRRO|CIDADE|ESTADO|CEP|NATUREZA|VALOR|CLASSIFICACAO|REGISTRADO|CEP_CREDOR|LOGRADOURO_CREDOR|NUMERO_CREDOR|COMPLEMENTO_CREDOR|BAIRRO_CREDOR|CIDADE_CREDOR|ESTADO_CREDOR
				$ARDado->add("\n");
				$ARDado->add(date("d/m/Y"));
				$ARDado->add("|");
				$ARDado->add(substr(completaString(preg_replace('/\s/',' ',$credor->nome),40," ",STR_PAD_RIGHT),0,39)); // AR Detalhe - Nome do Destinatário				
				$ARDado->add("|");
				$ARDado->add(utf8_decode($recuperacao?"COMUNICAÇÃO DE RECUPERAÇÃO JUDICIAL":"COMUNICAÇÃO DE FALÊNCIA"));
				$ARDado->add("|");
				$ARDado->add($numero);
				$ARDado->add("|");
				$ARDado->add($juizo);				
				$ARDado->add("|");
				$ARDado->add($comarca);
				$ARDado->add("|");
				$ARDado->add($data);
				$ARDado->add("|");
				$ARDado->add($razaosocial);
				$ARDado->add("|");
				$ARDado->add($cnpj_cpf);
				$ARDado->add("|");
				$ARDado->add($logradouro);
				$ARDado->add("|");
				$ARDado->add($numero_rua);
				$ARDado->add("|");
				$ARDado->add($bairro);
				$ARDado->add("|");				
				$ARDado->add(substr(completaString($cidade,30," ",STR_PAD_RIGHT),0,29));
				$ARDado->add("|");
				$ARDado->add($estado_sigla);
				$ARDado->add("|");
				$ARDado->add(str_replace(array("-","."),"",$cep));
				$ARDado->add("|");
				$ARDado->add($natureza);
				$ARDado->add("|");
				$ARDado->add(str_replace("R$ ","",$valor));
				$ARDado->add("|");
				$ARDado->add($classificacao);
				$ARDado->add("|");
				$ARDado->add("[".$numero."] " . $razaosocial . " - " . completaString($credor->codigo,5));
				
				$ARDado->add("|");
				$ARDado->add(str_replace(array("-","."),"",$credor->cep));
				$ARDado->add("|");
				$ARDado->add($credor->logradouro);
				$ARDado->add("|");
				$ARDado->add($credor->numero);
				$ARDado->add("|");
				$ARDado->add($credor->complemento);
				$ARDado->add("|");
				$ARDado->add($credor->bairro);
				$ARDado->add("|");
				$ARDado->add(tdClass::Criar("persistent",array("td_cidade",$credor->cidade))->contexto->nome);
				$ARDado->add("|");
				$ARDado->add(tdClass::Criar("persistent",array("td_estado",$credor->td_estado))->contexto->sigla);
				$ARDado->add("|");

				$ARDado->add("JC".$NumeroEtiqueInicio.getDiv((string)$NumeroEtiqueInicio)."BR");
				
				// Arquivo de Faturamento - SARA
				$datacoleta = "";
				
				$ARSara->add(3); // Lista de Postagem
				$ARSara->add("03"); // Código da Gráfica
				$ARSara->add("0000"); // Filter
				$ARSara->add("0000"); // Filter
				$ARSara->add(retornaDataColeta()); // Data da Coleta
				$ARSara->add("    "); // Filter
				$ARSara->add("9912385950"); // Número do Contrato
				$ARSara->add("15352323"); // Código Administrativo				
				$ARSara->add(completaString(str_replace(array("-","."),"",$credor->cep),8,"0")); // CEP
				$ARSara->add("10065"); // Código do Serviço
				$ARSara->add("10"); // Código do País
				$ARSara->add("25"); // Cod Serv Adic.
				$ARSara->add("37"); // Cod Serv Adic.
				$ARSara->add("00"); // Cod Serv Adic.
				$ARSara->add("00000,00"); // Valor Declarado
				$ARSara->add("000000000"); // Filter
				$ARSara->add("00"); // Filter
				$ARSara->add($NumeroEtiqueInicio.getDiv((string)$NumeroEtiqueInicio)); // Número Etiqueta				
				$ARSara->add("00005"); // Peso
				$ARSara->add("00000000"); // Filter
				$ARSara->add("00"); // Filter
				$ARSara->add("00"); // Filter
				$ARSara->add("00000000"); // Filter
				$ARSara->add("000"); // Filter
				$ARSara->add("00"); // Filter
				$ARSara->add("0000000000"); // Filter
				$ARSara->add(completaString((is_numeric($credor->numero)?$credor->numero:0),6)); // Número Logradouro
				$ARSara->add("00"); // Filter
				$ARSara->add("00071641670"); // Número do Cartão da Postagem
				$ARSara->add("0000000"); // Número da Nota Fiscal
				$ARSara->add("JC"); // Sigla do Serviço
				$ARSara->add("00000"); // Comprimento do Objeto
				$ARSara->add("00000"); // Largura do Objeto
				$ARSara->add("00000"); // Altura do Objeto
				$ARSara->add("00000,00"); // Valor a cobrar
				$ARSara->add(substr(completaString(preg_replace('/\s/',' ',trim($credor->nome)),40," "),0,40)); // Destinatário
				$ARSara->add("001"); // Código do Tipo de Objeto
				$ARSara->add("00000"); // Diametro do Pacote
				$ARSara->add("\n");

				$NumeroEtiqueInicio++;

				$credores_remessa .= (($credores_remessa=="")?"":",") . $credor->id . "^" .$login . "^" . $senha;				
			}

			// Header
			$ARHeader =	
				$ARHeaderTipoRegistro.
				$ARHeaderCodigoCliente.
				$ARHeaderFilter1.
				$ARHeaderNomeCliente.
				$ARHeaderDataGeracao.
				completaString($ARHeaderQtdeRegistro,6).
				$ARHeaderFilter2.
				$ARHeaderNumArquivoRemessa.
				$ARHeaderNumRemessa
			;

			// Footer - SARA
			$ARSara->add(9 . completaString($ARHeaderQtdeRegistro,8));

			// Botão Download
			$bDownload = tdClass::Criar("button");
			$bDownload->add("Download");
			$bDownload->class = "btn btn-primary b-ar";
			$bDownload->id = "btn-download";
			$bDownload->type="submit";
			
			// Botão Enviar
			$bEnviar = tdClass::Criar("button");
			$bEnviar->add("Enviar");
			$bEnviar->class = "btn btn-primary b-ar btn-lg";
			$bEnviar->id = "btn-enviar";
			$bEnviar->type="submit";
			
			$form = tdClass::Criar("form");
			$form->action = "index.php?controller=envioardigital";
			$form->method = "POST";
			$form->target = "retornoenvio";
			$form->add('
				<input type="hidden" id="op" name="op" value="gerar_ar" />
				<input type="hidden" id="header" name="header" value="'.$ARHeader.'" />
				<input type="hidden" id="processo" name="processo" value="'.$processo.'" />
				<input type="hidden" id="farein" name="farein" value="'.$farein.'" />
				<input type="hidden" id="numeroetiquetainicio" name="numeroetiquetainicio" value="'.$NumeroEtiqueInicio.'" />
				<input type="hidden" id="credores_remessa" name="credores_remessa" value="'.$credores_remessa.'" />				
				
			',$ARDetalhe,$ARSara,$ARDado,$bEnviar,
			'<iframe id="retornoenvio" name="retornoenvio" border="0" frameborder="0" scrolling="no" style="float:left;width:600px;border:0px;height:60px;"></iframe>'
			);
			
			// Body Panel
			$bodyPanel = tdClass::Criar("div");
			$bodyPanel->add($form);
			
			$panel = tdClass::Criar("panel");
			$panel->tipo = "primary";
			$panel->style = "float:left;clear:left;width:21cm;";
			$panel->head("AR Digital ( Correios ) ");
			$panel->body($bodyPanel);			
			$panel->mostrar();
			
			function getDiv($str){
				if (strlen($str) < 8){
					$str = completaString($str,8);
				}
					
				$fator = array(8,6,4,2,3,5,9,7);

				$somatorio = 0;
				for ($i=0;$i<8;$i++){
					$somatorio += $fator[$i] * (int)$str[$i];
				}

				$resto = $somatorio%11;
				if ($resto == 0){
					return 5;
				}elseif($resto == 1){
					return 0;
				}else{
					return (11 - $resto);
				}
			}
?>
	</body>
</html>