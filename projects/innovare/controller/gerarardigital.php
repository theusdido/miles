<?php

//@session_start("envioardigital");
set_time_limit(7200);
date_default_timezone_set('America/Sao_Paulo');

if (isset($_GET["op"])){
	if ($_GET["op"] == "download"){
		$tipo 		= $_GET["tipo"];
		$arquivo 	= $_GET["arquivo"];

		// **** Os Header foram retirados pois apresentaram problema no Google Chrome *** //
		#header("Content-Type: ".$tipo); // informa o tipo do arquivo ao navegador
		#header("Content-Length: ".filesize($arquivo)); // informa o tamanho do arquivo ao navegador
		header("Content-Disposition: attachment; filename=".basename($arquivo)); // informa ao navegador que é tipo anexo e faz abrir a janela de download, tambem informa o nome do arquivo
		header( 'Content-Type: text/html; charset=utf-8' );
		//header( 'Content-Type: text/html; charset=ISO-8859-1' );
		try{
			echo iconv("UTF-8", "WINDOWS-1252//TRANSLIT", file_get_contents($arquivo));
			throw new Exception($t);
		}catch(Throwable $t){
			//echo file_get_contents($arquivo);
			echo iconv("WINDOWS-1250", "UTF-8", file_get_contents($arquivo));
			//echo iconv("UTF-8", 'ASCII//TRANSLIT', file_get_contents($arquivo));
			//echo iconv("UTF-8", "ISO-8859-1//TRANSLIT", file_get_contents($arquivo));
			//echo iconv("ISO-8859-1", "UTF-8//TRANSLIT", file_get_contents($arquivo));
			//echo iconv("WINDOWS-1252", "UTF-8//TRANSLIT", file_get_contents($arquivo));
			
		}
		exit;
	}
}

$configuracoesetiqueta		= tdClass::Criar("persistent",array("td_comunicacaocredoresconfiguracoes",1));
if ($configuracoesetiqueta==""){
	echo 'Configuração da etiqueta não encontrada';
	exit;
}

//$NumeroEtiqueInicio			= (int)mb_substr(($configuracoesetiqueta->contexto->ultimonumeroetiqueta == '0'? $configuracoesetiqueta->contexto->numeroinicialmaximoetiqueta : $configuracoesetiqueta->contexto->ultimonumeroetiqueta),0,9);
$NumeroEtiqueInicio = (int)$configuracoesetiqueta->contexto->ultimonumeroetiqueta;
$falencia = $recuperacao = false;
if (isset($_GET["farein"])){
	$farein_array = explode("^",$_GET["farein"]);
	$processo 		= $farein_array[1];
	$farein 		= $farein_array[0];
	$tipo_farein 	= $farein_array[2];

	$conn 		= Transacao::get();
	$result 	= $conn->query("SELECT * FROM td_processo WHERE id ={$processo}");		
	$processo 	= "";
	foreach ($result as $key => $value){
		$processo 			= $value["id"];
		$numero 			= $value["numeroprocesso"];
		$dataenvio 			= dataExtenso(date("d/m/Y"));
		$juizo 				= utf8charset(tdClass::Criar("persistent",array("td_juizo",$value["juizo"]))->contexto->descricao);
		$comarca 			= utf8charset(tdClass::Criar("persistent",array("td_comarca",$value["comarca"]))->contexto->descricao);
		$tipoprocessofile 	= "";

		$resul_falida = $conn->query("SELECT * FROM td_falencia WHERE processo = '{$processo}' AND id = {$farein}");
		$_SESSION["tipoprocesso"] = $value["tipoprocesso"];
		if ($tipo_farein=="FA"){
			$tipoprocessofile = "FAL";
			foreach ($resul_falida as $falida){					
				$falencia 		= true;						
				$razaosocial 	= utf8charset(str_replace('’','',strtoupper($falida["razaosocial"])));
				$cnpj_cpf 		= $falida["cnpj"];
				$logradouro 	= utf8charset($falida["logradouro"]);
				$numero_rua 	= utf8charset($falida["numero"]);
				$bairro 		= utf8charset($falida["bairro"]);
				$cidade 		= utf8charset(tdClass::Criar("persistent",array("cidade",$falida["cidade"]))->contexto->nome);
				$estado 		= utf8charset(tdClass::Criar("persistent",array("estado",$falida["estado"]))->contexto->nome);
				$estado_sigla 	= tdClass::Criar("persistent",array("td_estado",$falida["estado"]))->contexto->sigla;
				$cep 			= $falida["cep"];
				$data 			= utf8charset(dataExtenso($falida["datasentenca"]));
			}
		}

		$resul_recuperanda = $conn->query("SELECT * FROM td_recuperanda WHERE processo = '{$processo}' AND id = {$farein}");
		if ($tipo_farein=="RE"){
			$tipoprocessofile = "REC";
			foreach ($resul_recuperanda as $recuperanda){
			$recuperacao 		= true;
			$razaosocial 		= utf8charset(strtoupper(str_replace('’','',$recuperanda["razaosocial"])));
			$cnpj_cpf 			= $recuperanda["cnpj"];
			$logradouro 		= utf8charset($recuperanda["logradouro"]);
			$numero_rua 		= utf8charset($recuperanda["numero"]);
			$bairro 			= utf8charset($recuperanda["bairro"]);
			$cidade 			= utf8charset(tdClass::Criar("persistent",array("td_cidade",$recuperanda["cidade"]))->contexto->nome);
			$estado 			= utf8charset(tdClass::Criar("persistent",array("td_estado",$recuperanda["estado"]))->contexto->nome);
			$estado_sigla 		= tdClass::Criar("persistent",array("td_estado",$recuperanda["estado"]))->contexto->sigla;
			$cep 				= $recuperanda["cep"];
			$data 				= utf8charset(dataExtenso($recuperanda["datapedido"]));
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

// Arquivo Físico Detalhe
$nomeArquivo 		= "Innovare_SGD_" . $tipoprocessofile . "_" . completaString($farein,3) . "_" . date("dmY") . ".txt";
$filenameDetalhe 	= PATH_CURRENT_FILE . "{$nomeArquivo}";
$fpDetalhe 			= fopen($filenameDetalhe,"w+");

$nomeArquivo 		= "Innovare_DADOS_" . $tipoprocessofile . "_" . completaString($farein,3) . "_" . date("dmY") . ".txt";
$filenameDado 		= PATH_CURRENT_FILE . "{$nomeArquivo}";
$fpDado 			= fopen($filenameDado,"w+");

// 7 Físico Sara
$nomeArquivo 		= "Innovare_SARA_" . $tipoprocessofile . "_" . completaString($farein,3) . "_" . date("dmY") . ".txt";
$filenameSara 		= PATH_CURRENT_FILE . "{$nomeArquivo}";
$fpSara 			= fopen($filenameSara,"w+");

if (!file_exists($filenameDetalhe)){
	echo 'Arquivo detalhe não encontrado => ' . $filenameDetalhe;
	exit;
}

if (!file_exists($filenameDado)){
	echo 'Arquivo dado não encontrado => ' . $filenameDado;
	exit;
}

if (!file_exists($filenameSara)){
	echo 'Arquivo sara não encontrado => ' . $filenameSara;
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

// Header - Detalhe
fwrite($fpDetalhe,
	$ARHeaderTipoRegistro.
	$ARHeaderCodigoCliente.
	$ARHeaderFilter1.
	$ARHeaderNomeCliente.
	$ARHeaderDataGeracao.
	completaString($ARHeaderQtdeRegistro,6).
	$ARHeaderFilter2.
	$ARHeaderNumArquivoRemessa.
	$ARHeaderNumRemessa
);

$iserror 			= false;
$credores_remessa 	= "";
fwrite($fpDado,'DATAATUAL|CREDOR|TIPO DE COMUNICACAO|NUMERO DO PROCESSO|JUIZO|COMARCA|DATA DE ABERTURA DO PROCESSO|RECUPERANDA/FALIDA/INSOLVENTE|CNPJ|LOGRADOURO|NUMERO|BAIRRO|CIDADE|ESTADO|CEP|NATUREZA|VALOR|CLASSIFICACAO|REGISTRADO|CEP_CREDOR|LOGRADOURO_CREDOR|NUMERO_CREDOR|COMPLEMENTO_CREDOR|BAIRRO_CREDOR|CIDADE_CREDOR|ESTADO_CREDOR|ETIQUETA');
$where_classificacao = "";
if (isset($_GET["classificacao"])){
	if ($_GET["classificacao"]!=""){
		$where_classificacao = "and classificacao not in (".$_GET["classificacao"].")";
	}
}
$where_origemcredor = "origemcredor = 10 OR origemcredor = NULL or origemcredor = '' OR origemcredor IS NULL";
$isrelacaoinicial = $_GET["isrelacaoinicial"]=='true'?true:false;
if ($isrelacaoinicial){
	$where_origemcredor .= " OR origemcredor = 1 ";
}
$sql = "
	SELECT * FROM td_relacaocredores 
	WHERE processo = {$processo} 
	AND farein = {$farein} 
	{$where_codigo} 
	{$where_classificacao} 
	AND ($where_origemcredor) 
	ORDER BY cep ASC,classificacao ASC, nome ASC;
";

$result = $conn->query($sql);
if ($result->rowcount() == 0){
	echo '<div class="alert alert-danger text-center"><b>Nenhum credor encontrados para esses par&acirc;metros.</b></div>';
	return false;
}
foreach ($result as $key => $value){
	
	if (!is_numeric($value["moeda"])){
		msgError('Tipo de moeda inválido no credor => ' . $value["id"]);
		break;
	}
	if ($value["moeda"] <= 0 || $value["moeda"] > 3){
		msgError('Índice do tipo de moeda inválido no credor => ' . $value["id"]);
		break;
	}

	if (!is_numeric($value["valor"])){
		msgError('Valor inválido no credor => ' . $value["id"]);
		break;
	}
	if ($value["valor"] <= 0){
		msgError('Valor não pode ser negativo no credor => ' . $value["id"]);
		break;
	}

	if (!is_numeric($value["classificacao"]) || (int)$value["classificacao"] <=0 ){
		msgError('Classificação inválida no credor => ' . $value["id"]);
		break;
	}

	if (!is_numeric($value["natureza"]) || (int)$value["natureza"] <=0 ){
		msgError('Natureza inválida no credor => ' . $value["id"]);
		break;
	}

	if ($value["logradouro"] == '' || $value["logradouro"] == null || empty($value["logradouro"])){
		msgError('Logradouro não pode ser vazio no credor => ' . $value["id"]);
		break;
	}

	if ($value["bairro"] == '' || $value["bairro"] == null || empty($value["bairro"])){
		msgError('Bairro não pode ser vazio no credor => ' . $value["id"]);
		break;
	}

	if (!is_numeric($value["cidade"]) || (int)$value["cidade"] <=0 ){
		msgError('Cidade não pode ser vazio no credor => ' . $value["id"]);
		break;
	}

	if (!is_numeric($value["estado"]) || (int)$value["estado"] <=0 ){
		msgError('Estado não pode ser vazio no credor => ' . $value["id"]);
		break;
	}

	if (!isCEP($cep)){
		msgError('CEP inválido no credor => ' . $value["id"]);
		break;
	}
	
	switch($value["moeda"]){
		case 1: $simboloMoeda = "R$"; break;
		case 2: $simboloMoeda = "US$"; break;
		case 3: $simboloMoeda = "EUR€"; break;
		default:
			$simboloMoeda = "R$";
	}

	$credor 				= utf8charset(strtoupper($value["nome"]));
	$valor					= moneyToFloat($value["valor"],false);
	$valor 					= $simboloMoeda . " " . number_format($value["valor"], 2, ',', '.');
	$classificacao 			= utf8charset(tdClass::Criar("persistent",array("td_classificacao",$value["classificacao"]))->contexto->descricao);
	$natureza 				= utf8charset(tdClass::Criar("persistent",array("td_natureza",$value["natureza"]))->contexto->descricao);

	$login 					= geraSenha(4,false,true,false);
	$senha 					= geraSenha(4,true,true,false);
	$credor 				= tdClass::Criar("persistent",array("td_relacaocredores",$value["id"]))->contexto;
	
	$ARHeaderQtdeRegistro++;
	
	$nomeCredor				= utf8charset($credor->nome);
	$logradouroCredor		= utf8charset($credor->logradouro);
	$numeroCredor			= utf8charset($credor->numero);
	$complementoCredor		= utf8charset($credor->complemento);
	$cidadeCredor			= utf8charset(tdClass::Criar("persistent",array("td_cidade",$credor->cidade))->contexto->nome);
	
	$siglapostal			= "YA";

	$_numero_etiqueta_sequencial 	= completaString($NumeroEtiqueInicio,8,"0").getDiv((string)$NumeroEtiqueInicio);
	$_nome_credor 					= substr(mb_substr(completaString(preg_replace('/\s/',' ',trim($nomeCredor)),40," ",STR_PAD_LEFT),0,40),0,40);
	$_endereco_credor				= substr(completaString(mb_substr(preg_replace('/\s/',' ',$logradouroCredor . ($numeroCredor!=""?"," . $numeroCredor:"")) .  ($complementoCredor!=""?" - " . $complementoCredor:""),0,40),40," ",STR_PAD_RIGHT),0,40);
	$_credor_cidade					= substr(completaString(mb_substr($cidadeCredor,0,30),30," ",STR_PAD_RIGHT),0,30);
	$_credor_uf						= tdClass::Criar("persistent",array("td_estado",$credor->estado))->contexto->sigla;
	$_credor_cep					= apenas_numero(str_replace(array("-","."),"",$credor->cep));
	$_indice_remessa				= completaString($configuracoesetiqueta->contexto->indiceremessa,5);
	$_header_qtdade_registro		= completaString($ARHeaderQtdeRegistro,7);

	$_cidade						= mb_substr(completaString($cidade,30," ",STR_PAD_RIGHT),0,29);
	$_credor_bairro					= utf8charset($credor->bairro);

	// Previsão de Postagem
	fwrite($fpDetalhe,
		"\r\n" . // AR Detalhe - Quebra de linha
		9 . // AR Detalhe - Tipo de Registro
		"5950" . // AR Detalhe - Código do Cliente
		"IX" . // AR Detalhe - Identificador do Cliente
		$siglapostal . // AR Detalhe - Sigla do Objeto ( Tipo Postal )
		$_numero_etiqueta_sequencial . // AR Detalhe - Número do Objeto ( Faixa fornecida pela ETC )
		"BR" . // AR Detalhe - Pais
		"1101" . // AR Detalhe - Código da Operação (1101 - Envio / 1102 - Exclusão )
		"                " . // Campo Livre
		$_nome_credor . // AR Detalhe - Nome do Destinatário
		$_endereco_credor . // AR Detalhe - Endereço
		$_credor_cidade . // AR Detalhe - Cidade
		$_credor_uf . // AR Detalhe - Estado ( Sigla )
		$_credor_cep . // AR Detalhe - CEP
		completaString("0",8) . // AR Detalhe - Filtro
		$_indice_remessa . // AR Detalhe - Número da Remessa
		$_header_qtdade_registro // AR Detalhe - Número Registro
	);

	// Arquivo de Dados
	//DATAATUAL|CREDOR|TIPO DE COMUNICAÇÃO|NUMERO DO PROCESSO|JUIZO|COMARCA|DATA DE ABERTURA DO PROCESSO|RECUPERANDA/FALIDA/INSOLVENTE|CNPJ|LOGRADOURO|NUMERO|BAIRRO|CIDADE|ESTADO|CEP|NATUREZA|VALOR|CLASSIFICACAO|REGISTRADO|CEP_CREDOR|LOGRADOURO_CREDOR|NUMERO_CREDOR|COMPLEMENTO_CREDOR|BAIRRO_CREDOR|CIDADE_CREDOR|ESTADO_CREDOR
	fwrite($fpDado,"\r\n");
	fwrite($fpDado,date("d/m/Y"));
	fwrite($fpDado,"|");
	fwrite($fpDado,$_nome_credor); // AR Detalhe - Nome do Destinatário
	fwrite($fpDado,"|");
	fwrite($fpDado,utf8charset($recuperacao?"COMUNICAÇÃO DE RECUPERAÇÃO JUDICIAL":"COMUNICAÇÃO DE FALÊNCIA"));
	fwrite($fpDado,"|");
	fwrite($fpDado,$numero);
	fwrite($fpDado,"|");
	fwrite($fpDado,$juizo);				
	fwrite($fpDado,"|");
	fwrite($fpDado,$comarca);
	fwrite($fpDado,"|");
	fwrite($fpDado,$data);
	fwrite($fpDado,"|");
	fwrite($fpDado,$razaosocial);
	fwrite($fpDado,"|");
	fwrite($fpDado,$cnpj_cpf);
	fwrite($fpDado,"|");
	fwrite($fpDado,$logradouro);
	fwrite($fpDado,"|");
	fwrite($fpDado,$numero_rua);
	fwrite($fpDado,"|");
	fwrite($fpDado,$bairro);
	fwrite($fpDado,"|");				
	fwrite($fpDado,$_cidade);
	fwrite($fpDado,"|");
	fwrite($fpDado,$estado_sigla);
	fwrite($fpDado,"|");
	fwrite($fpDado,str_replace(array("-","."),"",$cep));
	fwrite($fpDado,"|");
	fwrite($fpDado,$natureza);
	fwrite($fpDado,"|");
	fwrite($fpDado,$valor);
	fwrite($fpDado,"|");
	fwrite($fpDado,$classificacao);
	fwrite($fpDado,"|");
	fwrite($fpDado,"[".$numero."] " . $razaosocial . " - " . completaString($credor->codigo,5));
	fwrite($fpDado,"|");
	fwrite($fpDado,str_replace(array("-","."),"",$credor->cep));
	fwrite($fpDado,"|");
	fwrite($fpDado,$logradouroCredor);
	fwrite($fpDado,"|");
	fwrite($fpDado,$numeroCredor);
	fwrite($fpDado,"|");
	fwrite($fpDado,$complementoCredor);
	fwrite($fpDado,"|");
	fwrite($fpDado,$_credor_bairro);
	fwrite($fpDado,"|");
	fwrite($fpDado,$cidadeCredor);
	fwrite($fpDado,"|");
	fwrite($fpDado,tdClass::Criar("persistent",array("td_estado",$credor->estado))->contexto->sigla);
	fwrite($fpDado,"|");

	fwrite($fpDado,$siglapostal.$_numero_etiqueta_sequencial."BR");

	// Arquivo de Faturamento - SARA
	$datacoleta = "";
	$linha_sara = '';

	$linha_sara .= 3; #fwrite($fpSara,3); // Lista de Postagem
	$linha_sara .= '03'; #fwrite($fpSara,"03"); // Código da Gráfica
	$linha_sara .= '0000';#fwrite($fpSara,"0000"); // Filter
	$linha_sara .= '0000';#fwrite($fpSara,"0000"); // Filter
	$linha_sara .= retornaDataColeta();#fwrite($fpSara,retornaDataColeta()); // Data da Coleta
	$linha_sara .= '    ';#fwrite($fpSara,"    "); // Filter
	$linha_sara .= '9912385950';#fwrite($fpSara,"9912385950"); // Número do Contrato
	$linha_sara .= '15352323';#fwrite($fpSara,"15352323"); // Código Administrativo
	$_cep 		= completaString(str_replace(array("-","."),"",apenas_numero($credor->cep)),8,"0");
	if (strlen($_cep) != 8){
		msgError('CEP Inválido. [' . $_cep . '] - CREDOR => ' . $credor->id);
	}
	$linha_sara .= $_cep;#fwrite($fpSara,completaString(str_replace(array("-","."),"",$credor->cep),8,"0")); // CEP
	$linha_sara .= '10065';#fwrite($fpSara,"10065"); // Código do Serviço
	$linha_sara .= '10';#fwrite($fpSara,"10"); // Código do País
	$linha_sara .= '25';#fwrite($fpSara,"25"); // Cod Serv Adic.
	$linha_sara .= '37';#fwrite($fpSara,"37"); // Cod Serv Adic.
	$linha_sara .= '00';#fwrite($fpSara,"00"); // Cod Serv Adic.
	$linha_sara .= '00000,00';#fwrite($fpSara,"00000,00"); // Valor Declarado
	$linha_sara .= '000000000';#fwrite($fpSara,"000000000"); // Filter
	$linha_sara .= '00';#fwrite($fpSara,"00"); // Filter	
	$linha_sara .= $_numero_etiqueta_sequencial; #fwrite($fpSara,completaString($NumeroEtiqueInicio,8,"0").getDiv((string)$NumeroEtiqueInicio)); // Número Etiqueta
	$linha_sara .= '00005';#fwrite($fpSara,"00005"); // Peso
	$linha_sara .= '00000000';#fwrite($fpSara,"00000000"); // Filter
	$linha_sara .= '00';#fwrite($fpSara,"00"); // Filter
	$linha_sara .= '00';#fwrite($fpSara,"00"); // Filter
	$linha_sara .= '00000000';#fwrite($fpSara,"00000000"); // Filter
	$linha_sara .= '000';#fwrite($fpSara,"000"); // Filter
	$linha_sara .= '00';#fwrite($fpSara,"00"); // Filter
	$linha_sara .= '0000000000';#fwrite($fpSara,"0000000000"); // Filter
	$linha_sara .= completaString((is_numeric($numeroCredor)?$numeroCredor:0),6);#fwrite($fpSara,completaString((is_numeric($numeroCredor)?$numeroCredor:0),6)); // Número Logradouro
	$linha_sara .= '00';#fwrite($fpSara,"00"); // Filter
	$linha_sara .= '00071641670';#fwrite($fpSara,"00071641670"); // Número do Cartão da Postagem
	$linha_sara .= '0000000';#fwrite($fpSara,"0000000"); // Número da Nota Fiscal
	$linha_sara .= $siglapostal;#fwrite($fpSara,$siglapostal); // Sigla do Serviço
	$linha_sara .= '00000';#fwrite($fpSara,"00000"); // Comprimento do Objeto
	$linha_sara .= '00000';#fwrite($fpSara,"00000"); // Largura do Objeto
	$linha_sara .= '00000';#fwrite($fpSara,"00000"); // Altura do Objeto
	$linha_sara .= '00000,00';#fwrite($fpSara,"00000,00"); // Valor a cobrar
	
	if (strlen($_nome_credor) > 40){
		$_nome_credor = substr($_nome_credor,0,40);
	}
	$linha_sara .= $_nome_credor;#fwrite($fpSara,mb_substr(completaString(preg_replace('/\s/',' ',$nomeCredor),40," ",STR_PAD_LEFT),0,40)); // Destinatário
	$linha_sara .= '001';#fwrite($fpSara,"001"); // Código do Tipo de Objeto
	$linha_sara .= '00000';#fwrite($fpSara,"00000"); // Diametro do Pacote
	
	if (strlen($linha_sara) != 229){		
		msgError('Quantidade de caracter inválido. Arquivo SARA. [ ' . strlen($linha_sara) . ' ]<br/>' . $linha_sara);
	}

	fwrite($fpSara,$linha_sara);
	fwrite($fpSara,"\r\n");

	$NumeroEtiqueInicio++;
	$credores_remessa .= (($credores_remessa=="")?"":",") . $credor->id . "^" .$login . "^" . $senha;
}

// Atualiza a última etiqueta
$configuracoesetiqueta->contexto->ultimonumeroetiqueta = $NumeroEtiqueInicio;
$configuracoesetiqueta->contexto->armazenar();

// Footer - SARA
fwrite($fpSara,9 . completaString($ARHeaderQtdeRegistro,8));

fclose($fpDetalhe);
fclose($fpDado);
fclose($fpSara);





chmod($filenameDetalhe,0777);
chmod($filenameDado,0777);
chmod($filenameSara,0777);

// Funções
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
function isFeriado($data){					
	$datacoleta_array 	= explode("-",$data);					
	$sqlFeriado 		= tdClass::Criar("sqlcriterio");
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

function msgError($msg){
	echo '<div class="alert alert-danger">'.$msg.'</div>';
	global $iserror;
	$iserror = true;
}

if (!$iserror){
	echo '
			<div class="btn-group btn-group-justified" role="group" aria-label="Baixar arquivos do AR Digital">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" onclick=window.open(getURLProject("index.php?controller=gerarardigital&op=download&tipo=txt&arquivo='.$filenameDetalhe.'"),"_blank");>SGD</button>
				</div>
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" onclick=window.open(getURLProject("index.php?controller=gerarardigital&op=download&tipo=txt&arquivo='.$filenameSara.'"),"_blank");>SARA</button>
				</div>
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" onclick=window.open(getURLProject("index.php?controller=gerarardigital&op=download&tipo=txt&arquivo='.$filenameDado.'"),"_blank");>DADOS</button>
				</div>
			</div>
	';
}	