<?php
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB.
    * @link http://www.teia.tec.br

    * Classe Config
    * Data de Criacao: 19/06/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Config {
	
	/*
		* Método currentProject
	    * Data de Criacao: 19/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o ID do projeto atual
	*/
	public static function currentProject() {
		if (defined("PROJETO_CONSUMIDOR")){			
			$currentProject = (int)PROJETO_CONSUMIDOR;
		}else if (defined("MILES_PROJECT")){
			$currentProject = (int)MILES_PROJECT;
		}else if (isset($_GET["currentproject"])){
			$currentProject = (int)$_GET["currentproject"];
		}else if (isset($_POST["currentproject"])){
			$currentProject = (int)$_POST["currentproject"];
		}else if(isset($mjc->currentproject)){
			$currentproject = $mjc->currentproject;
		}else{
			if (isset($_SESSION["currentproject"])){
				$currentProject = $_SESSION["currentproject"];
			}else{
				$currentProject = 1;
			}
		}
		return $currentProject;
	}

	/*
		* Método isOnline
	    * Data de Criacao: 19/06/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna se está online/offline
	*/
	public static function isOnline(){
		switch(HTTP_HOST){
			case "localhost":
			case "127.0.0.1":
				$isonline 				= false;
			break;
			default:
				$isonline 				= true;
		}
		return $isonline;
	}

	/*
		* integridade
		* Data de Criacao: desconhecido
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* Retorna um padrão de dados e processa o salvamento de dados padrão
		* PARAMETROS
		*	@params: int entidade:"ID da Entidade"
		*	@params: String atributo:"Nome do Atributo"
		*	@params: any valor:"Valor a ser salvo"
		*	@params: int id:"ID do registro"
		* RETORNO
		*	@return any: Valor formatado para salvamento
	*/
	public static function integridade($entidade,$atributo,$valor,$id){

		$sql = tdClass::Criar("sqlcriterio");
		$sql->addFiltro("nome","=",$atributo);
		$sql->addFiltro("entidade","=",$entidade);
		$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql);
		if (sizeof($dataset) <= 0) return $valor;
		switch((int)$dataset[0]->tipohtml){
			case 1: case 2: case 3:
				$retorno = tdc::utf8($valor);
			break;
			case 5:
				if (gettype($valor) == 'array'){
					$retorno = implode(',',$valor);
				}else{
					$retorno = '';
				}
			break;
			case 6: # Senha
				$retorno = strlen($valor) == 32 ? $valor : md5($valor);
			break;
			case 7:				
				$retorno = $valor == "" ? 0 : $valor;
			break;
			case 10:
				$retorno = $valor;
			break;
			case 11: # Data
				if ($valor != ""){
					$separador = (strpos($valor,"/") > 0)?"/":"-";
					$dt = explode($separador,$valor);
					return $dt[2] . "-" . $dt[1] . "-" . $dt[0];
				}else{
					return null;
				}
			break;
			case 13:
				$retorno = (double)moneyToFloat($valor);
			break;
			case 19: # Upload
				if ($valor == ""){
					$retorno = $valor;
				}else{
					$val 				= json_decode(utf8_decode($valor),true);
					$op             	= isset($val["op"])?$val["op"]:'';
					$filename 			= isset($val["filename"])?$val["filename"]: (isset($val[1])?$val[1]:'');
					$tipo 				= isset($val["tipo"])?$val["tipo"]:'';
					$src 				= isset($val["src"])?$val["src"]:'';
					$legenda			= isset($val["legenda"])?$val["legenda"]:'';					
					$isexcluirtemp		= isset($val["isexcluirtemp"])?(bool)$val["isexcluirtemp"]:true;
					$filenamefixed		= $atributo . "-" . $entidade . "-". $id. "." . getExtensao($filename);
					$filenametemp		= isset($val["filenametemp"])?$val["filenametemp"]:'';
					$pathfiletemp		= PATH_CURRENT_FILE_TEMP . $filenametemp;
					$pathfile       	= PATH_CURRENT_FILE . $filenamefixed;
					$pathexternalfile	= '/public_html/miles/project/arquivos/' . $filenamefixed;
					$file_full_temp		= isset($val["filenametemp"])?PATH_CURRENT_FILE_TEMP . $val["filenametemp"]:'';

					// Em modo de exclusão
					if ($op == "excluir"){
						if (file_exists($pathfile)){

							// Exclui o arquivo no FTP
							$ftp = new FTP();
							$ftp->delete($pathexternalfile);

							// Exclui o arquivo
							unlink($pathfile);
						}
						$filename = '';
					}

					// Efetiva o salvamento do arquivo
					if (
						file_exists($file_full_temp) && 
						sizeof($val) > 0 && 
						$filename != '' && 
						$file_full_temp != ''
					){
						// Envia arquivo para o FTP Externo do Projeto
						$ftp = new FTP();
						$ftp->put($file_full_temp,$pathexternalfile);

						// Move o arquivo da pasta temporária para permanente
						if (copy($file_full_temp,$pathfile)){

							// [ NÃO APAGAR ] - Exclui o arquivo temporário 
							#if ($isexcluirtemp && !is_dir($pathfiletemp)) unlink($pathfiletemp);

						}else{
							Debug::log('Não foi possível mover o arquivo [ '.$file_full_temp.' ] para ['.$pathexternalfile.']');
						}
					}
					$retorno = $filename;
				}
			break;
			case 22: # Filtro
				if ($valor == "" || $valor == null){
					return 0;
				}else{
					return $valor;
				}
			break;
			case 23: # Data e Hora
				if ($valor != ""){
					$separador 	= (strpos($valor,"/") > 0)?"/":"-";
					$data 		= explode(" ",$valor);
					$hora 		= explode(" ",$valor);
					$dt 		= explode($separador,$data[0]);
					return $dt[2] . "-" . $dt[1] . "-" . $dt[0] . (isset($data[1])?" " . $data[1]:"");
				}else{
					return null;
				}	
			break;
			case 24: # Filtro (Endereço)
				if ($valor == "" || $valor == null){
					return 0;
				}else{
					return $valor;
				}
			break;
			default:
				$retorno = $valor;
		}
		return $retorno;
	}
	/*
		* getEnvirommentVariable
		* Data de Criacao: 16/09/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* Retorna o valor da variável de ambiente
		* PARAMETROS
		*	@params: String variavel:string | any
		* RETORNO
		*	@return: any
	*/
	public static function getEnvirommentVariable($path_variable,$default = NULL){
		global $_env;
		global $mjc;

		$_custom	= isset($_env->{$path_variable}) ? $_env->{$path_variable} : false;
		$_system	= isset($mjc->{$path_variable}) ? $mjc->{$path_variable} : false;

		return $_custom ? $_custom : ($_system ? $_system : $default);
	}

	/*
		* createFileDbIni
		* Data de Criacao: 16/09/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* Retorna o valor da variável de ambiente
		* PARAMETROS
		*	@params: String variavel:string | any
		* RETORNO
		*	@return: any
	*/
	public static function createFileDbIni($path,$data = [], $env_db = 'temp',$sgdb = 'mysql'){
		$ini_data = array(
			'usuario='	.$data["db_user"],
			'senha='	.$data["db_password"],
			'base='		.$data["db_base"],
			'host='		.$data["db_host"],
			'tipo='		.$data["db_type"],
			'porta='	.$data["db_port"]
		);
		$fpMySQLINIDesenv    = fopen($path . $env_db . '_' . $sgdb .'.ini',"w");
		foreach($ini_data as $c){
			fwrite($fpMySQLINIDesenv,trim($c)."\n");
		}
		fclose($fpMySQLINIDesenv);
	}
}