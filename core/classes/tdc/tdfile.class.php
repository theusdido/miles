<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa tratamento com arquivo e diretórios
    * Data de Criacao: 23/04/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class tdFile {
	/*  
		* Método pathDateDir 
	    * Data de Criacao: 23/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Criar uma hieraquia de diretório baseado numa data
		@dir: Diretório inicial. Padrão: Raiz do sistema.
		@data: Data para criação, Padrão: Data atual.
	*/
	public static function pathDateDir($dir = null,$data = null){
		$dirinicial = $dir == null ? RAIZ : $dir;
		$data 		= $data==null?date("Y-m-d"):$data;
		if (file_exists($dirinicial)){
			if (is_date($data)){
				$ano = tdDate::getPartDate("Y",$data);
				$mes = tdDate::getPartDate("M",$data);
				$dia = tdDate::getPartDate("D",$data);
				
				$pathano = $dirinicial . $ano . "/";
				if (!file_exists($pathano)){
					self::mkdir($pathano);
				}
				$pathmes = $pathano . $mes . "/" ;
				if (!file_exists($pathmes)){				
					self::mkdir($pathmes);
				}
				$pathdia = $pathmes . $dia . "/";
				if (!file_exists($pathdia)){
					self::mkdir($pathdia);
				}
				return $pathdia;
			}else{
				Debug::put("tdFile:pathDateDir Data Inválida => " . $data);
				return false;
			}
		}else{
			Debug::put("tdFile:pathDateDir Diretório não existe => " . $dirinicial);
			return false;
		}
	}
	/*
		* Método add
	    * Data de Criacao: 24/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Criar e adiciona um arquivo
		@pathfile: Caminho absoluto do arquivo
		@conteudo: Informação a ser gravada no arquivo
	*/
	public static function add($pathfile,$conteudo){
		try{
			if (isvalidnamedir($pathfile)){
				$fp = fopen($pathfile,"w");
				fwrite($fp,$conteudo);
				fclose($fp);
				return true;
			}else{
				return false;
			}	
		}catch(Exception $e){
			return false;
		}
	}
	/*
		* Método download
	    * Data de Criacao: 26/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Criar e adiciona um arquivo. Se a estrutura de arquivo não existir será criada.
		@localpathfile: Caminho local do arquivo
		@remotopathfile: Endereço Remoto do arquivo
	*/
	public static function download($localpathfile,$remotopathfile){
		try{
			$diretorios 		= explode("/",$localpathfile);
			$arquivo 			= end($diretorios);
			$diretorioagredado 	= "";

			array_pop($diretorios);
			foreach($diretorios as $d){
				$diretorioagredado .= $diretorioagredado==""?$d:"/".$d;
				self::mkdir($diretorioagredado,0777,true,true);
			}
			self::add($diretorioagredado . "/" . $arquivo, file_get_contents($remotopathfile));
		}catch(Exception $e){
			return false;
		}
	}
	/*
		* Método mkdir
	    * Data de Criacao: 26/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Criar e adiciona um diretório.
		@path: Caminho absoluto do diretório
	*/
	public static function mkdir($path,$permissao = 0777,$recursivo = true,$force = false){
		$res = false;
		if ($force){
			$folder 	= '';
			$folders 	= explode("/",$path);
			foreach($folders as $f){
				$folder .= $f . "/";
				$res = self::mkdir($folder);
			}
		}else{
			if (!file_exists($path)){
				$res 	= mkdir($path,$permissao,$recursivo);
				$chmod 	= chmod($path, $permissao);
			}
		}
		return $res;
	}

	/*
		* Método upload
	    * Data de Criacao: 23/08/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Efetua upload de arquivos no TD Formulário
		@files: $_FILES
		@atributo: Objeto do tipo td_atributo
	*/
	public static function uploadTDForm($files,$atributo){
		foreach($files as $f){
			$hashtemp		= md5($f["tmp_name"] . session_id() . "-" . $atributo->nome . "-" . $atributo->entidade);
			$filenametemp 	= $hashtemp . "." . getExtensao($f["name"]);
			$pathtdtempfile = PATH_CURRENT_FILE_TEMP . $filenametemp;
			$urltdtempfile	= URL_CURRENT_FILE_TEMP . $filenametemp;
			
			if (move_uploaded_file($f["tmp_name"], $pathtdtempfile)){
				$dadosarquivo = array(
					"tipo" 			=> getCategoriaArquivo($f["name"]),
					"src" 			=> $urltdtempfile,
					"filename" 		=> $f["name"],
					"legenda"		=> "",
					"alt" 			=> "",
					"temp" 			=> true,
					"filenametemp"	=> $filenametemp
				);
				return $dadosarquivo;
			}else{
				return false;
			}
		}
	}
}