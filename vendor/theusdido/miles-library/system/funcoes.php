<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link https://teia.tec.br

    * Arquivo com as funções do sistema
    * Data de Criacao: 14/02/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/

/*  
	* Função para incluir um arquivo 
	* Data de Criacao: 14/02/2012
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* PARAMETROS
	*	1 - Arquivo incluído
	*	2 - [ True ] incluir várias vezes, [ false ] incluir apenas uma única vez
	* RETORNO
	*	[ Boolean ]
*/	
function incluir($arquivo,$repetir=true){
	$retorno = false;	
	if (file_exists(RAIZ.$arquivo)){ #Carrega o arquivo sobrescrito pelo usuário
		if ($repetir) include RAIZ.$arquivo;
		else include_once RAIZ.$arquivo;
		$retorno = true;
	}
	return $retorno;
}
/*  
	* Função para requerer um arquivo 
	* Data de Criacao: 14/02/2012
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* PARAMETROS
	*	1 - Arquivo requerido
	*	2 - [ True ] incluir várias vezes, [ false ] incluir apenas uma única vez
	* RETORNO
	*	[ sem retorno ]	
*/	
function requerer($arquivo,$repetir=true){
	if (file_exists(RAIZ.$arquivo)){
		if ($repetir) require RAIZ.$arquivo;
		else require_once RAIZ.$arquivo;
	}
}
/*  
	* Função para tratar dados  vindos do usuário 
	* Data de Criacao: 16/12/2014
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* PARAMETROS
	*	1 - Nome da Variavel ( String )
	* RETORNO
	*	Valor da variavel	
*/	
function getValor($valor){
	if (!vazio($valor)){	
		$valor = trim($valor);
		$valor = strip_tags($valor);
		$valor = addslashes($valor);
		return $valor;
	}else{
		return "";
	}	
}
/*  
	* Função para verificar se o campo vem vazio 
	* Data de Criacao: 15/01/2015
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* PARAMETROS
	*	1 - Variavel ( String )
	* RETORNO
	*	Boolean	
*/	
function vazio($valor){
	if (!isset($valor)) return true;	
	if ($valor == "") return true;
	if (empty($valor)) return true;
	if ($valor == null) return true;
	return false;
}

/*  
	* Função para converter array em string
	* Data de Criacao: 04/02/2015
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* PARAMETROS
	*	1 - Variavel ( Array )
	*	2 - Separador
	* RETORNO
	*	String
*/	
function arrayToString($array,$separador=","){
	$retorno = "";
	foreach ($array as $valor){
		$retorno .= ($retorno=="")?$valor:$separador . $valor;
	}
	return $retorno;
}
function is_date( $str = ''){
	if ($str == '') return false;
	if (strpos($str, "/") > 0){
		// Com barras
		$dt = explode("/",$str);
		if (sizeof($dt) != 3) return false;	
		$month = $dt[1];
		$day   = $dt[0];
		$year  = (int)$dt[2];
		if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year)) return "";
		if (checkdate($month, $day, $year)){
			return true;
		}
	}	
	if (strpos($str, "-") > 0){	
		// Formato interno MySQL	
		$dt = explode("-",$str);
		if (sizeof($dt) != 3) return false;
		$month = $dt[1];
		$day   = $dt[2];
		$year  = (int)$dt[0];
		if (!is_numeric($day) || !is_numeric($month) || !is_numeric($year)) return "";
		if (checkdate($month, $day, $year)){
			return true;
		}
	}		
    return false;
}
function is_datetime( $str ){
	if (is_date($str)){
		$dt = explode(" ",$str);
		if (sizeof($dt) > 1){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
    return false;
}
/*  
	* Função para verificar se uma data está no formato interno do MySQL
	* Data de Criacao: 02/05/2021
	* Author: @theusdido
	* PARAMETROS
	*	1 - data
	* RETORNO
	*	String
*/
function is_date_mysql($data){
	$partes = explode("-",$data);
	if (sizeof($partes) != 3) return false;
	$ano = $partes[0];
	$mes = $partes[1];
	$dia = $partes[2];

	if (!is_numeric_natural($ano) || strlen($ano) != 4){
		return false;
	}

	if (!is_numeric_natural($mes) || strlen($mes) != 2 || $mes > 12){
		return false;
	}
	if (!is_numeric_natural($dia) || strlen($dia) != 2 || $dia > 31){
		return false;
	}
	return true;
}
function dateToMysqlFormat($str_date,$invertido=false){
	if ($str_date=="" || $str_date == null) return "";
	if (is_date_mysql($str_date) && !$invertido) return $str_date;

	// Retira a hora
	if (strpos($str_date,' ') !== false){
		$str_date = explode(' ',$str_date)[0];
	}

	if (!$invertido){
		if (strpos($str_date,"/") > 0){ // O strpos eu testo se existe alguma barra
			$dt = explode("/",$str_date);  // Quebra a string com a barra e transforma em array ( dia,mes,ano )
			if (sizeof($dt) != 3) return $str_date; // Retorna a data sem formatação se não for 3 partes, data inválida no caso
		}else if (strpos($str_date,".") > 0){
			$dt = explode(".",$str_date);
			if (sizeof($dt) != 3) return $str_date;
		}else{
			// Partimos do princípio que a data veio sem barra, no formato ddmmyyyy
			$dt[0] = substr($str_date,0,2); // o substr pega uma parte da string
			$dt[1] = substr($str_date,2,2); 
			$dt[2] = substr($str_date,4,4);
		}
		return $dt[2] ."-". $dt[1] ."-". $dt[0]; // retorna o formato do MySQL
	}else{
		if (strpos($str_date,"/") > 0){
			return $str_date;
		}else{
			$dt = explode("-",$str_date);
			if (sizeof($dt) == 3){
				return $dt[2] ."/" . $dt[1] ."/". $dt[0];
			}else{
				return '!e#' . $str_date;
			}	
		}
	}	
}
function datetimeToMysqlFormat($str_datetime,$invertido=false){
	if ($str_datetime=="" || $str_datetime == null) return "";
	$e = explode(" ",$str_datetime);
	$str_date = $e[0];
	$retorno = "";
	if ($invertido){
		if (strpos($str_date,"/") > 0){
			$retorno = $str_date;
		}else{
			$dt = explode("-",$str_date);
			$retorno = $dt[2] ."/" . $dt[1] ."/". $dt[0];
		}		
	}else{
		$dt = explode("/",$str_date);
		if (sizeof($dt) != 3) return $str_date;
		$retorno = $dt[2] ."-". $dt[1] ."-". $dt[0];
	}
	return $retorno . " " . $e[1];
}
function is_money($str){
	if (strpos($str, ",") > 0){
		$e = explode(",",$str);
		if (!isset($e[0]) || !isset($e[1])) return false;
		$inteiro = str_replace(".","",$e[0]);
		$decimal = $e[1];
		if (strlen($e[1]) !=2) return false;
		if (!is_numeric($inteiro) || !is_numeric($decimal)) return false;
		if (strlen($inteiro) > 0 & strlen($decimal) > 0) return true;
	}else{
		return false;
	}
	return false;
}
function moneyToFloat($str,$invertido=false){
	if ($str == '' || $str == null) return false;
	if (!$invertido){
		$str = str_replace(".","",$str);
		$str = str_replace(",",".",$str);
		return $str;
	}else{
		return number_format($str, 2, ',', '.');
	}
}
function completaString($input,$pad_length,$pad_string="0",$pad_type = STR_PAD_LEFT,$encoding = 'UTF-8'){
    $input_length 		= mb_strlen($input, $encoding);
    $pad_string_length 	= mb_strlen($pad_string, $encoding);

    if ($pad_length <= 0 || ($pad_length - $input_length) <= 0) {
        return $input;
    }

    $num_pad_chars = $pad_length - $input_length;

    switch ($pad_type) {
        case STR_PAD_RIGHT:
            $left_pad 	= 0;
            $right_pad 	= $num_pad_chars;
        break;

        case STR_PAD_LEFT:
            $left_pad 	= $num_pad_chars;
            $right_pad 	= 0;
        break;

        case STR_PAD_BOTH:
            $left_pad 	= floor($num_pad_chars / 2);
            $right_pad 	= $num_pad_chars - $left_pad;
        break;
    }

    $result = '';
    for ($i = 0; $i < $left_pad; ++$i) {
        $result .= mb_substr($pad_string, $i % $pad_string_length, 1, $encoding);
	}	
	$result .= $input;
	
    for ($i = 0; $i < $right_pad; ++$i) {
        $result .= mb_substr($pad_string, $i % $pad_string_length, 1, $encoding);
	}
    return $result;

	#return str_pad($valor,$qtde, $caracter, $direcao);
}
function existe($valor){
	if (isset($valor)) return "";
	if ($valor==null) return "";
	return $valor;
}
function addLog($instrucao){
	$arq = fopen("../../config/log-mdm.txt","a");
	fwrite($arq, trim("
/* " . date("d/m/Y H:i:s"). " */") . " " . $instrucao . "
");
	fclose($arq);
}
function formatarCEP($str,$formatar=true){
	if ($formatar){
		if (strlen($str) != 8) return $str;
		else return substr($str,0,5) . "-" . substr($str,5,3);
	}else{
		if (strlen($str) != 9) return $str;
		else return str_replace("-","",$str);
	}
}
function formatarTELEFONE($str,$formatar=true){
	if ($str == "" || $str == null) return "";
	if ($formatar){
		switch(strlen($str)){
			case 8:
				$telefone = substr($str,0,4) . "-" . substr($str,3,4);
			break;
			case 10:
				$telefone = "(".substr($str,0,2) .") " .substr($str,2,4) . "-" . substr($str,5,4);
			break;
			default:
				$telefone = "";
		}
		return $telefone;
	}else{
		return str_replace("()-","",$str);
	}	
}
function formatarCPF($str,$formatar=true){
	if ($str=="") return "";
	$str = str_replace(array("-",".","/"),"",$str);
	if ($formatar){
		if (strlen($str) == 11){
			return substr($str,0,3) . "." . substr($str,3,3) . "." . substr($str,6,3) . "-" . substr($str,9,2);
		}
	}else{
		return str_replace(array(".","-"),"",$str);
	}
}
function formatarCNPJ($str,$formatar=true){
	if ($str=="") return "";
	$str = str_replace(array("-",".","/"),"",$str);
	if ($formatar){
		if (strlen($str) == 14){
			return substr($str,0,2) . "." . substr($str,2,3) . "." . substr($str,5,3) . "/" . substr($str,8,4)  . "-" . substr($str,12,2);
		}
	}else{
		return str_replace(array(".","/","-"),"",$str);
	}
}

/*
	@Parms: DDMMYYYY
	FORMATO 1 : DD/MM/YYYY
	FORMATO 2 : YYYY-MM-DD
*/
function formatarData($str,$formato=1){
	if (strlen($str) != 8) return $str;
	switch($formato){
		case 1:
			return substr($str,0,2) . "/" .  substr($str,2,2) . "/" . substr($str,4,4);
		break;
		case 2:
			return substr($str,4,4) . "-" .  substr($str,2,2) . "-" . substr($str,0,2);
		break;
	}
}
function carregarArquivo($arquivo){
	if (file_exists($arquivo)){
		$linhas = file($arquivo);
		$conteudo = "";
		foreach($linhas as $linha){
			$conteudo .= $linha;
		}
		return $conteudo;
	}else{
		return "";
	}	
}
function dataExtenso($data){
	if ($data == "" || $data == null) return false;
	if (strpos($data,"-")){
		$dt = explode("-",$data);	
		return completaString($dt[2],2) . " de " . retornaMesExtenso($dt[1]) . " de " . $dt[0];		
	}else{
		$dt = explode("/",$data);	
		return completaString($dt[0],2) . " de " . retornaMesExtenso($dt[1]) . " de " . $dt[2];		
	}	
}
function retornaMesExtenso($mes){
	switch($mes){
		case 1: $mes = "Janeiro"; break;
		case 2: $mes = "Fevereiro"; break;
		case 3: $mes = "Março"; break;
		case 4: $mes = "Abril"; break;
		case 5: $mes = "Maio"; break;
		case 6: $mes = "Junho"; break;
		case 7: $mes = "Julho"; break;
		case 8: $mes = "Agosto"; break;
		case 9: $mes = "Setembro"; break;
		case 10: $mes = "Outubro"; break;
		case 11: $mes = "Novembro"; break;
		case 12: $mes = "Dezembro"; break;
	}
	return $mes;
}
function retornar($str_var){
	if (isset($_GET[$str_var])){
		return $_GET[$str_var];
	}else if (isset($_POST[$str_var])){
		return $_POST[$str_var];
	}else{
		return "";
	}
}
function isemail($email){
	
	$conta 		= '/^[a-zA-Z0-9\._-]+?@';
	$domino 	= '([a-zA-Z0-9_-]+?\.)*'; // dominio. ; subdominio.dominio. ;
	$gTLD 		= '[a-zA-Z]{2,6}'; //.com; .coop; .gov; .museum; etc.
	$ccTLD 		= '((\.[a-zA-Z]{2,4}){0,1})$/'; //.br; .us; .scot; etc.
	$pattern 	= $conta.$domino.$gTLD.$ccTLD;
	
	if (preg_match($pattern, $email))
		return true;
	else
		return false;
}

function getExtensao($str){
	if ($str == '') return '';
	$array = explode(".", $str);
	if (sizeof($array) > 1){
		return strtolower(end($array));
	}else{
		return '';
	}
}
function getUrl($url,$opcoes = null){
	try{
		if ($opcoes != null){
			if (isset($opcoes["params"])){
				$url .= (strpos($url,'?') === false ? '?' : '&') . http_build_query($opcoes["params"]);
			}
		}
		$cookie = isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : '';
		$opts 	= array(
			'http' => array(
				'header'		=> 'Cookie: ' .  $cookie ."\r\n",
				'method'		=> 'GET',
				'ignore_errors' => true
			)
		);
		session_write_close(); //Desboqueia o arquivo de sessão
		$context 	= stream_context_create($opts);
		$conteudo 	= file_get_contents($url,false,$context);
		session_start(); //Bloqueia o arquivo de sessão
	}catch(Exception $e){
		if (IS_SHOW_ERROR_MESSAGE){
			var_dump($e);
		}
		$conteudo = '';
	}finally{
		return $conteudo;
	}
}
function getHTMLTipoFormato($htmltipo,$valor,$entidade=0,$atributo=0,$id=0){
	
	switch((int)$htmltipo){
		case 10:
			$retorno = formatarCPF($valor);
		break;		
		case 11:										
			$retorno = dateToMysqlFormat($valor,true);										
		break;
		case 13:
			$retorno = moneyToFloat($valor,true);
		break;
		case 19:

			if (is_numeric($atributo)){
				$atributonome = tdClass::Criar("persistent",array(ATRIBUTO,$atributo))->contexto->nome;
			}else{
				$atributonome = $atributo;
			}
			$entidadeOBJ = tdClass::Criar("persistent",array(ENTIDADE,$entidade))->contexto;
			
			if (isset(tdClass::Criar("persistent",array($entidadeOBJ->nome,$id))->contexto->legenda)){
				$legenda = tdClass::Criar("persistent",array($entidadeOBJ->nome,$id))->contexto->legenda;
			}else{
				$legenda = "";
			}

			$tipoarquivo 		= $valor==''?'imagem':getCategoriaArquivo($valor);
			$srcFileName		= $atributonome . "-" . $entidade . "-" . $id . "." . getExtensao($valor);
			$srcFile 			= PATH_CURRENT_FILE . $srcFileName;
			$urlFile			= PATH_CURRENT_FILE . $srcFileName;
			$srcFileNameTemp	= $atributonome . "-" . $entidade . "-" . session_id() . "." . getExtensao($valor);
			$srcFileTemp 		= PATH_CURRENT_FILE_TEMP . $srcFileNameTemp;
			$urlFileTemp		= PATH_CURRENT_FILE . $srcFileNameTemp;
			
			if (file_exists($srcFileTemp)){
				$dadosarquivo = array(
					"tipo" 		=> $tipoarquivo,
					"src" 		=> $urlFileTemp,
					"filename" 	=> $valor,
					"legenda" 	=> $legenda,
					"alt" 		=> $legenda,
					"temp" 		=> true
				);
				$retorno =  json_encode($dadosarquivo);
			}else if (file_exists($srcFile)){
				$registro = tdClass::Criar("persistent",array($entidadeOBJ->nome,$id))->contexto->{$atributonome};
				$dadosarquivo = array(
					"tipo" 		=> $tipoarquivo,
					"src" 		=> $urlFile,
					"filename" 	=> $valor,
					"legenda" 	=> $legenda,
					"alt" 		=> $legenda,
					"temp" 		=> false
				);
				$retorno =  json_encode($dadosarquivo);
			}else{
				switch($tipoarquivo){
					case 'imagem':
						$urlFile = URL_NOIMAGE;
					break;
					default:
						$urlFile = URL_NOIMAGE;
				}
				$dadosarquivo = array(
					"tipo" 			=> $tipoarquivo,
					"src" 			=> $urlFile,
					"filename" 		=> $valor,
					"legenda" 		=> '',
					"alt" 			=> '',
					"temp" 			=> true
				);
				$retorno =  json_encode($dadosarquivo);
			}
		break;
		case 23:
		if ($valor == "0000-00-00 00:00:00") return "";
			if ($valor != ""){
				$e = explode(" ",$valor);
				if (sizeof($e) == 2){
					$retorno = dateToMysqlFormat($e[0],true) . " " . $e[1];
				}else{
					$retorno =  '!e#' . $valor;
				}
			}else{
				$retorno = "";
			}
		break;
		case 26:
			$retorno = moneyToFloat($valor,true);
		break;
		default:
			$retorno = $valor;
	}
	return $retorno;
}
function getNavegador(){
	$useragente = isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'';
	if (stripos($useragente,"Firefox") > 0){
		return 'ff'; // Firefox
	}else if (stripos($useragente,"MSIE") >0){
		return 'ie'; // Internet Explorer
	}else if (stripos($useragente,"Edg") >0){
		return 'eg'; // Edge
    }else if(stripos($useragente,"OPR") > 0){
        return 'op'; // Opera
	}else if(stripos($useragente,"Chrome") > 0){
		return 'gc'; // Google Chrome
    }else if(stripos($useragente,"Safari") > 0){
        return 'sf'; // Safari
	}else{
		return '';
	}
}
function getNomeNavegador($sigla){
    switch($sigla){
        case 'ff': return 'Firefox'; break;
        case 'ie': return 'Internet Explorer'; break;
        case 'eg': return 'Edge'; break;
        case 'op': return 'Opera'; break;
        case 'gc': return 'Ghrome'; break;
        case 'sf': return 'Safari'; break;
        default: return 'Não Identificado';
    }
}
/**
* Função para gerar senhas aleatórias
*
* @author    Thiago Belem <contato@thiagobelem.net>
*
* @param integer $tamanho Tamanho da senha a ser gerada
* @param boolean $maiusculas Se terá letras maiúsculas
* @param boolean $numeros Se terá números
* @param boolean $simbolos Se terá símbolos
*
* @return string A senha gerada
*/
function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';
	$retorno = '';
	$caracteres = '';
	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;
	$len = strlen($caracteres);
	for ($n = 1; $n <= $tamanho; $n++) {
		$rand = mt_rand(1, $len);
		$retorno .= $caracteres[$rand-1];
	}
	return $retorno;
}
function getHierarquia($entidade,$pai = ""){
	$retorno = "";
	
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("filho","=",$entidade);
	if ($pai != ""){
		$sql->addFiltro("pai","=",$pai);
	}
	$dataset = tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sql);
	foreach($dataset as $dado){
		$retorno = getHierarquia($dado->pai) . tdClass::Criar("persistent",array(ENTIDADE,$dado->pai))->contexto->nome . "-";
	}
	return $retorno;
}
function criarEntidade(
	$conn, #0
	$nome, #1
	$descricao = "", #2
	$ncolunas=1, #3
	$exibirmenuadministracao = 0, #4
	$exibircabecalho = 1, #5
	$campodescchave = "", #6
	$atributogeneralizacao = 0, #7
	$exibirlegenda = 1, #8
	$criarprojeto = 0, #9
	$criarempresa = 0, #10
	$criarauth = 0, #11
	$registrounico = 0, #12
	$carregarlibjavascript = 1, #13
	$criarinativo = true, #14
	$tipoaba = 'tabs' #15
){
	$nome 		= getSystemPREFIXO() . $nome;
	$descricao 	= tdc::utf8($descricao);
	
	$sqlExisteEntidade = "SELECT id,nome FROM " . ENTIDADE . " WHERE nome='{$nome}'";
	$queryExisteEntidade = $conn->query($sqlExisteEntidade);
	if (!$queryExisteEntidade){
		if (IS_SHOW_ERROR_MESSAGE){
			echo $sqlExisteEntidade;
			var_dump($conn->errorInfo());
		}
	}
	
	$linhaExisteEntidade = $queryExisteEntidade->fetch();

	if ($queryExisteEntidade->rowCount() <= 0){
		$entidade = getProxId("entidade",$conn);
		$sql = "INSERT INTO ".ENTIDADE." (id,nome,descricao,exibirmenuadministracao,exibircabecalho,ncolunas,atributogeneralizacao,exibirlegenda,registrounico,carregarlibjavascript,tipoaba) VALUES (".$entidade.",'{$nome}','".$descricao."',{$exibirmenuadministracao},{$exibircabecalho},{$ncolunas},{$atributogeneralizacao},{$exibirlegenda},{$registrounico},{$carregarlibjavascript},'{$tipoaba}');";
	}else{
		$entidade = $linhaExisteEntidade["id"];
		$sql = "UPDATE ".ENTIDADE." SET nome = '{$nome}',descricao = '".$descricao."',exibirmenuadministracao = {$exibirmenuadministracao},exibircabecalho = {$exibircabecalho},ncolunas = {$ncolunas},atributogeneralizacao = {$atributogeneralizacao},exibirlegenda = {$exibirlegenda},registrounico = {$registrounico},carregarlibjavascript={$carregarlibjavascript}, tipoaba='{$tipoaba}' WHERE id = {$entidade}";
	}
	
	$query = $conn->query($sql);
	if (!$query){
		if (IS_SHOW_ERROR_MESSAGE){
			echo $sql;
			var_dump($conn->errorInfo());
		}
		exit;
	}
	$sqlExisteFisicamente = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('".$nome."') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$queryExisteFisicamente = $conn->query($sqlExisteFisicamente);
	
	if ($queryExisteFisicamente->rowCount() <= 0){
		$sql = "CREATE TABLE IF NOT EXISTS {$nome}(id int not null primary key) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$query = $conn->query($sql);
		if (!$query){
			if (IS_SHOW_ERROR_MESSAGE){
				echo $sql;
				var_dump($conn->errorInfo());
			}
		}		
	}else{
		if (strtoupper($linhaExisteEntidade["nome"]) != strtoupper($nome) && $linhaExisteEntidade["nome"] != "" && $nome != ""){
			$sql = "RENAME TABLE {$linhaExisteEntidade["nome"]} TO {$nome}";
			echo $sql;
			$query = $conn->query($sql);
			if (!$query){
				if (IS_SHOW_ERROR_MESSAGE){
					echo $sql;
					var_dump($conn->errorInfo());
				}
			}
		}	
	}
	
	if ($criarprojeto == 1){
		criarAtributo($conn,$entidade,'projeto','Projeto','smallint',0,1,'16',0,installDependencia("projeto","system/projeto"),0,'session.projeto',1);
	}
	if ($criarempresa == 1){
		criarAtributo($conn,$entidade,'empresa','Empresa','smallint',0,1,'16',0,installDependencia("empresa","system/empresa"),0,'session.empresa',1);
	}
	
	if ($criarauth == 1){
		criarAtributo($conn,$entidade,'auth','Auth','varchar','45',1,'16',0,null,0);
		criarAtributo($conn,$entidade,'auth0','Auth0','varchar','45',1,'16',0,null,0);
	}
	
	if ($criarinativo){
		criarAtributo($conn,$entidade,'inativo','Inativo','boolean',0,1,7);
	}
	return $entidade;
}

function criarAtributo(
	$conn, #0
	$entidade, #1
	$nome, #2
	$descricao, #3
	$tipo, #4
	$tamanho = 0, #5
	$nulo = 0, #6
	$tipohtml = 3, #7
	$exibirgradededados = 0, #8
	$chaveestrangeira = 0, #9
	$dataretroativa = 0, #10
	$inicializacao = '', #11
	$tipoinicializacao = 1, #12
	$readonly = 0, #13
	$legenda = '', #14
	$naoexibircampo = false #15
){

	$naoexibircampo = is_bool($naoexibircampo) ? ($naoexibircampo?1:0) : 1;
	if ($tipohtml == 7){
		if (getType($descricao) == "array"){			
			$labelzerocheckbox 	= $descricao[1];
			$labelumcheckbox 	= $descricao[2];
			$descricao 			= $descricao[0]; #Não inverter essa ordem
		}else{
			$labelzerocheckbox = tdc::utf8("Não");
			$labelumcheckbox = "Sim";
		}
	}else{
		$labelzerocheckbox = "";
		$labelumcheckbox = "";	
	}

	$descricao 			= tdc::utf8($descricao);
	$nuloSQL			= ((int)$nulo==0)?'NOT NULL':'NULL';
	$chaveestrangeira 	= ($chaveestrangeira=="")?0:($chaveestrangeira);		
	$inicializacao 		= str_replace("'","\'",$inicializacao);
	if ($tipo == "varchar" || $tipo == "char" ){
		if ($tamanho == '' || (int)$tamanho == 0){
			$tamanhoSQL = "(0)";
		}else{
			$tamanhoSQL = "({$tamanho})";
		}
	}else{
		$tamanhoSQL = '';
	}
	
	$tamanho 					= $tamanho == ''?0:$tamanho;	
	$entidadeentidadedefault 	= ENTIDADE;
	$entidadeatributodefault 	= ATRIBUTO;
	$PREFIXO 					= getSystemPREFIXO();

	$sqlExisteAtributo 			= "SELECT id FROM {$entidadeatributodefault} WHERE nome='{$nome}' AND entidade={$entidade};";
	$queryExisteAtributo 		= $conn->query($sqlExisteAtributo);
	if (!$queryExisteAtributo){
		if (IS_SHOW_ERROR_MESSAGE){
			echo $sqlExisteAtributo;
			var_dump($conn->errorInfo());
		}
	}
	
	try{
		$sql = "SELECT nome FROM {$entidadeentidadedefault} WHERE id = {$entidade}";
		$query = $conn->query($sql);	
	}catch(Throwable $t){
		if (IS_SHOW_ERROR_MESSAGE){
			echo $sql;
			var_dump($conn->errorInfo());
		}
	}
	
	$linha = $query->fetchAll();		
	if ($queryExisteAtributo->rowCount() <= 0){		
		$id = getProxId("atributo",$conn);
		$sql = "
			INSERT INTO {$entidadeatributodefault} 
			(
				id,
				entidade,
				nome,
				descricao,
				tipo,
				tamanho,
				nulo,
				tipohtml,
				exibirgradededados,
				chaveestrangeira,
				dataretroativa,
				inicializacao,
				tipoinicializacao,
				labelzerocheckbox,
				labelumcheckbox,
				readonly,
				legenda,
				naoexibircampo
			) VALUES (
				".$id.",
				{$entidade},
				'{$nome}',
				'".$descricao."',
				'{$tipo}',
				'{$tamanho}',
				{$nulo},
				'{$tipohtml}',
				{$exibirgradededados},
				{$chaveestrangeira},
				{$dataretroativa},
				'{$inicializacao}',
				{$tipoinicializacao},
				'{$labelzerocheckbox}',
				'{$labelumcheckbox}',
				{$readonly},
				'{$legenda}',
				{$naoexibircampo}
			);";
		$query = $conn->query($sql);
		if ($query){
			try{
				$sql = "ALTER TABLE {$linha[0]["nome"]} ADD COLUMN {$nome} {$tipo}{$tamanhoSQL} {$nuloSQL};";
				$criar = $conn->query($sql);
			}catch(Throwable $t){

			}
		}else{
			if (IS_SHOW_ERROR_MESSAGE){
				echo $sql;
				var_dump($conn->errorInfo());
			}
		}
	}else{
		$linhaExistequeAtributo = $queryExisteAtributo->fetch();
		$id = $linhaExistequeAtributo["id"];
		
		// Pega os dados antigos da entidade para alterar
		$sql_old = "SELECT nome FROM {$entidadeatributodefault} WHERE id = {$id}";
		$query_old = $conn->query($sql_old);
		if (!$query_old){
			if (IS_SHOW_ERROR_MESSAGE){
				echo $sql_old;
				var_dump($conn->errorInfo());
			}
		}
		$linha_old = $query_old->fetchAll();
		
		$sql = "
			UPDATE {$entidadeatributodefault} 
			SET 
				entidade={$entidade},
				nome='{$nome}',
				descricao='{$descricao}',
				tipo='{$tipo}',
				tamanho='{$tamanho}',
				nulo={$nulo},
				tipohtml='{$tipohtml}',
				exibirgradededados={$exibirgradededados},
				chaveestrangeira={$chaveestrangeira},
				dataretroativa={$dataretroativa},
				inicializacao='{$inicializacao}',
				tipoinicializacao={$tipoinicializacao},
				labelzerocheckbox='{$labelzerocheckbox}',
				labelumcheckbox='{$labelumcheckbox}',
				readonly={$readonly},
				legenda='{$legenda}',
				naoexibircampo={$naoexibircampo}
			WHERE id={$id};
		";
		$query = $conn->query($sql);
		if ($query){
			$sqlFisicamante = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$linha[0]["nome"]}' AND  COLUMN_NAME = '{$nome}';";
			$queryFisicamante = $conn->query($sqlFisicamante);
			if (!$queryFisicamante){
				if (IS_SHOW_ERROR_MESSAGE){
					echo $sqlFisicamente;
					var_dump($conn->errorInfo());
				}
			}
			
			if ($queryFisicamante->rowCount() > 0 ){
				try{
					$sql = "ALTER TABLE {$linha[0]["nome"]} CHANGE {$linha_old[0]['nome']} {$nome} {$tipo}{$tamanhoSQL} {$nuloSQL};";
					$atualizar = $conn->query($sql);
				}catch(Throwable $t){
					if (IS_SHOW_ERROR_MESSAGE){
						echo $sql;
						var_dump($t->getMessage());
					}
					exit;
				}
				if (!$atualizar){
					$sql = "ALTER TABLE {$linha[0]["nome"]} ADD COLUMN {$nome} {$tipo}{$tamanhoSQL} {$nuloSQL};";
					$inserir = $conn->query($sql);
					if (!$inserir){
						if (IS_SHOW_ERROR_MESSAGE){
							echo $sql;
							var_dump($conn->errorInfo());
						}
					}
				}
			}else{
				$sql = "ALTER TABLE {$linha[0]["nome"]} ADD COLUMN {$nome} {$tipo}{$tamanhoSQL} {$nuloSQL};";
				$criar = $conn->query($sql);
				if (!$criar){
					if (IS_SHOW_ERROR_MESSAGE){
						echo $sql;
						var_dump($conn->errorInfo());
					}
				}
			}
		}else{
			if (IS_SHOW_ERROR_MESSAGE){
				echo $sql;
				var_dump($conn->errorInfo());
			}
		}
	}
	
	ordenarAtributo($id);

	return $id;
}
function getProxId($entidade,$conn = null){
	if ($conn == null) global $conn;
	$entidadeName 	= getSystemPREFIXO() . str_replace(getSystemPREFIXO(),"",$entidade);

	$sql 	= 'SELECT IFNULL(MAX(id),0) + 1 FROM ' . $entidadeName;
	$query 	= $conn->query($sql);
	if (!$query){
		if (IS_SHOW_ERROR_MESSAGE){
			echo $sql;
			var_dump($conn->errorInfo());
		}
	}
	$prox = $query->fetch(PDO::FETCH_BOTH);
	return $prox[0];
}
function addMenu(
	$conn, #0 
	$descricao, #1
	$link = "#", #2
	$target = "", #3
	$pai = 0, #4
	$ordem = 0, #5
	$fixo = "" , #6
	$entidade = 0, #7
	$tipomenu = "" #8
){
	$pai		= $pai == '' ? 0 : $pai;
	$descricao 	= tdc::utf8($descricao);
	$sql 		= "SELECT id FROM " . MENU. " WHERE fixo = '".$fixo."';";
	$query 		= $conn->query($sql);
	if ($query->rowCount() > 0){
		$linha = $query->fetch();
		$menu_webiste = $linha["id"];
		$sqlMenu = 	"UPDATE " . MENU ." SET descricao = '".$descricao."',link = '".$link."',target = '".$target."',pai = ".$pai.",fixo = '".$fixo."' , entidade = {$entidade} , tipomenu = '{$tipomenu}' WHERE id = ".$menu_webiste.";";
	}else{
		$menu_webiste = getProxId("menu",$conn);
		if ($ordem == 0){
			$sqlOrdem 	= "SELECT IFNULL(MAX(ordem),0) + 1 ordem FROM " . MENU . " WHERE fixo = '{$target}';";
			$queryOrdem = $conn->query($sqlOrdem);
			$linhaOrdem = $queryOrdem->fetch();
			$ordem 		= $linhaOrdem["ordem"];
		}
		$sqlMenu = 	"INSERT INTO ". MENU." (id,descricao,link,target,pai,ordem,fixo,entidade,tipomenu) VALUES 
		(".$menu_webiste.",'".$descricao."','".$link."','".$target."',".$pai.",'".$ordem."','".$fixo."',".$entidade.",'".$tipomenu."');";
	}
	try{
		if ($conn->exec($sqlMenu)){
			addMenuPermissao($menu_webiste);
		}
	}catch(Throwable $t){
		if (IS_SHOW_ERROR_MESSAGE) var_dump($sqlMenu);
	}

	return $menu_webiste;
}

function addMenuPermissao(
	int $menu,
	int $usuario = null,
	$permissao = 1
){
	global $conn;	
	if ($usuario == null) $usuario = isset($_SESSION["userid"])?$_SESSION["userid"]:1;
	$idMP = installDependencia("menupermissoes","system/menupermissoes");
	$sqlv = "SELECT id FROM td_menupermissoes WHERE menu = {$menu} AND usuario = {$usuario};";
	$queryv = $conn->query($sqlv);
	if ($queryv->rowCount() > 0){
		$linhav = $queryv->fetch();
		$id = $linhav["id"];
		$sql = "UPDATE td_menupermissoes SET permissao = {$permissao} WHERE id = {$id};";
	}else{
		$id = getProxId("menupermissoes");
		$sql = "INSERT INTO td_menupermissoes (id,projeto,empresa,menu,usuario,permissao) VALUES ({$id},1,1,{$menu},$usuario,$permissao);";
	}
	if ($conn->exec($sql)){
		return true;
	}else{
		return false;
	}
}

function criarAba(
$conn, #0
$entidade, #1
$descricao, #2
$atributos #3
){
	$sql_verificar = "SELECT id FROM ".ABAS." WHERE descricao = '{$descricao}' AND entidade = " . $entidade;
	$query_verificar = $conn->query($sql_verificar);
	if (!$query_verificar){
		if (IS_SHOW_ERROR_MESSAGE){
			echo $sql_verificar;
			var_dump($conn->errorInfo());
		}
	}
	$linha_verificar = $query_verificar->fetch();
	$descricao = tdc::utf8($descricao);
	if (gettype($atributos) == "array"){
		$atributos = arrayToString($atributos);
	}
	if ($query_verificar->rowCount() > 0){
		$id = $linha_verificar[0];
		$sql = "UPDATE ".ABAS." SET descricao = '{$descricao}' , atributos = '{$atributos}' WHERE id = {$id};";
	}else{
		$id = getProxId("abas",$conn);
		$sql = "INSERT INTO ".ABAS." (id,entidade,descricao,atributos) values ({$id},{$entidade},'{$descricao}','{$atributos}');";
	}
	$query = $conn->query($sql);
	if ($query){
		return $id;
	}else{
		return false;
	}
}

function getEntidadeId($entidadeString,$conn = null){
	$conn 		= getCurrentConnection();
	$PREFIXO 	= getSystemPREFIXO();
	if ($entidadeString == "" || $entidadeString == null){
		return 0;
	}else{
		try{
			if ($PREFIXO == substr($entidadeString,0,3)) $PREFIXO = '';
			$sql = "SELECT id FROM ".ENTIDADE." WHERE nome = '".$PREFIXO.$entidadeString."';";
			$query = $conn->query($sql);
			if ($query->rowCount() > 0){
				$linha = $query->fetch();
				return (int)$linha["id"];
			}else{
				return 0;
			}
		}catch(Throwable $t){
			echo $sql . "<br/>";
		}
	}
}
function getAtributoId($entidadeString,$atributoString,$conn = null){
	$conn 		= getCurrentConnection();
	$PREFIXO 	= getSystemPREFIXO();
	$_id 		= 0;
	if ($entidadeString != "" && $entidadeString != null && $atributoString != "" && $atributoString != null){
		$entidadeString = str_replace($PREFIXO,"",$entidadeString);
		try{
			$sql = "SELECT id FROM ".ATRIBUTO." WHERE ".ATRIBUTO_ENTIDADE." = ".getEntidadeId($entidadeString,$conn)." AND nome = '".$atributoString."'";
			$query = $conn->query($sql);		
			if ($query->rowCount() > 0){
				$linha 	= $query->fetch();
				$_id 	= $linha["id"];
			}
		}catch(Throwable $t){
			if (IS_SHOW_ERROR_MESSAGE){
				var_dump($t->getMessage());
			}
		}
	}
	return $_id;
}
function criarRelacionamento($conn,$tipo,$entidadePai,$entidadeFilho,$descricao = "",$atributo = 0){
	$descricao		= tdc::utf8($descricao);
	$cardinalidade 	= getCardinalidade($tipo);
	$sqlVerifica 	= "SELECT id FROM ".RELACIONAMENTO." WHERE pai = " . $entidadePai . " AND filho = " . $entidadeFilho . " AND tipo = " . $tipo;
	$queryVerifica 	= $conn->query($sqlVerifica);
	if ($queryVerifica->rowcount() > 0){
		$linhaVerifica 	= $queryVerifica->fetch();
		$idRetorno 		= $linhaVerifica["id"];
		$sql = "UPDATE " . RELACIONAMENTO . " SET descricao = '$descricao' , atributo = $atributo , cardinalidade = '{$cardinalidade}' WHERE id = " . $idRetorno;
	}else{
		$idRetorno = getProxId("relacionamento",$conn);		
		$sql = "INSERT INTO " . RELACIONAMENTO . " (id,descricao,tipo,pai,filho,atributo,cardinalidade) 
				VALUES (".$idRetorno.",'".$descricao."',".$tipo.",".$entidadePai.",".$entidadeFilho.",".$atributo.",'{$cardinalidade}');";
	}
	try{
		$query = $conn->query($sql);
		return $idRetorno;
	}catch(Throwable $t){
		if (IS_SHOW_ERROR_MESSAGE){
			echo $sql;
			var_dump($conn->errorInfo());
		}
		return 0;
	}	
}
function getCardinalidade($tipo){
	switch($tipo){

		case 1: return '11'; break;
		case 3: return '11'; break;
		case 4: return '11'; break;
		case 7: return '11'; break;
		case 9: return '11'; break;

		case 2: return '1N'; break;
		case 6: return '1N'; break;
		case 8: return '1N'; break;

		case 5: return 'NN'; break;
		case 10: return 'NN'; break;
	}
}
function retirarAcentos($string){
    return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/", "/(ª|º)/"),explode(" ","a A e E i I o O u U n N c C"),$string);
}
function qtdePaiMenu($menu,$conn){
	$sql = "SELECT IFNULL(pai,0) pai FROM ".MENU." WHERE id = " . $menu;
	$query = $conn->query($sql);
	$linha = $query->fetch();
	if ($linha["pai"] == 0){
		return 0;
	}else{
		return qtdePaiMenu($linha["pai"],$conn) + 1;
	}
}
function criarTabelaDicionario($conn,$nome){
	$sqlExisteFisicamente = "SELECT 1 FROM INFORMATION_SCHEMA.TABLES WHERE UPPER(TABLE_NAME) = UPPER('".$nome."') AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$queryExisteFisicamente = $conn->query($sqlExisteFisicamente);

	if ($queryExisteFisicamente->rowCount() <= 0){
		$sql = "CREATE TABLE IF NOT EXISTS {$nome}(id INT NOT NULL PRIMARY KEY) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$conn->query($sql);
	}
	
}
function criarCampoDicionario($conn,$tabela,$nome,$tipo,$tamanho = 0,$nulo = 0){
	$sqlFisicamante = "SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '{$tabela}' AND  COLUMN_NAME = '{$nome}' AND UPPER(TABLE_SCHEMA) = UPPER('".SCHEMA."')";
	$queryFisicamante = $conn->query($sqlFisicamante);

	if ($tipo == "varchar" || $tipo == "char"){
		$tamanho = "(".$tamanho.")";
	}else{
		$tamanho = '';
	}
	$nulo =  $nulo==0?"NOT NULL":"NULL";

	if ($queryFisicamante->rowCount() <= 0 ){		
		$sql = 'ALTER TABLE '. $tabela.' ADD COLUMN '.$nome.' '.$tipo.$tamanho.' '.$nulo;
	}else{
		$sql = "ALTER TABLE {$tabela} CHANGE {$nome} {$nome} {$tipo}{$tamanho} {$nulo};";
	}
	$query = $conn->query($sql);
	
	if (!$query){
		if (IS_SHOW_ERROR_MESSAGE){
			var_dump($conn->errorInfo());
		}
	}
}


function inserirRegistro($conn,$tabela,$id,$atributos,$valores,$criarnovoregistro=false){
	if ($id == '' || $id == null){
		$id = getEntidadeId($tabela);
	}

	// Força a criação de um novo registro registro
	// independentemente do ID
	if ($criarnovoregistro){
		$id = getProxId(str_replace("td_","",$tabela),$conn);
	}else {
		if (is_numeric($id)){
			$sql 	= "SELECT 1 FROM " . $tabela . " WHERE id = " . $id . ";";
			$query 	= $conn->query($sql);
			if ($query->rowCount() > 0){
				// Atualiza a informação caso o ID já exista
				atualizarRegistro($conn,$tabela,$id,$atributos,$valores);
				return $id;
			}			
		}
	}

	try{
		$valores_i	= implode(",",$valores);
		$valores_ 	= tdc::utf8($valores_i);
		$sqlInserir = "INSERT " . $tabela . " (id,".implode(",",$atributos).") VALUES (".$id.",".$valores_.");";
		$query 		= $conn->query($sqlInserir);
		return $id;
	}catch(Throwable $t){
		if (IS_SHOW_ERROR_MESSAGE){
        	echo $sqlInserir;
        	var_dump($conn->errorInfo());
		}
        return false;
	}
}
function atualizarRegistro($conn,$tabela,$id = "",$atributos = [],$valores = [],$atualizarnulo=false){
	$campos = "";
	$_atributos 	= gettype($atributos) == 'string' ? explode(',',$atributos) : $atributos;
	$_valores		= gettype($valores) == 'string' ? explode(',',$valores) : $valores;
	$totalAtributos = sizeof($_atributos) - 1;
	for($i=0;$i<=$totalAtributos;$i++){
		$campos .= $_atributos[$i] . " = " . tdc::utf8($_valores[$i]);
		if ($i != $totalAtributos) $campos .= ",";
	}
	try{
		$sqlInserir = "UPDATE " . $tabela . " SET " . $campos . " WHERE 1=1 " . (!$atualizarnulo?"":" AND " . $_atributos[$i] . " IS NOT NULL ") . ($id == ""?"":" AND id = " . $id) . "";
		$query = $conn->query($sqlInserir);
	}catch(Throwable $t){
		if (IS_SHOW_ERROR_MESSAGE){
        	echo $sqlInserir;
        	var_dump($conn->errorInfo());
		}
        return false;
	}
}
function clonarAtributo($atributo,$conn){

	$sql = "SELECT * FROM ".ATRIBUTO." WHERE id = {$atributo}";
	$query = $conn->query($sql);
	if ($query->rowCount() >0){
		$linha = (object)$query->fetch();
		
		$newAtributo = getProxId("atributo",$conn);
		$sql = "INSERT INTO ".PREFIXO."atributo (id,".PREFIXO."entidade,nome,descricao,tipo,tamanho,nulo,tipohtml,exibirgradededados,chaveestrangeira,dataretroativa,inicializacao,tipoinicializacao,labelzerocheckbox,labelumcheckbox,readonly) 
		VALUES (".$newAtributo.",{$linha->entidade},'{$linha->nome}','".($linha->descricao)."','{$linha->tipo}','{$linha->tamanho}',{$linha->nulo},'{$linha->tipohtml}',{$linha->exibirgradededados},{$linha->chaveestrangeira},{$linha->dataretroativa},'{$linha->inicializacao}',{$linha->tipoinicializacao},'','',0);";
		$query = $conn->query($sql);
		
	}else{
		$newAtributo = 0;
	}
	return $newAtributo;
}
function isCPF($str){
	if ($str == "" || $str == null) return false;
	$str = str_replace(array("-",".","/"),"",$str);
	
	switch($str){
		case '00000000000':
		case '11111111111':
		case '22222222222':
		case '33333333333':
		case '44444444444':
		case '55555555555':
		case '66666666666':
		case '77777777777':
		case '88888888888':
		case '99999999999':
			return false;
		break;
	}
	
	if (strlen($str) == 11){
		$padrao = '/^[0-9]{11}$/';
		if (preg_match($padrao,$str)){
		   return true;
		}else{
		   return false;
		}
	}else{
		return false;
	}
}
function isCNPJ($str){
	if ($str == "" || $str == null) return false;
	$str = str_replace(array("-",".","/"),"",$str);
	
	switch($str){
		case '00000000000000':
		case '11111111111111':
		case '22222222222222':
		case '33333333333333':
		case '44444444444444':
		case '55555555555555':
		case '66666666666666':
		case '77777777777777':
		case '88888888888888':
		case '99999999999999':
			return false;
		break;
	}
	if (strlen($str) == 14){
		$padrao = '/^[0-9]{14}$/';
		if (preg_match($padrao,$str)){
		   return true;
		}else{
		   return false;
		}
	}
}

function isCPFJ($str){
	if (isCNPJ($str) || isCPF($str)){
		return true;
	}else{
		return false;
	}
}

function isCEP($str){
	if ($str == "" || $str == null) return false;
	$str = str_replace(array("-",".","/"," "),"",$str);
	if (strlen($str) == 8){
		$padrao = '/^[0-9]{8}$/';
		if (preg_match($padrao,$str)){
		   return true;
		}else{
		   return false;
		}
	}
}

function isUFSigla($str){
	if ($str == "" || $str == null) return false;
	$padrao = '/^[A-Z]{2}$/';
	if (preg_match($padrao,$str)){
	   return true;
	}else{
	   return false;
	}
}

function getConnectionDataBaseMaster(){
	return null;
	// Define o código de cliente
	if (!defined("CODIGOCLIENTE"))
		define("CODIGOCLIENTE",isset($config["CLIENTE"])?$config["CLIENTE"]:0);

}

function getMilesDataBase(){
	// Conexão com a base de dados da Teia
	$bdMiles = "config/miles_mysql.ini";

	if (file_exists($bdMiles)){
		// Conecta nabase da Estilo Site		
		$bdConfigMILES = parse_ini_file($bdMiles);

		$usuario 	= $bdConfigMILES["usuario"];
		$senha 		= $bdConfigMILES["senha"];
		$base 		= $bdConfigMILES["base"];
		$host		= $bdConfigMILES["host"];
		$tipo		= $bdConfigMILES["tipo"];
		$porta		= $bdConfigMILES["porta"];
	}else{
		echo tdc::utf8('Não existe arquivo de configuração com o banco de dados da MILES.');
		exit;
	}

	// Conexão PDO com o banco da Estilo Site
	$connMILES = new PDO("$tipo:host=$host;port=$porta;dbname=$base",$usuario,$senha);

	if (!$connMILES){
		echo tdc::utf8('Não foi possível conectar no banco de dados da MILES.');
	}

	return $connMILES;
}
function getRegistro($conn = null,$entidade = null,$campos = "*",$where = "",$propriedades = ""){
	try{
		if ($conn == null){
			global $conn;
			if ($conn == null){
				$conn = Transacao::get();
			}
		}
		if (!$entidade || $entidade == null){
			return false;
		}
		$sql = "SELECT {$campos} FROM {$entidade} WHERE {$where} {$propriedades}";
		$query = $conn->query($sql);
		return $query->fetch();
	}catch(Throwable $t){
		echo $sql;
		return false;
	}
}
function isAtributoDependenciaPai($attr){
	$conn = Transacao::Get();
	
	$atributo = tdClass::Criar("persistent",array(ATRIBUTO,$attr))->contexto;
	if ($atributo == null) return false;
	$sql = "SELECT 1 FROM ".ATRIBUTO." WHERE entidade = ".$atributo->entidade . " AND atributodependencia = " . $attr;
	$query = $conn->query($sql);
	if ($query->rowCount() > 0){
		return true;
	}else{
		return false;
	}
}
function getConfigFile(){
	$config = parse_ini_file("config/default_config.inc");
	return $config;
}
function getCurrentConfigFile(){
	if (file_exists(FILE_CURRENT_CONFIG_PROJECT)){
		$config = parse_ini_file(FILE_CURRENT_CONFIG_PROJECT);
	}else{
		$config = [];
	}
	return $config;
}
function createDirectory($dir){
	$ccf 	= getCurrentConfigFile();
	$raiz	= "/" . $ccf["PROJETO_FOLDER"] . "/";
	$path 	= $raiz . $dir;
	if (!file_exists($path)){
		$dirpart = explode("/",$dir);
		$dircreate = "";
		for($i=0;$i<sizeof($dirpart);$i++){
			$dircreate .=  $dirpart[$i] . "/";
			if (!file_exists($dircreate)){
				if (!tdFile::mkdir($dircreate)){
					echo 'Erro ao criar diretório => ' . $dircreate;
				}
			}
		}
	}
}
function getDescTipoConnection($typedatabase = null){
    $typedatabase = $typedatabase==null?DATABASECONNECTION:$typedatabase;
	if (is_numeric($typedatabase)){
		switch($typedatabase){
			case 1: $tipo = "desenv"; break;
			case 2: $tipo = "teste"; break;
			case 3: $tipo = "homologacao"; break;
			case 4: $tipo = "producao"; break;
			default: $tipo = "desenv";
		}
	}else{
		switch($typedatabase){
			case "desenv": $tipo = 1; break;
			case "teste": $tipo = 2; break;
			case "homologacao": $tipo = 3; break;
			case "producao": $tipo = 4; break;
			default: $tipo = 1;
		}
	}
	return $tipo;
}
function parte_string($texto,$inicial,$final){
		$pos_inicial 	= (int)(strpos($texto,$inicial,1) + strlen($inicial));
		$pos_final		= (int)strpos($texto,$final,1) - $pos_inicial;
		return  substr($texto,$pos_inicial,$pos_final);	
}
function conteudo_tag($texto,$tag){
	$tags_inicial 	= "<$tag>";	
	if (!is_int(strpos($texto,$tags_inicial))) return "";
		
	$tag_final		= "</$tag>";
	if (!is_int(strpos($texto,$tag_final))) return "";
	return parte_string($texto,$tags_inicial,$tag_final);
}
function setConfigFileDefault(){
	$configpathdefault = "config/default_config.inc";
	$configpathcurrent = "config/current_config.inc";
	copy($configpathdefault,$configpathcurrent);
}
function setCurrentFileDatabseDefault(){
	$mysqlDefault = parse_ini_file("config/default_mysql.ini");	
	setCurrentFileDatabse(
		array(
			"host" 		=> $mysqlDefault["host"],
			"base" 		=> $mysqlDefault["base"],
			"porta"		=> $mysqlDefault["porta"],
			"usuario" 	=> $mysqlDefault["usuario"],
			"senha" 	=> $mysqlDefault["senha"],
			"tipo"		=> $mysqlDefault["tipo"]
		)
	);
}
function setCurrentConfigFile($atualizar,$replace = true){
	$pathconfig = "project/config/";
	$fileconfig = "current_config.inc";
	$pathfinal	= $pathconfig . $fileconfig;
	if (!file_exists($pathconfig)){
		tdFile::mkdir($pathconfig);
	}
	if ($replace){
		$fp = fopen($pathfinal,"w");
		foreach($atualizar as $n => $k){
			if ($n != '' && $atualizar[$n] != ''){
				fwrite($fp,$n."=".$atualizar[$n]."\r\n");
			}
		}
		fclose($fp);	
	}else{
		$config = parse_ini_file($pathfinal);
		$valores = array_intersect_key($config, $atualizar);
		$new = array();
		foreach($config as $c => $k){
			if (array_key_exists($c,$atualizar)){
				$new[$c] = $atualizar[$c];
			}else{
				$new[$c] = $config[$c];
			}
		}

		$fp = fopen(PATH_CURRENT_CONFIG_PROJECT,"w");
		foreach($new as $n => $k){
			if ($n != '' && $new[$n] != ''){
				fwrite($fp,$n."=".$new[$n]."\r\n");
			}
		}
		fclose($fp);
	}	
}
function setCurrentFileDatabse($config){
	$usuario = $senha = $base = $host = $tipo = $porta = "";	
	$bdMysqlCurrent = "config/current_mysql.ini";
	$bdMysqlDefault = "config/default_mysql.ini";

	$configCurrent = parse_ini_file("config/current_config.inc");
	$type = $configCurrent["CURRENT_DATABASE"];

	$connMILES = getMilesDataBase();
	$sql = "SELECT * FROM td_connectiondatabase WHERE projeto = " . $configCurrent["CURRENT_PROJECT"] . " AND type = " . getDescTipoConnection($type);
	$query = $connMILES->query($sql);

	if ($linha = $query->fetch()){
		$usuario = $linha["user"];
		$senha = $linha["password"];
		$base = $linha["base"];
		$host = $linha["host"];
		$tipo = "mysql";
		$porta = $linha["port"];
	}else{
		$usuario = $config["usuario"];
		$senha = $config["senha"];
		$base = $config["base"];
		$host = $config["host"];
		$tipo = "mysql";
		$porta = $config["porta"];
	}

	$fp = fopen($bdMysqlCurrent,"w+");
	fwrite($fp,"usuario=".$usuario."\r\n");
	fwrite($fp,"senha=".$senha."\r\n");
	fwrite($fp,"base=".$base."\r\n");
	fwrite($fp,"host=".$host."\r\n");
	fwrite($fp,"tipo=".$tipo."\r\n");
	fwrite($fp,"porta=".$porta."\r\n");
	fclose($fp);		

}

function setAtributoGeneralizacao($conn,$entidade,$atributo){
	$sql = "UPDATE ".ENTIDADE."  SET atributogeneralizacao = " . $atributo . " WHERE id = " . $entidade;
	$conn->exec($sql);
}

// Inclui e instala uma entidade na instalação
function installDependencia($entidade_nome_install = "",$package = "package/sistema/"){
	global $conn;
	$pathfile = PATH_INSTALL . $package . ".php";
	if (file_exists($pathfile)){	
		include_once $pathfile;
		return getEntidadeId($entidade_nome_install,$conn);
	}else{
		echo 'Arquivo da entidade [ <b>'.$entidade_nome_install.'</b> ] não encotrado => ' . $pathfile . '<br/>\n';
		return 0;
	}
}
// Funзгo que retorna o texto em formato HTML Especial
function htmlespecialcaracteres($string,$tipo){
	$html = array('&Aacute;'	,'&aacute;'		,'&Acirc;'		,'&acirc;'		,'&Agrave;'		,'&agrave;'		,'&Aring;'		,'&aring;'		,'&Atilde;'		,'&atilde;'		,'&Auml;'		,'&auml;'		,'&AElig;'		,'&aelig;'		,'&Eacute;'		,'&eacute;'		,'&Ecirc;'		,'&ecirc;'		,'&Egrave;'		,'&egrave;'		,'&Euml;'		,'&euml;'		,'&ETH;'		,'&eth;'		,'&Iacute;'		,'&iacute;'		,'&Icirc;'		,'&icirc;'		,'&Igrave;'		,'&igrave;'		,'&Iuml;'		,'&iuml;'		,'&Oacute;'		,'&oacute;'		,'&Ocirc;'		,'&ocirc;'		,'&Ograve;'		,'&ograve;'		,'&Oslash;'		,'&oslash;'		,'&Otilde;'		,'&otilde;'		,'&Ouml;'		,'&ouml;'		,'&Uacute;'		,'&uacute;'		,'&Ucirc;'		,'&ucirc;'		,'&Ugrave;'		,'&ugrave;'		,'&Uuml;'		,'&uuml;'		,'&Ccedil;'		,'&ccedil;'		,'&Ntilde;'		,'&ntilde;'		,'&Yacute;'		,'&yacute;'		,'&quot;'		,'&lt;'		,'&gt;'		,'&amp;'		,'&reg;'		,'&copy;'		,'&THORN;'		,'&thorn;'		,'&szlig;'	,'&#176;'	,''		,''		,''		,'' 		,''			);
	$char = array('Б'			,'б'			,'В'			,'в'			,'А'			,'а'			,'Е'			,'е'			,'Г'			,'г'			,'Д'			,'д'			,'Ж'			,'ж'			,'Й'			,'й'			,'К'			,'к'			,'И'			,'и'			,'Л'			,'л'			,'Р'			,'р'			,'Н'			,'н'			,'О'			,'о'			,'М'			,'м'			,'П'			,'п'			,'У'			,'у'			,'Ф'			,'ф'			,'Т'			,'т'			,'Ш'			,'ш'			,'Х'			,'х'			,'Ц'			,'ц'			,'Ъ'			,'ъ'			,'Ы'			,'ы'			,'Щ'			,'щ'			,'Ь'			,'ь'			,'З'			,'з'			,'С'			,'с'			,'Э'			,'э'			,'"'			,'<'		,'>'		,'&'			,'®'			,'©'			,'Ю'			,'ю'			,'Я'		,'є'		,''		,' а'	,'а '	,' а '		,''			);
	$estr = array('ГѓВЃ'		,'ГѓВЎ'			,'ГѓвЂљ'		,'ГѓВў'			,'А'			,'а'			,'Е'			,'е'			,'ГѓЖ’'			,'ГѓВЈ'			,'Д'			,'д'			,'Ж'			,'ж'			,'ГѓвЂ°'		,'ГѓВ©'			,'К'			,'ГѓВЄ'			,'И'			,'и'			,'Л'			,'л'			,'Р'			,'р'			,'Н'			,'ГѓВ­'			,'О'			,'о'			,'М'			,'м'			,'П'			,'п'			,'У'			,'ГѓВі'			,'Ф'			,'ГѓВґ'			,'Т'			,'т'			,'Ш'			,'ш'			,'Х'			,'ГѓВµ'			,'Ц'			,'ц'			,'ГѓЕЎ'			,'ГѓВє'			,'Ы'			,'ы'			,'Щ'			,'щ'			,'Ь'			,'ГѓВј'			,'ГѓвЂЎ'		,'ГѓВ§'			,'С'			,'с'			,'Э'			,'э'			,'"'			,'<'		,'>'		,'&'			,'®'			,'©'			,'Ю'			,'ю'			,'Я'		,'є'		,'Г‚В'	,' ГѓВ'	,'ГѓВ '	,' ГѓВ '	,'Г†вЂ™'	);
	// Ainda não testei com ISO
	$iso  = array('Á'			,'á'			,'Â'			,'â'			,'À'			,'à'			,'Å'			,'å'			,'Ã'			,'ã'			,'Ä'			,'ä'			,'Æ'			,'æ'			,'É'			,'é'			,'Ê'			,'ê'			,'È'			,'è'			,'Ë'			,'ë'			,'Ð'			,'ð'			,'Í'			,'í'			,'Î'			,'î'			,'Ì'			,'ì'			,'Ï'			,'ï'			,'Ó'			,'ó'			,'Ô'			,'ô'			,'Ò'			,'ò'			,'Ø'			,'ø'			,'Õ'			,'õ'			,'Ö'			,'ö'			,'Ú'			,'ú'			,'Û'			,'û'			,'Ù'			,'ù'			,'Ü'			,'ü'			,'Ç'			,'ç'			,'Ñ'			,'ñ'			,'®'			,'©'			,'"'			,'<'		,'>'		,'&'			,'®'			,'®'			,''				,''				,''			,''			,''		,''		,''		,''			,''			);
	
	$retorno = "";
	if ($tipo == 1){
		
		$primeiraletra = substr($string,0,1);
		$ultimaletra = substr($string,(strlen($string)-1),1);
		$istag = false;
		if ($primeiraletra == '<' && $ultimaletra == '>'){
			$istag = true;
		}		
		
		if (!$istag){
			for ($i=0;$i<sizeof($html);$i++){
				$string = str_replace($char[$i],$html[$i],$string);
			}
		}	
	}else if ($tipo == 2){		
		for ($i=0;$i<sizeof($html);$i++){
			$string = str_replace($html[$i],$char[$i],$string);
		}	
	}else if ($tipo == 3){		
		for ($i=0;$i<sizeof($html);$i++){
			$string = str_replace($estr[$i],$char[$i],$string);
		}			
	}else if ($tipo == 4){		
		for ($i=0;$i<sizeof($html);$i++){
			$string = str_replace($char[$i],$estr[$i],$string);
		}					
	}else if ($tipo == 5){		
		for ($i=0;$i<sizeof($html);$i++){
			$string = str_replace($estr[$i],$html[$i],$string);
		}							
	}else if ($tipo == 6){
		for ($i=0;$i<sizeof($char);$i++){
			$string = str_replace($estr[$i],$char[$i],$string);
		}
	}else if ($tipo == 7){
		for ($i=0;$i<sizeof($html);$i++){
			$string = str_replace($html[$i],$iso[$i],$string);
		}		
		
	}
	return $string;
}

function getListaRegFilho($entidadepai,$entidadefilho,$regpai){	
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("entidadepai","=",$entidadepai);
	$sql->addFiltro("regpai","=",$regpai);
	$sql->addFiltro("entidadefilho","=",$entidadefilho);
	$lista = tdClass::Criar("repositorio",array("td_lista"))->carregar($sql);
	return $lista;
}
function getListaRegFilhoObject($entidadepai,$entidadefilho,$regpai){
	$retorno = array();
	$lista = getListaRegFilho($entidadepai,$entidadefilho,$regpai);
	foreach($lista as $l){
		$filho = tdc::p( tdc::e($entidadefilho)->nome , $l->regfilho );
		array_push($retorno,$filho);
	}
	return $retorno;
}
function getListaRegFilhoArray($entidadepai,$entidadefilho,$regpai){
	$retorno = array();
	$lista = getListaRegFilho($entidadepai,$entidadefilho,$regpai);
	foreach($lista as $l){
		$filho = tdc::pa( tdc::e($entidadefilho)->nome , $l->regfilho );
		array_push($retorno,$filho);
	}
	return $retorno;
}
function utf8charset($texto, $local = null, $decodificacao = null , $convert = null){
	if ($texto == '') return $texto;
	if ($decodificacao == null && $local == null){
		switch ($convert){
			case 1: # UTF8 para ISO
				return isutf8($texto) ? convertecharset($texto,2) : $texto;
			break;
			default:
				return isutf8($texto) ? utf8charset($texto,'D') : utf8charset($texto,'E');
		}
	}else{
		
		if (is_numeric($local)){
			$charset = tdClass::Criar("persistent",array(CHARSET,$local))->contexto->charset;
			switch(strtoupper($charset)){
				case 'N': return $texto; break;
				case 'D': return utf8decode($texto,'d'); break;
				case 'E': return utf8encode($texto,'e'); break;
				default: return $texto;
			}
		}else{
			$utf8_codificacao = $decodificacao == null ? $local : $decodificacao;
			switch(strtolower($utf8_codificacao)){
				case 'd': return utf8decode($texto); break;
				case 'e': return utf8encode($texto); break;
				default:
					return $texto;
			}
		}
	}
}
function utf8decode($texto){
	return utf8_decode($texto);
}
function utf8encode($texto){
	return utf8_encode($texto);
}

// Retorna o valor baseado no tipo
function retornaValorTipo($valor){
	if (is_numeric($valor)){
		return $valor;
	}else if (is_string($valor)){
		$retorno = "'" . $valor . "'";
		return $retorno;
	}
	return $valor;
}
function isutf8($str){
	if ($str == null) return '';
	$c=0; $b=0;
	$bits=0;
	$len=strlen($str);
	for($i=0; $i<$len; $i++){
		if (!isset($str[$i])) continue;
		$c=ord($str[$i]);		
		if($c > 128){
			if(($c >= 254)) return false;
			elseif($c >= 252) $bits=6;
			elseif($c >= 248) $bits=5;
			elseif($c >= 240) $bits=4;
			elseif($c >= 224) $bits=3;
			elseif($c >= 192) $bits=2;
			else return false;
			if(($i+$bits) > $len) return false;
			while($bits > 1){
				$i++;
				$b=ord($str[$i]);
				if($b < 128 || $b > 191) return false;
				$bits--;
			}
		}
	}
	return true;
}

function convertecharset($valor,$tipo = 1){	
	if ($valor == '' || $valor == null || !is_string($valor)) return $valor;
	$iso = "iso-8859-1";
	$utf8 = "utf-8";
	switch ($tipo){
		case 1:
			return iconv($iso,$utf8,$valor);
		break;
		case 2:
			return iconv($utf8,$iso,$valor);
		break;	
		default:
			return iconv($iso,$utf8,$valor);
	}
}

function getRelacionamentos($entidade,$tipos = null){
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("pai","=",$entidade);
	if ($tipos != null && is_array($tipos)){		
		$sql->addFiltro("tipo","in",$tipos);
	}
	return tdClass::Criar("repositorio",array(RELACIONAMENTO))->carregar($sql);
}

function copiardiretorio($origem,$destino,$criardiretoriodestino = true,$retirar = ""){
	
	if (!file_exists($origem)){
		echo 'Origem não existe';
		return false;
	}
	if (!file_exists($destino)){
		if ($criardiretoriodestino){
			tdFile::mkdir($destino);
		}
	}
	
	$diretorio = dir($origem);
	
	while($arquivo = $diretorio -> read()){
		if ($arquivo != $retirar){
			if (is_dir($origem.$arquivo) && $arquivo != "." && $arquivo != ".."){
				copiardiretorio($origem.$arquivo."/",$destino.$arquivo."/");
			}else if ($arquivo != "." && $arquivo != ".."){
				copy($origem.$arquivo,$destino.$arquivo);
			}
		}
	}
	$diretorio -> close();			
}

function getDataType($atributo,$entidade = "",$conn = null){
	if ($conn == null){
		global $conn;
	}
	
	if (!is_numeric($atributo)){
		if (($atributo != "" && $atributo != 0) && $entidade != ""){
			if (is_numeric($entidade)){
				$entidade = $entidadeOBJ = tdClass::Criar("persistent",array(ENTIDADE,$entidade))->contexto->nome;
			}
			$atributo = getAtributoId($entidade,$atributo,$conn);
		}else{
			return '';
		}
	}

	if ($atributo != 0){
		return tdc::a($atributo)->tipo;
	}else{
		return '';
	}
}

function getTipoHTML($atributo,$entidade = null){	
	if (is_numeric($atributo)){
		$tipohtml = tdc::p(ATRIBUTO,$atributo)->tipohtml;
	}else{	
		$entidade = is_string($entidade) ? $entidade : tdc::p(ENTIDADE,$entidade)->nome;
		$tipohtml = tdc::p(ATRIBUTO,getAtributoId($entidade,$atributo))->tipohtml;
	}
	return $tipohtml;
}
function addCampoFormatadoDB($dados,$entidade){
	// Entidade interna não precisa adicionar a formatação dos campos
	// Futuramento criar um atributo para fazer esse controle
	if (
		$entidade == RELACIONAMENTO || 
		$entidade == ATRIBUTO ||
		$entidade == ENTIDADE ||
		$entidade == MENU
	){
		foreach ($dados as $key => $value){
			switch($key){
				case 'descricao':
				case 'labelzerocheckbox':
				case 'labelumcheckbox':
					$dados[$key] = isutf8($value) ? $value : tdc::utf8($value);
				break;
			}
		}
		return $dados;
	}

	foreach ($dados as $key => $value){
		$tipohtml 		= getTipoHTML($key,$entidade);
		$linha 			= array( $key => $value );

		// Converte os acentos, afeta o método tdc::dj(), tdc::da e tdc::pa	
		$dados[$key] = isutf8($value) ? $value  : tdc::utf8($value);

		if ($tipohtml == 11){
			$dados[$key . '_formated']	= dateToMysqlFormat($value,true);
		}else if ($tipohtml == 13 ){
			$valorformatado = getHTMLTipoFormato( $tipohtml , $value );
			$dados["formated_" . $key] = $valorformatado; # Padrão errado, retirar.			
			$dados[$key . "_formated"] = $valorformatado;
		}else if ($tipohtml == 4 || $tipohtml == 22){
			if (is_numeric_natural($value)){
				$atributoOBJ 			= tdc::p(ATRIBUTO,getAtributoId($entidade,$key));		
				$campodescdefault 		= tdc::p(ATRIBUTO,getCampoDescricaoDefault($atributoOBJ->chaveestrangeira));
				$dados[$key . "_obj"]	= tdc::pj(tdc::e($atributoOBJ->chaveestrangeira)->nome,$value);
				if ($campodescdefault->hasData()){
					$valorfk 				= is_numeric_natural($value)?$value:0;
					$registro 				= getRegistro(null,tdc::p(ENTIDADE,$atributoOBJ->chaveestrangeira)->nome,$campodescdefault->nome, "id={$valorfk}" , "limit 1");
					$dados[$key . "_desc"] 	= tdc::utf8($registro[$campodescdefault->nome]);
				}
			}
		}else if ($tipohtml == 19){
			$file						= $key . '-' . getEntidadeId($entidade) . '-' . $dados['id'] . '.' . getExtensao($value);
			$url_file 					= URL_CURRENT_FILE . $file;
			$path_file					= PATH_CURRENT_FILE . $file;
			$dados[$key . '_src'] 		= file_exists($path_file) ? $url_file : URL_ASSETS . 'img/noimage.png';
		}else if ($tipohtml == 23){
			$dados[$key . '_formated']	= datetimeToMysqlFormat($value,true);
		}
	}
	return $dados;
}
function getCampoDescricaoDefault($entidade){
	if (is_numeric_natural($entidade)){
		$entidade = tdc::p(ENTIDADE,$entidade);
	}else if (is_string($entidade)){
		$entidade = tdc::p(ENTIDADE,getEntidadeId($entidade));
	}else if (is_object($entidade)){
		$entidade = tdc::e((int)$entidade->getId());
	}

	if (!$entidade->campodescchave){
		return 0;
	}
	if (!is_valid_key($entidade->campodescchave)){
		// Pega o primeiro campo que é utilizado na grade de dados
		$dataset = getRegistro(null,ATRIBUTO,"id",'entidade='.$entidade->id,"limit 1");
		if (sizeof($dataset) < 1){
			return 0;
		}else{
			$campodescchave = $dataset["id"];
		}
	}else{
		$campodescchave = $entidade->campodescchave;
	}
	return $campodescchave;
}

function is_numeric_natural($numero){	
	if (is_numeric($numero)){		
		if ($numero > 0){
			return true;
		}
	}
	return false;
}

function is_valid_key($valor){
	if ($valor==0 || $valor == null || $valor == "" || !is_numeric($valor)){
		return false;
	}else{
		return true;
	}
}
/*  
	* getCategoriaArquivo
	* Data de Criacao: 05/06/2020
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Retorna a categoria do arquivo baseado no nome do arquivo
	* PARAMETROS
	*	@params: String filename:"Nome do Arquivo"
	* RETORNO
	*	@return String categoria
*/	
function getCategoriaArquivo($filename){
	switch(strtolower(getExtensao($filename))){
		case 'jpg':
		case 'jpeg':
		case 'png':
		case 'gif':
			return "imagem";
			break;
		default:
			return "";
	}
}
/*  
	* isRelacionamentoPai
	* Data de Criacao: 13/06/2020
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Verifica se a entiddade é pai em algum relacionamento
	* PARAMETROS
	*	@params: String|Number entidade:"Nome ou ID entidade"
	* RETORNO
	*	@return:Boolean
*/
function isRelacionamentoPai($entidade){
	if (!is_numeric($entidade)){
		$entidade = getEntidadeId($entidade);
	}
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("pai","=",$entidade);
	return tdClass::Criar("repositorio",array(RELACIONAMENTO))->quantia($sql) <= 0 ? false : true;
}

function getSystemPREFIXO(){
	return PREFIXO . "_";
}
function getCurrentConnection(){
	global $conn;
	if (!isset($conn) || $conn == null || empty($conn) || gettype($conn) != "object"){
		return Transacao::Get();
	}else{
		return $conn;
	}
}
function setConfigSessionDefault(){
	Session::append("currenttypedatabase","desenv");
	Session::append("currentproject","miles/sistema/");
	Session::append("projeto",1);
	Session::append("currentprojectname","Miles");
}
function isvalidnamedir($dirname){
	$partes = explode("/",$dirname);
	$valido = true;
	foreach($partes as $p){
		if (preg_match('/[\/\\\:*?<>\|"]/i',$p)){
			$valido =  false;
		}
	}
	return $valido;
}
function formatURLAmigavel($url){
	$url  		= retirarAcentos(trim($url));

	$url 	= str_replace(" ","-",$url);
	$url	= str_replace(".","-",$url);
	$url	= str_replace("'","",$url);
	$url	= str_replace('%','',$url);
	$url	= str_replace(array("(",")"),"",$url);
	$url	= str_replace(array("c/","C/"),"com",$url);
	$url	= strtolower($url);

	return $url;
}

function isjson($string){
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

function tdexplode($separador,$string,$returntype = "array"){
	switch($returntype){
		case "object":
			return (object)explode($separador,$string);
		break;
		case "array":
			return explode($separador,$string);
		break;
	}
}

function getnocacheparams(){
	return '';
	#return "nocahe=" . md5("td" . date("dmYHis"));
}

function getPHPVersion(){
	$version = explode(".",phpversion());
	$currentversion = $version[0] . "." . $version[1];
	return (float)$currentversion;
}
function splitTelefone($numero, $codigoarea = "48" , $codigopais = "55" , $digitoadicional = "9"){
	$strlen = strlen($numero);

	if ($strlen < 8) return false;
	$tipo = getTipoTelefone($numero);
	if ($tipo == "F"){
		switch ($strlen){
			case 10:
				$retorno = array("codigopais" => $codigopais , "codigoarea" => substr($numero,-10,2) , "numero" => substr($numero,-8,8) );
			break;
			case 11:
			case 12:
				$retorno = array( "codigopais" => substr($numero,-12,2) , "codigoarea" => substr($numero,-10,2) , "numero" => substr($numero,-8,8) );
			break;
			default:
				$retorno = array( "codigopais" => $codigopais , "codigoarea" => $codigoarea , "numero" => substr($numero,-8,8) );
		}
	}else if ($tipo == "C"){
		switch ($strlen){
			case 9:
			case 10:
				$retorno = array( "codigopais" => $codigopais , "codigoarea" => $codigoarea , "digitoadicional" => substr($numero,-9,1), "numero" => substr($numero,-8,8) );
			break;
			case 11:
			case 12:
				$retorno = array( "codigopais" => $codigopais , "codigoarea" => substr($numero,-11,2) , "digitoadicional" => substr($numero,-9,1) , "numero" => substr($numero,-8,8) );
			break;
			case 13:
				$retorno = array( "codigopais" => substr($numero,-13,2) , "codigoarea" => substr($numero,-11,2) , "digitoadicional" => substr($numero,-9,1) , "numero" => substr($numero,-8,8) );
			break;
			default:
				$retorno = array( "codigopais" => $codigopais , "codigoarea" => $codigoarea , "digitoadicional" => $digitoadicional, "numero" => substr($numero,-8,8) );
		}
	}

	return array_merge($retorno, array("tipo" => $tipo));
}
// Tipo => C: Celular , F: Fixo
function getTipoTelefone($numero){
	if (strlen($numero) < 8){
		return false;
	}else{
		return substr($numero,-8,1) < 7 ? "F" : "C";
	}
}
function isTelefone($numero){
	if ($numero == '' || $numero == null || empty($numero)){
		return false;
	}
	$len = strlen(str_replace(array("-"," ","(",")","."),"",$numero));
	if ($len < 8 || $len > 13){
		return false;
	}
	return true;
}
function addAutoIncrement($entidade){
    global $conn;
	$sql = "SELECT nome FROM ".ENTIDADE." WHERE id = " . $entidade . ";";
	$query = $conn->query($sql);
	if ($query->rowCount() > 0){
		$linha = $query->fetch();
		$entidadenome = $linha["nome"];
		$conn->exec("ALTER TABLE {$entidadenome} CHANGE id id INT NOT NULL AUTO_INCREMENT;");
	}
}
function isTipoHTMLNumero($tipohtml){
    switch((int)$tipohtml){
        case 4: case 7: case 22: case 24: case 25: case 26:
            return true;
            break;
        default:
            return false;
    }
}
function isTipoNumerico($tipo){
    switch($tipo){
        case 'int':case 'tinyint':case 'smallint':case 'mediumint':case 'bigint':
        case 'decimal':case 'float':case 'bit':
            return true;
        default:
            return false;
    }
}
function getValorDefaultAtributo($valor,$tipohtml,$tipo){
    if (isTipoHTMLNumero($tipohtml) || isTipoNumerico($tipo)){
        return $valor == "" ? 0 : $valor;
    }else{
        return "'{$valor}'";
    }
}
function isOnline($returntype = "boolean"){
    $isonline = isset($_SESSION["ISONLINE"]) ? $_SESSION["ISONLINE"] : false;
    return getBoolean($isonline,$returntype);
}
function isProducao($returntype = "boolean"){
    $tipo = getDescTipoConnection();
    $isproducao = ($tipo == 4 or $tipo == 'producao') ? true : false;
    return getBoolean($isproducao,$returntype);
}
function getBoolean($boolean,$returntype){
    $retorno = $boolean;
    switch($returntype){
        case "string":
            $retorno = $boolean?'true':'false';
            break;
        case "numeric":
            $retorno = $boolean?1:0;
            break;
        case "boolean":
            $retorno = $boolean?true:false;
        break;
    }
    return $retorno;
}
function getURLProject($parametro = null){
	$urlproject 		= URL_MILES . "index.php";
	$parmsProject 		= array("currentproject" => CURRENT_PROJECT_ID);
	switch(gettype($parametro)){
		case 'string':
			if (strpos("?",$parametro) < 0) return $parametro;
			$paramsArray 			= array_merge(getURLParamsArray($parametro),$parmsProject);
		break;
		case 'array':
			$paramsArray  			= array_merge($parametro,$parmsProject);
		break;
		default:
			$paramsArray				= array();
	}
	$params = "";
	foreach($paramsArray  as $key => $value){
		$params .= ($params==""?"?":"&") . $key . "=" . $value;
	}
	$url = $urlproject . $params;
	return $url;
}
function getURLParamsArray($url){
	$arrayParams = array();
	$paramsURL 			= explode("?",$url);
	if (isset($paramsURL[1])){
		$params 			=  explode("&",$paramsURL[1]);
		foreach($params as $param){
			$p 		= explode("=",$param);
			$key 	= $p[0];
			$value	= $p[1];
			$arrayParams[$key] = $value;
		}
	}
	return $arrayParams;
}

function getSystemFKPreFixo($atributo = ""){
	return $atributo;
}
/*  
	* ordenarAtributo
	* Data de Criacao: 18/09/2021
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Ordena o atributo para ser exibido no formulário
	* PARAMETROS
	*	@params: Number atributo:"ID do Atributo"
	* RETORNO
	*	@return:void
*/
function ordenarAtributo($atributo){
	$a = tdc::p(ATRIBUTO,$atributo);
	
	if (!$a->ordem) return false;
	if ($a->ordem <= 0 || $a->ordem == ''){
		switch($a->tipohtml){
			case 16:
				$ordem = 1;
			break;
			case 1:
			case 2:
			case 3:
				$ordem = 2;
			break;
			case 4:
			case 5:
				$ordem = 3;
			case 10:
			case 15:
			case 17:
				$ordem = 4;
			break;
			case 11:
			case 23:
				$ordem = 5;
			break;
			case 12:
			case 8:
			case 6:
			case 13:
				$ordem = 6;
			break;
			case 9:
			case 18:
			case 29:
				$ordem = 7;
			break;
			case 25:
			case 26:
			case 28:
				$ordem = 8;
			break;
			case 21:
			case 22:
			case 24:
				$ordem = 9;
			break;
			case 19:
			case 20:
				$ordem = 10;
			break;
			case 7:
				$ordem = 11;
			break;
			case 14:
			case 27:
				$ordem = 12;
			break;
			case 30:
				$ordem = 13;
			break;
			default:
				$ordem = 0;
		}
		$a->ordem = $ordem;
		$a->armazenar();
	}
}

/*
	* getTableName
	* Data de Criacao: 14/11/2021
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Trata o nome da tabela, retorna o padrão do sistema
	* PARAMETROS
	*	@params: String tabela:"Nome da tabela"
	* RETORNO
	*	@return: String tabela
*/
function getTableName($tabela){
	$prefixo 		= getSystemPREFIXO();
	return (substr($tabela,0,strlen($prefixo)) == $prefixo ? '' : $prefixo) . $tabela;
}

/*
	* loadPage
	* Data de Criacao: 20/11/2021
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Carrega uma página interna do MILES
	* PARAMETROS
	*	@params: String page:"Path da Página"
	* RETORNO
	*	@return: String Página
*/
function loadPage($page){
	return getURL(URL_MILES . 'index.php?controller=page&page=' . $page);
}

/*
	* exists_lista
	* Data de Criacao: 28/12/2021
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Verifica se existe um registro na lista
	* PARAMETROS
	*	@params: Inteiro entidadepai:"Entidade Pai"
	*	@params: Inteiro entidadefilho:"Entidade Filho"
	*	@params: Inteiro regpai:"Registro Pai"
	*	@params: Inteiro regfilho:"Registro Filho"
	* RETORNO
	*	@return: Boolean
*/
function exists_lista($entidadepai,$entidadefilho,$regpai,$regfilho){	
	$sql = tdClass::Criar("sqlcriterio");
	$sql->addFiltro("entidadepai"	,"=",$entidadepai);
	$sql->addFiltro("entidadefilho"	,"=",$entidadefilho);
	$sql->addFiltro("regpai"		,"=",$regpai);
	$sql->addFiltro("regfilho"		,"=",$regfilho);

	if (tdClass::Criar("repositorio",array(LISTA))->quantia($sql) <= 0){
		return false;
	}else{
		return true;
	}
}

/*
	* consoleJS
	* Data de Criacao: 19/01/2022
	* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	* Exibi uma mensagem no console do navegador via JavaScript
	* PARAMETROS
	*	@params: Literal mensagem:"Mensagem a ser exibida"
	*	@params: Literal tipo:" log | warn | error "
	* RETORNO
	*	@return: void
*/
function consoleJS($mensagem,$tipo = 'log'){
	echo '<script type="text/javascript">console.'.$tipo.'(\''.$log.'\');</script>';
}

// Anti Injection SQL
function anti_injection($sql)
{
   $sql = preg_replace("/(from|select|insert|delete|where|drop table|show tables|drop database|#|\*|--|\\\\)/i","",$sql);
   $sql = trim($sql);
   $sql = strip_tags($sql);
   $sql = (get_magic_quotes_gpc()) ? $sql : addslashes($sql);
   return $sql;
}

function noClick(){
	return 'onclick=event.preventDefault();event.stopPropagation();';
}

// Testa se a variável existe ( @theusdido 07/09/2000 )
function is_exists($variable,$replace = null){
	eval('$is_exists_variable = isset('.$variable.');');
	return $is_exists_variable ? $variable : $replace;
}